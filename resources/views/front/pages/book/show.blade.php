@extends('front.app')
@section('seo')
    {{-- Google Scholar / Highwire Press Citation Meta Tags --}}
    @php
        $citationAbstract = trim(strip_tags($book->description ?? ''));
        $citationPdfUrl = $book->getCitationPdfUrl();
        $citationFulltextUrl = route('book.show', $book->slug);
        $citationAuthorsData = $book->citation_authors_data ?? [];
        $citationAuthors = $book->citation_authors ?? [];
    @endphp

    <meta name="citation_title" content="{{ $book->title }}">
    @if (!empty($citationAuthorsData))
        @foreach ($citationAuthorsData as $authorData)
            <meta name="citation_author" content="{{ $authorData['name'] }}">
            @if (!empty($authorData['affiliation']))
                <meta name="citation_author_institution" content="{{ $authorData['affiliation'] }}">
            @endif
            @if (!empty($authorData['email']))
                <meta name="citation_author_email" content="{{ $authorData['email'] }}">
            @endif
        @endforeach
    @else
        @foreach ($citationAuthors as $citationAuthor)
            <meta name="citation_author" content="{{ $citationAuthor }}">
        @endforeach
    @endif
    <meta name="citation_book_title" content="{{ $book->title }}">
    @if ($book->publisher)
        <meta name="citation_publisher" content="{{ $book->publisher }}">
    @endif
    @if ($book->publish_year)
        <meta name="citation_publication_date" content="{{ $book->publish_year }}">
        <meta name="citation_date" content="{{ $book->publish_year }}">
    @endif
    <meta name="citation_online_date" content="{{ $book->created_at->format('Y/m/d') }}">
    @if ($book->edition)
        <meta name="citation_edition" content="{{ $book->edition }}">
    @endif
    @if ($book->isbn)
        <meta name="citation_isbn" content="{{ $book->isbn }}">
    @endif
    @if ($citationAbstract)
        <meta name="citation_abstract" content="{{ $citationAbstract }}">
    @endif
    @if ($book->keywords && count($book->keywords) > 0)
        <meta name="citation_keywords"
            content="{{ collect($book->keywords)->map(function ($keyword) {
                    if (is_array($keyword)) {
                        return $keyword['value'] ?? implode(', ', $keyword);
                    }

                    if (is_object($keyword)) {
                        return $keyword->value ?? json_encode($keyword);
                    }

                    return $keyword;
                })->filter()->implode(', ') }}">
    @endif
    @if ($citationPdfUrl)
        <meta name="citation_pdf_url" content="{{ $citationPdfUrl }}">
    @endif
    <meta name="citation_fulltext_html_url" content="{{ $citationFulltextUrl }}">
    <meta name="citation_language" content="{{ $book->language ?: 'id' }}">

    {{-- Dublin Core Metadata --}}
    <meta name="DC.title" content="{{ $book->title }}">
    @if (!empty($citationAuthors))
        @foreach ($citationAuthors as $cAuthor)
            <meta name="DC.creator" content="{{ $cAuthor }}">
        @endforeach
    @else
        <meta name="DC.creator" content="{{ $book->author ?: 'Unknown' }}">
    @endif
    <meta name="DC.subject"
        content="{{ collect($book->keywords)->map(function ($keyword) {
                if (is_array($keyword)) {
                    return $keyword['value'] ?? implode(', ', $keyword);
                }
                if (is_object($keyword)) {
                    return $keyword->value ?? json_encode($keyword);
                }
                return $keyword;
            })->filter()->implode('; ') }}">
    <meta name="DC.description" content="{{ $citationAbstract }}">
    <meta name="DC.publisher" content="{{ $book->publisher ?: 'Unknown' }}">
    @if ($book->publish_year)
        <meta name="DC.issued" content="{{ $book->publish_year }}">
    @endif
    <meta name="DC.language" content="{{ $book->language ?: 'id' }}">
    @if ($book->isbn)
        <meta name="DC.identifier" content="ISBN:{{ $book->isbn }}">
    @endif
    <meta name="DC.rights" content="Copyright © {{ $book->publish_year ?: date('Y') }}">

    {{-- Schema.org JSON-LD (Scholarly Book / Open Access Repository) --}}
    @php
        $schemaOrg = [
            '@context' => 'https://schema.org',
            '@type' => 'Book',
            'name' => $book->title,
            'headline' => $book->title,
            'author' => collect($citationAuthorsData)->map(fn($a) => [
                '@type' => 'Person',
                'name' => $a['name'],
                'affiliation' => !empty($a['affiliation']) ? ['@type' => 'Organization', 'name' => $a['affiliation']] : null,
            ])->filter()->values()->toArray() ?: [['@type' => 'Person', 'name' => $book->author ?: 'Unknown']],
            'publisher' => [
                '@type' => 'Organization',
                'name' => $book->publisher ?: 'Unknown',
            ],
            'description' => $citationAbstract,
            'image' => $book->getThumbnail(),
            'url' => route('book.show', $book->slug),
            'inLanguage' => $book->language ?: 'id',
            'isAccessibleForFree' => true,
            'keywords' => collect($book->keywords)
                ->map(function ($keyword) {
                    if (is_array($keyword)) {
                        return $keyword['value'] ?? implode(', ', $keyword);
                    }
                    if (is_object($keyword)) {
                        return $keyword->value ?? json_encode($keyword);
                    }
                    return $keyword;
                })
                ->filter()
                ->implode(', '),
        ];

        if ($citationPdfUrl) {
            $schemaOrg['encoding'] = [
                '@type' => 'MediaObject',
                'contentUrl' => $citationPdfUrl,
                'encodingFormat' => 'application/pdf',
            ];
        }

        if ($book->publish_year) {
            $schemaOrg['datePublished'] = (string) $book->publish_year;
        }
        if ($book->isbn) {
            $schemaOrg['isbn'] = $book->isbn;
        }
        if ($book->edition) {
            $schemaOrg['bookEdition'] = $book->edition;
        }
        if ($book->pages) {
            $schemaOrg['numberOfPages'] = $book->pages;
        }
    @endphp
    <script type="application/ld+json">
        {!! json_encode($schemaOrg, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
    </script>

    {{-- COinS (ContextObjects in Spans) --}}
    @php
        $coinsParams = [
            'ctx_ver' => 'Z39.88-2004',
            'rft_val_fmt' => 'info:ofi/fmt:kev:mtx:book',
            'rft.title' => $book->title,
            'rft.au' => $book->author,
            'rft.pub' => $book->publisher,
            'rft.date' => $book->publish_year,
            'rft.isbn' => $book->isbn,
            'rft.edition' => $book->edition,
            'rft.pages' => $book->pages,
        ];

        $coinsTitle = http_build_query(array_filter($coinsParams, fn($v) => !is_null($v) && $v !== ''));
    @endphp
    <span class="Z3988" title="{{ $coinsTitle }}"></span>
@endsection

@section('content')
    <!-- BOOK DETAIL
    ============================================= -->
    <section id="book-detail" class="wide-60 blog-page-section division">
        <div class="container">
            <div class="row">

                <!-- MAIN CONTENT -->
                <div class="col-lg-8">
                    <div class="posts-wrapper pr-25">

                        <!-- BOOK HEADER -->
                        <div class="row mb-40">
                            <div class="col-md-5 mb-30">
                                <div class="radius-06 overflow-hidden">
                                    <img src="{{ $book->getThumbnail() }}" alt="{{ $book->title }}"
                                         class="img-fluid w-100" style="min-height: 380px; object-fit: cover;">
                                </div>
                            </div>
                            <div class="col-md-7">
                                <p class="post-tag txt-upcase mb-10">
                                    <a href="{{ route('book.category', $book->category->slug) }}" class="theme-color">
                                        {{ $book->category->name }}
                                    </a>
                                </p>

                                <h1 class="h4-lg mb-10">{{ $book->title }}</h1>
                                <p class="p-md grey-color mb-20">
                                    <span class="flaticon-user mr-1"></span>
                                    {{ $book->author ?: '-' }}
                                </p>

                                <!-- INFO BADGES -->
                                <div class="mb-20">
                                    @if($book->isbn)
                                        <span class="badge badge-light p-2 mr-1 mb-1">ISBN: {{ $book->isbn }}</span>
                                    @endif
                                    @if($book->qrcbn)
                                        <span class="badge badge-light p-2 mr-1 mb-1">QRCBN: {{ $book->qrcbn }}</span>
                                    @endif
                                    @if($book->edition)
                                        <span class="badge badge-light p-2 mr-1 mb-1">Edisi: {{ $book->edition }}</span>
                                    @endif
                                    @if($book->publish_year)
                                        <span class="badge badge-light p-2 mb-1">Tahun: {{ $book->publish_year }}</span>
                                    @endif
                                </div>

                                <!-- REPOSITORY ACCESS BANNER -->
                                <div class="bg-lightgrey p-3 radius-04 mb-25 border">
                                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                                        <div>
                                            <small class="grey-color d-block text-uppercase" style="font-size: 11px; font-weight: 600; letter-spacing: 0.5px;">Akses Repositori</small>
                                            <span class=" px-3 py-2 mt-1" style="font-size: 13px;">
                                                <span class="flaticon-document mr-1"></span> Akses Terbuka (Open Access)
                                            </span>
                                        </div>

                                    </div>
                                </div>

                                <!-- PUBLISHER & LANGUAGE -->
                                <div class="row">
                                    <div class="col-6 ">
                                        <small class="grey-color d-block">Penerbit</small>
                                        <p class="p-sm txt-500 mb-0">{{ $book->publisher ?: '-' }}</p>
                                    </div>
                                    <div class="col-6">
                                        <small class="grey-color d-block">Bahasa</small>
                                        <p class="p-sm txt-500 mb-0">
                                            @if ($book->language == 'en') English
                                            @elseif ($book->language == 'id') Indonesia
                                            @elseif ($book->language == 'jp') 日本語
                                            @else -
                                            @endif
                                        </p>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- DESCRIPTION -->
                        @if ($book->description)
                            <div class="mb-40">
                                <h5 class="h5-md mb-20">Deskripsi Buku</h5>
                                <div class="post-txt">
                                    {!! $book->description !!}
                                </div>
                            </div>
                        @endif

                        <!-- KEYWORDS -->
                        @if ($book->keywords && count($book->keywords) > 0)
                            <div class="mb-40">
                                <h5 class="h5-md mb-20">Keywords</h5>
                                <div>
                                    @foreach ($book->keywords as $keyword)
                                        @php
                                            $keywordText = is_array($keyword)
                                                ? ($keyword['value'] ?? implode(', ', $keyword))
                                                : (is_object($keyword)
                                                    ? ($keyword->value ?? json_encode($keyword))
                                                    : $keyword);
                                        @endphp
                                        <span class="badge badge-light mr-1 mb-2 p-2">{{ $keywordText }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif



                        <!-- HOW TO CITE (SITASI) -->
                        @php
                            $apaAuthors = !empty($citationAuthors) ? implode(', ', $citationAuthors) : ($book->author ?: 'Penulis');
                            $apaYear = $book->publish_year ?: $book->created_at->format('Y');
                            $apaCitation = $apaAuthors . ' (' . $apaYear . '). ' . $book->title . '. ' . ($book->publisher ?: 'Penerbit') . ($book->isbn ? '. ISBN: ' . $book->isbn : '') . '.';
                            $bibtexCitation = "@book{book_" . $book->id . ",\n" .
                                "  title={" . addslashes($book->title) . "},\n" .
                                "  author={" . addslashes(implode(' and ', $citationAuthors ?: [$book->author ?: 'Unknown'])) . "},\n" .
                                "  year={" . $apaYear . "},\n" .
                                "  publisher={" . addslashes($book->publisher ?: 'Penerbit') . "},\n" .
                                ($book->isbn ? "  isbn={" . $book->isbn . "},\n" : "") .
                                "  url={" . route('book.show', $book->slug) . "}\n" .
                                "}";
                        @endphp
                        <div class="mb-40 p-4 bg-lightgrey radius-06 border">
                            <div class="d-flex justify-content-between align-items-center mb-15 flex-wrap">
                                <h5 class="h5-sm mb-1 mb-sm-0">
                                    <span class="flaticon-bookmark mr-1 text-primary"></span> Cara Mengutip (How to Cite)
                                </h5>
                                <button type="button" class="btn btn-sm btn-tra-grey theme-hover" id="btn-copy-citation" onclick="copyCitationText('{{ addslashes($apaCitation) }}')">
                                    <span class="flaticon-copy mr-1"></span> Salin Sitasi APA
                                </button>
                            </div>
                            <div class="p-3 bg-white radius-04 border mb-2 font-italic" style="font-size: 13px; line-height: 1.6; color: #334155;">
                                {{ $apaCitation }}
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-2 flex-wrap">
                                <small class="text-muted">Format Standar: APA 7th Edition &bull; Terindeks OAI-PMH</small>
                                <a href="#bibtexCollapse" data-toggle="collapse" class="small text-primary font-weight-bold">
                                    Lihat Format BibTeX &darr;
                                </a>
                            </div>
                            <div class="collapse mt-3" id="bibtexCollapse">
                                <pre class="bg-white p-3 border radius-04 mb-0 text-left" style="font-size: 11px; max-height: 160px; overflow-y: auto;"><code>{{ $bibtexCitation }}</code></pre>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- SIDEBAR -->
                <aside id="sidebar" class="col-lg-4">

                    <!-- REPOSITORY METADATA -->
                    <div class="sidebar-div mb-50">
                        <h6 class="h6-xl">Metadata Repositori</h6>

                        <ul class="blog-category-list clearfix">
                            <li>
                                <p><span class="grey-color">Kategori</span>
                                <a href="{{ route('book.category', $book->category->slug) }}" class="theme-color" style="float: right;">{{ $book->category->name }}</a></p>
                            </li>
                            <li>
                                <p><span class="grey-color">Ditambahkan</span>
                                <span style="float: right;">{{ $book->created_at->format('d M Y') }}</span></p>
                            </li>
                            <li>
                                <p><span class="grey-color">ISBN</span>
                                <span style="float: right;">{{ $book->isbn ?: '-' }}</span></p>
                            </li>
                            <li>
                                <p><span class="grey-color">QRCBN</span>
                                <span style="float: right;">{{ $book->qrcbn ?: '-' }}</span></p>
                            </li>
                            <li>
                                <p><span class="grey-color">Edisi</span>
                                <span style="float: right;">{{ $book->edition ?: '-' }}</span></p>
                            </li>
                            <li>
                                <p><span class="grey-color">Ukuran</span>
                                <span style="float: right;">{{ $book->size ?: '-' }}</span></p>
                            </li>
                            <li>
                                <p><span class="grey-color">Berat</span>
                                <span style="float: right;">{{ $book->weight ? $book->weight . ' gr' : '-' }}</span></p>
                            </li>
                            <li>
                                <p><span class="grey-color">Halaman</span>
                                <span style="float: right;">{{ $book->pages ? $book->pages . ' halaman' : '-' }}</span></p>
                            </li>
                        </ul>
                    </div>

                    <!-- PREVIEW BUTTON -->
                    @if ($citationPdfUrl)
                        <div class="mb-30">
                            <a href="{{ $citationPdfUrl }}" target="_blank" rel="noopener"
                               class="btn btn-theme btn-block">
                                Baca / Unduh Preview (PDF)
                            </a>

                        </div>
                    @endif

                    <!-- ISBN & QRCBN IMAGES -->
                    @if ($book->isbn_file || $book->qrcbn_file)
                        <div class="sidebar-div mb-50">
                            <h6 class="h6-xl mb-20">Sertifikat & Barcode</h6>

                            @if ($book->isbn_file)
                                @php
                                    $isIsbnPdf = \Illuminate\Support\Str::endsWith(strtolower($book->isbn_file), '.pdf');
                                @endphp
                                <div class="mb-25">
                                    <small class="grey-color d-block mb-2 font-weight-bold" style="font-size: 12px;">
                                        Barcode / Sertifikat ISBN
                                    </small>
                                    @if ($isIsbnPdf)
                                        <a href="{{ asset('storage/' . $book->isbn_file) }}" target="_blank"
                                           class="btn btn-outline-secondary btn-sm btn-block">
                                            <span class="flaticon-document mr-2"></span> Buka Sertifikat ISBN (PDF)
                                        </a>
                                    @else
                                        <div class="border rounded p-2 bg-white text-center shadow-sm">
                                            <a href="{{ asset('storage/' . $book->isbn_file) }}" target="_blank" title="Klik untuk melihat ukuran penuh">
                                                <img src="{{ asset('storage/' . $book->isbn_file) }}"
                                                     alt="Barcode / Sertifikat ISBN {{ $book->isbn }}"
                                                     class="img-fluid rounded"
                                                     style="max-height: 220px; width: 100%; object-fit: contain;">
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            @if ($book->qrcbn_file)
                                @php
                                    $isQrcbnPdf = \Illuminate\Support\Str::endsWith(strtolower($book->qrcbn_file), '.pdf');
                                @endphp
                                <div class="mb-25">
                                    <small class="grey-color d-block mb-2 font-weight-bold" style="font-size: 12px;">
                                        Barcode / Sertifikat QRCBN
                                    </small>
                                    @if ($isQrcbnPdf)
                                        <a href="{{ asset('storage/' . $book->qrcbn_file) }}" target="_blank"
                                           class="btn btn-outline-secondary btn-sm btn-block">
                                            <span class="flaticon-document mr-2"></span> Buka Sertifikat QRCBN (PDF)
                                        </a>
                                    @else
                                        <div class="border rounded p-2 bg-white text-center shadow-sm">
                                            <a href="{{ asset('storage/' . $book->qrcbn_file) }}" target="_blank" title="Klik untuk melihat ukuran penuh">
                                                <img src="{{ asset('storage/' . $book->qrcbn_file) }}"
                                                     alt="Barcode / Sertifikat QRCBN {{ $book->qrcbn }}"
                                                     class="img-fluid rounded"
                                                     style="max-height: 220px; width: 100%; object-fit: contain;">
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- RELATED BOOKS -->
                    @if ($related_books->count() > 0)
                        <div class="sidebar-div mb-50">
                            <h6 class="h6-xl">Buku Terkait</h6>

                            @foreach ($related_books as $related)
                                <div class="d-flex align-items-center mb-20 {{ !$loop->last ? 'pb-20 b-bottom' : '' }}">
                                    <div class="mr-15" style="width: 65px; height: 85px; overflow: hidden; border-radius: 4px; flex-shrink: 0;">
                                        <a href="{{ route('book.show', $related->slug) }}">
                                            <img src="{{ $related->getThumbnail() }}" alt="{{ $related->title }}"
                                                 class="w-100 h-100" style="object-fit: cover;">
                                        </a>
                                    </div>
                                    <div>
                                        <h6 class="h6-xs mb-1" style="line-height: 1.3;">
                                            <a href="{{ route('book.show', $related->slug) }}">
                                                {{ Str::limit($related->title, 40) }}
                                            </a>
                                        </h6>
                                        <p class="p-sm grey-color mb-0"><span class="badge badge-light border">{{ $related->publish_year ?: $related->created_at->format('Y') }}</span> <span class="text-success small font-weight-bold ml-1">Open Access</span></p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                </aside>

            </div>
        </div>
    </section>

    <script>
        function copyCitationText(text) {
            var btn = document.getElementById('btn-copy-citation');
            if (!navigator.clipboard) {
                var textArea = document.createElement("textarea");
                textArea.value = text;
                document.body.appendChild(textArea);
                textArea.select();
                try {
                    document.execCommand('copy');
                    showCopiedFeedback(btn);
                } catch (err) {}
                document.body.removeChild(textArea);
                return;
            }
            navigator.clipboard.writeText(text).then(function() {
                showCopiedFeedback(btn);
            });
        }

        function showCopiedFeedback(btn) {
            if (!btn) return;
            var original = btn.innerHTML;
            btn.innerHTML = '<span class="flaticon-check mr-1"></span> Tersalin!';
            btn.classList.remove('btn-tra-grey');
            btn.classList.add('btn-success', 'text-white');
            setTimeout(function() {
                btn.innerHTML = original;
                btn.classList.remove('btn-success', 'text-white');
                btn.classList.add('btn-tra-grey');
            }, 2500);
        }
    </script>
@endsection
