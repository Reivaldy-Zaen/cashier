@extends('layouts.app')
@section('title','Halaman Tambah Barang')

@section('nav')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

    body {
        font-family: 'Inter', sans-serif;
        min-height: 100vh;
        color: #2d3748;
    }

    .form-container {
        max-width: 1000px;
        margin: 1rem auto;
        background: rgba(255, 255, 255, 0.98);
        padding: 3rem;
        border-radius: 20px;
        box-shadow: 
        0 20px 60px rgba(0, 0, 0, 0.12),
        0 8px 25px rgba(0, 0, 0, 0.08);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        position: relative;
        overflow: hidden;
    }

    .form-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: linear-gradient(90deg, #3b82f6, #1d4ed8, #2563eb);
    }

    .form-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 3rem;
        text-align: center;
        background: linear-gradient(135deg, #1e40af 0%, #3b82f6 50%, #60a5fa 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        position: relative;
    }

    .form-title::after {
        content: '';
        position: absolute;
        bottom: -15px;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 4px;
        background: linear-gradient(90deg, #3b82f6, #1d4ed8);
        border-radius: 2px;
    }

    .form-fields-wrapper {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
    }

    .form-group {
        margin-bottom: 0;
        position: relative;
    }

    .form-label {
        font-size: 0.95rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.75rem;
        display: block;
        transition: color 0.3s ease;
    }

    .form-control, .form-select {
        font-size: 1rem;
        padding: 1rem 1.25rem;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        width: 100%;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(5px);
        position: relative;
    }

    .form-control:focus, .form-select:focus {
        outline: none;
        border-color: #3b82f6;
        background: #ffffff;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        transform: translateY(0);
    }

    .form-control.is-invalid, .form-select.is-invalid {
        border-color: #ef4444;
        animation: none;
    }

    .invalid-feedback {
        font-size: 0.875rem;
        color: #ef4444;
        margin-top: 0.5rem;
        display: block;
        content: none;
    }

    .alert {
        background: #fef2f2;
        border: 1px solid #fecaca;
        border-radius: 8px;
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
        position: relative;
        color: #991b1b;
        box-shadow: none;
    }

    .alert::before {
        content: none;
    }

    .alert strong {
        color: #7f1d1d;
        margin-left: 0;
        font-weight: 700;
    }

    .alert ul {
        margin: 0.5rem 0 0 1.5rem;
        color: #991b1b;
        list-style: disc;
        padding-left: 0;
    }

    .alert ul li {
        position: relative;
        padding-left: 0;
        margin-bottom: 0.25rem;
    }

    .alert ul li::before {
        content: none;
    }

    .alert .btn-close {
        background: none;
        border: none;
        font-size: 1rem;
        color: #991b1b;
        cursor: pointer;
        position: absolute;
        top: 0.5rem;
        right: 0.5rem;
        padding: 0.25rem;
        transition: none;
        border-radius: 0;
        width: auto;
        height: auto;
        display: inline-block;
    }

    .alert .btn-close:hover {
        background: none;
        transform: none;
    }

    .form-check-group {
        display: flex;
        gap: 2rem;
        margin-bottom: 1.5rem;
        padding: 1rem;
        background: #eff6ff;
        border-radius: 8px;
        border: 1px solid #bfdbfe;
    }

    .form-check-inline {
        display: flex;
        align-items: center;
        position: relative;
    }

    .form-check-input {
        width: 1.25rem;
        height: 1.25rem;
        margin: 0;
        border: 1px solid #9ca3af;
        border-radius: 4px;
        transition: none;
        cursor: pointer;
        position: relative;
        appearance: none;
        background: #ffffff;
    }

    .form-check-input:checked {
        background: #3b82f6;
        border-color: #3b82f6;
        transform: none;
    }

    .form-check-input:checked::after {
        content: '✓';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: white;
        font-size: 0.75rem;
        font-weight: normal;
    }

    .form-check-label {
        font-size: 0.95rem;
        font-weight: 500;
        color: #374151;
        margin-left: 0.75rem;
        cursor: pointer;
        transition: none;
    }

    .form-check-input:checked + .form-check-label {
        color: #1d4ed8;
        font-weight: 500;
    }

    .btn {
        font-size: 1rem;
        font-weight: 600;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        transition: none;
        text-align: center;
        border: none;
        cursor: pointer;
        position: relative;
        overflow: hidden;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        min-width: 120px;
    }

    .btn-primary {
        background: #3b82f6;
        color: #ffffff;
        box-shadow: none;
    }

    .btn-primary:hover {
        background: #2563eb;
        transform: none;
        box-shadow: none;
    }

    .btn-primary:active {
        transform: none;
    }

    .btn-secondary {
        background: #6b7280;
        color: #ffffff;
        box-shadow: none;
    }

    .btn-secondary:hover {
        background: #4b5563;
        transform: none;
        box-shadow: none;
    }

    .d-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
    }

    .d-flex-bottom {
        display: flex;
        gap: 2rem;
        margin-top: 2.5rem;
        justify-content: center;
        align-items: center;
    }

    input[type="file"] {
        padding: 1rem 1.25rem;
        background: #f8fafc;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        transition: none;
        cursor: pointer;
        position: relative;
    }

    input[type="file"]:hover {
        border-color: #3b82f6;
        background: #eff6ff;
    }

    input[type="file"]:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: none;
    }

    .btn-primary:disabled {
        background: #9ca3af;
        cursor: not-allowed;
        position: relative;
    }

    .btn-primary:disabled::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 15px;
        height: 15px;
        margin: -7.5px 0 0 -7.5px;
        border: 1.5px solid #ffffff;
        border-top: 1.5px solid transparent;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    @media (max-width: 768px) {
        .form-container {
            margin: 1rem;
            padding: 1.5rem;
            border-radius: 12px;
            max-width: 780px;
        }

        .form-title {
            font-size: 2rem;
            margin-bottom: 2rem;
        }

        .form-fields-wrapper {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .d-grid {
            grid-template-columns: 1fr; 
            gap: 1rem;
        }

        .d-flex-bottom {
            flex-direction: column;
            gap: 0.75rem;
        }

        .btn {
            width: 100%;
            padding: 1rem 1.5rem;
        }

        .form-check-group {
            flex-direction: column;
            gap: 1rem;
            padding: 1rem;
        }

        .alert {
            padding: 1rem;
            margin-bottom: 1.5rem;
        }

        .alert strong {
            margin-left: 0;
        }

        .alert ul {
            margin-left: 1.5rem;
        }
    }

    @media (max-width: 480px) {
        .form-container {
            margin: 0.5rem;
            padding: 1rem;
        }

        .form-title {
            font-size: 1.5rem;
        }

        .form-control, .form-select {
            padding: 0.75rem 1rem;
        }

        .btn {
            font-size: 0.9rem;
            padding: 0.8rem 1.2rem;
        }
    }

</style>

<div class="form-container">
    <h2 class="form-title">Tambah Produk Baru</h2>

    <form action="{{ route('product.index', ['userId' => $currentUserId]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="form-fields-wrapper">
            <div class="form-group">
                <label for="gambar" class="form-label">Gambar Produk</label>
                <input class="form-control @error('gambar') is-invalid @enderror" type="file" id="gambar" name="gambar">
                @error('gambar')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="kode_barang" class="form-label">Kode Barang</label>
                <input type="text" name="kode_barang" id="kode_barang" value="{{ old('kode_barang', $kode_barang ?? '') }}" class="form-control @error('kode_barang') is-invalid @enderror" readonly />
                @error('kode_barang')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="nama" class="form-label">Nama Produk</label>
                <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" />
                @error('nama')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="kategori" class="form-label">Kategori</label>
                <select name="kategori" id="kategori" required class="form-select @error('kategori') is-invalid @enderror">
                    <option value="">Pilih Kategori</option>
                    @foreach ($kategori as $id_kategori_db => $nama_kategori)
                        <option value="{{ $nama_kategori }}" {{ old('kategori', $kategori_lama) == $nama_kategori ? 'selected' : '' }}>
                            {{ $nama_kategori }}
                        </option>
                    @endforeach
                </select>
                @error('kategori')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div id="opsiRokokCheckboxes" style="display: none;" class="form-group form-check-group">
                <div class="form-check form-check-inline">
                    <input class="form-check-input @error('bungkus') is-invalid @enderror" type="checkbox" id="bungkus" name="bungkus" value="1" {{ old('bungkus') ? 'checked' : '' }}>
                    <label class="form-check-label" for="bungkus">Bungkus</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input @error('satuan') is-invalid @enderror" type="checkbox" id="satuan" name="satuan" value="1" {{ old('satuan') ? 'checked' : '' }}>
                    <label class="form-check-label" for="satuan">Satuan</label>
                </div>
                @error('bungkus')
                    <div class="d-block invalid-feedback">{{ $message }}</div>
                @enderror
                @error('satuan')
                    <div class="d-block invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div id="divHargaOriginal" class="form-group">
                <label for="harga" class="form-label">Harga (Rp)</label>
                <input type="text" name="harga" id="harga" class="form-control @error('harga') is-invalid @enderror" min="0" value="{{ old('harga') }}" onkeyup="formatRupiah(this, 'Rp ')" onblur="removeRupiah(this)" onfocus="addRupiah(this)" />
                @error('harga')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div id="divHargaBungkus" style="display: none;" class="form-group">
                <label for="harga_bungkus" class="form-label">Harga Bungkus (Rp)</label>
                <input type="text" name="harga_bungkus" id="harga_bungkus" class="form-control @error('harga_bungkus') is-invalid @enderror" min="0" value="{{ old('harga_bungkus') }}" onkeyup="formatRupiah(this, 'Rp ')" onblur="removeRupiah(this)" onfocus="addRupiah(this)" />
                @error('harga_bungkus')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div id="divHargaSatuan" style="display: none;" class="form-group">
                <label for="harga_satuan" class="form-label">Harga Satuan (Rp)</label>
                <input type="text" name="harga_satuan" id="harga_satuan" class="form-control @error('harga_satuan') is-invalid @enderror" min="0" value="{{ old('harga_satuan') }}" onkeyup="formatRupiah(this, 'Rp ')" onblur="removeRupiah(this)" onfocus="addRupiah(this)" />
                @error('harga_satuan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="stock" class="form-label">Stok</label>
                <input type="number" name="stock" id="stock" class="form-control @error('stock') is-invalid @enderror" min="0" value="{{ old('stock') }}" />
                @error('stock')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        
        <div class="d-flex-bottom">
            <button type="submit" class="btn btn-primary">Tambah Produk</button>
            <a href="{{ route('product.index',['userId' => $currentUserId]) }}" class="btn btn-secondary">Kembali</a>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    function formatRupiah(angka, prefix) {
        if (!angka || angka.value == null) return;
        let number_string = angka.value.replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            let separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        angka.value = prefix == undefined ? rupiah : (rupiah ? 'Rp ' + rupiah : '');
    }

    function removeRupiah(input) {
        if (!input || input.value == null) return;
        input.value = input.value.replace(/[^,\d]/g, '');
    }
    
    function removeFormattingForSubmit(input) {
        if (!input || input.value == null) return;
        input.value = input.value.replace(/[^0-9]/g, '');
    }

    function addRupiah(input) {
        if (input.value) {
            formatRupiah(input, 'Rp ');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const kategoriSelect = document.getElementById('kategori');
        const opsiRokokCheckboxesDiv = document.getElementById('opsiRokokCheckboxes');
        const bungkusCheckbox = document.getElementById('bungkus');
        const satuanCheckbox = document.getElementById('satuan');

        const divHargaOriginal = document.getElementById('divHargaOriginal');
        const hargaInput = document.getElementById('harga');
        const divHargaBungkus = document.getElementById('divHargaBungkus');
        const hargaBungkusInput = document.getElementById('harga_bungkus');
        const divHargaSatuan = document.getElementById('divHargaSatuan');
        const hargaSatuanInput = document.getElementById('harga_satuan');

        function updateDynamicPricingFields() {
            const selectedKategori = kategoriSelect.value;
            const isBungkusChecked = bungkusCheckbox.checked;
            const isSatuanChecked = satuanCheckbox.checked;

            divHargaOriginal.style.display = 'block';
            hargaInput.disabled = false;

            opsiRokokCheckboxesDiv.style.display = 'none';
            bungkusCheckbox.disabled = true;
            satuanCheckbox.disabled = true;

            divHargaBungkus.style.display = 'none';
            hargaBungkusInput.value = '';
            hargaBungkusInput.disabled = true;

            divHargaSatuan.style.display = 'none';
            hargaSatuanInput.value = '';
            hargaSatuanInput.disabled = true;
            
            if (selectedKategori === 'rokok') {
                divHargaOriginal.style.display = 'none';
                hargaInput.value = '';
                hargaInput.disabled = true; 

                opsiRokokCheckboxesDiv.style.display = 'flex'; 
                bungkusCheckbox.disabled = false; 
                satuanCheckbox.disabled = false; 

                if (isBungkusChecked) {
                    divHargaBungkus.style.display = 'block';
                    hargaBungkusInput.disabled = false; 

                    if (isSatuanChecked) {
                        divHargaSatuan.style.display = 'block';
                        hargaSatuanInput.disabled = false; 
                    } else {
                        divHargaSatuan.style.display = 'none';
                        hargaSatuanInput.value = '';
                        hargaSatuanInput.disabled = true;
                    }
                } else {
                    divHargaBungkus.style.display = 'none';
                    hargaBungkusInput.value = '';
                    hargaBungkusInput.disabled = true;

                    divHargaSatuan.style.display = 'none';
                    hargaSatuanInput.value = '';
                    hargaSatuanInput.disabled = true;

                    satuanCheckbox.checked = false;
                    satuanCheckbox.disabled = true; 
                }
            }
        }

        kategoriSelect.addEventListener('change', function() {
            bungkusCheckbox.checked = false;
            satuanCheckbox.checked = false;
            updateDynamicPricingFields();
        });
        bungkusCheckbox.addEventListener('change', updateDynamicPricingFields);
        satuanCheckbox.addEventListener('change', function() {
            if (this.checked && !bungkusCheckbox.checked) {
                alert('Pilihan "Satuan" hanya bisa dipilih jika "Bungkus" juga dipilih.');
                this.checked = false; 
            }
            updateDynamicPricingFields();
        });
        updateDynamicPricingFields();

        if (hargaInput && hargaInput.value && divHargaOriginal.style.display !== 'none') { formatRupiah(hargaInput, 'Rp '); }
        if (hargaBungkusInput && hargaBungkusInput.value && divHargaBungkus.style.display !== 'none') { formatRupiah(hargaBungkusInput, 'Rp '); }
        if (hargaSatuanInput && hargaSatuanInput.value && divHargaSatuan.style.display !== 'none') { formatRupiah(hargaSatuanInput, 'Rp '); }

        document.querySelector('form').addEventListener('submit', function() {
            if (hargaInput && hargaInput.value) { removeFormattingForSubmit(hargaInput); }
            if (hargaBungkusInput && hargaBungkusInput.value) { removeFormattingForSubmit(hargaBungkusInput); }
            if (hargaSatuanInput && hargaSatuanInput.value) { removeFormattingForSubmit(hargaSatuanInput); }
        });
    });
</script>
@endsection