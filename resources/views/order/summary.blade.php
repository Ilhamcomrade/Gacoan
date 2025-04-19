@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2>Ringkasan Pesanan</h2>

    <p><strong>Nama:</strong> {{ $order->customer_name }}</p>
    <p><strong>Nomor Meja:</strong> {{ $order->table_number }}</p>

    <table class="table">
        <thead>
            <tr>
                <th>Menu</th>
                <th>Jumlah</th>
                <th>Harga</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->items as $item)
                <tr>
                    <td>{{ $item->nama_menu }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>Rp{{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="2" class="text-end"><strong>Total</strong></td>
                <td><strong>Rp{{ number_format($order->total_price, 0, ',', '.') }}</strong></td>
            </tr>
        </tbody>
    </table>

    <div class="alert alert-info">
        Status pesanan Anda: <strong>{{ ucfirst($order->status) }}</strong>
    </div>
</div>
@endsection
