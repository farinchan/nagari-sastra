@extends('front.app')
@section('seo')
    <title>{{ $meta['title'] }}</title>
    <meta name="description" content="{{ $meta['description'] }}">
    <meta name="keywords" content="{{ $meta['keywords'] }}">
    <meta name="author" content="{{ $book->authorString ?: $book->author }}">

    @php
        $citationAbstract = trim(strip_tags($book->description ?? ''));
        $citationPdfUrl = $book->getPreviewFile();
        $citationFulltextUrl = route('book.show', $book->slug);
        $citationAuthors = $book->citation_authors ?? [];
    @endphp

    <meta name="citation_title" content="{{ $book->title }}">
    @foreach ($citationAuthors as $citationAuthor)
        <meta name="citation_author" content="{{ $citationAuthor }}">
    @endforeach
    <meta name="citation_book_title" content="{{ $book->title }}">
    @if ($book->publisher)
        <meta name="citation_publisher" content="{{ $book->publisher }}">
    @endif
    @if ($book->publish_year)
        <meta name="citation_publication_date" content="{{ $book->publish_year }}">
    @endif
    @if ($book->edition)
        <meta name="citation_edition" content="{{ $book->edition }}">
    @endif
    @if ($book->isbn)
        <meta name="citation_isbn" content="{{ $book->isbn }}">
    @endif
    @if ($citationAbstract)
        <meta name="citation_abstract" content="{{ Str::limit($citationAbstract, 500, '') }}">
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

    <meta property="og:title" content="{{ $meta['title'] }}">
    <meta property="og:description" content="{{ $meta['description'] }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ route('book.show', $book->slug) }}">
    <link rel="canonical" href="{{ route('book.show', $book->slug) }}">
    <meta property="og:image" content="{{ $book->getThumbnail() }}">

    <!-- Dublin Core Metadata -->
    <meta name="DC.title" content="{{ $book->title }}">
    <meta name="DC.creator" content="{{ $book->authorString ?: $book->author ?: 'Unknown' }}">
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
    <meta name="DC.description" content="{{ Str::limit($citationAbstract, 160, '') }}">
    <meta name="DC.publisher" content="{{ $book->publisher ?: 'Unknown' }}">
    @if ($book->publish_year)
        <meta name="DC.issued" content="{{ $book->publish_year }}">
    @endif
    <meta name="DC.language" content="{{ $book->language ?: 'id' }}">
    @if ($book->isbn)
        <meta name="DC.identifier" content="ISBN:{{ $book->isbn }}">
    @endif
    <meta name="DC.rights" content="Copyright © {{ $book->publish_year ?: date('Y') }}">

    <!-- Schema.org JSON-LD -->
    @php
        $schemaOrg = [
            '@context' => 'https://schema.org',
            '@type' => 'Book',
            'name' => $book->title,
            'author' => [
                '@type' => 'Person',
                'name' => $book->authorString ?: $book->author ?: 'Unknown',
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => $book->publisher ?: 'Unknown',
            ],
            'description' => Str::limit($citationAbstract, 500, ''),
            'image' => $book->getThumbnail(),
            'url' => route('book.show', $book->slug),
            'inLanguage' => $book->language ?: 'id',
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
            'offers' => [
                '@type' => 'Offer',
                'price' => $book->price,
                'priceCurrency' => 'IDR',
                'availability' => $book->stock > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
            ],
        ];

        if ($book->publish_year) {
            $schemaOrg['datePublished'] = $book->publish_year;
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
        {!! json_encode($schemaOrg, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>

    <!-- COinS (ContextObjects in Spans) -->
    @php
        $coinsParams = [
            'ctx_ver' => 'Z39.88-2004',
            'rft_val_fmt' => 'info:ofi/fmt:kev:mtx:book',
            'rft.title' => $book->title,
            'rft.au' => $book->authorString ?: $book->author,
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

                <div class="col-lg-8">
                    <div class="single-post-wrapper ">
                        <div class="card shadow-sm border-0 mb-4 overflow-hidden" style="background: #fff;">
                            <div class="row g-0">
                                <div class="col-md-5 p-3 p-md-4 bg-light">
                                    <div class="position-relative rounded overflow-hidden shadow-sm"
                                        style="min-height: 420px;">
                                        <img src="{{ $book->getThumbnail() }}" alt="{{ $book->title }}"
                                            class="img-fluid w-100 h-100" style="object-fit: cover; min-height: 420px;">
                                    </div>


                                </div>

                                <div class="col-md-7 p-3 p-md-4">
                                    <p class="post-tag txt-upcase mb-2">
                                        <a href="{{ route('book.category', $book->category->slug) }}" class="theme-color">
                                            {{ $book->category->name }}
                                        </a>
                                    </p>

                                    <h2 class="h4-lg mb-2">{{ $book->title }}</h2>
                                    <p class="p-md grey-color mb-3">
                                        by <strong>{{ $book->authorString ?: $book->author ?: '-' }}</strong>
                                    </p>

                                    <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                                        <span class="badge badge-light p-2">
                                            ISBN: {{ $book->isbn ?: '-' }}
                                        </span>
                                        <span class="badge badge-light p-2">
                                            Edisi: {{ $book->edition ?: '-' }}
                                        </span>
                                        <span class="badge badge-light p-2">
                                            Tahun: {{ $book->publish_year ?: '-' }}
                                        </span>
                                    </div>

                                    <div class="book-price-box p-3 rounded mb-4"
                                        style="background: linear-gradient(135deg, #f8fbff 0%, #eef4ff 100%); border: 1px solid rgba(0,0,0,.06);">
                                        <div class="d-flex align-items-end justify-content-between flex-wrap gap-2">
                                            <div>
                                                <small class="text-muted d-block mb-1">Harga</small>
                                                <h3 class="h3-lg mb-0 theme-color">
                                                    Rp {{ number_format($book->price, 0, ',', '.') }}
                                                </h3>
                                            </div>
                                            <div class="text-end">
                                                <small class="text-muted d-block mb-1">Stok</small>
                                                @if ($book->stock > 0)
                                                    <span class="badge badge-success px-3 py-2">{{ $book->stock }}
                                                        tersedia</span>
                                                @else
                                                    <span class="badge badge-danger px-3 py-2">Stok habis</span>
                                                @endif
                                            </div>
                                        </div>

                                    </div>


                                    <div class="row mb-4">
                                        <div class="col-sm-6 mb-3 mb-sm-0">
                                            <div class="p-3 rounded h-100"
                                                style="background: #fafafa; border: 1px solid rgba(0,0,0,.06);">
                                                <small class="text-muted d-block mb-1">Penerbit</small>
                                                <p class="mb-0 fw-bold">{{ $book->publisher ?: '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="p-3 rounded h-100"
                                                style="background: #fafafa; border: 1px solid rgba(0,0,0,.06);">
                                                <small class="text-muted d-block mb-1">Bahasa</small>
                                                <p class="mb-0 fw-bold">
                                                    @if ($book->language == 'en')
                                                        English
                                                    @elseif ($book->language == 'id')
                                                        Indonesia
                                                    @elseif ($book->language == 'jp')
                                                        日本語
                                                    @else
                                                        -
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>



                                </div>
                            </div>
                        </div>


                    </div>
                </div>

                <!-- SIDEBAR -->
                <aside id="sidebar" class="col-lg-4">

                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-body p-4">
                            <h6 class="h6-xl mb-3">Informasi Buku</h6>

                            <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                                <span class="text-muted">Kategori</span>
                                <a href="{{ route('book.category', $book->category->slug) }}"
                                    class="theme-color fw-bold">
                                    {{ $book->category->name }}
                                </a>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                                <span class="text-muted">Ditambahkan</span>
                                <span class="fw-bold">{{ $book->created_at->format('d M Y') }}</span>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                                <span class="text-muted">ISBN</span>
                                <span class="fw-bold">{{ $book->isbn ?: '-' }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                                <span class="text-muted">Edisi</span>
                                <span class="fw-bold text-end">{{ $book->edition ?: '-' }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                                <span class="text-muted">Ukuran</span>
                                <span class="fw-bold text-end">{{ $book->size ?: '-' }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                                <span class="text-muted">Berat</span>
                                <span class="fw-bold text-end">{{ $book->weight ?: '-' }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-0">
                                <span class="text-muted">Jumlah Halaman</span>
                                <span
                                    class="fw-bold text-end">{{ $book->pages ? $book->pages . ' halaman' : 'Halaman tidak tersedia' }}</span>
                            </div>
                        </div>
                    </div>

                    @if ($book->getPreviewFile())
                        <div class="mb-4">
                            <a href="{{ route('book.preview', $book->slug) }}" target="_blank" style="display: block; width: 100%; padding: 12px 18px; background: #0d6efd; color: #fff; border-radius: 6px; text-decoration: none; font-weight: 600; text-align: center;">
                                Preview Buku
                            </a>
                        </div>
                    @endif



                    @if ($related_books->count() > 0)
                        <div class="card shadow-sm border-0">
                            <div class="card-body p-4">
                                <h6 class="h6-xl mb-3">Buku Terkait</h6>

                                @foreach ($related_books as $related)
                                    <div
                                        class="d-flex align-items-center mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                                        <div class="flex-shrink-0 me-3"
                                            style="width: 72px; height: 96px; overflow: hidden; border-radius: 8px;">
                                            <a href="{{ route('book.show', $related->slug) }}">
                                                <img src="{{ $related->getThumbnail() }}" alt="{{ $related->title }}"
                                                    class="w-100 h-100" style="object-fit: cover;">
                                            </a>
                                        </div>
                                        <div>
                                            <h6 class="mb-1" style="line-height: 1.3;">
                                                <a href="{{ route('book.show', $related->slug) }}" class="theme-color">
                                                    {{ Str::limit($related->title, 40) }}
                                                </a>
                                            </h6>
                                            <p class="p-sm grey-color mb-0">Rp
                                                {{ number_format($related->price, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                    @endif

                </aside>

            </div>

            <div class="row g-4">
                @if ($book->description)
                    <div class="col-12">
                        <div class="card shadow-sm border-0 mb-10">
                            <div class="card-body p-4">
                                <h5 class="h5-md mb-3">Deskripsi Buku</h5>
                                <div class="post-content">
                                    {!! $book->description !!}
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($book->keywords && count($book->keywords) > 0)
                    <div class="col-12 mt-10 mb-10">
                        <div class="card shadow-sm border-0">
                            <div class="card-body p-4">
                                <h5 class="h5-md mb-3">Keywords</h5>
                                <div>
                                    @foreach ($book->keywords as $keyword)
                                        @php
                                            $keywordText = is_array($keyword)
                                                ? $keyword['value'] ?? implode(', ', $keyword)
                                                : (is_object($keyword)
                                                    ? $keyword->value ?? json_encode($keyword)
                                                    : $keyword);
                                        @endphp
                                        <span class="badge badge-light mr-2 mb-2">{{ $keywordText }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="col-12 mt-10">
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-4">
                            <h5 class="h5-md mb-4">Cite This Book</h5>

                            <ul class="nav nav-tabs" id="citationTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="apa-tab" data-bs-toggle="tab"
                                        data-bs-target="#apa" type="button" role="tab">APA</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="mla-tab" data-bs-toggle="tab" data-bs-target="#mla"
                                        type="button" role="tab">MLA</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="chicago-tab" data-bs-toggle="tab"
                                        data-bs-target="#chicago" type="button" role="tab">Chicago</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="harvard-tab" data-bs-toggle="tab"
                                        data-bs-target="#harvard" type="button" role="tab">Harvard</button>
                                </li>
                            </ul>

                            <div class="tab-content mt-3" id="citationTabContent">
                                @php
                                    $citationAuthors = collect($book->citation_authors ?? [])
                                        ->filter()
                                        ->values();
                                    $citationAuthorCount = $citationAuthors->count();
                                    $year = $book->publish_year ?: date('Y');
                                    $publisher = $book->publisher ?: 'Unknown Publisher';
                                    $isbn = $book->isbn ?: 'N/A';

                                    $formatApaAuthor = function (string $name): string {
                                        $name = trim($name);

                                        if ($name === '') {
                                            return 'Unknown Author';
                                        }

                                        $parts = preg_split('/\s+/', $name, -1, PREG_SPLIT_NO_EMPTY);

                                        if (count($parts) === 1) {
                                            return $parts[0];
                                        }

                                        $surname = array_pop($parts);
                                        $initials = collect($parts)
                                            ->map(function ($part) {
                                                $initial = mb_substr($part, 0, 1);

                                                return Str::upper($initial) . '.';
                                            })
                                            ->implode(' ');

                                        return trim($surname . ', ' . $initials);
                                    };

                                    $formatEtAlAuthor = function (
                                        string $name,
                                        bool $uppercaseSurname = false,
                                    ): string {
                                        $name = trim($name);

                                        if ($name === '') {
                                            return 'Unknown Author';
                                        }

                                        $parts = preg_split('/\s+/', $name, -1, PREG_SPLIT_NO_EMPTY);

                                        if (count($parts) === 1) {
                                            return $uppercaseSurname ? Str::upper($parts[0]) : $parts[0];
                                        }

                                        $surname = array_pop($parts);
                                        $givenNames = implode(' ', $parts);
                                        $surname = $uppercaseSurname ? Str::upper($surname) : $surname;

                                        return trim($surname . ', ' . $givenNames);
                                    };

                                    if ($citationAuthorCount > 0) {
                                        $firstAuthor = $citationAuthors->first();
                                        $secondAuthor = $citationAuthors->get(1);

                                        $citationAuthorText =
                                            $citationAuthorCount > 2
                                                ? $formatEtAlAuthor($firstAuthor) . ', et al.'
                                                : ($citationAuthorCount === 2
                                                    ? $formatEtAlAuthor($firstAuthor) .
                                                        ', ' .
                                                        $formatEtAlAuthor($secondAuthor)
                                                    : $formatEtAlAuthor($firstAuthor));

                                        $citationAuthorTextUpper =
                                            $citationAuthorCount > 2
                                                ? $formatEtAlAuthor($firstAuthor, true) . ', et al.'
                                                : ($citationAuthorCount === 2
                                                    ? $formatEtAlAuthor($firstAuthor, true) .
                                                        ', ' .
                                                        $formatEtAlAuthor($secondAuthor, true)
                                                    : $formatEtAlAuthor($firstAuthor, true));

                                        $apaAuthorText = $citationAuthors
                                            ->map(fn($author) => $formatApaAuthor($author))
                                            ->values();

                                        if ($citationAuthorCount > 2) {
                                            $apaAuthorText = $apaAuthorText->take(1)->push('et al.')->implode(', ');
                                        } elseif ($citationAuthorCount === 2) {
                                            $apaAuthorText = $apaAuthorText->slice(0, 2)->implode(', ');
                                        } else {
                                            $apaAuthorText = $apaAuthorText->implode(', ');
                                        }
                                    } else {
                                        $citationAuthorText = $book->authorString ?: $book->author ?: 'Unknown Author';
                                        $citationAuthorTextUpper = Str::upper($citationAuthorText);
                                        $apaAuthorText = $citationAuthorText;
                                    }

                                    // APA Format
                                    $apaFormat = "$apaAuthorText ($year). {$book->title}. $publisher.";

                                    // MLA Format
                                    $mlaFormat = "$citationAuthorText. {$book->title}. $publisher, $year.";

                                    // Chicago Format
                                    $chicagoFormat = "$citationAuthorTextUpper. {$book->title}. $publisher, $year.";

                                    // Harvard Format
                                    $harvardFormat = "$citationAuthorText. {$book->title}. $publisher, $year.";
                                @endphp

                                <div class="tab-pane fade show active" id="apa" role="tabpanel">
                                    <div class="position-relative">
                                        <pre class="bg-light p-3 rounded" style="overflow-x: auto; font-size: 0.9rem;">{{ $apaFormat }}</pre>
                                        <button class="btn btn-sm btn-outline-primary position-absolute top-0 end-0 m-2"
                                            onclick="copyToClipboard(this, '{{ addslashes($apaFormat) }}')">Copy</button>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="mla" role="tabpanel">
                                    <div class="position-relative">
                                        <pre class="bg-light p-3 rounded" style="overflow-x: auto; font-size: 0.9rem;">{{ $mlaFormat }}</pre>
                                        <button class="btn btn-sm btn-outline-primary position-absolute top-0 end-0 m-2"
                                            onclick="copyToClipboard(this, '{{ addslashes($mlaFormat) }}')">Copy</button>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="chicago" role="tabpanel">
                                    <div class="position-relative">
                                        <pre class="bg-light p-3 rounded" style="overflow-x: auto; font-size: 0.9rem;">{{ $chicagoFormat }}</pre>
                                        <button class="btn btn-sm btn-outline-primary position-absolute top-0 end-0 m-2"
                                            onclick="copyToClipboard(this, '{{ addslashes($chicagoFormat) }}')">Copy</button>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="harvard" role="tabpanel">
                                    <div class="position-relative">
                                        <pre class="bg-light p-3 rounded" style="overflow-x: auto; font-size: 0.9rem;">{{ $harvardFormat }}</pre>
                                        <button class="btn btn-sm btn-outline-primary position-absolute top-0 end-0 m-2"
                                            onclick="copyToClipboard(this, '{{ addslashes($harvardFormat) }}')">Copy</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        function copyToClipboard(button, text) {
            navigator.clipboard.writeText(text).then(() => {
                const originalText = button.textContent;
                button.textContent = 'Copied!';
                button.classList.add('btn-success');
                button.classList.remove('btn-outline-primary');

                setTimeout(() => {
                    button.textContent = originalText;
                    button.classList.remove('btn-success');
                    button.classList.add('btn-outline-primary');
                }, 2000);
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const tabButtons = document.querySelectorAll('#citationTabs [data-bs-target]');
            const tabPanes = document.querySelectorAll('#citationTabContent .tab-pane');

            tabButtons.forEach(function(button) {
                button.addEventListener('click', function(event) {
                    event.preventDefault();

                    const targetSelector = button.getAttribute('data-bs-target');
                    const targetPane = document.querySelector(targetSelector);

                    tabButtons.forEach(function(item) {
                        item.classList.remove('active');
                        item.setAttribute('aria-selected', 'false');
                    });

                    tabPanes.forEach(function(pane) {
                        pane.classList.remove('show', 'active');
                    });

                    button.classList.add('active');
                    button.setAttribute('aria-selected', 'true');

                    if (targetPane) {
                        targetPane.classList.add('show', 'active');
                    }
                });
            });
        });
    </script>

@endsection
