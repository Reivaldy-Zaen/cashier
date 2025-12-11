@extends('layouts.app')

@section('title', 'Log Transaksi')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Log Transaksi</h1>

    @if ($transactions->isEmpty())
        <div class="alert alert-info" role="alert">
            Belum ada transaksi yang tercatat.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID Transaksi</th>
                        <th>Tanggal</th>
                        <th>Total Belanja</th>
                        <th>Nominal Dibayar</th>
                        <th>Kembalian</th>
                        <th>Metode Pembayaran</th>
                        <th>Detail Item</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transactions as $transaction)
                    <tr>
                        <td>{{ $transaction->id }}</td>
                        <td>{{ $transaction->created_at->format('d M Y H:i') }}</td>
                        <td>Rp {{ number_format($transaction->total_amount, 0, '.', '.') }}</td>
                        <td>Rp {{ number_format($transaction->payment_amount, 0, '.', '.') }}</td>
                        <td>Rp {{ number_format($transaction->change_amount, 0, '.', '.') }}</td>
                        <td>{{ ucfirst($transaction->payment_method) }}</td>
                        <td>
                            <ul>
                                @foreach ($transaction->items as $item)
                                    <li>{{ $item->product_name }} ({{ $item->quantity }}x) - Rp {{ number_format($item->subtotal, 0, '.', '.') }}</li>
                                @endforeach
                            </ul>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $transactions->links() }} {{-- Untuk pagination --}}
    @endif

    <div class="mt-3">
        <a href="{{ route('transaction.index', ['userId' => $userId]) }}" class="btn btn-secondary">Kembali ke Transaksi Baru</a>
    </div>
</div>
@endsection