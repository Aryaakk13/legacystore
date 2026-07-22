@extends('layouts.app')

@section('title', 'Shop')

@section('content')
<div class="container">
    <!-- Hero Section -->
    <div class="hero-section text-center py-5 mb-4">
        <h1 class="display-4 fw-bold text-white">
            <i class="bi bi-shop me-2"></i> LegacySMP Store
        </h1>
        <p class="lead text-light">Purchase ranks, items, crates, and more to enhance your gameplay experience!</p>
        <div class="d-flex justify-content-center gap-3 mt-4">
            <a href="#products-section" class="btn btn-warning btn-lg fw-bold">
                <i class="bi bi-bag-fill me-2"></i> Browse Products
            </a>
            <a href="#ranks" class="btn btn-outline-light btn-lg">
                <i class="bi bi-star-fill me-2"></i> View Ranks
            </a>
        </div>
    </div>

    <!-- Server Status -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-dark border-secondary">
                <div class="card-body">
                    <div class="row align-items-center text-center text-md-start">
                        <div class="col-md-4 mb-2 mb-md-0">
                            <div class="d-flex align-items-center justify-content-center justify-content-md-start">
                                <span class="status-indicator online me-2"></span>
                                <span class="text-white fw-bold">Server Online</span>
                            </div>
                        </div>
                        <div class="col-md-4 mb-2 mb-md-0">
                            <span class="text-muted">IP:</span>
                            <span class="text-info fw-bold">play.legacysmp.com</span>
                        </div>
                        <div class="col-md-4">
                            <span class="text-muted">Players Online:</span>
                            <span class="badge bg-success" id="online-players">0</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Section -->
    <div id="products-section">
        @php
            $categories = [
                'rank' => ['icon' => 'bi-star-fill', 'color' => 'warning', 'title' => 'Ranks'],
                'item' => ['icon' => 'bi-box-fill', 'color' => 'primary', 'title' => 'Items'],
                'crate' => ['icon' => 'bi-gift-fill', 'color' => 'danger', 'title' => 'Crates'],
                'key' => ['icon' => 'bi-key-fill', 'color' => 'info', 'title' => 'Keys'],
                'other' => ['icon' => 'bi-grid-fill', 'color' => 'secondary', 'title' => 'Other'],
            ];
        @endphp

        @foreach ($categories as $category => $meta)
            @if (isset($products[$category]) && count($products[$category]) > 0)
            <div class="mb-5" id="{{ $category }}">
                <div class="d-flex align-items-center mb-4">
                    <div class="category-icon bg-{{ $meta['color'] }} bg-opacity-10 p-3 rounded-3 me-3">
                        <i class="bi {{ $meta['icon'] }} fs-3 text-{{ $meta['color'] }}"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0">{{ $meta['title'] }}</h3>
                        <small class="text-muted">{{ count($products[$category]) }} products available</small>
                    </div>
                </div>

                <div class="row g-4">
                    @foreach ($products[$category] as $product)
                    <div class="col-lg-4 col-md-6">
                        <div class="card product-card h-100 border-0 shadow-sm">
                            <div class="card-img-top bg-dark text-center py-4 position-relative">
                                @if ($product->isOnSale)
                                <span class="badge bg-danger position-absolute top-0 end-0 m-3">
                                    -{{ $product->discount_percent }}%
                                </span>
                                @endif
                                <img src="{{ $product->image_url ?? '/images/default-product.png' }}"
                                     alt="{{ $product->name }}"
                                     class="product-img"
                                     style="height: 100px; width: auto;"
                                     onerror="this.src='https://via.placeholder.com/100x100/2d2d2d/ffc107?text=Store'">
                            </div>
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="card-title fw-bold mb-0">{{ $product->name }}</h5>
                                    <span class="badge bg-{{ $meta['color'] }} bg-opacity-10 text-{{ $meta['color'] }}">
                                        {{ ucfirst($category) }}
                                    </span>
                                </div>
                                <p class="card-text text-muted small flex-grow-1">{{ Str::limit($product->description, 100) }}</p>

                                @if ($product->features && count($product->features) > 0)
                                <ul class="list-unstyled mb-3 small">
                                    @foreach (array_slice($product->features, 0, 4) as $feature)
                                    <li><i class="bi bi-check-circle-fill text-success me-1"></i> {{ $feature }}</li>
                                    @endforeach
                                    @if (count($product->features) > 4)
                                    <li><i class="bi bi-plus-circle text-muted me-1"></i> +{{ count($product->features) - 4 }} more</li>
                                    @endif
                                </ul>
                                @endif

                                <div class="d-flex justify-content-between align-items-center mt-auto pt-3 border-top">
                                    <div>
                                        @if ($product->isOnSale)
                                        <span class="text-muted text-decoration-line-through small">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                        <span class="fw-bold fs-5 text-warning">Rp {{ number_format($product->discountedPrice, 0, ',', '.') }}</span>
                                        @else
                                        <span class="fw-bold fs-5">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                        @endif
                                    </div>
                                    <button class="btn btn-warning btn-sm add-to-cart"
                                            data-product-id="{{ $product->id }}">
                                        <i class="bi bi-cart-plus"></i> Buy
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        @endforeach
    </div>
</div>

@push('scripts')
<script>
document.querySelectorAll('.add-to-cart').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        const productId = this.dataset.productId;
        const btn = this;

        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';

        fetch(`/shop/cart/add/${productId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const cartBadge = document.querySelector('.cart-count');
                cartBadge.textContent = data.cartCount;
                cartBadge.style.display = data.cartCount > 0 ? 'inline' : 'none';

                // Toast notification
                const toast = document.createElement('div');
                toast.className = 'position-fixed bottom-0 end-0 p-3';
                toast.style.zIndex = '9999';
                toast.innerHTML = `
                    <div class="toast show" role="alert">
                        <div class="toast-header bg-success text-white">
                            <i class="bi bi-check-circle me-2"></i>
                            <strong class="me-auto">Cart</strong>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
                        </div>
                        <div class="toast-body">${data.message}</div>
                    </div>
                `;
                document.body.appendChild(toast);
                setTimeout(() => toast.remove(), 3000);
            }
        })
        .catch(err => console.error(err))
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-cart-plus"></i> Buy';
        });
    });
});
</script>
@endpush
@endsection

