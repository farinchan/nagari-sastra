<div class="card mb-9">
    <div class="card-body pt-9 pb-0">
        <div class="d-flex flex-wrap flex-sm-nowrap mb-6">
            {{-- Thumbnail --}}
            @if($book->thumbnail)
            <div class="me-7 mb-4">
                <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
                    <img src="{{ asset('storage/' . $book->thumbnail) }}" alt="{{ $book->title }}" class="rounded" style="object-fit: cover;" />
                </div>
            </div>
            @endif
            <div class="flex-grow-1">
                <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                    <div class="d-flex flex-column">
                        <div class="d-flex align-items-center mb-1">
                            <a class="text-gray-800 fs-2 fw-bold me-3">{{ $book->title }}</a>
                            @if($book->status == 'published')
                                <span class="badge badge-light-success">Published</span>
                            @elseif($book->status == 'draft')
                                <span class="badge badge-light-warning">Draft</span>
                            @else
                                <span class="badge badge-light-secondary">{{ ucfirst($book->status) }}</span>
                            @endif
                        </div>
                        <div class="d-flex flex-wrap fw-semibold mb-4 fs-5 text-gray-500">
                            {{ $book->category->name ?? '-' }} &bull; {{ $book->publisher ?? '-' }}
                        </div>
                    </div>
                </div>
                <div class="d-flex flex-wrap">
                    <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                        <div class="fw-semibold fs-6 text-gray-500">ISBN</div>
                        <div class="fw-bold fs-4">{{ $book->isbn ?? '-' }}</div>
                    </div>
                    <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                        <div class="fw-semibold fs-6 text-gray-500">Halaman</div>
                        <div class="fw-bold fs-4">{{ $book->pages ?? '-' }}</div>
                    </div>
                    <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                        <div class="fw-semibold fs-6 text-gray-500">Harga</div>
                        <div class="fw-bold fs-4">@money($book->price)</div>
                    </div>
                    <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                        <div class="fw-semibold fs-6 text-gray-500">Penulis</div>
                        <div class="fw-bold fs-4">{{ $book->bookAuthors->count() }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="separator"></div>
        <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold">
            <li class="nav-item">
                <a class="nav-link text-active-primary py-5 me-6 @if(request()->routeIs('back.book.show')) active @endif"
                    href="{{ route('back.book.show', $book->id) }}">Detail Buku</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-active-primary py-5 me-6 @if(request()->routeIs('back.book.authors')) active @endif"
                    href="{{ route('back.book.authors', $book->id) }}">Penulis & Editor</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-active-primary py-5 me-6 @if(request()->routeIs('back.book.payment')) active @endif"
                    href="{{ route('back.book.payment', $book->id) }}">Pembayaran</a>
            </li>
        </ul>
    </div>
</div>
