@extends('layouts.app')

@section('title', 'Edit Akun Karyawan')
@section('nav')
<div class="container">
    <h3 class="mb-3">Edit Karyawan</h3>

    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Nama</label>
            <input type="text" name="name" class="form-control" 
                   value="{{ $user->name }}" required>
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control"
                   value="{{ $user->email }}" required>
        </div>

        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control"
                   placeholder="Biarkan kosong jika tidak diganti">
        </div>

        <button class="btn btn-success">Update</button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
