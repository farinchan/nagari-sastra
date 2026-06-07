@extends('front.app')

@section('content')

    <!-- EVENT DETAIL
    ============================================= -->
    <section id="event-detail" class="wide-60 blog-page-section division">
        <div class="container">
            <div class="row">

                <!-- MAIN CONTENT -->
                <div class="col-lg-8">
                    <div class="posts-wrapper pr-25">

                        <!-- THUMBNAIL -->
                        <div class="mb-25 radius-06 overflow-hidden">
                            <img src="{{ $event->getThumbnail() }}" alt="{{ $event->name }}"
                                 class="img-fluid w-100" style="max-height: 400px; object-fit: cover;">
                        </div>

                        <!-- META -->
                        <div class="mb-15">
                            @if($event->type)
                                <span class="post-tag txt-upcase theme-color mr-10">{{ $event->type }}</span>
                            @endif
                            @if($event->status)
                                <span class="badge {{ $event->status == 'selesai' ? 'badge-secondary' : 'badge-success' }}" style="padding: 5px 12px; font-size: 12px;">
                                    {{ ucfirst($event->status) }}
                                </span>
                            @endif
                        </div>

                        <!-- TITLE -->
                        <h4 class="h4-lg mb-20">{{ $event->name }}</h4>

                        <!-- INFO -->
                        <div class="bg-lightgrey radius-04 p-4 mb-30">
                            <div class="row">
                                <div class="col-sm-6 mb-15">
                                    <small class="grey-color d-block">Waktu</small>
                                    <p class="p-sm txt-500 mb-0">{{ $event->datetime ?: '-' }}</p>
                                </div>
                                <div class="col-sm-6 mb-15">
                                    <small class="grey-color d-block">Lokasi</small>
                                    <p class="p-sm txt-500 mb-0">{{ $event->location ?? 'Online' }}</p>
                                </div>
                                <div class="col-sm-6 mb-0">
                                    <small class="grey-color d-block">Kuota</small>
                                    <p class="p-sm txt-500 mb-0">
                                        @if($event->limit)
                                            {{ $event->users->count() }}/{{ $event->limit }} peserta
                                        @else
                                            Tidak terbatas
                                        @endif
                                    </p>
                                </div>
                                <div class="col-sm-6 mb-0">
                                    <small class="grey-color d-block">Akses</small>
                                    <p class="p-sm txt-500 mb-0">{{ ucfirst($event->access ?? 'terbuka') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- DESCRIPTION -->
                        @if($event->description)
                            <div class="mb-30">
                                <h5 class="h5-md mb-15">Deskripsi Kegiatan</h5>
                                <div class="post-txt">
                                    {!! $event->description !!}
                                </div>
                            </div>
                        @endif

                    </div>
                </div>

                <!-- SIDEBAR -->
                <aside id="sidebar" class="col-lg-4">

                    <!-- REGISTRATION CARD -->
                    <div class="sidebar-div mb-50">
                        <h6 class="h6-xl">Pendaftaran</h6>

                        @if($event->is_active && $event->access == 'terbuka')
                            @if($check_registered)
                                <div class="bg-lightgrey p-3 radius-04 text-center mb-20">
                                    <div class="ico-40 theme-color mb-10"><span class="flaticon-check"></span></div>
                                    <p class="p-sm txt-500 mb-5">Anda sudah terdaftar</p>
                                    <p class="p-sm grey-color mb-0">untuk kegiatan ini</p>
                                </div>
                                @if($eticket)
                                    <a href="{{ route('event.eticket', $eticket->id) }}" target="_blank"
                                       class="btn btn-theme btn-block">
                                        <span class="flaticon-document mr-2"></span> Lihat E-Ticket
                                    </a>
                                @endif
                            @else
                                @auth
                                    <form action="{{ route('event.register', $event->slug) }}" method="POST" class="event-register-form">
                                        @csrf
                                        <div class="form-group mb-15">
                                            <label class="control-label">Nama Lengkap <span class="text-danger">*</span></label>
                                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                                   value="{{ old('name', auth()->user()->name) }}" required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group mb-15">
                                            <label class="control-label">Email <span class="text-danger">*</span></label>
                                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                                   value="{{ old('email', auth()->user()->email) }}" required>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group mb-20">
                                            <label class="control-label">No. Telepon</label>
                                            <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                                   value="{{ old('phone', auth()->user()->phone) }}" placeholder="08xxxxxxxxxx">
                                            @error('phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <button type="submit" class="btn btn-theme btn-block">
                                            Daftar Sekarang
                                        </button>
                                    </form>
                                @else
                                    <p class="p-sm grey-color mb-20">Silakan login terlebih dahulu untuk mendaftar kegiatan ini.</p>
                                    <a href="{{ route('login') }}" class="btn btn-theme btn-block">
                                        Login untuk Daftar
                                    </a>
                                @endauth
                            @endif
                        @else
                            <div class="bg-lightgrey p-3 radius-04 text-center">
                                <p class="p-sm grey-color mb-0">Pendaftaran kegiatan ini sudah ditutup.</p>
                            </div>
                        @endif
                    </div>

                    <!-- EVENT LATEST -->
                    @if($event_latest->where('id', '!=', $event->id)->count() > 0)
                        <div class="sidebar-div mb-50">
                            <h6 class="h6-xl">Agenda Lainnya</h6>

                            @foreach($event_latest->where('id', '!=', $event->id)->take(4) as $latest)
                                <div class="d-flex align-items-center mb-20 {{ !$loop->last ? 'pb-20 b-bottom' : '' }}">
                                    <div class="mr-15" style="width: 65px; height: 65px; overflow: hidden; border-radius: 4px; flex-shrink: 0;">
                                        <a href="{{ route('event.show', $latest->slug) }}">
                                            <img src="{{ $latest->getThumbnail() }}" alt="{{ $latest->name }}"
                                                 class="w-100 h-100" style="object-fit: cover;">
                                        </a>
                                    </div>
                                    <div>
                                        <h6 class="h6-xs mb-1" style="line-height: 1.3;">
                                            <a href="{{ route('event.show', $latest->slug) }}">{{ Str::limit($latest->name, 40) }}</a>
                                        </h6>
                                        <p class="p-sm grey-color mb-0">{{ Str::limit($latest->datetime ?: '-', 30) }}</p>
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

@section('styles')
<style>
    .event-register-form .control-label {
        font-weight: 500;
        font-size: 14px;
        color: #555;
        margin-bottom: 8px;
        display: block;
    }
    .event-register-form .form-control { margin-bottom: 0; }
    .event-register-form .form-group { margin-bottom: 0; }
    .invalid-feedback {
        display: block;
        font-size: 13px;
        color: #dc3545;
        margin-top: 5px;
    }
</style>
@endsection
