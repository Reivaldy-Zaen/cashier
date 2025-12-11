@extends('layouts.app')

@section('title', 'Detail Produk')

@section('nav')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-md-12">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body text-center py-4">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <div class="bg-info rounded-circle p-3 me-3">
                            <i class="fas fa-eye text-white fs-4"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 text-dark fw-bold">Detail Produk</h4>
                            <p class="text-muted mb-0">Informasi lengkap produk {{ $product->nama }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="card border-2 border-light bg-light mb-4"> 
                                <div class="card-body text-center p-4">
                                    @if ($product->gambar)
                                        <img src="{{ asset('storage/' . $product->gambar) }}"
                                             class="img-fluid rounded shadow-sm product-image"
                                             alt="Gambar Produk {{ $product->nama }}"
                                             style="max-width: 100%; max-height: 400px; object-fit: cover;">
                                    @else
                                        <div class="no-image-placeholder d-flex flex-column align-items-center justify-content-center"
                                             style="min-height: 300px; background-color: #f8f9fa; border: 2px dashed #dee2e6;">
                                            <i class="fas fa-image text-muted mb-3" style="font-size: 4rem;"></i>
                                            <p class="text-muted fs-5">Tidak ada gambar</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="mb-4">
                                <div class="card border-0 bg-light">
                                    <div class="card-body">
                                        <h6 class="fw-bold text-dark mb-3">
                                            <i class="fas fa-clock me-2 text-primary"></i>Informasi Waktu
                                        </h6>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="d-flex align-items-center mb-2">
                                                    <i class="fas fa-plus-circle text-success me-2"></i>
                                                    <div>
                                                        <small class="text-muted d-block">Dibuat pada</small>
                                                        <strong class="text-dark">{{ $product->created_at->format('d M Y') }}</strong>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="d-flex align-items-center mb-2">
                                                    <i class="fas fa-edit text-warning me-2"></i>
                                                    <div>
                                                        <small class="text-muted d-block">Terakhir diperbarui</small>
                                                        <strong class="text-dark">{{ $product->updated_at->format('d M Y') }}</strong>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-7">
                            <div class="h-100 d-flex flex-column">
                                <div class="mb-4">
                                    <h3 class="fw-bold text-dark mb-2">{{ $product->nama }}</h3>
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-primary fs-6 px-3 py-2">{{ $product->kategori }}</span>
                                        <span class="badge bg-secondary ms-2 fs-6 px-3 py-2">{{ $product->kode_barang }}</span>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-12">
                                        <div class="card border-0 bg-light">
                                            <div class="card-body">
                                                <h6 class="fw-bold text-dark mb-3">
                                                    <i class="fas fa-info-circle me-2 text-primary"></i>Informasi Produk
                                                </h6>

                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <div class="d-flex align-items-center">
                                                            <i class="fas fa-barcode text-primary me-3"></i>
                                                            <div>
                                                                <small class="text-muted d-block">Nama Barang</small>
                                                                <strong class="text-dark">{{ $product->nama }}</strong>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <div class="d-flex align-items-center">
                                                            <i class="fas fa-barcode text-primary me-3"></i>
                                                            <div>
                                                                <small class="text-muted d-block">Kode Barang</small>
                                                                <strong class="text-dark">{{ $product->kode_barang }}</strong>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6 mb-3">
                                                        <div class="d-flex align-items-center">
                                                            <i class="fas fa-tags text-primary me-3"></i>
                                                            <div>
                                                                <small class="text-muted d-block">Kategori</small>
                                                                <strong class="text-dark">{{ $product->kategori }}</strong>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6 mb-3">
                                                        <div class="d-flex align-items-center">
                                                            <i class="fas fa-cubes text-primary me-3"></i>
                                                            <div>
                                                                <small class="text-muted d-block">Stok Tersedia</small>
                                                                <strong class="text-dark fs-5">
                                                                    {{ number_format($product->stock, 0, ',', '.') }}
                                                                    <span class="badge {{ $product->stock > 10 ? 'bg-success' : ($product->stock > 0 ? 'bg-warning' : 'bg-danger') }} ms-2">
                                                                        {{ $product->stock > 10 ? 'Tersedia' : ($product->stock > 0 ? 'Terbatas' : 'Habis') }}
                                                                    </span>
                                                                </strong>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <div class="card border-0 bg-light">
                                        <div class="card-body text-black">
                                            <h6 class="fw-bold mb-3">
                                                <i class="fas fa-money-bill-wave me-2"></i>Informasi Harga
                                            </h6>

                                            @if ($product->kategori == 'rokok')
                                                <div class="row">
                                                    @if ($product->bungkus && $product->harga_bungkus)
                                                        <div class="col-md-6 mb-2">
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <span>Harga per Bungkus:</span>
                                                                <strong class="fs-5">Rp {{ number_format($product->harga_bungkus, 0, ',', '.') }}</strong>
                                                            </div>
                                                        </div>
                                                    @endif

                                                    @if ($product->satuan && $product->harga_satuan)
                                                        <div class="col-md-6 mb-2">
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <span>Harga per Batang:</span>
                                                                <strong class="fs-5">Rp {{ number_format($product->harga_satuan, 0, ',', '.') }}</strong>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            @else
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="fs-5">Harga Produk:</span>
                                                    <strong class="fs-3">Rp {{ number_format($product->harga, 0, ',', '.') }}</strong>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-auto">
                                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                        <a href="{{ route('product.index', ['userId' => $currentUserId])}}"
                                        class="btn btn-outline-secondary btn-lg px-4 me-md-2">
                                            <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .product-image {
        transition: transform 0.3s ease;
        cursor: pointer;
    }

    .product-image:hover {
        transform: scale(1.05);
    }

    .card {
        transition: all 0.3s ease;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }

    .btn {
        transition: all 0.3s ease;
    }

    .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .badge {
        transition: all 0.3s ease;
    }

    .badge:hover {
        transform: scale(1.05);
    }

    .no-image-placeholder {
        transition: all 0.3s ease;
    }

    .no-image-placeholder:hover {
        background-color: #e9ecef !important;
    }

    .bg-gradient:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.3);
    }

    .border-2 {
        border-width: 2px !important;
    }

    @media (max-width: 768px) {
        .product-image {
            max-height: 250px;
        }

        .fs-3 {
            font-size: 1.5rem !important;
        }

        .fs-5 {
            font-size: 1.1rem !important;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Image modal functionality
        const productImage = document.querySelector('.product-image');
        if (productImage) {
            productImage.addEventListener('click', function() {
                // Create modal for full-size image view
                const modal = document.createElement('div');
                modal.className = 'modal fade';
                modal.innerHTML = `
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">{{ $product->nama }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body text-center">
                                <img src="${this.src}" class="img-fluid" alt="${this.alt}">
                            </div>
                        </div>
                    </div>
                `;
                document.body.appendChild(modal);

                const bsModal = new bootstrap.Modal(modal);
                bsModal.show();

                // Remove modal from DOM when hidden
                modal.addEventListener('hidden.bs.modal', function() {
                    document.body.removeChild(modal);
                });
            });
        }

        // Smooth scrolling for buttons
        document.querySelectorAll('.btn').forEach(btn => {
            btn.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
            });

            btn.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });

        // Add loading state for navigation buttons
        document.querySelectorAll('a.btn').forEach(link => {
            link.addEventListener('click', function(e) {
                if (!this.href.includes('#')) {
                    this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Loading...';
                    this.disabled = true;
                }
            });
        });
    });
</script>
@endsection