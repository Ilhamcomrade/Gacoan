<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;

class OrderController extends Controller
{
    public function submit(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'table' => 'required|string',
        ]);

        $cart = session('cart');

        if (!$cart || count($cart) === 0) {
            return redirect('/cart')->with('error', 'Keranjang masih kosong!');
        }

        // Hitung total harga
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        // Simpan data order utama
        $order = Order::create([
            'customer_name' => $request->name,
            'table_number' => $request->table,
            'total_price' => $total,
        ]);

        // Simpan detail tiap item
        foreach ($cart as $menuId => $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'menu_id' => $menuId,
                'nama_menu' => $item['name'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }

        // Kosongkan keranjang
        session()->forget('cart');

        // Redirect ke halaman ringkasan pesanan
        return redirect()->route('order.summary', $order->id);
    }

    public function summary($id)
    {
        $order = Order::with('items')->findOrFail($id);

        return view('order.summary', compact('order'));
    }

    public function cancel($id)
{
    // Hapus order item dulu
    OrderItem::where('order_id', $id)->delete();

    // Hapus order utama
    Order::where('id', $id)->delete();

    return redirect('/')->with('success', 'Pesanan berhasil dibatalkan.');
}

}

