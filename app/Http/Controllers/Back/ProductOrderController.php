<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\ProductOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class ProductOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = ProductOrder::with(['user', 'items.product']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
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
        $order = ProductOrder::with(['user', 'items.product'])->findOrFail($id);

        $data = [
            'title' => 'Detail Pesanan #' . $order->order_number,
            'breadcrumbs' => [
                ['name' => 'Pesanan Produk', 'link' => route('back.product-order.index')],
                ['name' => 'Detail Pesanan'],
            ],
            'order' => $order,
        ];

        return view('back.pages.product.order.show', $data);
    }

    public function updateStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,paid,cancelled,refunded',
        ], [
            'status.required' => 'Status harus dipilih',
            'status.in' => 'Status tidak valid',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('error', $validator->errors()->all());
        }

        $order = ProductOrder::findOrFail($id);
        $order->status = $request->status;

        if ($request->status === 'paid' && !$order->paid_at) {
            $order->paid_at = now();
        }

        $order->save();

        Alert::success('Berhasil', 'Status pesanan berhasil diperbarui');

        return redirect()->back();
    }

    public function destroy($id)
    {
        $order = ProductOrder::findOrFail($id);
        $order->delete();

        Alert::success('Berhasil', 'Pesanan berhasil dihapus');

        return redirect()->route('back.product-order.index');
    }
}
