<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\McAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Constructor - menerapkan middleware admin.
     */
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Dashboard admin.
     */
    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_products' => Product::count(),
            'total_purchases' => Purchase::count(),
            'total_revenue' => Purchase::where('status', 'completed')->sum('total_amount'),
            'pending_orders' => Purchase::where('status', 'pending')->count(),
            'recent_purchases' => Purchase::with('user')->latest()->take(10)->get(),
            'monthly_revenue' => Purchase::where('status', 'completed')
                ->whereMonth('created_at', now()->month)
                ->sum('total_amount'),
            'new_users_this_month' => User::whereMonth('created_at', now()->month)->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    /**
     * Statistik penjualan.
     */
    public function statistics()
    {
        $dailySales = Purchase::where('status', 'completed')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_amount) as total'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->take(30)
            ->get();

        $topProducts = DB::table('purchases')
            ->where('status', 'completed')
            ->select(DB::raw('JSON_EXTRACT(items, "$[*].product_name") as names'))
            ->get()
            ->pluck('names')
            ->flatten()
            ->countBy()
            ->sortDesc()
            ->take(10);

        $stats = [
            'total_revenue' => Purchase::where('status', 'completed')->sum('total_amount'),
            'total_orders' => Purchase::count(),
            'completed_orders' => Purchase::where('status', 'completed')->count(),
            'average_order_value' => Purchase::where('status', 'completed')->avg('total_amount') ?? 0,
            'revenue_by_method' => Purchase::where('status', 'completed')
                ->select('payment_method', DB::raw('SUM(total_amount) as total'))
                ->groupBy('payment_method')
                ->get(),
        ];

        return view('admin.statistics', compact('dailySales', 'topProducts', 'stats'));
    }

    /**
     * Manajemen pengguna.
     */
    public function users()
    {
        $users = User::withCount('purchases', 'mcAccounts')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.users', compact('users'));
    }

    /**
     * Detail pengguna.
     */
    public function userDetail($id)
    {
        $user = User::with(['mcAccounts', 'purchases' => function ($query) {
            $query->latest()->take(20);
        }])->findOrFail($id);

        $totalSpent = Purchase::where('user_id', $id)
            ->where('status', 'completed')
            ->sum('total_amount');

        return view('admin.user_detail', compact('user', 'totalSpent'));
    }

    /**
     * Menampilkan form tambah produk.
     */
    public function createProduct()
    {
        return view('admin.products.create');
    }

    /**
     * Menyimpan produk baru.
     */
    public function storeProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string|in:rank,item,crate,key,other',
            'stock' => 'nullable|integer|min:-1',
            'image_url' => 'nullable|url',
        ]);

        Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'category' => $request->category,
            'stock' => $request->category === 'rank' ? -1 : ($request->stock ?? -1),
            'image_url' => $request->image_url ?? '/images/default-product.png',
            'is_active' => true,
            'features' => $request->features ?? [],
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Produk berhasil ditambahkan!');
    }

    /**
     * Menampilkan form edit produk.
     */
    public function editProduct($id)
    {
        $product = Product::findOrFail($id);
        return view('admin.products.edit', compact('product'));
    }

    /**
     * Mengupdate produk.
     */
    public function updateProduct(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string|in:rank,item,crate,key,other',
            'stock' => 'nullable|integer|min:-1',
            'is_active' => 'boolean',
            'image_url' => 'nullable|url',
        ]);

        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'category' => $request->category,
            'stock' => $request->stock ?? -1,
            'is_active' => $request->has('is_active'),
            'image_url' => $request->image_url,
            'features' => $request->features ?? [],
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Produk berhasil diupdate!');
    }

    /**
     * Menghapus produk.
     */
    public function destroyProduct($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('admin.dashboard')->with('success', 'Produk berhasil dihapus.');
    }

    /**
     * Daftar pesanan.
     */
    public function orders()
    {
        $orders = Purchase::with('user')
            ->latest()
            ->paginate(20);

        return view('admin.orders', compact('orders'));
    }

    /**
     * Update status pesanan.
     */
    public function updateOrderStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,failed,cancelled',
        ]);

        $purchase = Purchase::findOrFail($id);
        $purchase->update(['status' => $request->status]);

        // TODO: Kirim command ke Minecraft server jika status completed

        return back()->with('success', "Status pesanan #{$purchase->payment_reference} diubah menjadi {$request->status}.");
    }
}

