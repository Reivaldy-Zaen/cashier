<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $admin = Auth::user();

        $employees = User::where('role', 'karyawan')
            ->where('userId', $admin->userId)
            ->latest()
            ->get();

        return view('admin.users.index', compact('employees'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $admin = Auth::user();
        $sameUserId = $admin->userId;

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'userId' => $sameUserId,
            'password' => Hash::make($request->password),
            'role' => 'karyawan',
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Akun karyawan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);

        if ($user->userId !== Auth::user()->userId || $user->role !== 'karyawan') {
            return redirect()->route('admin.users.index')
                ->with('error', 'Tidak diizinkan mengedit user ini.');
        }

        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => "required|string|email|max:255|unique:users,email,$id",
            'password' => 'nullable|string|min:8',
        ]);

        $user = User::findOrFail($id);

        if ($user->userId !== Auth::user()->userId || $user->role !== 'karyawan') {
            return redirect()->route('admin.users.index')
                ->with('error', 'Tidak diizinkan mengupdate user ini.');
        }

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('admin.users.index')
            ->with('success', 'Akun karyawan berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        if ($user->userId !== Auth::user()->userId || $user->role !== 'karyawan') {
            return redirect()->route('admin.users.index')
                ->with('error', 'Anda tidak berhak menghapus user ini.');
        }

        $user->delete();
        
        return redirect()->route('admin.users.index')
            ->with('success', 'Akun karyawan berhasil dihapus.');
    }
}
