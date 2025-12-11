@extends('layouts.app')

@section('title', 'Halaman Kategori')

@section('nav')

<div class="container mt-4">

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="card-title mb-3"><i class="bi bi-search me-2"></i>Pencarian Kategori</h5>
            <div class="d-flex justify-content-between align-items-center">
                <form action="{{ route('kategori.index',['userId'=>$currentUserId]) }}" method="GET" class="flex-grow-1 me-3">
                    <div class="input-group">
                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Cari kategori..."
                            value="{{ request('search') }}"
                        >
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i> Cari
                        </button>
                        <a href="{{ route('kategori.index',['userId' =>$currentUserId]) }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-clockwise"></i> Reset
                        </a>
                    </div>
                </form>
                <a href="{{ route('kategori.create',['userId' => $currentUserId]) }}" class="btn btn-primary text-nowrap">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Kategori
                </a>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0"> 
                    <thead class="table-light">
                        <tr class="text-center">
                            <th style="width: 50px;">No</th>
                            <th>Kategori</th>
                            <th>Deskripsi</th>
                            <th style="width: 180px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $k = 1; @endphp
                        @foreach ($kategori as $ka)
                            <tr>
                                <td class="text-center">{{ $k++ }}</td>
                                <td class="text-center">
                                    <span class="badge bg-primary text-white">{{ $ka->kategori }}</span>
                                </td>
                                <td class="text-center">{{ $ka->description }}</td>
                                <td class="text-center">
                                    <a href="{{ route('kategori.edit', ['userId' => $currentUserId, 'kategori'=>$ka->id]) }}" class="btn btn-warning btn-sm me-1">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                <form class="d-inline delete-form" data-kategori-name="{{ $ka->kategori }}" action="{{ route('kategori.destroy', ['userId' => $currentUserId, 'kategori' => $ka->id]) }}" method="POST">
                                     @csrf
                                     @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" title="Hapus Produk">
                                    <i class="bi bi-trash"></i>
                                </button>
                                </form>
                                </td>
                            </tr>
                        @endforeach

                        @if($kategori->isEmpty())
                            <tr>
                                <td colspan="4" class="text-center text-muted">
                                    Tidak ada kategori ditemukan.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteForms = document.querySelectorAll('.delete-form');

        deleteForms.forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();

                const kategoriName = this.dataset.kategoriName;
                const formToSubmit = this;

                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: "btn btn-success ms-2",
                        cancelButton: "btn btn-danger"
                    },
                    buttonsStyling: false
                });

                swalWithBootstrapButtons.fire({
                    title: `Anda Ingin Menghapus Kategori ${kategoriName}?`,
                    text: `Anda tidak akan dapat mengembalikan ini! Menghapus Kategori: ${kategoriName}`,
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonText: "Ya, Hapus!",
                    cancelButtonText: "Tidak, Batalkan!",
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: "Berhasil Dihapus!",
                            text: `Kategori ${kategoriName} telah dihapus.`,
                            icon: "success",
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            formToSubmit.submit();
                        });
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        swalWithBootstrapButtons.fire({
                            title: "Dibatalkan",
                            text: "Kategori Anda aman :)",
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
