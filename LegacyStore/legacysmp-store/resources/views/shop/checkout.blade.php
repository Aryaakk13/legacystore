@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="container">
    <h2 class="fw-bold mb-4"><i class="bi bi-cart-check-fill me-2"></i>Checkout</h2>

    <div class="row g-4">
        <!-- Cart Items -->
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Your Order</h5>
                    <span class="badge bg-warning text-dark">{{ count($items) }} items</span>
                </div>
                <div class="card-body">
                    @if (count($items) > 0)
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>Product</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Price</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $item['product']->image_url ?? '/images/default-product.png' }}"
                                                 alt="" style="width: 40px; height: 40px; object-fit: contain;"
                                                 class="me-2 bg-dark rounded p-1">
                                            <div>
                                                <span class="fw-bold">{{ $item['product']->name }}</span>
                                                <br>
                                                <small class="text-muted">{{ ucfirst($item['product']->category) }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="input-group input-group-sm justify-content-center">
                                            <button class="btn btn-outline-secondary qty-minus" data-id="{{ $item['product']->id }}">-</button>
                                            <input type="number" class="form-control form-control-sm text-center qty-input"
                                                   style="width: 50px;" value="{{ $item['quantity'] }}" min="1" max="99"
                                                   data-id="{{ $item['product']->id }}">
                                            <button class="btn btn-outline-secondary qty-plus" data-id="{{ $item['product']->id }}">+</button>
                                        </div>
                                    </td>
                                    <td class="text-end">Rp {{ number_format($item['product']->price, 0, ',', '.') }}</td>
                                    <td class="text-end fw-bold">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="table-dark">
                                    <td colspan="3" class="text-end fw-bold">Total:</td>
                                    <td class="text-end fw-bold fs-5 text-warning">Rp {{ number_format($total, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="bi bi-cart-x display-1 text-muted"></i>
                        <p class="mt-3">Your cart is empty</p>
                        <a href="{{ route('shop.index') }}" class="btn btn-warning">Browse Products</a>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Payment Summary -->
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="bi bi-credit-card me-2"></i>Payment Details</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('shop.payment') }}" method="POST" id="paymentForm">
                        @csrf

                        <!-- Minecraft Account -->
                        <div class="mb-3">
                            <label class="form-label">Minecraft Account</label>
                            <select name="mc_account_id" class="form-select" required>
                                <option value="">Select your MC account</option>
                                @foreach ($mcAccounts as $account)
                                <option value="{{ $account->id }}">
                                    {{ $account->username }} {{ $account->is_primary ? '(Primary)' : '' }}
                                </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Items will be sent to this account</small>
                        </div>

                        <!-- Order Summary -->
                        <div class="bg-dark p-3 rounded-3 mb-3">
                            <h6 class="text-white mb-3">Order Summary</h6>
                            @foreach ($items as $item)
                            <div class="d-flex justify-content-between small mb-2">
                                <span class="text-muted">{{ $item['product']->name }} x{{ $item['quantity'] }}</span>
                                <span class="text-white">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                            </div>
                            @endforeach
                            <hr class="border-secondary">
                            <div class="d-flex justify-content-between">
                                <span class="fw-bold text-white">Total</span>
                                <span class="fw-bold text-warning fs-5">Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <!-- Payment Method -->
                        <div class="mb-3">
                            <label class="form-label">Payment Method</label>
                            <div class="d-grid gap-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method"
                                           id="midtrans" value="midtrans" checked>
                                    <label class="form-check-label d-flex align-items-center" for="midtrans">
                                        <i class="bi bi-credit-card-fill me-2 text-primary"></i>
                                        Midtrans (All Banks & E-Wallet)
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method"
                                           id="cashfree" value="cashfree">
                                    <label class="form-check-label d-flex align-items-center" for="cashfree">
                                        <i class="bi bi-coin me-2 text-success"></i>
                                        Cashfree
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method"
                                           id="manual" value="manual">
                                    <label class="form-check-label d-flex align-items-center" for="manual">
                                        <i class="bi bi-cash me-2 text-warning"></i>
                                        Manual Transfer
                                    </label>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-warning w-100 fw-bold py-2">
                            <i class="bi bi-check-lg me-2"></i> Pay Now - Rp {{ number_format($total, 0, ',', '.') }}
                        </button>
                    </form>
                </div>
            </div>

            <!-- Security Badge -->
            <div class="card mt-3 border-success">
                <div class="card-body text-center">
                    <i class="bi bi-shield-lock-fill text-success fs-3"></i>
                    <p class="small text-muted mt-2 mb-0">Secure payment. Your information is protected.</p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Quantity handlers
document.querySelectorAll('.qty-minus').forEach(btn => {
    btn.addEventListener('click', function() {
        const input = this.parentElement.querySelector('.qty-input');
        if (parseInt(input.value) > 1) {
            input.value = parseInt(input.value) - 1;
            updateCart(input.dataset.id, input.value);
        }
    });
});

document.querySelectorAll('.qty-plus').forEach(btn => {
    btn.addEventListener('click', function() {
        const input = this.parentElement.querySelector('.qty-input');
        if (parseInt(input.value) < 99) {
            input.value = parseInt(input.value) + 1;
            updateCart(input.dataset.id, input.value);
        }
    });
});

function updateCart(productId, quantity) {
    fetch(`/shop/cart/update/${productId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ quantity: quantity })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}
</script>
@endpush
@endsection

