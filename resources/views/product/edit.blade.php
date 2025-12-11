@extends('layouts.app')
@section('title','Halaman Edit Barang')

@section('nav')

<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body text-center py-4">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <div class="bg-primary rounded-circle p-3 me-3">
                            <i class="fas fa-edit text-white fs-4"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 text-dark fw-bold">Edit Produk</h4>
                            <p class="text-muted mb-0">Perbarui informasi produk {{$product->nama}}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <form action="{{route('product.update', ['userId' => $currentUserId , 'product' => $product->id])}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        @if ($errors->any())
                            <div class="alert alert-danger border-0 rounded-3 mb-4">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <strong>Terdapat kesalahan:</strong>
                                </div>
                                <ul class="mb-0 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6">

                                <div class="mb-4">
                                    <label for="kode_barang" class="form-label fw-semibold text-dark">
                                        <i class="fas fa-barcode me-2 text-primary"></i>Kode Barang
                                    </label>
                                    <input type="text" name="kode_barang" id="kode_barang" 
                                           value="{{ old('kode_barang', $product->kode_barang) }}" 
                                           class="form-control form-control-lg border-2 bg-light" readonly />
                                </div>

                                <div class="mb-4">
                                    <label for="nama" class="form-label fw-semibold text-dark">
                                        <i class="fas fa-box me-2 text-primary"></i>Nama Produk <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="nama" id="nama" 
                                           value="{{ old('nama', $product->nama) }}" 
                                           class="form-control form-control-lg border-2" required 
                                           placeholder="Masukkan nama produk" />
                                </div>

                                <div class="mb-4">
                                    <label for="kategori" class="form-label fw-semibold text-dark">
                                        <i class="fas fa-tags me-2 text-primary"></i>Kategori <span class="text-danger">*</span>
                                    </label>
                                    <select name="kategori" id="kategori" class="form-select form-select-lg border-2" required>
                                        <option value="">--- Pilih Kategori ---</option>
                                        @foreach ($kategori as $nama_kategori)
                                            <option value="{{ $nama_kategori }}" {{ (old('kategori', $product->kategori) == $nama_kategori) ? 'selected' : '' }}>
                                                {{ $nama_kategori }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-4">
                                    <label for="stock" class="form-label fw-semibold text-dark">
                                        <i class="fas fa-cubes me-2 text-primary"></i>Stok <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" name="stock" id="stock" 
                                           value="{{ old('stock', $product->stock) }}" 
                                           class="form-control form-control-lg border-2" min="0" required 
                                           placeholder="Jumlah stok" />
                                </div>

                                @if ($product->gambar)
                                    <div class="mb-4">
                                        <label class="form-label fw-semibold text-dark">
                                            <i class="fas fa-image me-2 text-primary"></i>Gambar Saat Ini
                                        </label>
                                        <div class="card border-2">
                                            <div class="card-body p-3 text-center">
                                                <img src="{{ asset('storage/' . $product->gambar) }}" 
                                                     alt="Gambar Produk Saat Ini" 
                                                     class="img-thumbnail shadow-sm mb-3" 
                                                     style="width: 200px; height: 200px; object-fit: cover;">
                                                <div class="form-check d-flex justify-content-center">
                                                    <input type="checkbox" name="remove_gambar" id="remove_gambar" 
                                                           value="true" class="form-check-input form-check-input-lg me-2">
                                                    <label class="form-check-label text-danger fw-semibold" for="remove_gambar">
                                                        <i class="fas fa-trash me-1"></i>Hapus Gambar Ini
                                                    </label>
                                                </div>
                                                <small class="text-muted d-block mt-2">
                                                    <i class="fas fa-info-circle me-1"></i>
                                                    Centang untuk menghapus gambar yang ada
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="col-md-6">

                                <div id="rokokFields" class="mb-4" style="display: {{ (old('kategori', $product->kategori) == 'rokok') ? 'block' : 'none' }};">
                                    <div class="card bg-light border-2 border-primary">
                                        <div class="card-header bg-primary text-white py-3">
                                            <h6 class="mb-0 fw-semibold">
                                                <i class="fas fa-smoking me-2"></i>Pengaturan Rokok
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-check mb-3">
                                                <input type="checkbox" name="bungkus" id="bungkus" class="form-check-input form-check-input-lg" value="1" {{ old('bungkus', $product->bungkus) ? 'checked' : '' }}>
                                                <label class="form-check-label fw-semibold" for="bungkus">
                                                    Jual per Bungkus
                                                </label>
                                            </div>

                                            <div class="mb-3" id="hargaBungkusField" style="display: {{ (old('kategori', $product->kategori) == 'rokok' && old('bungkus', $product->bungkus)) ? 'block' : 'none' }};">
                                                <label for="harga_bungkus" class="form-label fw-semibold">Harga per Bungkus</label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-primary text-white">Rp</span>
                                                    <input type="text" name="harga_bungkus" id="harga_bungkus" 
                                                           value="{{ old('harga_bungkus', $product->harga_bungkus ?? '') }}" 
                                                           class="form-control form-control-lg currency-input border-start-0" 
                                                           placeholder="0" />
                                                </div>
                                            </div>

                                            <div class="form-check mb-3">
                                                <input type="checkbox" name="satuan" id="satuan" class="form-check-input form-check-input-lg" value="1" {{ old('satuan', $product->satuan) ? 'checked' : '' }}>
                                                <label class="form-check-label fw-semibold" for="satuan">
                                                    Jual per Batang (Satuan)
                                                </label>
                                            </div>

                                            <div class="mb-3" id="hargaSatuanField" style="display: {{ (old('kategori', $product->kategori) == 'rokok' && old('satuan', $product->satuan)) ? 'block' : 'none' }};">
                                                <label for="harga_satuan" class="form-label fw-semibold">Harga per Batang</label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-primary text-white">Rp</span>
                                                    <input type="text" name="harga_satuan" id="harga_satuan" 
                                                           value="{{ old('harga_satuan', $product->harga_satuan ?? '') }}" 
                                                           class="form-control form-control-lg currency-input border-start-0" 
                                                           placeholder="0" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4" id="hargaNormalField" style="display: {{ (old('kategori', $product->kategori) != 'rokok' || old('kategori', $product->kategori) == '') ? 'block' : 'none' }};">
                                    <label for="harga" class="form-label fw-semibold text-dark">
                                        <i class="fas fa-money-bill-wave me-2 text-primary"></i>Harga
                                    </label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-primary text-white fw-bold">Rp</span>
                                        <input type="text" name="harga" id="harga" 
                                               value="{{ old('harga', $product->harga ?? '') }}" 
                                               class="form-control border-2 border-start-0 currency-input" 
                                               placeholder="0" />
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="gambar" class="form-label fw-semibold text-dark">
                                        <i class="fas fa-upload me-2 text-primary"></i>Upload Gambar Baru
                                    </label>
                                    
                                    <div class="card border-2 border-dashed border-primary bg-light">
                                        <div class="card-body text-center p-4">
                                            <div class="mb-3">
                                                <i class="fas fa-cloud-upload-alt fa-3x text-primary mb-3"></i>
                                                <h6 class="fw-semibold text-dark">Pilih Gambar</h6>
                                            </div>
                                            
                                            <div class="input-group">
                                                <span class="input-group-text bg-primary text-white">
                                                    <i class="fas fa-upload"></i>
                                                </span>
                                                <input type="file" name="gambar" id="gambar" 
                                                       class="form-control form-control-lg border-start-0" 
                                                       accept="image/jpeg,image/png,image/jpg">
                                            </div>
                                            
                                            <small class="form-text text-muted mt-2 d-block">
                                                <i class="fas fa-info-circle me-1"></i>
                                                @if ($product->gambar)
                                                    Biarkan kosong jika tidak ingin mengubah gambar. Format: JPG, JPEG, PNG. Max: 2MB.
                                                @else
                                                    Pilih gambar produk. Format: JPG, JPEG, PNG. Max: 2MB.
                                                @endif
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-grid gap-3 d-md-flex justify-content-md-end">
                                    <a href="{{ route('product.index', ['userId' => $currentUserId]) }}" 
                                       class="btn btn-outline-secondary btn-lg px-4 me-md-2">
                                        <i class="fas fa-arrow-left me-2"></i>Kembali
                                    </a>
                                    <button type="submit" class="btn btn-primary btn-lg px-4">
                                        <i class="fas fa-save me-2"></i>Update Produk
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .form-control:focus, .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
    }
    
    .card {
        transition: all 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-2px);
    }
    
    .form-check-input:checked {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
    
    .input-group-text {
        border: 2px solid;
    }
    
    .btn {
        transition: all 0.3s ease;
    }
    
    .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    .img-thumbnail {
        border: 2px solid #dee2e6;
        transition: all 0.3s ease;
    }
    
    .img-thumbnail:hover {
        transform: scale(1.02);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .form-check-input-lg {
        width: 1.25rem;
        height: 1.25rem;
    }
    
    .border-2 {
        border-width: 2px !important;
    }
    
    .border-dashed {
        border-style: dashed !important;
    }
    
    .form-check-input-lg {
        width: 1.25rem;
        height: 1.25rem;
    }
</style>

<script>
    function formatNumberForDisplay(numberString) {
        if (!numberString) return '';
        let cleanNumberString = String(numberString).replace(/[^0-9-]/g, '');
        if (cleanNumberString === '-' || cleanNumberString === '') return '';
        let number = parseInt(cleanNumberString, 10);
        if (isNaN(number)) return '';
        let formatted = number.toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
        return formatted;
    }

    function unformatNumberForSubmission(formattedString) {
        if (!formattedString) return '';
        let unformatted = String(formattedString).replace(/Rp\s?|\./g, '');
        if (unformatted === '') {
            return null;
        }
        return unformatted;
    }

    document.addEventListener('DOMContentLoaded', function() {
        const kategoriSelect = document.getElementById('kategori');
        const rokokFields = document.getElementById('rokokFields');
        const hargaNormalField = document.getElementById('hargaNormalField');
        const bungkusCheckbox = document.getElementById('bungkus');
        const satuanCheckbox = document.getElementById('satuan');
        const hargaBungkusInput = document.getElementById('harga_bungkus');
        const hargaSatuanInput = document.getElementById('harga_satuan');
        const hargaNormalInput = document.getElementById('harga');

        const priceInputs = [hargaBungkusInput, hargaSatuanInput, hargaNormalInput];

        priceInputs.forEach(input => {
            if (input) {
                input.value = formatNumberForDisplay(input.value);

                input.addEventListener('input', function() {
                    const cursorPosition = this.selectionStart;
                    const oldLength = this.value.length;
                    let cleanValue = this.value.replace(/[^0-9-]/g, '');
                    if (cleanValue.startsWith('-')) {
                        cleanValue = '-' + cleanValue.substring(1).replace(/[^0-9]/g, '');
                    } else {
                        cleanValue = cleanValue.replace(/[^0-9]/g, '');
                    }
                    this.value = formatNumberForDisplay(cleanValue);

                    const newLength = this.value.length;
                    const difference = newLength - oldLength;
                    this.setSelectionRange(cursorPosition + difference, cursorPosition + difference);
                });

                input.addEventListener('focus', function() {
                    if (this.value === '' || this.value === 'Rp ') {
                        this.value = '';
                    } else {
                        this.value = unformatNumberForSubmission(this.value);
                        setTimeout(() => {
                            this.setSelectionRange(this.value.length, this.value.length);
                        }, 0);
                    }
                });

                input.addEventListener('blur', function() {
                    if (this.value !== '' && this.value !== 'Rp ') {
                        this.value = formatNumberForDisplay(this.value);
                    } else {
                        this.value = '';
                    }
                });
            }
        });

        function togglePriceFields() {
            if (kategoriSelect.value === 'rokok') {
                rokokFields.style.display = 'block';
                hargaNormalField.style.display = 'none';
                hargaNormalInput.value = '';
                toggleRokokSubFields();
            } else {
                rokokFields.style.display = 'none';
                hargaNormalField.style.display = 'block';
                bungkusCheckbox.checked = false;
                satuanCheckbox.checked = false;
                hargaBungkusField.style.display = 'none';
                hargaSatuanField.style.display = 'none';
                hargaBungkusInput.value = '';
                hargaSatuanInput.value = '';
            }
        }

        function toggleRokokSubFields() {
            if (kategoriSelect.value === 'rokok') {
                if (bungkusCheckbox.checked) {
                    hargaBungkusField.style.display = 'block';
                    if (satuanCheckbox.checked) {
                        hargaSatuanField.style.display = 'block';
                    } else {
                        hargaSatuanField.style.display = 'none';
                        hargaSatuanInput.value = '';
                    }
                } else {
                    hargaBungkusField.style.display = 'none';
                    hargaSatuanField.style.display = 'none';
                    hargaBungkusInput.value = '';
                    hargaSatuanInput.value = '';
                    satuanCheckbox.checked = false;
                }
            }
        }

        kategoriSelect.addEventListener('change', togglePriceFields);
        bungkusCheckbox.addEventListener('change', toggleRokokSubFields);
        satuanCheckbox.addEventListener('change', toggleRokokSubFields);

        togglePriceFields();

        const form = document.querySelector('form');
        form.addEventListener('submit', function() {
            priceInputs.forEach(input => {
                if (input) {
                    input.value = unformatNumberForSubmission(input.value);
                }
            });
        });
    });
</script>

@endsection