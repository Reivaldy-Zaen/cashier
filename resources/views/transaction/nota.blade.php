<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Transaksi</title>
    <style>
        /* Styling Elegan dan Modern */
        body {
            background-color: #f4f7f6;
            color: #333;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .btn {
            display: inline-block;
            font-weight: 600;
            text-align: center;
            white-space: nowrap;
            vertical-align: middle;
            user-select: none;
            border: 1px solid transparent;
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
            line-height: 1.5;
            border-radius: 0.25rem;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease-in-out;
        }

        .btn-primary {
            color: #fff;
            background-color: #2c3e50;
            border-color: #2c3e50;
        }

        .btn-primary:hover {
            background-color: #34495e;
            border-color: #34495e;
        }

        .btn-secondary {
            color: #2c3e50;
            background-color: transparent;
            border-color: #bdc3c7;
        }

        .btn-secondary:hover {
            background-color: #ecf0f1;
        }

        .font-bold {
            font-weight: bold;
        }

        .text-gray-500 {
            color: #7f8c8d;
        }

        .receipt-container {
            max-width: 800px;
            margin: 2rem auto;
            background-color: #ffffff;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            padding: 2.5rem;
            border-radius: 8px;
            border: 1px solid #e0e0e0;
        }

        .receipt-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            border-bottom: 1px solid #ecf0f1;
            padding-bottom: 1rem;
        }

        .receipt-title {
            font-size: 1.6rem;
            font-weight: 600;
            color: #2c3e50;
        }

        .receipt-info {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            margin-bottom: 2rem;
            font-size: 0.9rem;
        }

        .receipt-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1.5rem;
        }

        .receipt-table th {
            background-color: #ecf0f1;
            padding: 0.85rem 1rem;
            text-align: left;
            font-weight: 600;
            font-size: 0.9rem;
            color: #34495e;
        }

        .receipt-table td {
            padding: 0.85rem 1rem;
            border-bottom: 1px solid #ecf0f1;
            font-size: 0.9rem;
        }

        .receipt-table tbody tr:last-child td {
            border-bottom: none;
        }

        .receipt-table tfoot td {
            border-top: 2px solid #bdc3c7;
            padding-top: 1rem;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .receipt-footer {
            margin-top: 2rem;
            text-align: center;
            border-top: 1px dashed #bdc3c7;
            padding-top: 1.5rem;
        }

        @media print {
            .no-print {
                display: none;
            }

            body {
                font-size: 12px;
                margin: 0;
                padding: 0;
                background-color: #fff;
            }

            .receipt-container {
                max-width: 100%;
                margin: 0;
                padding: 0;
                box-shadow: none;
                border: none;
            }
        }
    </style>

    <div class="receipt-container">
        <div class="receipt-header">
            <h1 class="receipt-title">Nota Transaksi #{{ $invoice_number }}</h1>
            <div class="no-print">
                <button onclick="window.print()" class="btn btn-primary">
                    Cetak Nota
                </button>
                @if (Auth::user()->role === 'admin')
                    <a href="{{ route('transaction.history', ['userId' => Auth::user()->userId]) }}" class="btn btn-secondary">
                        Kembali
                    </a>
                @else
                    <a href="{{ route('transaction.index', ['userId' => Auth::user()->userId]) }}" class="btn btn-secondary">
                        Kembali
                    </a>
                @endif
            </div>
        </div>

        <div class="receipt-info">
            <div>
            <p><strong>Tanggal:</strong> {{ $date }}</p>
                <p><strong>Kasir:</strong> {{ $transaction->user->name ?? 'Admin' }}</p>
            </div>
            <div>
                <p><strong>Metode Pembayaran:</strong> {{ ucfirst($transaction->payment_method) }}</p>
            </div>
        </div>

        <table class="receipt-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Produk</th>
                    <th class="text-center">Qty</th>
                    <th class="text-right">Harga</th>
                    <th class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            {{ $item->product->nama ?? 'Produk' }}
                            @if ($item->product->kategori == 'rokok')
                                @if ($item->price == $item->product->harga_satuan)
                                    (Batangan)
                                @elseif($item->price == $item->product->harga_bungkus)
                                    (Bungkus)
                                @endif
                            @endif
                        </td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td class="text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="text-right font-bold">Total</td>
                    <td class="text-right font-bold">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                    </td>
                </tr>
                <tr>
                    <td colspan="4" class="text-right">Pembayaran</td>
                    <td class="text-right">Rp {{ number_format($transaction->payment_amount, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td colspan="4" class="text-right">Kembalian</td>
                    <td class="text-right">Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>

        <div class="receipt-footer">
            <p>Terima kasih telah berbelanja</p>
            <small class="text-gray-500">Barang yang sudah dibeli tidak dapat dikembalikan</small>
        </div>
    </div>
