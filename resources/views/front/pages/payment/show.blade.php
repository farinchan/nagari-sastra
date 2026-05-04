@extends('front.app')
@section('seo')
	<title>{{ $meta['title'] }}</title>
	<meta name="description" content="{{ $meta['description'] }}">
	<meta name="keywords" content="{{ $meta['keywords'] }}">
	<meta name="author" content="Torkata Research">

	<meta property="og:title" content="{{ $meta['title'] }}">
	<meta property="og:description" content="{{ $meta['description'] }}">
	<meta property="og:type" content="website">
	<meta property="og:url" content="{{ route('payment.show', ['invoice_number' => str_replace('/', '-', $payment_invoice->invoice_number)]) }}">
	<link rel="canonical" href="{{ route('payment.show', ['invoice_number' => str_replace('/', '-', $payment_invoice->invoice_number)]) }}">
	<meta property="og:image" content="{{ Storage::url($meta['favicon']) }}">
@endsection
@section('content')
	<section id="payment-show-1" class="wide-60 payment-section division">
		<div class="">
			<div class="row justify-content-center">
				<div class="col-lg-10 col-xl-8">
					<div class="section-title text-center mb-50">
						<h2 class="h2-xs">Pembayaran</h2>
						<p class="p-xl grey-color">
							Invoice <strong>{{ $payment_invoice->invoice_number }}</strong> dapat dibayar langsung di halaman ini.
						</p>
					</div>
				</div>
			</div>

			<div class="row justify-content-center">
				<div class="col-lg-10 col-xl-8">
					<div class="row">
						<div class="col-md-7">
							<div class="bg-lightgrey p-4 rounded-3 mb-4">
								<h5 class="h5-xs mb-3">Detail Invoice</h5>
								<p class="mb-2">Nomor Invoice: <strong>{{ $payment_invoice->invoice_number }}</strong></p>
								<p class="mb-2">Submission ID: <strong>{{ $submission->ojs_submission_id ?? '-' }}</strong></p>
								<p class="mb-2">Judul: <strong>{{ $submission->fullTitle ?? '-' }}</strong></p>
								<p class="mb-2">Penulis: <strong>{{ collect($submission->authors ?? [])->pluck('name')->filter()->implode(', ') ?: '-' }}</strong></p>
								<p class="mb-2">Journal: <strong>{{ $journal->title ?? '-' }}</strong></p>
								<p class="mb-2">Persentase Pembayaran: <strong>{{ $payment_invoice->payment_percent ?? '-' }}%</strong></p>
								<p class="mb-2">Total Tagihan: <strong>@money($payment_invoice->payment_amount ?? 0)</strong></p>
								<p class="mb-0">Status: <strong class="{{ $payment_invoice->is_paid ? 'text-success' : 'text-danger' }}">{{ $payment_invoice->is_paid ? 'Lunas' : 'Belum Lunas' }}</strong></p>
							</div>
						</div>

						<div class="col-md-5">
							<div class="bg-lightgrey p-4 rounded-3 mb-4">
								<h5 class="h5-xs mb-3">Invoice</h5>

									<p class="mb-3">Unduh invoice ini dengan mengklik tombol di bawah.</p>
									<button type="button" class="btn btn-theme w-100">Download Invoice</button>

							</div>
							<div class="bg-lightgrey p-4 rounded-3 mb-4">
								<h5 class="h5-xs mb-3">Pembayaran</h5>
								@if ($payment_invoice->is_paid)
									<div class="alert alert-success mb-0">Invoice ini sudah lunas.</div>
								@else
									<p class="mb-3">Klik tombol di bawah untuk membuka popup dan memilih metode pembayaran.</p>
									<button type="button" id="pay-button" class="btn btn-theme w-100">Bayar Sekarang</button>
								@endif
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
@endsection
@section('scripts')
	@if (! $payment_invoice->is_paid)
		<script src="{{ config('midtrans.isProduction') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ config('midtrans.clientKey') }}"></script>
		<script>
			document.addEventListener('DOMContentLoaded', function () {
				const payButton = document.getElementById('pay-button');
				if (payButton) {
					payButton.addEventListener('click', function () {
						snap.pay(@json($snap_token), {
							onSuccess: function () { window.location.reload(); },
							onPending: function () { window.location.reload(); },
							onError: function () { alert('Pembayaran gagal diproses. Silakan coba lagi.'); },
							onClose: function () { console.log('Popup pembayaran ditutup sebelum selesai.'); }
						});
					});
				}
			});
		</script>
	@endif
@endsection
