<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\PaymentInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class ProductOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = PaymentInvoice::where('source_type', 'product')->with('user');

        if ($request->filled('status')) {
            if ($request->status === 'paid') {
                $query->where('is_paid', true);
            } elseif ($request->status === 'unpaid') {
                $query->where('is_paid', false);
            }
        }

        $data = [
            'title' => 'Pesanan Produk',
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'link' => route('back.dashboard')],
                ['name' => 'Pesanan Produk'],
            ],
            'orders' => $query->latest()->get(),
            'current_status' => $request->status,
        ];

        return view('back.pages.product.order.index', $data);
    }

    public function show($id)
    {
        $order = PaymentInvoice::where('source_type', 'product')
            ->with('user')
            ->findOrFail($id);

        $data = [
            'title' => 'Detail Pesanan #' . $order->invoice,
            'breadcrumbs' => [
                ['name' => 'Pesanan Produk', 'link' => route('back.product.order.index')],
                ['name' => 'Detail Pesanan'],
            ],
            'order' => $order,
        ];

        return view('back.pages.product.order.show', $data);
    }

    public function updateStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:paid,unpaid',
        ], [
            'status.required' => 'Status harus dipilih',
            'status.in' => 'Status tidak valid',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('error', $validator->errors()->all());
        }

        $order = PaymentInvoice::where('source_type', 'product')->findOrFail($id);

        if ($request->status === 'paid') {
            $order->is_paid = true;
            $order->confirmed_at = $order->confirmed_at ?? now();
            $order->midtrans_payment_method = $order->midtrans_payment_method ?? 'Manual Confirmation';
        } else {
            $order->is_paid = false;
            $order->confirmed_at = null;
        }

        $order->save();

        Alert::success('Berhasil', 'Status pesanan berhasil diperbarui');

        return redirect()->back();
    }

    public function destroy($id)
    {
        $order = PaymentInvoice::where('source_type', 'product')->findOrFail($id);
        $order->delete();

        Alert::success('Berhasil', 'Pesanan berhasil dihapus');

        return redirect()->route('back.product.order.index');
    }
}
