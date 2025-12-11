@extends('layouts.app')

@section('title', 'Kelola Akun Karyawan')

@section('nav')
<div class="container">
    <h1>Daftar Akun Karyawan</h1>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary mb-3">
        + Tambah Akun Karyawan Baru
    </a>

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

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>ID Karyawan</th> <th>Nama</th>
                <th>Email</th>
                <th>Tanggal Dibuat</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($employees as $employee)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    
                    <td><span class="badge bg-secondary">{{ $employee->userId }}</span></td> 
                    
                    <td>{{ $employee->name }}</td>
                    <td>{{ $employee->email }}</td>
                    <td>{{ $employee->created_at->format('d M Y') }}</td>
                    <td class="text-center">

                        <a href="{{ route('admin.users.edit', $employee->id) }}" 
                           class="btn btn-sm btn-warning">
                           <i class="bi bi-pencil-square"></i> Edit
                        </a>

                        <form action="{{ route('admin.users.destroy', $employee->id) }}"
                              method="POST"
                              class="d-inline"
                              onsubmit="return confirm('Yakin ingin menghapus akun {{ $employee->userId }}?')">
                            @csrf
                            @method('DELETE')

                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class="bi bi-trash"></i> Hapus
                            </button>
                        </form>

                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Belum ada akun karyawan di bawah manajemen Anda.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection