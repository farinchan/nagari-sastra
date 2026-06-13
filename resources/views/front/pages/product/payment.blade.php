@extends('front.app')

@section('content')

    <!-- BREADCRUMB
    ============================================= -->
    @include('front.partials.breadcrumb', ['title' => 'Pembayaran', 'breadcrumbs' => $breadcrumbs])

    <!-- PAYMENT
    ============================================= -->
    <section id="product-payment" class="wide-60 blog-page-section division">
        <div class="container">
            <div class="row justify-content-center">

                <!-- ORDER SUMMARY -->
                <div class="col-lg-8">
                    <div class="sidebar-div mb-40">

                        <!-- Order Header -->
                        <div class="d-flex justify-content-between align-items-center mb-20">
                            <div>
                                <h5 class="h5-md mb-5">Ringkasan Pesanan</h5>
                                <p class="p-sm grey-color mb-0">
                                    No. Order: <span class="theme-color font-weight-bold">{{ $order->order_number }}</span>
                                </p>
                            </div>
                            <div class="text-right">
                                <span class="badge badge-warning px-3 py-2" style="font-size: 13px;">
                                    {{ ucfirst($order->status) }}
                                </span>
                                <p class="p-sm grey-color mb-0 mt-5">
                                    {{ $order->created_at->format('d M Y, H:i') }}
                                </p>
                            </div>
                        </div>

                        <hr class="mb-20">

                        <!-- Order Items -->
                        <div class="mb-20">
                            <h6 class="h6-xl mb-15">Detail Produk</h6>

                            @foreach ($order->items as $item)
                                <div class="d-flex justify-content-between align-items-center py-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                                    <div>
                                        <p class="p-md mb-0 font-weight-bold">{{ $item->product->name }}</p>
                                        <p class="p-sm grey-color mb-0">Qty: {{ $item->quantity }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="p-md mb-0 font-weight-bold">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <hr class="mb-20">

                        <!-- Total -->
                        <div class="d-flex justify-content-between align-items-center mb-30">
                            <h6 class="h6-xl mb-0">Total Pembayaran</h6>
                            <h3 class="h3-lg theme-color mb-0">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</h3>
                        </div>

                        <!-- Pay Button -->
                        <button id="pay-button" class="btn btn-theme btn-lg btn-block">
                            Bayar Sekarang
                        </button>

                    </div>
                </div>
                <!-- END ORDER SUMMARY -->

            </div>
            <!-- End row -->
        </div>
        <!-- End container -->
    </section>
    <!-- END PAYMENT -->

@endsection

@push('scripts')
<script src="https://app.{{ config('midtrans.is_production') ? '' : 'sandbox.' }}midtrans.com/snap/snap.js" data-client-key="{{ $clientKey }}"></script>
<script>
    document.getElementById('pay-button').addEventListener('click', function () {
        snap.pay('{{ $order->snap_token }}', {
            onSuccess: function(result) {
                window.location.href = '{{ route('product.my-products') }}?payment=success';
            },
            onPending: function(result) {
                alert('Pembayaran sedang diproses.');
                window.location.href = '{{ route('product.my-products') }}';
            },
            onError: function(result) {
                alert('Pembayaran gagal. Silakan coba lagi.');
                window.location.reload();
            },
            onClose: function() {}
        });
    });
</script>
@endpush
