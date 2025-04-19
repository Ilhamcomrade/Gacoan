@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2>Keranjang Pesanan</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Menu</th>
                <th>Jumlah</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @php $grandTotal = 0; @endphp
            @foreach ($cart as $item)
                @php
                    $total = $item['price'] * $item['quantity'];
                    $grandTotal += $total;
                @endphp
                <tr>
                    <td>{{ $item['name'] }}</td>
                    <td>{{ $item['quantity'] }}</td>
                    <td>Rp{{ number_format($total, 0, ',', '.') }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="2" class="text-end fw-bold">Total Harga Keseluruhan:</td>
                <td>
                <span class="badge bg-success text-white">
                    Rp{{ number_format($grandTotal, 0, ',', '.') }}
                </span>
                </td>
            </tr>
        </tbody>
    </table>

    <form method="POST" action="{{ route('order.submit') }}">
        @csrf
        <div class="mb-3">
            <input type="text" name="name" class="form-control" placeholder="Nama" required>
        </div>
        <div class="mb-3">
            <input type="text" name="table" class="form-control" placeholder="Nomor Meja" required>
        </div>
        <button type="submit" class="btn btn-primary">Pesan Sekarang</button>
    </form>
</div>
@endsection
