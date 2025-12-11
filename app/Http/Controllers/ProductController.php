<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Product;
use App\Models\Kategori;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index(Request $request, string $userId)
    {
        if (Auth::user()->userId !== $userId) {
            return redirect()->route('dashboard', ['userId' => Auth::user()->userId])->withErrors('Akses tidak sah untuk melihat produk pengguna lain.');
        }

        $search = $request->query('search');
        $kategoriFilter = $request->query('kategori_filter');

        $query = Product::query();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                  ->orWhere('kode_barang', 'like', '%' . $search . '%')
                  ->orWhere('harga', 'like', '%' . $search . '%')
                  ->orWhere('stock', 'like', '%' . $search . '%')
                  ->orWhere('kategori', 'like', '%' . $search . '%');
            });
        }

        if ($kategoriFilter) {
            $query->where('kategori', $kategoriFilter);
        }

        $products = $query->orderBy('id', 'desc')->get();

        $kategoris = Kategori::pluck('kategori')->toArray();

        return view('product.product')->with([
            'product' => $products,
            'kategori' => $kategoris,
            'search' => $search,
            'kategoriFilter' => $kategoriFilter,
            'currentUserId' => $userId,
        ]);
    }

    public function create(string $userId)
    {
        if (Auth::user()->userId !== $userId) {
            return redirect()->route('dashboard', ['userId' => Auth::user()->userId])->withErrors('Akses tidak sah.');
        }

        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $length = rand(4, 5);

        $generateUniqueKodeBarang = function() use ($characters, $length) {
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, strlen($characters) - 1)];
            }
            $randomNumber = rand(1, 9);
            return $randomString . '-' . $randomNumber;
        };

        $generated_kode_barang = $generateUniqueKodeBarang();

        while (Product::where('userId', Auth::user()->userId)->where('kode_barang', $generated_kode_barang)->exists()) {
            $generated_kode_barang = $generateUniqueKodeBarang();
        }

        $kategoris = Kategori::pluck('kategori')->toArray();

        return view('product.create', [
            'kode_barang' => $generated_kode_barang,
            'kategori' => $kategoris,
            'nama_lama' => old('nama'),
            'harga_lama' => old('harga'),
            'stock_lama' => old('stock'),
            'kategori_lama' => old('kategori'),
            'harga_bungkus_lama' => old('harga_bungkus'),
            'harga_satuan_lama' => old('harga_satuan'),
            'currentUserId' => $userId,
        ]);
    }

    public function store(Request $request, string $userId)
    {
        if (Auth::user()->userId !== $userId) {
            return redirect()->route('dashboard', ['userId' => Auth::user()->userId])->withErrors('Akses tidak sah.');
        }

        $request->merge([
            'harga' => $request->filled('harga') ? preg_replace('/[^0-9]/', '', $request->input('harga')) : null,
            'harga_bungkus' => $request->filled('harga_bungkus') ? preg_replace('/[^0-9]/', '', $request->input('harga_bungkus')) : null,
            'harga_satuan' => $request->filled('harga_satuan') ? preg_replace('/[^0-9]/', '', $request->input('harga_satuan')) : null,
        ]);

        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
            'kode_barang' => ['required', 'string', Rule::unique('products')->where(fn ($query) => $query->where('userId', Auth::user()->userId))],
            'kategori' => 'required|string|max:255|exists:kategoris,kategori',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'bungkus' => 'nullable|boolean',
            'satuan' => 'nullable|boolean',
            'harga' => [
                'nullable',
                'numeric',
                'min:0',
                'required_unless:kategori,rokok',
            ],
            'harga_bungkus' => 'nullable|numeric|min:0|required_if:bungkus,1,kategori,rokok',
            'harga_satuan' => [
                'nullable',
                'numeric',
                'min:0',
                'required_if:satuan,1,kategori,rokok',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->boolean('satuan') && !$request->boolean('bungkus') && $request->input('kategori') === 'rokok') {
                        $fail('Harga satuan hanya valid jika opsi jual per bungkus juga dipilih untuk kategori rokok.');
                    }
                },
            ]
        ], [
            'kode_barang.unique' => 'Kode barang ini sudah ada untuk akun Anda.',
            'kategori.exists' => 'Kategori yang dipilih tidak valid.',
            'harga.required_unless' => 'Harga wajib diisi untuk kategori ini.',
            'harga.numeric' => 'Harga harus berupa angka.',
            'harga_bungkus.required_if' => 'Harga per bungkus wajib diisi jika opsi jual per bungkus dipilih untuk rokok.',
            'harga_bungkus.numeric' => 'Harga per bungkus harus berupa angka.',
            'harga_satuan.required_if' => 'Harga per batang wajib diisi jika opsi jual per batang dipilih untuk rokok.',
            'harga_satuan.numeric' => 'Harga per batang harus berupa angka.',
        ]);

        $gambarPath = null;
        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('gambar_produk', 'public');
        }

        $productData = [
            'userId' => Auth::user()->userId,
            'nama' => $validatedData['nama'],
            'stock' => $validatedData['stock'],
            'kode_barang' => $validatedData['kode_barang'],
            'kategori' => $validatedData['kategori'],
            'gambar' => $gambarPath,
            'bungkus' => false,
            'satuan' => false,
            'harga' => null,
            'harga_bungkus' => null,
            'harga_satuan' => null,
        ];

        if ($validatedData['kategori'] === 'rokok') {
            $productData['bungkus'] = $request->boolean('bungkus');
            $productData['satuan'] = $request->boolean('satuan');

            if ($productData['bungkus']) {
                $productData['harga_bungkus'] = (int) $validatedData['harga_bungkus'];
                if ($productData['satuan']) {
                    $productData['harga_satuan'] = (int) $validatedData['harga_satuan'];
                }
            }
        } else {
            $productData['harga'] = (int) $validatedData['harga'];
        }

        if ($productData['kategori'] === 'rokok' && $productData['satuan'] && !$productData['bungkus']) {
            $productData['satuan'] = false;
            $productData['harga_satuan'] = null;
        }

        Product::create($productData);
        return redirect()->route('product.index', ['userId' => Auth::user()->userId])->with('success', 'Produk berhasil ditambahkan!');
    }

    public function show(string $userId, Product $product)
    {
        if (Auth::user()->userId !== $userId) {
            return redirect()->route('dashboard', ['userId' => Auth::user()->userId])->withErrors('Akses tidak sah.');
        }

        if ($product->userId !== Auth::user()->userId) {
            return redirect()->route('product.index', ['userId' => Auth::user()->userId])->withErrors('Anda tidak memiliki akses untuk melihat produk ini.');
        }

        return view('product.detail')->with([
            'product' => $product,
            'currentUserId'=> $userId,
        ]);
    }

    public function edit(string $userId, Product $product)
    {
        if (Auth::user()->userId !== $userId) {
            return redirect()->route('dashboard', ['userId' => Auth::user()->userId])->withErrors('Akses tidak sah.');
        }

        if ($product->userId !== Auth::user()->userId) {
            return redirect()->route('product.index', ['userId' => Auth::user()->userId])->withErrors('Anda tidak memiliki akses untuk mengedit produk ini.');
        }

        $kategoris = Kategori::pluck('kategori')->toArray();

        return view('product.edit')->with([
            'product' => $product,
            'kategori' => $kategoris,
            'currentUserId' => $userId,
        ]);
    }

    public function update(Request $request, string $userId, Product $product)
    {
        if (Auth::user()->userId !== $userId) {
            return redirect()->route('dashboard', ['userId' => Auth::user()->userId])->withErrors('Akses tidak sah.');
        }

        if ($product->userId !== Auth::user()->userId) {
            return redirect()->route('product.index', ['userId' => Auth::user()->userId])->withErrors('Anda tidak memiliki akses untuk mengedit produk ini.');
        }

        $request->merge([
            'harga' => $request->filled('harga') ? preg_replace('/[^0-9]/', '', $request->input('harga')) : null,
            'harga_bungkus' => $request->filled('harga_bungkus') ? preg_replace('/[^0-9]/', '', $request->input('harga_bungkus')) : null,
            'harga_satuan' => $request->filled('harga_satuan') ? preg_replace('/[^0-9]/', '', $request->input('harga_satuan')) : null,
        ]);

        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
            'kode_barang' => ['required', 'string', Rule::unique('products')->ignore($product->id)->where(fn ($query) => $query->where('userId', Auth::user()->userId))],
            'kategori' => 'required|string|max:255|exists:kategoris,kategori',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'bungkus' => 'nullable|boolean',
            'satuan' => 'nullable|boolean',
            'harga' => [
                'nullable',
                'numeric',
                'min:0',
                'required_unless:kategori,rokok',
            ],
            'harga_bungkus' => 'nullable|numeric|min:0|required_if:bungkus,1,kategori,rokok',
            'harga_satuan' => [
                'nullable',
                'numeric',
                'min:0',
                'required_if:satuan,1,kategori,rokok',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->boolean('satuan') && !$request->boolean('bungkus') && $request->input('kategori') === 'rokok') {
                        $fail('Harga satuan hanya valid jika opsi jual per bungkus juga dipilih untuk kategori rokok.');
                    }
                },
            ]
        ], [
            'kode_barang.unique' => 'Kode barang ini sudah ada untuk akun Anda.',
            'kategori.exists' => 'Kategori yang dipilih tidak valid.',
            'harga.required_unless' => 'Harga wajib diisi untuk kategori ini.',
            'harga.numeric' => 'Harga harus berupa angka.',
            'harga_bungkus.required_if' => 'Harga per bungkus wajib diisi jika opsi jual per bungkus dipilih untuk rokok.',
            'harga_bungkus.numeric' => 'Harga per bungkus harus berupa angka.',
            'harga_satuan.required_if' => 'Harga per batang wajib diisi jika opsi jual per batang dipilih untuk rokok.',
            'harga_satuan.numeric' => 'Harga per batang harus berupa angka.',
        ]);

        $updateData = [
            'nama' => $validatedData['nama'],
            'stock' => $validatedData['stock'],
            'kode_barang' => $validatedData['kode_barang'],
            'kategori' => $validatedData['kategori'],
            'bungkus' => false,
            'satuan' => false,
            'harga' => null,
            'harga_bungkus' => null,
            'harga_satuan' => null,
        ];

        if ($request->has('remove_gambar') && $request->input('remove_gambar') === 'true') {
            if ($product->gambar) {
                Storage::disk('public')->delete($product->gambar);
            }
            $updateData['gambar'] = null;
        } elseif ($request->hasFile('gambar')) {
            if ($product->gambar) {
                Storage::disk('public')->delete($product->gambar);
            }
            $updateData['gambar'] = $request->file('gambar')->store('gambar_produk', 'public');
        } else {
            $updateData['gambar'] = $product->gambar;
        }

        if ($validatedData['kategori'] === 'rokok') {
            $updateData['bungkus'] = $request->boolean('bungkus');
            $updateData['satuan'] = $request->boolean('satuan');

            if ($updateData['bungkus']) {
                $updateData['harga_bungkus'] = (int) $validatedData['harga_bungkus'];
                if ($updateData['satuan']) {
                    $updateData['harga_satuan'] = (int) $validatedData['harga_satuan'];
                }
            }
        } else {
            $updateData['harga'] = (int) $validatedData['harga'];
        }

        if ($updateData['kategori'] === 'rokok' && $updateData['satuan'] && !$updateData['bungkus']) {
            $updateData['satuan'] = false;
            $updateData['harga_satuan'] = null;
        }
        
        unset($validatedData['userId']); 

        $product->update($updateData);

        return redirect()->route('product.index', ['userId' => Auth::user()->userId])->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy(string $userId, Product $product)
    {
        if (Auth::user()->userId !== $userId) {
            return redirect()->route('dashboard', ['userId' => Auth::user()->userId])->withErrors('Akses tidak sah.');
        }

        if ($product->userId !== Auth::user()->userId) {
            return redirect()->route('product.index', ['userId' => Auth::user()->userId])->withErrors('Anda tidak memiliki akses untuk menghapus produk ini.');
        }

        if ($product->gambar) {
            Storage::disk('public')->delete($product->gambar);
        }
        $product->delete();
        return redirect()->route('product.index', ['userId' => Auth::user()->userId])->with('success', 'Produk berhasil dihapus!');
    }
}