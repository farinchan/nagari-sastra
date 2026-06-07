<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\BookCategory;
use App\Models\SettingWebsite;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * OAI-PMH 2.0 Controller for Book Repository
 *
 * Implements the Open Archives Initiative Protocol for Metadata Harvesting (OAI-PMH)
 * to support indexing by Google Scholar, BASE, WorldCat, OAPEN, and other academic harvesters.
 *
 * Supported verbs: Identify, ListMetadataFormats, ListSets, ListIdentifiers, ListRecords, GetRecord
 * Supported metadata formats: oai_dc (Dublin Core), marcxml (MARC 21)
 *
 * @see https://www.openarchives.org/OAI/openarchivesprotocol.html
 */
class OaiPmhController extends Controller
{
    private string $repositoryName;
    private string $baseUrl;
    private string $adminEmail;
    private string $repositoryIdentifier;

    public function __construct()
    {
        $setting = SettingWebsite::first();
        $this->repositoryName = ($setting->name ?? 'Nagari Sastra') . ' Book Repository';
        $this->baseUrl = url('/oai');
        $this->adminEmail = $setting->email ?? config('mail.from.address', 'admin@nagastra.org');
        $this->repositoryIdentifier = parse_url(config('app.url'), PHP_URL_HOST) ?: 'nagastra.org';
    }

    /**
     * Main OAI-PMH request handler
     */
    public function handle(Request $request)
    {
        $verb = $request->query('verb');

        $response = match ($verb) {
            'Identify' => $this->identify(),
            'ListMetadataFormats' => $this->listMetadataFormats($request),
            'ListSets' => $this->listSets(),
            'ListIdentifiers' => $this->listIdentifiers($request),
            'ListRecords' => $this->listRecords($request),
            'GetRecord' => $this->getRecord($request),
            default => $this->errorResponse('badVerb', 'Illegal OAI verb'),
        };

        return response($response, 200)->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    // =========================================================================
    // OAI-PMH Verbs
    // =========================================================================

    /**
     * Identify — Repository description
     */
    private function identify(): string
    {
        $earliestBook = Book::where('status', 'published')->oldest()->first();
        $earliestDate = $earliestBook ? $earliestBook->created_at->toW3cString() : Carbon::now()->toW3cString();

        return $this->wrapResponse('Identify', '', '
    <repositoryName>' . e($this->repositoryName) . '</repositoryName>
    <baseURL>' . e($this->baseUrl) . '</baseURL>
    <protocolVersion>2.0</protocolVersion>
    <adminEmail>' . e($this->adminEmail) . '</adminEmail>
    <earliestDatestamp>' . $earliestDate . '</earliestDatestamp>
    <deletedRecord>no</deletedRecord>
    <granularity>YYYY-MM-DDThh:mm:ssZ</granularity>
    <description>
      <oai-identifier xmlns="http://www.openarchives.org/OAI/2.0/oai-identifier"
                      xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                      xsi:schemaLocation="http://www.openarchives.org/OAI/2.0/oai-identifier http://www.openarchives.org/OAI/2.0/oai-identifier.xsd">
        <scheme>oai</scheme>
        <repositoryIdentifier>' . e($this->repositoryIdentifier) . '</repositoryIdentifier>
        <delimiter>:</delimiter>
        <sampleIdentifier>oai:' . e($this->repositoryIdentifier) . ':book/1</sampleIdentifier>
      </oai-identifier>
    </description>');
    }

    /**
     * ListMetadataFormats — Available metadata formats
     */
    private function listMetadataFormats(Request $request): string
    {
        // Validate identifier if provided
        if ($request->has('identifier')) {
            $book = $this->findBookByIdentifier($request->query('identifier'));
            if (!$book) {
                return $this->errorResponse('idDoesNotExist', 'The identifier does not exist in this repository');
            }
        }

        $params = $request->has('identifier') ? ' identifier="' . e($request->query('identifier')) . '"' : '';

        return $this->wrapResponse('ListMetadataFormats', $params, '
    <metadataFormat>
      <metadataPrefix>oai_dc</metadataPrefix>
      <schema>http://www.openarchives.org/OAI/2.0/oai_dc.xsd</schema>
      <metadataNamespace>http://www.openarchives.org/OAI/2.0/oai_dc/</metadataNamespace>
    </metadataFormat>
    <metadataFormat>
      <metadataPrefix>marcxml</metadataPrefix>
      <schema>http://www.loc.gov/standards/marcxml/schema/MARC21slim.xsd</schema>
      <metadataNamespace>http://www.loc.gov/MARC21/slim</metadataNamespace>
    </metadataFormat>');
    }

    /**
     * ListSets — Available sets (book categories)
     */
    private function listSets(): string
    {
        $categories = BookCategory::all();

        if ($categories->isEmpty()) {
            return $this->errorResponse('noSetHierarchy', 'This repository does not support sets');
        }

        $xml = '';
        foreach ($categories as $cat) {
            $xml .= '
    <set>
      <setSpec>' . e(Str::slug($cat->name)) . '</setSpec>
      <setName>' . e($cat->name) . '</setName>
    </set>';
        }

        return $this->wrapResponse('ListSets', '', $xml);
    }

    /**
     * ListIdentifiers — List record headers only
     */
    private function listIdentifiers(Request $request): string
    {
        $metadataPrefix = $request->query('metadataPrefix');
        if (!$metadataPrefix) {
            return $this->errorResponse('badArgument', 'Missing required argument: metadataPrefix');
        }
        if (!in_array($metadataPrefix, ['oai_dc', 'marcxml'])) {
            return $this->errorResponse('cannotDisseminateFormat', 'Metadata format "' . e($metadataPrefix) . '" is not supported');
        }

        $query = Book::where('status', 'published')->with(['category', 'bookAuthors']);
        $query = $this->applyDateFilters($query, $request);
        $query = $this->applySetFilter($query, $request);

        $books = $query->orderBy('updated_at', 'asc')->get();

        if ($books->isEmpty()) {
            return $this->errorResponse('noRecordsMatch', 'No records match the request criteria');
        }

        $params = ' metadataPrefix="' . e($metadataPrefix) . '"';
        $xml = '';
        foreach ($books as $book) {
            $xml .= $this->buildHeader($book);
        }

        return $this->wrapResponse('ListIdentifiers', $params, $xml);
    }

    /**
     * ListRecords — List full records with metadata
     */
    private function listRecords(Request $request): string
    {
        $metadataPrefix = $request->query('metadataPrefix');
        if (!$metadataPrefix) {
            return $this->errorResponse('badArgument', 'Missing required argument: metadataPrefix');
        }
        if (!in_array($metadataPrefix, ['oai_dc', 'marcxml'])) {
            return $this->errorResponse('cannotDisseminateFormat', 'Metadata format "' . e($metadataPrefix) . '" is not supported');
        }

        $query = Book::where('status', 'published')->with(['category', 'bookAuthors']);
        $query = $this->applyDateFilters($query, $request);
        $query = $this->applySetFilter($query, $request);

        $books = $query->orderBy('updated_at', 'asc')->get();

        if ($books->isEmpty()) {
            return $this->errorResponse('noRecordsMatch', 'No records match the request criteria');
        }

        $params = ' metadataPrefix="' . e($metadataPrefix) . '"';
        $xml = '';
        foreach ($books as $book) {
            $xml .= $this->buildRecord($book, $metadataPrefix);
        }

        return $this->wrapResponse('ListRecords', $params, $xml);
    }

    /**
     * GetRecord — Get a single record by identifier
     */
    private function getRecord(Request $request): string
    {
        $identifier = $request->query('identifier');
        $metadataPrefix = $request->query('metadataPrefix');

        if (!$identifier || !$metadataPrefix) {
            return $this->errorResponse('badArgument', 'Missing required arguments: identifier and metadataPrefix');
        }
        if (!in_array($metadataPrefix, ['oai_dc', 'marcxml'])) {
            return $this->errorResponse('cannotDisseminateFormat', 'Metadata format "' . e($metadataPrefix) . '" is not supported');
        }

        $book = $this->findBookByIdentifier($identifier);
        if (!$book) {
            return $this->errorResponse('idDoesNotExist', 'The identifier "' . e($identifier) . '" does not exist');
        }

        $params = ' identifier="' . e($identifier) . '" metadataPrefix="' . e($metadataPrefix) . '"';
        return $this->wrapResponse('GetRecord', $params, $this->buildRecord($book, $metadataPrefix));
    }

    // =========================================================================
    // Record Building Helpers
    // =========================================================================

    /**
     * Build OAI-PMH header element for a book
     */
    private function buildHeader(Book $book): string
    {
        $identifier = $this->bookIdentifier($book);
        $datestamp = $book->updated_at->toW3cString();
        $setSpec = $book->category ? Str::slug($book->category->name) : '';

        $xml = '
    <header>
      <identifier>' . e($identifier) . '</identifier>
      <datestamp>' . $datestamp . '</datestamp>';
        if ($setSpec) {
            $xml .= '
      <setSpec>' . e($setSpec) . '</setSpec>';
        }
        $xml .= '
    </header>';

        return $xml;
    }

    /**
     * Build full OAI-PMH record element
     */
    private function buildRecord(Book $book, string $metadataPrefix): string
    {
        $metadata = match ($metadataPrefix) {
            'oai_dc' => $this->buildDublinCore($book),
            'marcxml' => $this->buildMarcXml($book),
            default => '',
        };

        return '
    <record>' . $this->buildHeader($book) . '
      <metadata>' . $metadata . '
      </metadata>
    </record>';
    }

    /**
     * Build Dublin Core (oai_dc) metadata
     */
    private function buildDublinCore(Book $book): string
    {
        $abstract = Str::limit(strip_tags($book->description ?? ''), 500, '');
        $authors = $book->bookAuthors;
        $keywords = $this->extractKeywords($book);

        $xml = '
        <oai_dc:dc xmlns:oai_dc="http://www.openarchives.org/OAI/2.0/oai_dc/"
                   xmlns:dc="http://purl.org/dc/elements/1.1/"
                   xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                   xsi:schemaLocation="http://www.openarchives.org/OAI/2.0/oai_dc/ http://www.openarchives.org/OAI/2.0/oai_dc.xsd">';

        // dc:title
        $xml .= '
          <dc:title>' . e($book->title) . '</dc:title>';

        // dc:creator (authors)
        if ($authors && $authors->count() > 0) {
            foreach ($authors as $author) {
                $name = $author->name;
                if ($name) {
                    $xml .= '
          <dc:creator>' . e($name) . '</dc:creator>';
                }
            }
        }

        // dc:subject (keywords + category)
        if ($book->category) {
            $xml .= '
          <dc:subject>' . e($book->category->name) . '</dc:subject>';
        }
        foreach ($keywords as $kw) {
            $xml .= '
          <dc:subject>' . e($kw) . '</dc:subject>';
        }

        // dc:description
        if ($abstract) {
            $xml .= '
          <dc:description>' . e($abstract) . '</dc:description>';
        }

        // dc:publisher
        if ($book->publisher) {
            $xml .= '
          <dc:publisher>' . e($book->publisher) . '</dc:publisher>';
        }

        // dc:date
        if ($book->publish_year) {
            $xml .= '
          <dc:date>' . e($book->publish_year) . '</dc:date>';
        }

        // dc:type
        $xml .= '
          <dc:type>book</dc:type>';

        // dc:format
        $xml .= '
          <dc:format>application/pdf</dc:format>';

        // dc:identifier (ISBN, URL)
        if ($book->isbn) {
            $xml .= '
          <dc:identifier>ISBN:' . e($book->isbn) . '</dc:identifier>';
        }
        $xml .= '
          <dc:identifier>' . e(route('book.show', $book->slug)) . '</dc:identifier>';

        // dc:language
        $xml .= '
          <dc:language>' . e($book->language ?: 'id') . '</dc:language>';

        // dc:rights
        $xml .= '
          <dc:rights>Copyright © ' . e($book->publish_year ?: date('Y')) . ' ' . e($book->publisher ?: $this->repositoryName) . '</dc:rights>';

        $xml .= '
        </oai_dc:dc>';

        return $xml;
    }

    /**
     * Build MARC 21 XML metadata
     */
    private function buildMarcXml(Book $book): string
    {
        $authors = $book->bookAuthors;
        $keywords = $this->extractKeywords($book);
        $abstract = Str::limit(strip_tags($book->description ?? ''), 500, '');

        $xml = '
        <record xmlns="http://www.loc.gov/MARC21/slim"
                xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                xsi:schemaLocation="http://www.loc.gov/MARC21/slim http://www.loc.gov/standards/marcxml/schema/MARC21slim.xsd">
          <leader>     nam a22     4500</leader>';

        // 020 - ISBN
        if ($book->isbn) {
            $xml .= '
          <datafield tag="020" ind1=" " ind2=" ">
            <subfield code="a">' . e($book->isbn) . '</subfield>
          </datafield>';
        }

        // 041 - Language
        $xml .= '
          <datafield tag="041" ind1=" " ind2=" ">
            <subfield code="a">' . e($book->language ?: 'ind') . '</subfield>
          </datafield>';

        // 100 - Main author
        if ($authors && $authors->count() > 0) {
            $mainAuthor = $authors->first();
            $xml .= '
          <datafield tag="100" ind1="1" ind2=" ">
            <subfield code="a">' . e($mainAuthor->name) . '</subfield>
          </datafield>';
        }

        // 245 - Title
        $xml .= '
          <datafield tag="245" ind1="1" ind2="0">
            <subfield code="a">' . e($book->title) . '</subfield>
          </datafield>';

        // 250 - Edition
        if ($book->edition) {
            $xml .= '
          <datafield tag="250" ind1=" " ind2=" ">
            <subfield code="a">' . e($book->edition) . '</subfield>
          </datafield>';
        }

        // 260 - Publisher + Year
        $xml .= '
          <datafield tag="260" ind1=" " ind2=" ">';
        if ($book->publisher) {
            $xml .= '
            <subfield code="b">' . e($book->publisher) . '</subfield>';
        }
        if ($book->publish_year) {
            $xml .= '
            <subfield code="c">' . e($book->publish_year) . '</subfield>';
        }
        $xml .= '
          </datafield>';

        // 300 - Pages
        if ($book->pages) {
            $xml .= '
          <datafield tag="300" ind1=" " ind2=" ">
            <subfield code="a">' . e($book->pages) . ' halaman</subfield>
          </datafield>';
        }

        // 520 - Abstract
        if ($abstract) {
            $xml .= '
          <datafield tag="520" ind1=" " ind2=" ">
            <subfield code="a">' . e($abstract) . '</subfield>
          </datafield>';
        }

        // 650 - Subjects
        foreach ($keywords as $kw) {
            $xml .= '
          <datafield tag="650" ind1=" " ind2="4">
            <subfield code="a">' . e($kw) . '</subfield>
          </datafield>';
        }

        // 700 - Additional authors
        if ($authors && $authors->count() > 1) {
            foreach ($authors->skip(1) as $author) {
                $xml .= '
          <datafield tag="700" ind1="1" ind2=" ">
            <subfield code="a">' . e($author->name) . '</subfield>
          </datafield>';
            }
        }

        // 856 - URL
        $xml .= '
          <datafield tag="856" ind1="4" ind2="0">
            <subfield code="u">' . e(route('book.show', $book->slug)) . '</subfield>
          </datafield>';

        $xml .= '
        </record>';

        return $xml;
    }

    // =========================================================================
    // Utility Methods
    // =========================================================================

    /**
     * Generate OAI identifier for a book
     */
    private function bookIdentifier(Book $book): string
    {
        return 'oai:' . $this->repositoryIdentifier . ':book/' . $book->id;
    }

    /**
     * Find a book by OAI identifier
     */
    private function findBookByIdentifier(string $identifier): ?Book
    {
        // Format: oai:domain.com:book/123
        if (!preg_match('/^oai:.+:book\/(\d+)$/', $identifier, $matches)) {
            return null;
        }

        return Book::where('id', $matches[1])
            ->where('status', 'published')
            ->with(['category', 'bookAuthors'])
            ->first();
    }

    /**
     * Extract keywords from book's keywords JSON field
     */
    private function extractKeywords(Book $book): array
    {
        if (!$book->keywords || !is_array($book->keywords)) {
            return [];
        }

        return collect($book->keywords)->map(function ($keyword) {
            if (is_array($keyword)) {
                return $keyword['value'] ?? implode(', ', $keyword);
            }
            if (is_object($keyword)) {
                return $keyword->value ?? '';
            }
            return $keyword;
        })->filter()->values()->all();
    }

    /**
     * Apply date range filters (from/until)
     */
    private function applyDateFilters($query, Request $request)
    {
        if ($request->has('from')) {
            try {
                $from = Carbon::parse($request->query('from'));
                $query->where('updated_at', '>=', $from);
            } catch (\Exception $e) {
                // Ignore invalid dates
            }
        }

        if ($request->has('until')) {
            try {
                $until = Carbon::parse($request->query('until'));
                $query->where('updated_at', '<=', $until);
            } catch (\Exception $e) {
                // Ignore invalid dates
            }
        }

        return $query;
    }

    /**
     * Apply set filter (book category)
     */
    private function applySetFilter($query, Request $request)
    {
        if ($request->has('set')) {
            $setSpec = $request->query('set');
            $query->whereHas('category', function ($q) use ($setSpec) {
                $q->whereRaw('LOWER(REPLACE(name, " ", "-")) = ?', [strtolower($setSpec)]);
            });
        }

        return $query;
    }

    /**
     * Wrap content in OAI-PMH response envelope
     */
    private function wrapResponse(string $verb, string $params, string $content): string
    {
        $responseDate = Carbon::now()->toW3cString();
        $requestUrl = e($this->baseUrl);

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<OAI-PMH xmlns="http://www.openarchives.org/OAI/2.0/"
         xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
         xsi:schemaLocation="http://www.openarchives.org/OAI/2.0/ http://www.openarchives.org/OAI/2.0/OAI-PMH.xsd">' . "\n";
        $xml .= '  <responseDate>' . $responseDate . '</responseDate>' . "\n";
        $xml .= '  <request verb="' . e($verb) . '"' . $params . '>' . $requestUrl . '</request>' . "\n";
        $xml .= '  <' . $verb . '>' . $content . "\n";
        $xml .= '  </' . $verb . '>' . "\n";
        $xml .= '</OAI-PMH>';

        return $xml;
    }

    /**
     * Build OAI-PMH error response
     */
    private function errorResponse(string $code, string $message): string
    {
        $responseDate = Carbon::now()->toW3cString();
        $requestUrl = e($this->baseUrl);

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<OAI-PMH xmlns="http://www.openarchives.org/OAI/2.0/"
         xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
         xsi:schemaLocation="http://www.openarchives.org/OAI/2.0/ http://www.openarchives.org/OAI/2.0/OAI-PMH.xsd">' . "\n";
        $xml .= '  <responseDate>' . $responseDate . '</responseDate>' . "\n";
        $xml .= '  <request>' . $requestUrl . '</request>' . "\n";
        $xml .= '  <error code="' . e($code) . '">' . e($message) . '</error>' . "\n";
        $xml .= '</OAI-PMH>';

        return $xml;
    }
}
