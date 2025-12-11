@extends('layouts.app')

@section('title', 'Riwayat Transaksi')
{{-- {{ dd($transactions) }} --}}

@section('nav')

<div class="container py-4">
    <div class="mb-4">
        <h1 class="fw-semibold text-dark mb-2">Riwayat Transaksi</h1>
        <p class="text-muted mb-0">Daftar lengkap transaksi yang telah Anda catat</p>
    </div>

    <!-- Filter Card -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('transaction.history', ['userId' => $currentUserId]) }}" method="GET">
                <div class="row g-3">
                    
                    <!-- Date Filter -->
                    <div class="col-md-4">
                        <label for="date_filter" class="form-label fw-semibold">Filter Tanggal</label>
                        <input type="date" name="date_filter" id="date_filter" class="form-control"
                            value="{{ request('date_filter') }}">
                    </div>

                    <!-- Payment Method Filter -->
                    <div class="col-md-4">
                        <label for="payment_method_filter" class="form-label fw-semibold">Metode Pembayaran</label>
                        <select name="payment_method_filter" id="payment_method_filter" class="form-select">
                            <option value="">Semua</option>
                            <option value="cash" {{ request('payment_method_filter') == 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="transfer" {{ request('payment_method_filter') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                            <option value="qris" {{ request('payment_method_filter') == 'qris' ? 'selected' : '' }}>QRIS</option>
                        </select>
                    </div>

                    <!-- Action Buttons -->
                    <div class="col-md-4">
                        <label class="form-label d-none d-md-block">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-dark flex-fill">
                                <i class="bi bi-funnel me-1"></i> Filter
                            </button>
                            <a href="{{ route('transaction.history', ['userId' => $currentUserId]) }}"
                                class="btn btn-outline-secondary flex-fill">
                                <i class="bi bi-arrow-clockwise me-1"></i> Reset
                            </a>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <!-- Transaction Table -->
    <div class="card shadow-sm mb-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-uppercase small fw-semibold">ID</th>
                        <th class="text-uppercase small fw-semibold">Invoice</th>
                        <th class="text-uppercase small fw-semibold">Tanggal</th>
                        <th class="text-uppercase small fw-semibold">Items</th>
                        <th class="text-uppercase small fw-semibold">Total</th>
                        <th class="text-uppercase small fw-semibold">Pembayaran</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transactions as $transaction)
                        <tr>
                            <td>
                                <div>{{ $loop->iteration + ($transactions->currentPage() - 1) * $transactions->perPage() }}</div>
                                <small class="text-muted">{{ $transaction->user_id }}</small>
                            </td>

                            <td>
                                <a href="{{ route('transaction.nota', $transaction->id) }}"
                                    class="text-primary text-decoration-none fw-medium">
                                    {{ $transaction->invoice_number }}
                                </a>
                            </td>

                            <td>
                                <span class="text-nowrap">{{ $transaction->created_at->format('d M Y H:i') }}</span>
                            </td>

                            <td>
                                <ul class="list-unstyled mb-0">
                                    @foreach ($transaction->items as $item)
                                        <li class="mb-1">
                                            <a href="{{ route('transaction.nota', $transaction->id) }}"
                                                class="text-primary text-decoration-none">
                                                {{ $item->product->nama ?? 'Produk' }}
                                                <small class="text-muted">
                                                    ({{ $item->quantity }} &times; Rp {{ number_format($item->price, 0, ',', '.') }})
                                                </small>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </td>

                            <td class="fw-semibold">
                                Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                            </td>

                            <td>
                                <span class="badge bg-secondary">
                                    {{ ucfirst($transaction->payment_method) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <div class="py-4">
                                    <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                    <p class="mb-0">Tidak ada data transaksi yang ditemukan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center">
        {{ $transactions->withQueryString()->links() }}
    </div>

</div>

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const dateFilterInput = document.getElementById('date_filter');
            const urlParams = new URLSearchParams(window.location.search);

            if (!dateFilterInput.value && !urlParams.has('date_filter')) {
                const today = new Date();
                const year = today.getFullYear();
                const month = String(today.getMonth() + 1).padStart(2, '0');
                const day = String(today.getDate()).padStart(2, '0');
                dateFilterInput.value = `${year}-${month}-${day}`;
            }
        });
    </script>
@endsection

@endsection