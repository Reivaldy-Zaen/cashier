<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class KategoriController extends Controller
{
    public function index(Request $request, string $userId)
    {
        if (Auth::user()->userId !== $userId) {
            return redirect()->route('dashboard', ['userId' => Auth::user()->userId])->withErrors('Akses tidak sah untuk melihat kategori pengguna lain.');
        }

        $search = $request->query('search');

        $query = Kategori::query();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('kategori', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        $kategoris = $query->orderBy('id', 'desc')->get();

        return view('kategori.kategori')->with([
            'kategori' => $kategoris,
            'search' => $search,
            'currentUserId' => $userId,
        ]);
    }

    public function create(string $userId)
    {
        if (Auth::user()->userId !== $userId) {
            return redirect()->route('dashboard', ['userId' => Auth::user()->userId])->withErrors('Akses tidak sah.');
        }
        return view('kategori.create')->with('currentUserId', $userId);
    }

    public function store(Request $request, string $userId)
    {
        if (Auth::user()->userId !== $userId) {
            return redirect()->route('dashboard', ['userId' => Auth::user()->userId])->withErrors('Akses tidak sah.');
        }

        $request->validate([
            'kategori' => ['required', 'string', 'max:255', Rule::unique('kategoris')->where(fn ($query) => $query->where('userId', Auth::user()->userId))],
            'description' => 'required|string|max:255',
        ], [
            'kategori.required' => 'Kategori wajib diisi',
            'kategori.unique' => 'Nama kategori ini sudah ada untuk akun Anda.',
            'description.required' => 'Description wajib diisi',
        ]);

        $kategoriData = [
            'userId' => Auth::user()->userId,
            'kategori' => strtolower($request->input('kategori')),
            'description' => strtolower($request->input('description')),
        ];

        Kategori::create($kategoriData);

        return redirect()->route('kategori.index', ['userId' => Auth::user()->userId])->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function show(string $userId, Kategori $kategori)
    {
        if (Auth::user()->userId !== $userId) {
            return redirect()->route('dashboard', ['userId' => Auth::user()->userId])->withErrors('Akses tidak sah.');
        }
        return view('kategori.detail')->with('kategori', $kategori);
    }

    public function edit(string $userId, Kategori $kategori)
    {
        if (Auth::user()->userId !== $userId) {
            return redirect()->route('dashboard', ['userId' => Auth::user()->userId])->withErrors('Akses tidak sah.');
        }
        return view('kategori.edit')->with([
            'kategori' => $kategori , 
            'currentUserId' => $userId,
        ]);
    }

    public function update(Request $request, string $userId, Kategori $kategori)
    {
        if (Auth::user()->userId !== $userId) {
            return redirect()->route('dashboard', ['userId' => Auth::user()->userId])->withErrors('Akses tidak sah.');
        }

        $request->validate([
            'kategori' => ['required', 'string', 'max:255', Rule::unique('kategoris')->ignore($kategori->id, 'id')->where(fn ($query) => $query->where('userId', Auth::user()->userId))],
            'description' => 'required|string|max:255',
        ], [
            'kategori.required' => 'Kategori wajib diisi',
            'kategori.unique' => 'Nama kategori ini sudah ada untuk akun Anda.',
            'description.required' => 'Description wajib diisi',
        ]);

        $kategoriData = [
            'kategori' => strtolower($request->input('kategori')),
            'description' => strtolower($request->input('description')),
        ];

        $kategori->update($kategoriData);

        return redirect()->route('kategori.index', ['userId' => Auth::user()->userId])->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroy(string $userId, Kategori $kategori)
    {
        if (Auth::user()->userId !== $userId) {
            return redirect()->route('dashboard', ['userId' => Auth::user()->userId])->withErrors('Akses tidak sah.');
        }
        $kategori->delete();
        return redirect()->route('kategori.index', ['userId' => Auth::user()->userId])->with('success', 'Kategori berhasil dihapus!');
    }
}