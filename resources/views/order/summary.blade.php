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

    {{-- Tombol Batalkan Pesanan --}}
    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#cancelModal">
        Batalkan Pesanan
    </button>

    <!-- Modal Konfirmasi -->
    <div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="cancelModalLabel">Konfirmasi Pembatalan</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
          </div>
          <div class="modal-body">
            Apakah Anda yakin ingin membatalkan pesanan ini?
          </div>
          <div class="modal-footer">
            <form action="{{ route('order.cancel', $order->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Ya, Batalkan</button>
            </form>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>
          </div>
        </div>
      </div>
    </div>
</div>
@endsection
