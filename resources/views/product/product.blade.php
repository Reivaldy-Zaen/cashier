    @extends('layouts.app')
    @section('title', 'Halaman Produk')

    @section('nav')
        @php
            // Variabel sederhana untuk memeriksa peran
            $isAdmin = Auth::user()->role === 'admin';
        @endphp

        <style>
            .product-page {
                background-color: #f8f9fa;
                min-height: 100vh;
                padding: 0;
            }

            .filter-card,
            .search-card {
                background: white;
                border-radius: 12px;
                padding: 1.5rem;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
                border: 1px solid #e9ecef;
            }

            .form-label {
                color: #495057;
                font-weight: 600;
                margin-bottom: 0.5rem;
                font-size: 0.9rem;
            }

            .form-select,
            .form-control {
                border: 1px solid #ced4da;
                border-radius: 8px;
                padding: 0.75rem;
                font-size: 0.9rem;
                transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
            }

            .form-select:focus,
            .form-control:focus {
                border-color: #007bff;
                box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
            }

            .btn {
                border-radius: 8px;
                padding: 0.75rem 1.25rem;
                font-weight: 500;
                font-size: 0.9rem;
                transition: all 0.15s ease-in-out;
                border: none;
            }

            .btn-primary {
                background-color: #007bff;
                color: white;
            }

            .btn-primary:hover {
                background-color: #0056b3;
                transform: translateY(-1px);
            }

            .btn-secondary {
                background-color: #6c757d;
                color: white;
            }

            .btn-secondary:hover {
                background-color: #545b62;
                transform: translateY(-1px);
            }

            .table-container {
                background: white;
                border-radius: 12px;
                padding: 1.5rem;
                box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
                border: 1px solid #e9ecef;
                margin-top: 2rem;
            }

            .table {
                margin-bottom: 0;
            }

            .table thead th {
                background-color: #f8f9fa;
                color: #495057;
                font-weight: 600;
                border-bottom: 2px solid #dee2e6;
                padding: 1rem 0.75rem;
                font-size: 0.85rem;
                text-align: center;
            }

            .table tbody tr {
                border-bottom: 1px solid #f1f3f4;
                transition: background-color 0.15s ease-in-out;
            }

            .table tbody tr:hover {
                background-color: #f8f9fa;
            }

            .table tbody td {
                padding: 1rem 0.75rem;
                vertical-align: middle;
                font-size: 0.85rem;
                color: #495057;
            }

            .img-thumbnail {
                border: 1px solid #dee2e6;
                border-radius: 8px;
                padding: 0.25rem;
                background-color: white;
            }

            .action-buttons {
                display: flex;
                gap: 0.5rem;
                justify-content: center;
                align-items: center;
            }

            .btn-sm {
                padding: 0.5rem 0.75rem;
                font-size: 0.8rem;
                border-radius: 6px;
                min-width: 40px;
                height: 36px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }

            .btn-info {
                background-color: #17a2b8;
                color: white;
            }

            .btn-info:hover {
                background-color: #138496;
                color: white;
            }

            .btn-warning {
                background-color: #ffc107;
                color: #212529;
            }

            .btn-warning:hover {
                background-color: #e0a800;
                color: #212529;
            }

            .btn-danger {
                background-color: #dc3545;
                color: white;
            }

            .btn-danger:hover {
                background-color: #c82333;
                color: white;
            }

            .alert-info {
                background-color: #d1ecf1;
                border: 1px solid #bee5eb;
                color: #0c5460;
                border-radius: 8px;
                padding: 1rem;
                margin-top: 2rem;
            }

            .price-info {
                font-size: 0.85rem;
                line-height: 1.4;
                font-weight: 500;
            }

            .price-divider {
                height: 4px;
                background-color: #000000;
                border: none;
                margin: 0.5rem 0;
            }


            .page-title {
                color: #212529;
                font-weight: 700;
                margin-bottom: 2rem;
                text-align: center;
                font-size: 1.8rem;
            }

            .badge {
                font-size: 0.75rem;
                padding: 0.5rem 0.75rem;
                border-radius: 6px;
                font-weight: 500;
            }

            .badge-category {
                background-color: #e9ecef;
                color: #495057;
            }

            .badge-stock-high {
                background-color: #d4edda;
                color: #155724;
            }

            .badge-stock-medium {
                background-color: #fff3cd;
                color: #856404;
            }

            .badge-stock-low {
                background-color: #f8d7da;
                color: #721c24;
            }

            .no-image-placeholder {
                width: 80px;
                height: 80px;
                background-color: #f8f9fa;
                border: 1px solid #dee2e6;
                border-radius: 8px;
                display: flex;
                align-items: center;
                justify-content: center;
                color: #6c757d;
            }

            .product-code {
                font-weight: 600;
                color: #495057;
            }

            .product-name {
                font-weight: 600;
                color: #212529;
            }

            @media (max-width: 768px) {
                .product-page {
                    padding: 1rem 0;
                }

                .filter-card,
                .search-card,
                .table-container {
                    padding: 1rem;
                }

                .table {
                    font-size: 0.8rem;
                }

                .action-buttons {
                    flex-direction: column;
                    gap: 0.25rem;
                }

                .btn-sm {
                    width: 100%;
                    min-width: auto;
                }

                .img-thumbnail {
                    width: 60px;
                    height: 60px;
                    object-fit: cover;
                }

                .no-image-placeholder {
                    width: 60px;
                    height: 60px;
                }
            }
        </style>
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

        <div class="product-page">
            <div class="container">

                <div class="row mt-4 align-items-start">
                    <div class="col-12 col-md-3 mb-3 mb-md-0">
                        <div class="filter-card">
                            <form action="{{ route('product.index', ['userId' => $currentUserId]) }}" method="GET">
                                <label for="kategori_filter" class="form-label">
                                    <i class="bi bi-funnel me-1"></i>Filter Kategori
                                </label>
                                <select name="kategori_filter" id="kategori_filter" class="form-select"
                                    onchange="this.form.submit()">
                                    <option value="">Semua Kategori</option>
                                    @foreach ($kategori as $nama_kategori_filter)
                                        <option value="{{ $nama_kategori_filter }}"
                                            {{ request('kategori_filter') == $nama_kategori_filter ? 'selected' : '' }}>
                                            {{ ucfirst($nama_kategori_filter) }}
                                        </option>
                                    @endforeach
                                </select>
                                @if (request('search'))
                                    <input type="hidden" name="search" value="{{ request('search') }}">
                                @endif
                            </form>
                        </div>
                    </div>

                    <div class="col-12 col-md-9">
                        <div class="search-card">
                            <form action="{{ route('product.index', ['userId' => $currentUserId]) }}" method="GET">
                                <div class="row g-2">
                                    {{-- <div class="col-12 col-md-5">
                                        <label for="search" class="form-label">
                                            <i class="bi bi-search me-1"></i>Pencarian Produk
                                        </label>
                                        <input type="text" name="search" id="search" class="form-control"
                                            placeholder="Cari nama produk..." value="{{ request('search') }}">
                                    </div> --}}
                                    <div class="col-12 {{ $isAdmin ? 'col-md-5' : 'col-md-4' }}">
                                        <input type="text" class="form-control" placeholder="Cari nama produk..."
                                            name="search" value="{{ request('search') }}">
                                    </div>
                                    <div class="col-6 {{ $isAdmin ? 'col-md-2' : 'col-md-4' }} d-grid align-self-end">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-search me-1"></i>Cari
                                        </button>
                                    </div>
                                    <div class="col-6 {{ $isAdmin ? 'col-md-2' : 'col-md-4' }} d-grid align-self-end">
                                        <a href="{{ route('product.index', ['userId' => $currentUserId]) }}"
                                            class="btn btn-secondary">
                                            <i class="bi bi-arrow-clockwise me-1"></i>Reset
                                        </a>
                                    </div>
                                    @if ($isAdmin)
                                        <div class="col-12 col-md-3 d-grid align-self-end">
                                            <a href="{{ route('product.create', ['userId' => $currentUserId]) }}"
                                                class="btn btn-primary">
                                                <i class="bi bi-plus-lg me-1"></i>Tambah Produk
                                            </a>
                                        </div>
                                    @endif
                                </div>
                        </div>
                        @if (request('kategori_filter'))
                            <input type="hidden" name="kategori_filter" value="{{ request('kategori_filter') }}">
                        @endif
                        </form>
                    </div>
                </div>
            </div>

            @if ($product->isEmpty())
                <div class="alert alert-info" role="alert">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Tidak ada produk ditemukan.</strong> Silakan tambah produk baru atau ubah pencarian Anda.
                </div>
            @else
                <div class="table-container">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th style="width: 5%">No</th>
                                    <th style="width: 10%">Gambar</th>
                                    <th style="width: 12%">Kategori</th>
                                    <th style="width: 20%">Nama Barang</th>
                                    <th style="width: 18%">Harga</th>
                                    <th style="width: 8%">Stok</th>
                                    <th style="width: 12%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $p = 1; ?>
                                @foreach ($product as $po)
                                    <tr>
                                        <td class="text-center">
                                            <span class="fw-bold">{{ $p++ }}</span>
                                        </td>
                                        <td class="text-center">
                                            @if ($po->gambar)
                                                <img src="{{ asset('storage/' . $po->gambar) }}" alt="{{ $po->nama }}"
                                                    width="80" height="80" class="img-thumbnail"
                                                    style="object-fit: cover;" />
                                            @else
                                                <div class="no-image-placeholder">
                                                    <i class="bi bi-image" style="font-size: 1.5rem;"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-category">{{ ucfirst($po->kategori) }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="product-name">{{ $po->nama }}</span>
                                        </td>
                                        <td class="price-info">
                                            @if ($po->kategori == 'rokok')
                                                @if ($po->bungkus || $po->satuan)
                                                    @php
                                                        $hargaDitampilkan = [];
                                                        if ($po->bungkus && isset($po->harga_bungkus)) {
                                                            $hargaDitampilkan[] =
                                                                '<div><strong>Bungkus:</strong><br>Rp ' .
                                                                number_format($po->harga_bungkus, 0, '.', '.') .
                                                                '</div>';
                                                        }
                                                        if ($po->satuan && isset($po->harga_satuan)) {
                                                            $hargaDitampilkan[] =
                                                                '<div><strong>Batang:</strong><br>Rp ' .
                                                                number_format($po->harga_satuan, 0, '.', '.') .
                                                                '</div>';
                                                        }
                                                    @endphp
                                                    @if (count($hargaDitampilkan) > 0)
                                                        {!! implode('<hr class="price-divider">', $hargaDitampilkan) !!}
                                                    @else
                                                        <span class="text-muted">Harga belum diatur</span>
                                                    @endif
                                                @else
                                                    <strong>{{ isset($po->harga) ? 'Rp ' . number_format($po->harga, 0, '.', '.') : 'Harga belum diatur' }}</strong>
                                                @endif
                                            @else
                                                <strong>{{ isset($po->harga) ? 'Rp ' . number_format($po->harga, 0, '.', '.') : 'Harga belum diatur' }}</strong>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($po->stock > 10)
                                                <span class="badge badge-stock-high">{{ $po->stock }} pcs</span>
                                            @elseif($po->stock > 0)
                                                <span class="badge badge-stock-medium">{{ $po->stock }} pcs</span>
                                            @else
                                                <span class="badge badge-stock-low">Habis</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="{{ route('product.show', ['userId' => $currentUserId, 'product' => $po->id]) }}"
                                                    class="btn btn-info btn-sm" title="Lihat Detail">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                @if (Auth::user()->role === 'admin')
                                                    <a href="{{ route('product.edit', ['userId' => $currentUserId, 'product' => $po->id]) }}"
                                                        class="btn btn-warning btn-sm" title="Edit Produk">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <form class="d-inline delete-form"
                                                        data-product-name="{{ $po->nama }}"
                                                        action="{{ route('product.destroy', ['userId' => $currentUserId, 'product' => $po->id]) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm"
                                                            title="Hapus Produk">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
        </div>
    @endsection

    @section('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const deleteForms = document.querySelectorAll('.delete-form');

                deleteForms.forEach(form => {
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();

                        const productName = this.dataset.productName;
                        const formToSubmit = this;

                        const swalWithBootstrapButtons = Swal.mixin({
                            customClass: {
                                confirmButton: "btn btn-success ms-2",
                                cancelButton: "btn btn-danger"
                            },
                            buttonsStyling: false
                        });

                        swalWithBootstrapButtons.fire({
                            title: `Anda Ingin Mengahapus ${productName}?`,
                            text: `Anda tidak akan dapat mengembalikan ini! Menghapus produk: ${productName}`,
                            icon: "question",
                            showCancelButton: true,
                            confirmButtonText: "Ya, Hapus!",
                            cancelButtonText: "Tidak, Batalkan!",
                            reverseButtons: true
                        }).then((result) => {
                            if (result.isConfirmed) {
                                Swal.fire({
                                    title: "Berhasil Dihapus!",
                                    text: `Product ${productName} telah dihapus.`,
                                    icon: "success",
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => {
                                    formToSubmit.submit();
                                });
                            } else if (result.dismiss === Swal.DismissReason.cancel) {
                                swalWithBootstrapButtons.fire({
                                    title: "Dibatalkan",
                                    text: "Produk Anda aman :)",
                                    icon: "error",
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                            }
                        });
                    });
                });
            });
        </script>
    @endsection
