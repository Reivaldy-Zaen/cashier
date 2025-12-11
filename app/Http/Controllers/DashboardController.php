<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $tahun = $request->input('tahun', date('Y'));
        $bulan = $request->input('bulan', date('m'));
        $totalPenjualan = Transaction::whereYear('created_at', $tahun)
            ->whereMonth('created_at', $bulan)
            ->sum('total_amount');
        $totalTransaksi = Transaction::whereYear('created_at', $tahun)
            ->whereMonth('created_at', $bulan)
            ->count();

        $targetPenjualan = 1000000;
        $produkTerlarisSingle = TransactionItem::select('product_name', DB::raw('SUM(quantity) as total_qty'))
            ->whereYear('created_at', $tahun)
            ->whereMonth('created_at', $bulan)
            ->groupBy('product_name')
            ->orderByDesc('total_qty')
            ->first();

        $grafikBulanan = [];
        $grafikTarget = [];

        for ($i = 1; $i <= 12; $i++) {
            $sum = Transaction::whereYear('created_at', $tahun)
                ->whereMonth('created_at', $i)
                ->sum('total_amount');

            $grafikBulanan[] = $sum / 1000000;
            $grafikTarget[] = $targetPenjualan / 1000000; 
        }

        // 2. Grafik Trend Tahunan (3 Tahun Terakhir)
        $years = [$tahun - 2, $tahun - 1, $tahun];
        $grafikTahunan = [];
        foreach ($years as $y) {
            $sum = Transaction::whereYear('created_at', $y)->sum('total_amount');
            $grafikTahunan[] = $sum / 1000000;
        }

        // 3. Grafik Kategori (Pie Chart)
        // Asumsi: Table products punya kolom 'kategori' sesuai kode Anda
        $kategoriData = TransactionItem::join('products', 'transaction_items.product_id', '=', 'products.id')
            ->select('products.kategori', DB::raw('SUM(transaction_items.subtotal) as total_val'))
            ->whereYear('transaction_items.created_at', $tahun)
            ->groupBy('products.kategori')
            ->get();

        $labelKategori = $kategoriData->pluck('kategori');
        $valueKategori = $kategoriData->pluck('total_val');

        // Hitung Persentase untuk Pie Chart
        $totalValSemua = $valueKategori->sum();
        $persenKategori = $valueKategori->map(function ($item) use ($totalValSemua) {
            return $totalValSemua > 0 ? round(($item / $totalValSemua) * 100, 1) : 0;
        });

        // -------------------------------
        // C. DATA TABEL (TOP PRODUK)
        // -------------------------------
        $topProdukList = TransactionItem::selectRaw('product_name, SUM(quantity) as total_qty, SUM(subtotal) as total')
            ->whereYear('created_at', $tahun)
            ->whereMonth('created_at', $bulan)
            ->groupBy('product_name')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        // -------------------------------
        // D. SUSUN DATA UNTUK VIEW
        // -------------------------------
        // Data ini akan di-inject ke JavaScript via @json
        $chartData = [
            'bulanan' => [
                'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                'penjualan' => $grafikBulanan,
                'target' => $grafikTarget
            ],
            'tahunan' => [
                'labels' => $years,
                'values' => $grafikTahunan
            ],
            'kategori' => [
                'labels' => $labelKategori,
                'values' => $persenKategori, // Persen untuk grafik
                'amounts' => $valueKategori  // Rupiah asli untuk tooltip
            ]
        ];

        // Jika request AJAX (dari tombol update JS fetch), kembalikan JSON
        if ($request->wantsJson()) {
            return response()->json([
                'total_penjualan' => $totalPenjualan,
                'total_transaksi' => $totalTransaksi,
                'top_produk' => $topProdukList,
                'bulanan' => $grafikBulanan,
                // ... data lain jika perlu update via AJAX
            ]);
        }

        // Jika akses halaman biasa, kembalikan View
        return view('dashboard', compact(
            'tahun',
            'bulan',
            'totalPenjualan',
            'totalTransaksi',
            'targetPenjualan',
            'produkTerlarisSingle',
            'topProdukList',
            'chartData' // <--- Ini kunci utamanya
        ));
    }
}