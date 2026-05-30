@extends('back.app')
@section('content')
<div id="kt_content_container" class=" container-xxl ">
    <div class="card card-flush py-4">
        <div class="card-header">
            <div class="card-title">
                <h2>Detail Surat Keluar</h2>
            </div>
            <div class="card-toolbar">
                <a href="{{ route('back.outgoing-mail.edit', $mail->id) }}" class="btn btn-sm btn-primary me-2">
                    <i class="ki-duotone ki-pencil fs-4"></i> Edit
                </a>
                <a href="{{ route('back.outgoing-mail.index') }}" class="btn btn-sm btn-light">
                    <i class="ki-duotone ki-arrow-left fs-4"></i> Kembali
                </a>
            </div>
        </div>
        <div class="card-body pt-0">
            <div class="row mb-7">
                <label class="col-lg-3 fw-semibold text-muted">Nomor Surat</label>
                <div class="col-lg-9">
                    <span class="fw-bold fs-6 text-gray-800">{{ $mail->nomor_surat }}</span>
                </div>
            </div>
            <div class="row mb-7">
                <label class="col-lg-3 fw-semibold text-muted">Tujuan</label>
                <div class="col-lg-9">
                    <span class="fw-bold fs-6 text-gray-800">{{ $mail->tujuan }}</span>
                </div>
            </div>
            <div class="row mb-7">
                <label class="col-lg-3 fw-semibold text-muted">Kategori Surat</label>
                <div class="col-lg-9">
                    @if($mail->category)
                        <span class="badge badge-light-info fs-7">{{ $mail->category->name }} ({{ $mail->category->kode }})</span>
                    @else
                        <span class="text-muted">-</span>
                    @endif
                </div>
            </div>
            <div class="row mb-7">
                <label class="col-lg-3 fw-semibold text-muted">Tanggal Surat</label>
                <div class="col-lg-9">
                    <span class="fw-bold fs-6 text-gray-800">{{ \Carbon\Carbon::parse($mail->tanggal_surat)->format('d F Y') }}</span>
                </div>
            </div>
            <div class="row mb-7">
                <label class="col-lg-3 fw-semibold text-muted">Perihal</label>
                <div class="col-lg-9">
                    <span class="fw-bold fs-6 text-gray-800">{{ $mail->perihal }}</span>
                </div>
            </div>
            <div class="row mb-7">
                <label class="col-lg-3 fw-semibold text-muted">Klasifikasi</label>
                <div class="col-lg-9">
                    @if($mail->klasifikasi == 'biasa')
                        <span class="badge badge-light-primary">Biasa</span>
                    @elseif($mail->klasifikasi == 'penting')
                        <span class="badge badge-light-warning">Penting</span>
                    @elseif($mail->klasifikasi == 'rahasia')
                        <span class="badge badge-light-danger">Rahasia</span>
                    @elseif($mail->klasifikasi == 'sangat_rahasia')
                        <span class="badge badge-light-dark">Sangat Rahasia</span>
                    @endif
                </div>
            </div>
            <div class="row mb-7">
                <label class="col-lg-3 fw-semibold text-muted">Keterangan</label>
                <div class="col-lg-9">
                    <span class="fw-bold fs-6 text-gray-800">{{ $mail->keterangan ?? '-' }}</span>
                </div>
            </div>
            <div class="row mb-7">
                <label class="col-lg-3 fw-semibold text-muted">File Surat</label>
                <div class="col-lg-9">
                    @if ($mail->file_surat)
                        <a href="{{ $mail->getFileSurat() }}" target="_blank" class="btn btn-sm btn-light-primary">
                            <i class="ki-duotone ki-file-added fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>
                            Lihat File
                        </a>
                    @else
                        <span class="text-muted">Tidak ada file</span>
                    @endif
                </div>
            </div>
            <div class="row mb-7">
                <label class="col-lg-3 fw-semibold text-muted">Dibuat Oleh</label>
                <div class="col-lg-9">
                    <span class="fw-bold fs-6 text-gray-800">{{ $mail->user?->name ?? '-' }}</span>
                </div>
            </div>
            <div class="row mb-7">
                <label class="col-lg-3 fw-semibold text-muted">Tanggal Input</label>
                <div class="col-lg-9">
                    <span class="fw-bold fs-6 text-gray-800">{{ $mail->created_at->format('d F Y H:i') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
