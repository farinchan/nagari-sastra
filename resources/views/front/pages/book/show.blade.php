@extends('front.app')
@section('seo')
    {{-- Google Scholar / Highwire Press Citation Meta Tags --}}
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
        <meta name="citation_abstract" content="{{ $citationAbstract}}">
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
    <meta name="DC.creator" content="{{ $book->author ?: 'Unknown' }}">
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

    {{-- Schema.org JSON-LD (Book) --}}
    @php
        $schemaOrg = [
            '@context' => 'https://schema.org',
            '@type' => 'Book',
            'name' => $book->title,
            'author' => collect($citationAuthors)->map(fn($name) => [
                '@type' => 'Person',
                'name' => $name,
            ])->values()->toArray() ?: [['@type' => 'Person', 'name' => $book->author ?: 'Unknown']],
            'publisher' => [
                '@type' => 'Organization',
                'name' => $book->publisher ?: 'Unknown',
            ],
            'description' => $citationAbstract,
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

                                <h2 class="h4-lg mb-10">{{ $book->title }}</h2>
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

                                <!-- PRICE BOX -->
                                <div class="bg-lightgrey p-3 radius-04 mb-20">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <small class="grey-color d-block mb-1">Harga</small>
                                            <h3 class="h3-lg mb-0 theme-color">
                                                @if($book->price == 0)
                                                    Gratis
                                                @else
                                                    Rp {{ number_format($book->price, 0, ',', '.') }}
                                                @endif
                                            </h3>
                                        </div>
                                        <div class="text-right">
                                            <small class="grey-color d-block mb-1">Stok</small>
                                            @if ($book->stock > 0)
                                                <span class="badge badge-success px-3 py-2">{{ $book->stock }} tersedia</span>
                                            @else
                                                <span class="badge badge-danger px-3 py-2">Stok habis</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- PUBLISHER & LANGUAGE -->
                                <div class="row">
                                    <div class="col-6">
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



                    </div>
                </div>

                <!-- SIDEBAR -->
                <aside id="sidebar" class="col-lg-4">

                    <!-- BOOK INFO -->
                    <div class="sidebar-div mb-50">
                        <h6 class="h6-xl">Informasi Buku</h6>

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
                    @if ($book->getPreviewFile())
                        <div class="mb-30">
                            <a href="{{ route('book.preview', $book->slug) }}" target="_blank"
                               class="btn btn-theme btn-block">
                                <span class="flaticon-document mr-2"></span> Preview Buku
                            </a>
                        </div>
                    @endif

                    <!-- ISBN & QRCBN FILES -->
                    @if ($book->isbn_file || $book->qrcbn_file)
                        <div class="sidebar-div mb-50">
                            <h6 class="h6-xl">Dokumen</h6>
                            @if ($book->isbn_file)
                                <a href="{{ asset('storage/' . $book->isbn_file) }}" target="_blank"
                                   class="btn btn-outline-secondary btn-block mb-10" style="text-align: left;">
                                    <span class="flaticon-document mr-2"></span> Lihat Sertifikat ISBN
                                </a>
                            @endif
                            @if ($book->qrcbn_file)
                                <a href="{{ asset('storage/' . $book->qrcbn_file) }}" target="_blank"
                                   class="btn btn-outline-secondary btn-block mb-10" style="text-align: left;">
                                    <span class="flaticon-document mr-2"></span> Lihat Sertifikat QRCBN
                                </a>
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
                                        <p class="p-sm grey-color mb-0">Rp {{ number_format($related->price, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                </aside>

            </div>
        </div>
    </section>

@endsection
