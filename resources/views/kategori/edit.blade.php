@extends('layouts.app')

@section('title', 'Edit Kategori') 

@section('nav') 

<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0"><i class="bi bi-pencil-square me-2"></i>Edit Kategori</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('kategori.update' , ['userId'=>$currentUserId ,'kategori' => $kategori->id]) }}" method="POST">
                @csrf
                @method('PUT') 

                <div class="mb-3">
                    <label for="kategori" class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                    <input type="text" name="kategori" id="kategori" class="form-control @error('kategori') is-invalid @enderror" value="{{ old('kategori', $kategori->kategori) }}" placeholder="Masukkan nama kategori" required>
                    @error('kategori')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Deskripsi</label>
                    <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="3" paceholder="Masukkan deskripsi kategori (opsional)">{{ old('description', $kategori->description) }}</textarea> 
                    @error('description')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-arrow-repeat me-2"></i>Update Kategori
                    </button>
                    <a href="{{ route('kategori.index', ['userId' => $currentUserId]) }}" class="btn btn-secondary"> 
                        <i class="bi bi-x-circle me-2"></i>Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection