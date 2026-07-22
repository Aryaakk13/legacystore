<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\McAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ShopController extends Controller
{
    /**
     * Menampilkan halaman utama toko.
     */
    public function index()
    {
        $products = Product::where('is_active', true)
            ->orderBy('category')
            ->orderBy('price')
            ->get()
            ->groupBy('category');

        $cartCount = 0;
        if (Auth::check()) {
            $cartCount = session()->get('cart', []);
            $cartCount = count($cartCount);
        }

        return view('shop.index', compact('products', 'cartCount'));
    }

    /**
     * Menambahkan produk ke keranjang.
     */
    public function addToCart(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        if (!$product->is_active) {
            return response()->json(['error' => 'Produk tidak tersedia.'], 404);
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
                'image' => $product->image_url,
            ];
        }

        session()->put('cart', $cart);

        return response()->json([
            'success' => true,
            'message' => "{$product->name} berhasil ditambahkan ke keranjang!",
            'cartCount' => count($cart),
        ]);
    }

    /**
     * Menampilkan halaman checkout.
     */
    public function checkout()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login untuk melanjutkan pembelian.');
        }

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('shop.index')->with('error', 'Keranjang Anda kosong.');
        }

        $total = 0;
        $items = [];
        foreach ($cart as $id => $details) {
            $product = Product::find($id);
            if ($product) {
                $subtotal = $product->price * $details['quantity'];
                $total += $subtotal;
                $items[] = [
                    'product' => $product,
                    'quantity' => $details['quantity'],
                    'subtotal' => $subtotal,
                ];
            }
        }

        $mcAccounts = McAccount::where('user_id', Auth::id())->get();

        return view('shop.checkout', compact('items', 'total', 'mcAccounts'));
    }

    /**
     * Memproses pembayaran.
     */
    public function processPayment(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|in:midtrans,cashfree,manual',
            'mc_account_id' => 'required|exists:mc_accounts,id',
        ]);

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return back()->with('error', 'Keranjang Anda kosong.');
        }

        $mcAccount = McAccount::findOrFail($request->mc_account_id);

        if ($mcAccount->user_id !== Auth::id()) {
            return back()->with('error', 'Akun Minecraft tidak valid.');
        }

        DB::beginTransaction();
        try {
            $totalAmount = 0;
            $purchaseItems = [];

            foreach ($cart as $id => $details) {
                $product = Product::findOrFail($id);
                $subtotal = $product->price * $details['quantity'];
                $totalAmount += $subtotal;

                $purchaseItems[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'quantity' => $details['quantity'],
                    'price_per_unit' => $product->price,
                    'subtotal' => $subtotal,
                ];
            }

            $purchase = Purchase::create([
                'user_id' => Auth::id(),
                'mc_account_id' => $request->mc_account_id,
                'items' => json_encode($purchaseItems),
                'total_amount' => $totalAmount,
                'payment_method' => $request->payment_method,
                'status' => 'pending',
                'payment_reference' => 'INV-' . strtoupper(uniqid()),
            ]);

            session()->forget('cart');

            DB::commit();

            if ($request->payment_method === 'midtrans') {
                return $this->processMidtransPayment($purchase);
            } elseif ($request->payment_method === 'cashfree') {
                return $this->processCashfreePayment($purchase);
            }

            return redirect()->route('player.dashboard')
                ->with('success', "Pesanan #{$purchase->payment_reference} berhasil dibuat. Silakan lakukan pembayaran manual.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat memproses pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Proses pembayaran via Midtrans.
     */
    private function processMidtransPayment($purchase)
    {
        // TODO: Integrasi Midtrans API
        return redirect()->route('player.dashboard')
            ->with('info', 'Pembayaran Midtrans akan segera diintegrasikan.');
    }

    /**
     * Proses pembayaran via Cashfree.
     */
    private function processCashfreePayment($purchase)
    {
        // TODO: Integrasi Cashfree API
        return redirect()->route('player.dashboard')
            ->with('info', 'Pembayaran Cashfree akan segera diintegrasikan.');
    }

    /**
     * Menampilkan detail produk.
     */
    public function showProduct($id)
    {
        $product = Product::findOrFail($id);
        return view('shop.product-detail', compact('product'));
    }

    /**
     * Menghapus item dari keranjang.
     */
    public function removeFromCart($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return response()->json([
            'success' => true,
            'cartCount' => count($cart),
        ]);
    }

    /**
     * Mengupdate quantity item di keranjang.
     */
    public function updateCart(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:99',
        ]);

        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
        }

        return response()->json(['success' => true]);
    }
}

