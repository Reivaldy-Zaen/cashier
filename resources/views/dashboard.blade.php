@extends('layouts.app')

@section('title', 'Dashboard Penjualan - Family Cell')

@section('nav')
    <div class="container-fluid px-0">
        <div class="container py-4">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            <div class="mb-4">
                <h1 class="display-5 fw-bold text-primary mb-2">Dashboard Penjualan</h1>
                <p class="text-muted">Family Cell - Monitoring & Analisis Penjualan</p>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <form action="{{ route('dashboard', ['userId' => auth()->user()->userId]) }}" method="GET">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label fw-semibold"><i class="bi bi-calendar3"></i> Tahun</label>
                                <select class="form-select" name="tahun" id="filterTahun">
                                    @php
                                        $currentYear = date('Y');
                                        // PERBAIKAN: Menggunakan $tahun (bukan $year)
                                        $selectedYear = $tahun ?? $currentYear;
                                    @endphp

                                    @for ($y = 2022; $y <= 2026; $y++)
                                        <option value="{{ $y }}" {{ $y == $selectedYear ? 'selected' : '' }}>
                                            {{ $y }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold"><i class="bi bi-calendar-month"></i> Bulan</label>
                                <select class="form-select" name="bulan" id="filterBulan">
                                    @php
                                        // PERBAIKAN: Menggunakan $bulan (bukan $month)
                                        $selectedMonth = $bulan ?? date('n');
                                    @endphp

                                    @for ($m = 1; $m <= 12; $m++)
                                        <option value="{{ $m }}" {{ $m == $selectedMonth ? 'selected' : '' }}>
                                            {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">&nbsp;</label>
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-filter"></i> Terapkan Filter
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-md-6 col-lg-3">
                    <div class="card border-0 shadow-sm h-100"
                        style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);">
                        <div class="card-body text-white">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <i class="bi bi-currency-dollar fs-1"></i>
                                <span class="badge bg-white bg-opacity-25">Bulan Ini</span>
                            </div>
                            <h3 class="fw-bold mb-1" id="totalPenjualan">
                                Rp {{ number_format($totalPenjualan, 0, ',', '.') }}
                            </h3>
                            <p class="mb-0 opacity-75">Total Penjualan</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="card border-0 shadow-sm h-100"
                        style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                        <div class="card-body text-white">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <i class="bi bi-graph-up-arrow fs-1"></i>
                                @php
                                    $persen = $targetPenjualan > 0 ? ($totalPenjualan / $targetPenjualan) * 100 : 0;
                                @endphp
                                <span class="badge bg-white bg-opacity-25" id="pencapaianBadge">
                                    {{ round($persen) }}%
                                </span>
                            </div>
                            <h3 class="fw-bold mb-1" id="targetPenjualan">
                                Rp {{ number_format($targetPenjualan, 0, ',', '.') }}
                            </h3>
                            <p class="mb-0 opacity-75">Target Bulan Ini</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="card border-0 shadow-sm h-100"
                        style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);">
                        <div class="card-body text-white">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <i class="bi bi-cart-check fs-1"></i>
                                <span class="badge bg-white bg-opacity-25">Transaksi</span>
                            </div>
                            <h3 class="fw-bold mb-1" id="totalTransaksi">
                                {{ number_format($totalTransaksi) }}
                            </h3>
                            <p class="mb-0 opacity-75">Total Transaksi</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="card border-0 shadow-sm h-100"
                        style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                        <div class="card-body text-white">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <i class="bi bi-box-seam fs-1"></i>
                                <span class="badge bg-white bg-opacity-25">Terlaris</span>
                            </div>
                            <h3 class="fw-bold mb-1" id="produkTerlaris">
                                {{ $produkTerlarisSingle->product_name ?? 'Belum Ada Data' }}
                            </h3>
                            <p class="mb-0 opacity-75">Produk Terlaris</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-lg-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title fw-bold mb-4">
                                <i class="bi bi-bar-chart-fill text-primary"></i> Penjualan Bulanan {{ $tahun }}
                            </h5>
                            <canvas id="chartPenjualanBulanan" height="300"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title fw-bold mb-4">
                                <i class="bi bi-graph-up text-success"></i> Trend Penjualan Tahunan
                            </h5>
                            <canvas id="chartTrendTahunan" height="300"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title fw-bold mb-4">
                                <i class="bi bi-pie-chart-fill text-warning"></i> Distribusi Kategori
                            </h5>
                            <div class="position-relative" style="height: 250px;">
                                <canvas id="chartKategori"></canvas>
                            </div>
                            <div class="mt-4" id="kategoriLegend"></div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-8">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title fw-bold mb-4">
                                <i class="text-warning"></i> 5 Produk Terlaris
                            </h5>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Produk</th>
                                            <th class="text-center">Terjual</th>
                                            <th class="text-end">Total Nilai</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($topProdukList as $index => $produk)
                                        <tr>
                                            <td><span class="badge bg-primary rounded-circle">{{ $index + 1 }}</span></td>
                                            <td class="fw-semibold">{{ $produk->product_name }}</td>
                                            <td class="text-center">{{ $produk->total_qty }} unit</td>
                                            <td class="text-end fw-bold">Rp {{ number_format($produk->total, 0, ',', '.') }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">Belum ada data penjualan.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>

    <script>
        const salesData = @json($chartData);
        const ctxBulanan = document.getElementById('chartPenjualanBulanan').getContext('2d');
        const chartBulanan = new Chart(ctxBulanan, {
            type: 'bar',
            data: {
                labels: salesData.bulanan.labels,
                datasets: [{
                    label: 'Penjualan (Juta)',
                    data: salesData.bulanan.penjualan,
                    backgroundColor: '#3b82f6',
                    borderRadius: 6,
                    borderSkipped: false
                }, {
                    label: 'Target (Juta)',
                    data: salesData.bulanan.target,
                    backgroundColor: '#93c5fd',
                    borderRadius: 6,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top' },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': Rp ' + context.parsed.y + ' Juta';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { callback: function(value) { return value + ' Jt'; } }
                    }
                }
            }
        });

        // --- Chart Trend Tahunan ---
        const ctxTahunan = document.getElementById('chartTrendTahunan').getContext('2d');
        const chartTahunan = new Chart(ctxTahunan, {
            type: 'line',
            data: {
                labels: salesData.tahunan.labels,
                datasets: [{
                    label: 'Penjualan (Juta)',
                    data: salesData.tahunan.values,
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 6,
                    pointBackgroundColor: '#10b981',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top' },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Penjualan: Rp ' + context.parsed.y + ' Juta';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { callback: function(value) { return value + ' Jt'; } }
                    }
                }
            }
        });

        // --- Chart Kategori (Pie) ---
        const ctxKategori = document.getElementById('chartKategori').getContext('2d');
        const pieColors = ['#3b82f6', '#10b981', '#f59e0b', '#8b5cf6', '#ef4444', '#6366f1'];
        
        const chartKategori = new Chart(ctxKategori, {
            type: 'doughnut',
            data: {
                labels: salesData.kategori.labels,
                datasets: [{
                    data: salesData.kategori.values, 
                    backgroundColor: pieColors,
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let idx = context.dataIndex;
                                let amount = salesData.kategori.amounts[idx];
                                let formatted = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(amount);
                                return context.label + ': ' + context.parsed + '% (' + formatted + ')';
                            }
                        }
                    }
                }
            }
        });

        // --- Custom Legend untuk Kategori ---
        if(salesData.kategori.labels.length > 0) {
            const legendHTML = salesData.kategori.labels.map((label, i) => {
                const color = pieColors[i % pieColors.length];
                const amount = new Intl.NumberFormat('id-ID').format(salesData.kategori.amounts[i]);
                return `
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="d-flex align-items-center">
                            <span class="badge me-2" style="background-color: ${color}; width: 12px; height: 12px;"></span>
                            <small>${label}</small>
                        </div>
                        <small class="fw-bold">${salesData.kategori.values[i]}% (Rp ${amount})</small>
                    </div>
                `;
            }).join('');
            document.getElementById('kategoriLegend').innerHTML = legendHTML;
        } else {
            document.getElementById('kategoriLegend').innerHTML = '<p class="text-center text-muted small">Belum ada data kategori.</p>';
        }

        // --- Animasi Kartu saat Load ---
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    card.style.transition = 'all 0.5s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });
    </script>

    <style>
        .card { transition: transform 0.3s ease, box-shadow 0.3s ease; }
        .card:hover { transform: translateY(-5px); box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15) !important; }
        .table tbody tr { transition: background-color 0.2s ease; }
        .table tbody tr:hover { background-color: #f8f9fa; }
        .badge.rounded-circle { width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; font-size: 0.875rem; }
        canvas { max-height: 300px; }
        @media (max-width: 768px) {
            .display-5 { font-size: 1.75rem; }
            canvas { max-height: 250px; }
        }
    </style>
@endsection