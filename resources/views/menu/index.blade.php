@extends('layouts.app')

@section('title', 'Daftar Menu')

@section('content')
    <h1 class="mb-4 fw-bold text-center">Daftar Menu</h1>

    @if ($menus->count())
        <div class="row">
            @foreach ($menus as $menu)
                <div class="col-md-4">
                    <div class="card mb-4 shadow-sm h-100">
                        @if ($menu->image)
                            <img src="{{ asset('images/' . $menu->image) }}" class="card-img-top" alt="{{ $menu->name }}" style="height: 200px; object-fit: cover;">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $menu->name }}</h5>
                            <p class="card-text text-muted">{{ $menu->description }}</p>
                            <p class="card-text fw-semibold">Harga: Rp{{ number_format($menu->price, 0, ',', '.') }}</p>

                            <button class="btn btn-primary w-100 btn-pesan" 
                                    data-id="{{ $menu->id }}"
                                    data-name="{{ $menu->name }}"
                                    data-price="{{ $menu->price }}">
                                Pesan
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="alert alert-warning">
            Belum ada menu yang tersedia.
        </div>
    @endif

    <div id="cart-section" style="{{ session('cart') && count(session('cart')) > 0 ? '' : 'display:none;' }}" class="mt-5">
        <h3>Menu yang sudah dipesan:</h3>
        <ul class="list-group mb-3" id="cart-list">
            @php $total = 0; @endphp
            @php $cart = session('cart', []); @endphp
            @foreach ($cart as $id => $item)
                @php
                    $subtotal = $item['price'] * $item['quantity'];
                    $total += $subtotal;
                @endphp
                <li class="list-group-item d-flex justify-content-between align-items-center" id="item-{{ $id }}">
                    <div>
                        <strong>{{ $item['name'] }}</strong>
                        <div class="d-flex align-items-center mt-2">
                            <button type="button" class="btn btn-sm btn-danger btn-decrease" data-id="{{ $id }}">-</button>
                            <span class="mx-2 qty" id="qty-{{ $id }}">{{ $item['quantity'] }}</span>
                            <button type="button" class="btn btn-sm btn-success btn-increase" data-id="{{ $id }}">+</button>
                        </div>
                        <small class="text-muted">Rp{{ number_format($item['price'], 0, ',', '.') }} / item</small>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-primary text-white me-3 subtotal" id="subtotal-{{ $id }}">
                            Rp{{ number_format($subtotal, 0, ',', '.') }}
                        </span>
                        <button type="button" class="btn btn-sm" style="background-color: #dc3545; color: #fff;" onclick="removeItem('{{ $id }}')">Hapus</button>
                    </div>
                </li>
            @endforeach
        </ul>

        <div class="text-end fw-bold fs-5">
            Total Harga: <span class="badge bg-success text-white" id="total-harga">Rp{{ number_format($total, 0, ',', '.') }}</span>
        </div>

        <a href="{{ route('cart.view') }}" class="btn btn-success mt-3">Lihat Keranjang</a>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Tambahkan ke cart (tanpa reload)
        document.querySelectorAll('.btn-pesan').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.dataset.id;

                fetch(`/cart/add/${id}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        renderCart(data.cart, data.total);
                    }
                });
            });
        });

        document.querySelectorAll('.btn-increase').forEach(btn => {
            btn.addEventListener('click', () => updateCart(btn.dataset.id, 'increase'));
        });

        document.querySelectorAll('.btn-decrease').forEach(btn => {
            btn.addEventListener('click', () => updateCart(btn.dataset.id, 'decrease'));
        });

        updateCartVisibility();
    });

    function updateCart(id, action) {
        fetch(`/cart/update/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ action: action })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                if (data.quantity === 0) {
                    document.getElementById(`item-${id}`)?.remove();
                } else {
                    document.getElementById(`qty-${id}`).textContent = data.quantity;
                    document.getElementById(`subtotal-${id}`).textContent = formatRupiah(data.subtotal);
                }
                document.getElementById('total-harga').textContent = formatRupiah(data.total);
                updateCartVisibility();
            }
        });
    }

    function removeItem(id) {
        fetch(`/cart/remove/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.getElementById(`item-${id}`)?.remove();
                document.getElementById('total-harga').textContent = formatRupiah(data.total);
                updateCartVisibility();
            }
        });
    }

    function renderCart(cart, total) {
        const cartList = document.getElementById('cart-list');
        const cartSection = document.getElementById('cart-section');

        cartList.innerHTML = '';
        Object.entries(cart).forEach(([id, item]) => {
            const subtotal = item.price * item.quantity;
            cartList.innerHTML += `
                <li class="list-group-item d-flex justify-content-between align-items-center" id="item-${id}">
                    <div>
                        <strong>${item.name}</strong>
                        <div class="d-flex align-items-center mt-2">
                            <button type="button" class="btn btn-sm btn-danger btn-decrease" data-id="${id}">-</button>
                            <span class="mx-2 qty" id="qty-${id}">${item.quantity}</span>
                            <button type="button" class="btn btn-sm btn-success btn-increase" data-id="${id}">+</button>
                        </div>
                        <small class="text-muted">Rp${formatRupiah(item.price).replace('Rp', '')} / item</small>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-primary text-white me-3 subtotal" id="subtotal-${id}">
                            ${formatRupiah(subtotal)}
                        </span>
                        <button type="button" class="btn btn-sm" style="background-color: #dc3545; color: #fff;" onclick="removeItem('${id}')">Hapus</button>
                    </div>
                </li>`;
        });

        document.getElementById('total-harga').textContent = formatRupiah(total);
        cartSection.style.display = 'block';

        // Pasang ulang event increase/decrease
        document.querySelectorAll('.btn-increase').forEach(btn => {
            btn.addEventListener('click', () => updateCart(btn.dataset.id, 'increase'));
        });

        document.querySelectorAll('.btn-decrease').forEach(btn => {
            btn.addEventListener('click', () => updateCart(btn.dataset.id, 'decrease'));
        });
        
    }

    function updateCartVisibility() {
        const cartList = document.getElementById('cart-list');
        const cartSection = document.getElementById('cart-section');

        if (cartList && cartList.children.length === 0 && cartSection) {
            cartSection.style.display = 'none';
        }
    }

    function formatRupiah(number) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(number);
    }
</script>
@endpush
