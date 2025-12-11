<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Kategori;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Models\User;

class TransactionController extends Controller
{
    public function index(Request $request, $userId = null)
    {
        if (Auth::user()->userId !== $userId) {
            return redirect()->route('dashboard', ['userId' => Auth::user()->userId])
                ->withErrors('Akses tidak sah untuk melihat produk pengguna lain.');
        }

        $search = $request->query('search');
        $kategoriFilter = $request->query('kategori_filter');

        $query = Product::query();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                  ->orWhere('harga', 'like', '%' . $search . '%')
                  ->orWhere('stock', 'like', '%' . $search . '%');
            });
        }

        if ($kategoriFilter) {
            $query->where('kategori', $kategoriFilter);
        }

        $products = $query->orderBy('id', 'desc')->get();
        $kategoris = Kategori::pluck('kategori')->toArray();

        return view('transaction.index')->with([
            'product' => $products,
            'kategori' => $kategoris,
            'search' => $search,
            'kategoriFilter' => $kategoriFilter,
            'currentUserId' => $userId,
        ]);
    }

    public function checkout(Request $request, $userId)
    {
        try {
            DB::beginTransaction();

        
if (empty($userId)) {
    throw new \Exception('ID pengguna tidak boleh kosong');
}

            $validated = $request->validate([
                'items' => 'required|array',
                'items.*.id' => 'required',
                'items.*.quantity' => 'required|integer|min:1',
                'items.*.purchase_type' => 'nullable|string|in:batangan,bungkus',
                'payment_method' => 'required|string',
                'payment_amount' => 'required|numeric|min:0',
                'total_amount' => 'required|numeric|min:0',
                'change_amount' => 'required|numeric|min:0'
            ]);

            // Debug request data
            \Log::info('Checkout request data:', $validated);

            $invoice_number = 'INV-' . date('Ymd') . '-' . str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);

            $transaction = Transaction::create([
                'invoice_number' => $invoice_number,
                'user_id' => $userId,
                'total_amount' => $validated['total_amount'],
                'payment_amount' => $validated['payment_amount'],
                'change_amount' => $validated['change_amount'],
                'payment_method' => $validated['payment_method']
            ]);

            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['id']);
                
                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Stok tidak mencukupi untuk produk {$product->nama}");
                }

                $price = $product->harga;
                $product_name = $product->nama;

                if ($product->kategori == 'rokok') {

                    \Log::info('Processing cigarette item:', [
                        'product' => $product->nama,
                        'purchase_type' => $item['purchase_type'] ?? 'tidak ada',
                        'harga_satuan' => $product->harga_satuan,
                        'harga_bungkus' => $product->harga_bungkus
                    ]);

                    if ($item['purchase_type'] === 'batangan') {
                        if (!$product->harga_satuan) {
                            throw new \Exception("Harga satuan tidak ditemukan untuk rokok {$product->nama}");
                        }
                        $price = $product->harga_satuan;
                        $product_name = $product->nama . ' (Batangan)';
                    } else {
                        if (!$product->harga_bungkus) {
                            throw new \Exception("Harga bungkus tidak ditemukan untuk rokok {$product->nama}");
                        }
                        $price = $product->harga_bungkus;
                        $product_name = $product->nama . ' (Bungkus)';
                    }
                }

                // Create transaction item
                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['id'],
                    'product_name' => $product_name,
                    'quantity' => $item['quantity'],
                    'price' => $price,
                    'subtotal' => $price * $item['quantity']
                ]);

                // Update stock
                $product->update([
                    'stock' => $product->stock - $item['quantity']
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil',
                'invoice_url' => route('transaction.nota', $transaction->id)
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal: ' . $e->getMessage(),
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Checkout Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function nota($id)
    {
        try {
            $transaction = Transaction::with(['items' => function($query) {
                $query->join('products', 'transaction_items.product_id', '=', 'products.id')
                      ->select(
                          'transaction_items.*',
                          'products.kategori',
                          'products.harga_satuan',
                          'products.harga_bungkus'
                      );
            }])->findOrFail($id);

            \Log::info('Nota data:', [
                'transaction_id' => $id,
                'items' => $transaction->items->toArray()
            ]);

            return view('transaction.nota', [
                'transaction' => $transaction,
                'items' => $transaction->items,
                'date' => $transaction->created_at->format('d/m/Y H:i:s'),
                'invoice_number' => $transaction->invoice_number,
                

            ]);
        } catch (\Exception $e) {
            \Log::error('Nota error: ' . $e->getMessage());
            return redirect()
                ->route('transaction.index', ['userId' => Auth::user()->userId])
                ->with('error', 'Nota tidak ditemukan: ' . $e->getMessage());
        }
    }

public function history(Request $request, $userId)
{
    $user = User::where('userId', $userId)->firstOrFail();

    $query = Transaction::with(['items', 'user'])
                ->where('user_id', $userId);

     if ($request->filled('date_filter')) {
        $query->whereDate('created_at', $request->date_filter);
    }


    if ($request->filled('payment_method_filter')) {
        $query->where('payment_method', $request->payment_method_filter);
    }

    $transactions = $query->orderBy('created_at', 'desc')->paginate(10);

    return view('transaction.history', [
        'transactions' => $transactions,
        'user' => $user,
        'currentUserId' => $userId
    ]);
}


}