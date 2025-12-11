
@extends('layouts.app')

@section('title', 'Daftar Produk untuk Transaksi')

@section('nav')

<style>
    * {
        box-sizing: border-box;
    }

    body {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        margin: 0;
        padding: 0;
        min-height: 100vh;
        position: relative;
        overflow-x: hidden;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
        transition: all 0.3s ease;
        position: relative;
        z-index: 1;
    }

    .container.default {
        margin-right: 0;
        padding-right: 20px;
    }

    .container.cart-active {
        margin-right: 420px;
        padding-right: 20px;
        max-width: calc(100vw - 440px);
    }

    h1 {
        color: #2c3e50;
        margin-bottom: 10px;
        text-align: center;
        font-weight: 700;
        font-size: 2.5rem;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        letter-spacing: -0.5px;
    }

    .subtitle {
        color: #7f8c8d;
        text-align: center;
        margin-bottom: 40px;
        font-size: 1.1rem;
        font-weight: 400;
        line-height: 1.6;
    }

    .product-grid {
        display: grid;
        gap: 25px;
        margin-top: 30px;
        transition: all 0.3s ease;
    }

    .product-grid.default {
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    }

    .product-grid.cart-active {
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }

    .product-card {
        background: #ffffff;
        border: none;
        border-radius: 16px;
        padding: 20px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        align-items: flex-start;
        gap: 20px;
        position: relative;
        overflow: hidden;
        min-height: 200px;
    }

    .product-grid.cart-active .product-card {
        gap: 15px;
        padding: 18px;
    }

    .product-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #3498db, #2ecc71);
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }

    .product-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.15);
    }

    .product-card:hover::before {
        transform: scaleX(1);
    }

    .product-image-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 12px;
        flex-shrink: 0;
    }

    .product-image {
        width: 140px;
        height: 140px;
        border-radius: 12px;
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        border: 2px solid #f1f3f5;
        transition: all 0.3s ease;
    }

    .product-grid.cart-active .product-image {
        width: 120px;
        height: 120px;
    }

    .product-card:hover .product-image {
        border-color: #3498db;
    }

    .product-image img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        padding: 10px;
    }

    .cart-buttons {
        display: flex;
        flex-direction: column;
        gap: 8px;
        width: 140px;
        transition: all 0.3s ease;
    }

    .product-grid.cart-active .cart-buttons {
        width: 120px;
    }

    .cart-btn {
        padding: 8px 12px;
        border: none;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-align: center;
        text-decoration: none;
        display: inline-block;
    }

    .product-grid.cart-active .cart-btn {
        padding: 6px 10px;
        font-size: 0.8rem;
    }

    .cart-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .add-to-cart {
        background: linear-gradient(135deg, #3498db, #2980b9);
        color: white;
    }

    .add-to-cart:hover {
        background: linear-gradient(135deg, #2980b9, #1c5985);
        color: white;
    }

    .remove-from-cart {
        color: #e74c3c;
        font-size: 1rem;
        font-weight: 700;
        cursor: pointer;
        background: none;
        border: none;
        padding: 0;
        margin-left: 10px;
        transition: color 0.3s ease;
    }

    .remove-from-cart:hover {
        color: #c0392b;
    }

    .remove-from-cart::before {
        content: '';
        margin-right: 4px;
    }

    .qty-input {
        padding: 8px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        width: 100%;
        font-size: 0.85rem;
        text-align: center;
        outline: none;
        transition: all 0.3s ease;
    }

    .product-grid.cart-active .qty-input {
        padding: 6px;
        font-size: 0.8rem;
    }

    .qty-input:focus {
        border-color: #3498db;
        box-shadow: 0 0 5px rgba(52, 152, 219, 0.3);
    }

    .product-info {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .product-name {
        font-size: 1.3rem;
        color: #2c3e50;
        margin: 0 0 5px 0;
        font-weight: 600;
        line-height: 1.3;
        transition: font-size 0.3s ease;
    }

    .product-grid.cart-active .product-name {
        font-size: 1.1rem;
    }

    .product-code {
        font-size: 0.9rem;
        color: #7f8c8d;
        margin: 0;
        font-weight: 500;
        background: #f8f9fa;
        padding: 4px 8px;
        border-radius: 6px;
        display: inline-block;
        width: fit-content;
        transition: font-size 0.3s ease;
    }

    .product-grid.cart-active .product-code {
        font-size: 0.8rem;
        padding: 3px 6px;
    }

    .product-category {
        font-size: 0.85rem;
        color: #95a5a6;
        margin: 0;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: font-size 0.3s ease;
    }

    .product-grid.cart-active .product-category {
        font-size: 0.75rem;
    }

    .price-section {
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid #ecf0f1;
    }

    .product-grid.cart-active .price-section {
        margin-top: 10px;
        padding-top: 10px;
    }

    .price {
        font-size: 1.2rem;
        color: #27ae60;
        font-weight: 700;
        margin: 0 0 8px 0;
        transition: font-size 0.3s ease;
    }

    .product-grid.cart-active .price {
        font-size: 1rem;
    }

    .price-multiple {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .product-grid.cart-active .price-multiple {
        gap: 8px;
    }

    .price-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 14px;
        background: #f8f9fa;
        border-radius: 8px;
        border-left: 4px solid #3498db;
        min-height: 44px;
        width: 100%;
        transition: all 0.3s ease;
    }

    .product-grid.cart-active .price-item {
        padding: 8px 10px;
        min-height: 36px;
    }

    .price-label {
        font-weight: 600;
        color: #2c3e50;
        font-size: 0.95rem;
        flex-shrink: 0;
        white-space: nowrap;
        transition: font-size 0.3s ease;
    }

    .product-grid.cart-active .price-label {
        font-size: 0.85rem;
    }

    .price-value {
        font-weight: 700;
        color: #27ae60;
        font-size: 1rem;
        text-align: right;
        flex-shrink: 0;
        white-space: nowrap;
        margin-left: 10px;
        transition: font-size 0.3s ease;
    }

    .product-grid.cart-active .price-value {
        font-size: 0.9rem;
    }

    .price-divider {
        border: none;
        height: 1px;
        background: linear-gradient(90deg, transparent, #bdc3c7, transparent);
        margin: 8px 0;
    }

    .text-muted {
        color: #95a5a6 !important;
        font-size: 0.95rem;
        font-style: italic;
    }

    .stock {
        font-size: 0.9rem;
        color: #000000;
        font-weight: 500;
        background: #ffffff;
        padding: 6px 12px;
        border-radius: 20px;
        display: inline-block;
        margin-top: 10px;
        transition: all 0.3s ease;
    }

    .product-grid.cart-active .stock {
        font-size: 0.8rem;
        padding: 4px 8px;
        margin-top: 8px;
    }

    .stock::before {
        content: '📦 ';
        margin-right: 4px;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #7f8c8d;
        font-size: 1.1rem;
        grid-column: 1 / -1;
    }

    .cart-sidebar {
        position: fixed;
        top: 0;
        right: -400px;
        width: 400px;
        height: 100%;
        background: #ffffff;
        box-shadow: -5px 0 15px rgba(0,0,0,0.1);
        transition: right 0.3s ease;
        z-index: 1000;
        padding: 20px;
        overflow-y: auto;
    }

    .cart-sidebar.active {
        right: 0;
    }

    .cart-sidebar-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-bottom: 15px;
        border-bottom: 1px solid #ecf0f1;
    }

    .cart-sidebar-header h3 {
        margin: 0;
        font-size: 1.5rem;
        color: #2c3e50;
    }

    .cart-sidebar-close {
        background: none;
        border: none;
        font-size: 1.2rem;
        cursor: pointer;
        color: #7f8c8d;
    }

    .cart-items {
        margin-top: 20px;
    }

    .cart-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid #ecf0f1;
    }

    .cart-item-name {
        font-size: 1rem;
        color: #2c3e50;
        font-weight: 500;
    }

    .cart-item-qty {
        font-size: 0.9rem;
        color: #7f8c8d;
    }

    .cart-item-price {
        font-size: 1rem;
        color: #27ae60;
        font-weight: 600;
    }

    .cart-total {
        margin-top: 20px;
        font-size: 1.2rem;
        font-weight: 700;
        color: #2c3e50;
        text-align: right;
    }

    .cart-checkout-btn {
        display: block;
        width: 100%;
        padding: 10px;
        background: linear-gradient(135deg, #3498db, #2980b9);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        margin-top: 20px;
        text-align: center;
    }

    .cart-checkout-btn:hover {
        background: linear-gradient(135deg, #2980b9, #1c5985);
    }

    .payment-section {
        margin-top: 20px;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 8px;
    }

    .payment-section label {
        display: block;
        font-size: 1rem;
        color: #2c3e50;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .payment-method {
        width: 100%;
        padding: 8px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 0.9rem;
        margin-bottom: 12px;
    }

    .payment-input {
        width: 100%;
        padding: 8px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 0.9rem;
        margin-bottom: 12px;
        text-align: right;
    }

    .payment-input:focus {
        border-color: #3498db;
        box-shadow: 0 0 5px rgba(52, 152, 219, 0.3);
        outline: none;
    }

    .payment-info {
        display: flex;
        justify-content: space-between;
        font-size: 0.9rem;
        color: #2c3e50;
        margin-bottom: 8px;
    }

    .payment-info .price-label {
        font-weight: 600;
    }

    .payment-info .price-value {
        font-weight: 700;
        color: #27ae60;
    }

    #wrapper.toggled-cart #sidebar-wrapper {
        margin-left: -250px;
    }

    #wrapper.toggled-cart #page-content-wrapper {
        width: 100%;
        margin-left: 0;
    }

    @media (max-width: 1400px) {
        .container.cart-active {
            margin-right: 400px;
            max-width: calc(100vw - 420px);
        }
    }

    @media (max-width: 1200px) {
        .product-grid.default {
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        }
        
        .product-grid.cart-active {
            grid-template-columns: 1fr 1fr;
        }
    }

    @media (max-width: 768px) {
        .container {
            padding: 15px;
        }

        .container.cart-active {
            margin-right: 0;
            max-width: 100%;
        }

        .product-grid.default,
        .product-grid.cart-active {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        h1 {
            font-size: 2rem;
        }

        .subtitle {
            font-size: 1rem;
            margin-bottom: 30px;
        }

        .product-card {
            flex-direction: column;
            text-align: center;
            padding: 20px 15px;
        }

        .product-image-container {
            margin: 0 auto;
        }

        .product-image {
            width: 120px;
            height: 120px;
        }

        .cart-buttons {
            width: 120px;
        }

        .qty-input {
            width: 120px;
        }

        .product-info {
            align-items: center;
        }

        .product-code {
            margin: 0 auto;
        }

        .price-item {
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
            text-align: left;
            min-height: 40px;
            padding: 8px 12px;
        }

        .price-label {
            font-size: 0.9rem;
        }

        .price-value {
            font-size: 0.95rem;
            text-align: right;
        }

        .cart-sidebar {
            width: 100%;
            right: -100%;
        }

        .cart-sidebar.active {
            right: 0;
        }
    }

    @media (max-width: 480px) {
        .container {
            padding: 10px;
        }

        h1 {
            font-size: 1.8rem;
        }

        .product-card {
            padding: 15px;
        }

        .product-image {
            width: 100px;
            height: 100px;
        }

        .cart-buttons {
            width: 100px;
        }

        .qty-input {
            width: 100px;
        }

        .cart-btn {
            padding: 6px 8px;
            font-size: 0.8rem;
        }

        .product-name {
            font-size: 1.1rem;
        }

        .price-item {
            padding: 8px 10px;
            min-height: 38px;
        }

        .price-label {
            font-size: 0.85rem;
        }

        .price-value {
            font-size: 0.9rem;
        }
    }

    html {
        scroll-behavior: smooth;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .product-card {
        animation: fadeInUp 0.6s ease-out;
    }

    .product-card:nth-child(even) {
        animation-delay: 0.1s;
    }

    .product-card:nth-child(odd) {
        animation-delay: 0.2s;
    }

    .swal2-container.swal2-center {
        z-index: 1050;
    }
</style>

<div class="container default" id="mainContainer">
    <div class="row mt-4 align-items-start">
        <div class="col-12 col-md-3 mb-3 mb-md-0">
            <div class="filter-card">
                <form action="{{ route('transaction.index', ['userId' => $currentUserId]) }}" method="GET">
                    <label for="kategori_filter" class="form-label">
                        <i class="bi bi-funnel me-1"></i>Filter Kategori
                    </label>
                    <select name="kategori_filter" id="kategori_filter" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua Kategori</option>
                        @foreach ($kategori as $nama_kategori_filter)
                            <option value="{{ $nama_kategori_filter }}" {{ request('kategori_filter') == $nama_kategori_filter ? 'selected' : '' }}>
                                {{ ucfirst($nama_kategori_filter) }}
                            </option>
                        @endforeach
                    </select>
                    @if(request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif
                </form>
            </div>
        </div>
        
        <div class="col-12 col-md-9">
            <div class="search-card">
                <form action="{{ route('transaction.index', ['userId' => $currentUserId]) }}" method="GET">
                    <div class="row g-3">
                        <div class="col-12 col-md-5">
                            <label for="search" class="form-label">
                                <i class="bi bi-search me-1"></i>Pencarian Produk
                            </label>
                            <input type="text" name="search" id="search" class="form-control" placeholder="Cari nama produk..." value="{{ request('search') }}">
                        </div>
                        <div class="col-6 col-md-2 d-grid align-self-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search me-1"></i>Cari
                            </button>
                        </div>
                        <div class="col-6 col-md-2 d-grid align-self-end">
                            <a href="{{ route('transaction.index', ['userId' => $currentUserId]) }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-clockwise me-1"></i>Reset
                            </a>
                        </div>
                    </div>
                    @if(request('kategori_filter'))
                        <input type="hidden" name="kategori_filter" value="{{ request('kategori_filter') }}">
                    @endif
                </form>
            </div>
        </div>
    </div>

    <div class="product-grid default" id="productGrid">
        @forelse ($product as $products)
            <div class="product-card">
                <div class="product-image-container">
                    <div class="product-image">
                        @if ($products->gambar)
                            <img src="{{ asset('storage/' . $products->gambar) }}" alt="{{ $products->nama }}">
                        @else
                            <img src="{{ asset('images/placeholder.png') }}" alt="No Image">
                        @endif
                    </div>
                    
                    <div class="cart-buttons">
                        <input type="number" class="qty-input" min="1" max="{{ $products->stock }}" value="1" data-product-id="{{ $products->id }}">
                        <button class="cart-btn add-to-cart" 
                                data-product-id="{{ $products->id }}" 
                                data-product-name="{{ $products->nama }}" 
                                data-product-price="{{ $products->harga ?? ($products->kategori == 'rokok' && $products->harga_bungkus ? $products->harga_bungkus : 0) }}"
                                data-harga-satuan="{{ $products->harga_satuan ?? 0 }}"
                                data-harga-bungkus="{{ $products->harga_bungkus ?? 0 }}"
                                data-satuan="{{ $products->satuan ? '1' : '0' }}"
                                data-bungkus="{{ $products->bungkus ? '1' : '0' }}">
                            🛒 Add to Cart
                        </button>
                    </div>
                </div>
                
                <div class="product-info">
                    <h3 class="product-name">{{ $products->nama }}</h3>
                    <h6 class="product-code">{{ $products->kode_barang }}</h6>
                    <h6 class="product-category">{{ $products->kategori }}</h6>
                    
                    <div class="price-section">
                        @if($products->kategori == 'rokok')
                            @if($products->bungkus || $products->satuan)
                                @php
                                    $hargaDitampilkan = [];
                                    if ($products->bungkus && isset($products->harga_bungkus)) {
                                        $hargaDitampilkan[] = [
                                            'label' => 'Bungkus',
                                            'price' => 'Rp ' . number_format($products->harga_bungkus, 0, '.', '.')
                                        ];
                                    }
                                    if ($products->satuan && isset($products->harga_satuan)) {
                                        $hargaDitampilkan[] = [
                                            'label' => 'Batang',
                                            'price' => 'Rp ' . number_format($products->harga_satuan, 0, '.', '.')
                                        ];
                                    }
                                @endphp
                                @if(count($hargaDitampilkan) > 0)
                                    <div class="price-multiple">
                                        @foreach($hargaDitampilkan as $harga)
                                            <div class="price-item">
                                                <span class="price-label">{{ $harga['label'] }}</span>
                                                <span class="price-value">{{ $harga['price'] }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-muted">Harga belum diatur</span>
                                @endif
                            @else
                                <div class="price">{{ isset($products->harga) ? ('Rp ' . number_format($products->harga, 0, '.', '.')) : 'Harga belum diatur'}}</div>
                            @endif
                        @else
                            <div class="price">{{ isset($products->harga) ? ('Rp ' . number_format($products->harga, 0, '.', '.') ) : 'Harga belum diatur'}}</div>
                        @endif
                        <div class="stock" style="color: {{$products->stock < 10 ? 'red' : 'green'}}">Stok: {{ $products->stock }}</div>
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <p>Belum ada produk yang tersedia.</p>
            </div>
        @endforelse
    </div>

    <div class="cart-sidebar" id="cartSidebar">
        <div class="cart-sidebar-header">
            <h3>Keranjang Belanja</h3>
            <button class="cart-sidebar-close" id="closeCart">✕</button>
        </div>
        <div class="cart-items" id="cartItems">
        </div>
        <div class="cart-total" id="cartTotal">Total: Rp 0</div>
        <div class="payment-section">
            <label for="paymentMethod">Metode Pembayaran</label>
            <select id="paymentMethod" class="payment-method">
                <option value="cash">Tunai</option>
            </select>
            <label for="paymentAmount">Nominal Pembayaran</label>
            <input type="text" id="paymentAmount" class="payment-input" placeholder="Masukkan nominal">
            <div class="payment-info">
                <span class="price-label">Total</span>
                <span class="price-value" id="paymentTotal">Rp 0</span>
            </div>
            <div class="payment-info">
                <span class="price-label">Kembalian</span>
                <span class="price-value" id="paymentChange">Rp 0</span>
            </div>
        </div>
        <button class="cart-checkout-btn" id="checkoutBtn">Checkout</button>
    </div>
</div>

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
const currentUserId = "{{ $currentUserId }}";
document.addEventListener('DOMContentLoaded', function () {
    const cartSidebar = document.getElementById('cartSidebar');
    const closeCartBtn = document.getElementById('closeCart');
    const cartItemsContainer = document.getElementById('cartItems');
    const cartTotalElement = document.getElementById('cartTotal');
    const wrapper = document.getElementById('wrapper');
    const mainContainer = document.getElementById('mainContainer');
    const productGrid = document.getElementById('productGrid');
    const paymentAmountInput = document.getElementById('paymentAmount');
    const paymentTotalElement = document.getElementById('paymentTotal');
    const paymentChangeElement = document.getElementById('paymentChange');
    let cart = [];

    function formatRupiah(value) {
        return `Rp ${parseInt(value || 0).toLocaleString('id-ID')}`;
    }

    function parseRupiah(formatted) {
        return parseInt(formatted.replace(/[^0-9]/g, '')) || 0;
    }

    paymentAmountInput.addEventListener('input', function () {
        let value = this.value.replace(/[^0-9]/g, '');
        this.value = formatRupiah(value);
        updateChange();
    });

    paymentAmountInput.addEventListener('focus', function () {
        if (this.value === formatRupiah(0)) {
            this.value = '';
        }
    });

    paymentAmountInput.addEventListener('blur', function () {
        if (!this.value) {
            this.value = formatRupiah(0);
        }
    });

    function toggleCart() {
        const isCartActive = cartSidebar.classList.contains('active');
        if (isCartActive) {
            cartSidebar.classList.remove('active');
            wrapper.classList.remove('toggled-cart');
            mainContainer.classList.remove('cart-active');
            mainContainer.classList.add('default');
            productGrid.classList.remove('cart-active');
            productGrid.classList.add('default');
        } else {
            cartSidebar.classList.add('active');
            wrapper.classList.add('toggled-cart');
            mainContainer.classList.remove('default');
            mainContainer.classList.add('cart-active');
            productGrid.classList.remove('default');
            productGrid.classList.add('cart-active');
        }
    }

    closeCartBtn.addEventListener('click', toggleCart);

    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', function () {
            const productId = this.getAttribute('data-product-id');
            const productName = this.getAttribute('data-product-name');
            const qtyInput = this.parentElement.querySelector('.qty-input');
            const quantity = parseInt(qtyInput.value);
            const isRokok = this.closest('.product-card').querySelector('.product-category').textContent.toLowerCase() === 'rokok';
            const hargaBungkus = parseInt(this.getAttribute('data-harga-bungkus') || 0);
            const hargaSatuan = parseInt(this.getAttribute('data-harga-satuan') || 0);
            const hasSatuan = this.getAttribute('data-satuan') === '1';
            const hasBungkus = this.getAttribute('data-bungkus') === '1';

            if (quantity <= 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Jumlah harus lebih dari 0!',
                });
                return;
            }

            if (isRokok && hasSatuan && hasBungkus) {
                Swal.fire({
                    title: 'Pilih Jenis Pembelian',
                    text: `Anda ingin membeli ${productName} per batang atau per bungkus?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Per Batang',
                    confirmButtonColor: '#28a745',
                    cancelButtonText: 'Per Bungkus',
                    cancelButtonColor: '#0d6efd',
                    showDenyButton: true,
                    denyButtonText: 'Batal',
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Pilih batangan
                        addToCart(productId, productName, hargaSatuan, quantity, 'batangan');
                    } else if (result.isDismissed && result.dismiss === Swal.DismissReason.cancel) {
                        // Pilih bungkus
                        addToCart(productId, productName, hargaBungkus, quantity, 'bungkus');
                    }
                });
            } else {
                const productPrice = parseInt(this.getAttribute('data-product-price'));
                addToCart(productId, productName, productPrice, quantity);
            }
        });
    });

    function addToCart(productId, productName, productPrice, quantity, purchaseType = null) {
        if (!productPrice) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Harga produk belum diatur!',
            });
            return;
        }

        const item = {
            id: productId,
            name: productName,
            price: productPrice,
            quantity: quantity,
            purchase_type: purchaseType
        };

        const existingItem = cart.find(cartItem => 
            cartItem.id === productId && 
            cartItem.purchase_type === purchaseType
        );

        if (existingItem) {
            existingItem.quantity += quantity;
        } else {
            cart.push(item);
        }

        updateCartDisplay();

        if (!cartSidebar.classList.contains('active')) {
            toggleCart();
        }
    }

    function removeFromCart(productId, productName, purchaseType) {
        cart = cart.filter(item => {
            return !(item.id === productId && 
                     item.name === productName && 
                     item.purchase_type === purchaseType);
        });
        updateCartDisplay();
        if (cart.length === 0 && cartSidebar.classList.contains('active')) {
            toggleCart();
        }
    }

    function updateCartDisplay() {
        cartItemsContainer.innerHTML = '';
        let total = 0;

        cart.forEach(item => {
            total += item.price * item.quantity;
            const cartItem = document.createElement('div');
            cartItem.className = 'cart-item';
            cartItem.innerHTML = `
                <div>
                    <span class="cart-item-name">${item.name} ${item.purchase_type ? `(${item.purchase_type})` : ''}</span>
                    <span class="cart-item-qty">Qty: ${item.quantity}</span>
                </div>
                <div>
                    <span class="cart-item-price">${formatRupiah(item.price * item.quantity)}</span>
                    <button class="remove-from-cart" 
                        data-product-id="${item.id}" 
                        data-product-name="${item.name}"
                        data-purchase-type="${item.purchase_type || ''}">
                        🗑️ 
                    </button>
                </div>
            `;
            cartItemsContainer.appendChild(cartItem);
        });

        document.querySelectorAll('.remove-from-cart').forEach(button => {
            button.addEventListener('click', function () {
                const productId = this.getAttribute('data-product-id');
                const productName = this.getAttribute('data-product-name');
                const purchaseType = this.getAttribute('data-purchase-type') || null;
                removeFromCart(productId, productName, purchaseType);
            });
        });

        cartTotalElement.textContent = `Total: ${formatRupiah(total)}`;
        paymentTotalElement.textContent = formatRupiah(total);
        updateChange();
    }

    function updateChange() {
        const total = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
        const paymentAmount = parseRupiah(paymentAmountInput.value);
        const change = paymentAmount - total;
        paymentChangeElement.textContent = formatRupiah(Math.max(0, change));
        paymentChangeElement.style.color = change < 0 ? '#e74c3c' : '#27ae60';
    }

    paymentAmountInput.value = formatRupiah(0);

    document.getElementById('checkoutBtn').addEventListener('click', function() {
        const total = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
        const paymentAmount = parseRupiah(paymentAmountInput.value);
        const paymentMethod = document.getElementById('paymentMethod').value;

        if (cart.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Keranjang Kosong',
                text: 'Silakan tambahkan produk ke keranjang terlebih dahulu.'
            });
            return;
        }

        if (paymentAmount < total) {
            Swal.fire({
                icon: 'error',
                title: 'Pembayaran Kurang',
                text: 'Nominal pembayaran kurang dari total belanja.'
            });
            return;
        }

        // Show loading alert
        Swal.fire({
            title: 'Memproses Pembayaran...',
            text: 'Harap tunggu sebentar.',
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        fetch(`/transaction/checkout/${currentUserId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                items: cart.map(item => ({
                    id: item.id,
                    quantity: item.quantity,
                    purchase_type: item.purchase_type
                })),
                payment_method: paymentMethod,
                payment_amount: paymentAmount,
                total_amount: total,
                change_amount: paymentAmount - total
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Close loading alert and show success message
                Swal.fire({
                    icon: 'success',
                    title: 'Pembayaran Berhasil!',
                    text: 'Transaksi telah selesai. Anda akan diarahkan ke nota.',
                    timer: 2000, // Display for 2 seconds
                    showConfirmButton: false
                }).then(() => {
                    // Redirect to invoice URL after success message
                    window.location.href = data.invoice_url;
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: data.message || 'Terjadi kesalahan saat memproses transaksi.'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: 'Terjadi kesalahan saat memproses transaksi.'
            });
        });
    });
});
</script>

@endsection
@endsection
