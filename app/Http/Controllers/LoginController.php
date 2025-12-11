<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->role === 'admin') {

                return redirect()->route('dashboard', ['userId' => Auth::user()->userId]);
            }
            return redirect()->route('product.index', ['userId' => Auth::user()->userId]);
        }
        return view('login.akun');
    }

    public function login_proses(Request $request)
    {
        $request->validate([
            'identifikasi' => 'required',
            'password' => 'required',
        ]);

        $fieldType = filter_var($request->identifikasi, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';

        $credentials = [
            $fieldType => $request->input('identifikasi'),
            'password' => $request->password
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();
            if ($user->role === 'admin') {
                return redirect()->route('dashboard', ['userId' => $user->userId])
                    ->with('success', 'Login Berhasil, Selamat datang ' . $user->name . ' sebagai ' . $user->role . '!');

            }

            return redirect()->route('product.index', ['userId' => $user->userId])
                ->with('success', 'Login Berhasil, Selamat datang ' . $user->name . ' sebagai ' . $user->role . '!');
        }

        return back()->withErrors([
            'identifikasi' => 'Email/Nama Pengguna atau Kata Sandi salah.',
        ])->onlyInput('identifikasi');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil logout.');
    }

    public function dashboard(string $userId, Request $request)
    {
        $user = Auth::user();

        if (!$user || $user->userId !== $userId) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')->withErrors('Akses tidak sah atau sesi Anda telah berakhir. Silakan login kembali.');
        }

        $currentUserId = $user->userId;
        $userName = $user->name;
        $idAutoIncrement = $user->id;

        return view('dashboard', compact('currentUserId', 'userName', 'idAutoIncrement'));
    }

    public function create()
    {
    }
    public function store(Request $request)
    {
    }
    public function show(string $id)
    {
    }
    public function edit(string $id)
    {
    }
    public function update(Request $request, string $id)
    {
    }
    public function destroy(string $id)
    {
    }

    public function admin()
    {
        return view('login.admin');
    }

    public function storeAdmin(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6'
        ]);
        $lastAdmin = User::where('role', 'admin')
            ->orderBy('id', 'desc')
            ->first();
        $nextNumber = $lastAdmin
            ? ((int) str_replace('ADN-', '', $lastAdmin->userId)) + 1
            : 1;

        $userId = 'ADN-' . $nextNumber;

        $admin = User::create([
            'userId' => $userId,
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'admin'
        ]);

        Auth::login($admin);

        return redirect()->route('dashboard', ['userId' => $admin->userId])
            ->with('success', 'Admin berhasil dibuat dan Anda telah berhasil login!');
    }

}
