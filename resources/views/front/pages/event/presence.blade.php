@extends('front.app')

@section('content')

    <!-- EVENT PRESENCE
    ============================================= -->
    <section id="event-presence" class="wide-60 division">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">

                    <!-- EVENT INFO -->
                    <div class="text-center mb-40">
                        <p class="post-tag txt-upcase theme-color mb-10">Absensi Kegiatan</p>
                        <h3 class="h3-md mb-10">{{ $event_attendance->event->name }}</h3>
                        <p class="p-lg grey-color">{{ $event_attendance->description ?? 'Silakan tandai kehadiran Anda' }}</p>
                    </div>

                    <!-- ATTENDANCE INFO -->
                    <div class="bg-lightgrey p-4 radius-06 mb-30">
                        <div class="row text-center">
                            <div class="col-sm-4 mb-15">
                                <div class="ico-20 theme-color mb-5"><span class="flaticon-clock"></span></div>
                                <small class="grey-color d-block">Buka</small>
                                <p class="p-sm txt-500 mb-0">
                                    {{ \Carbon\Carbon::parse($event_attendance->start_datetime)->format('d M Y, H:i') }}
                                </p>
                            </div>
                            <div class="col-sm-4 mb-15">
                                <div class="ico-20 theme-color mb-5"><span class="flaticon-clock"></span></div>
                                <small class="grey-color d-block">Tutup</small>
                                <p class="p-sm txt-500 mb-0">
                                    {{ \Carbon\Carbon::parse($event_attendance->end_datetime)->format('d M Y, H:i') }}
                                </p>
                            </div>
                            <div class="col-sm-4 mb-15">
                                <div class="ico-20 theme-color mb-5"><span class="flaticon-check"></span></div>
                                <small class="grey-color d-block">Status</small>
                                <p class="p-sm txt-500 mb-0">
                                    @if($attendance_check)
                                        <span class="theme-color">Sudah Hadir</span>
                                    @else
                                        <span class="text-warning">Belum Hadir</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- FORM / STATUS -->
                    @if($attendance_check)
                        <div class="text-center p-4 radius-06" style="border: 2px solid #28a745;">
                            <div class="ico-50 mb-15" style="color: #28a745;"><span class="flaticon-check"></span></div>
                            <h5 class="h5-md mb-10">Kehadiran Tercatat</h5>
                            <p class="p-md grey-color mb-5">
                                Waktu hadir: {{ \Carbon\Carbon::parse($attendance_check->attendance_datetime)->format('d M Y, H:i') }}
                            </p>
                            @if($attendance_check->notes)
                                <p class="p-sm grey-color">Catatan: {{ $attendance_check->notes }}</p>
                            @endif
                        </div>
                    @else
                        <div class="bg-white p-4 radius-06" style="border: 1px solid #e8e8e8;">
                            <h5 class="h5-md mb-20 text-center">Konfirmasi Kehadiran</h5>
                            <form action="{{ route('event.presence.store', $event_attendance->code) }}" method="POST" class="event-presence-form">
                                @csrf
                                <div class="form-group mb-20">
                                    <label class="control-label">Catatan (Opsional)</label>
                                    <textarea name="notes" class="form-control @error('notes') is-invalid @enderror"
                                              rows="3" placeholder="Tambahkan catatan jika diperlukan...">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-theme tra-white-hover">
                                        <span class="flaticon-check mr-2"></span> Tandai Hadir
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </section>

@endsection

@section('styles')
<style>
    .event-presence-form .control-label {
        font-weight: 500;
        font-size: 14px;
        color: #555;
        margin-bottom: 8px;
        display: block;
    }
    .event-presence-form .form-control { margin-bottom: 0; }
    .event-presence-form textarea.form-control { min-height: 80px; padding: 15px; }
    .invalid-feedback {
        display: block;
        font-size: 13px;
        color: #dc3545;
        margin-top: 5px;
    }
</style>
@endsection
