<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Menu;

class CartController extends Controller
{
    public function index() {
        $cart = session()->get('cart', []);
        return view('cart.index', compact('cart'));
    }
    
    public function add($id)
{
    $menu = Menu::findOrFail($id);
    $cart = session()->get('cart', []);

    if (isset($cart[$id])) {
        $cart[$id]['quantity']++;
    } else {
        $cart[$id] = [
            'name' => $menu->name,
            'price' => $menu->price,
            'quantity' => 1
        ];
    }

    session()->put('cart', $cart);

    $total = collect($cart)->reduce(fn($carry, $item) => $carry + ($item['price'] * $item['quantity']), 0);

    return response()->json([
        'success' => true,
        'cart' => $cart,
        'total' => $total
    ]);
}


public function view()
{
    $cart = session()->get('cart', []);
    return view('cart.index', compact('cart'));
}

public function remove(Request $request, $id)
{
    $cart = session()->get('cart', []);
    unset($cart[$id]);
    session(['cart' => $cart]);

    $total = collect($cart)->reduce(fn($sum, $item) => $sum + $item['price'] * $item['quantity'], 0);

    return response()->json([
        'success' => true,
        'total' => $total
    ]);
}


public function update(Request $request, $id)
{
    $cart = session()->get('cart', []);

    if (!isset($cart[$id])) {
        return response()->json(['success' => false], 404);
    }

    if ($request->action === 'increase') {
        $cart[$id]['quantity']++;
    } elseif ($request->action === 'decrease') {
        $cart[$id]['quantity']--;
        if ($cart[$id]['quantity'] <= 0) {
            unset($cart[$id]); // HAPUS dari keranjang
        }
    }

    session()->put('cart', $cart);

    // Hitung total
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['quantity'] * $item['price'];
    }

    return response()->json([
        'success' => true,
        'quantity' => isset($cart[$id]) ? $cart[$id]['quantity'] : 0,
        'subtotal' => isset($cart[$id]) ? $cart[$id]['quantity'] * $cart[$id]['price'] : 0,
        'total' => $total
    ]);
}

}
