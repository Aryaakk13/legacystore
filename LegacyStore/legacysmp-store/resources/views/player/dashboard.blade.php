@extends('layouts.app')

@section('title', 'Player Dashboard')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-dark border-warning">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <img src="https://crafatar.com/avatars/{{ Auth::user()->primaryMcAccount->username ?? 'steve' }}?size=64&overlay"
                             alt="MC Avatar" class="rounded-circle border border-2 border-warning me-3"
                             style="width: 64px; height: 64px;">
                        <div>
                            <h4 class="text-white mb-0">{{ Auth::user()->name }}</h4>
                            <small class="text-muted">
                                <i class="bi bi-puzzle me-1"></i>
                                {{ Auth::user()->primaryMcAccount->username ?? 'No MC account linked' }}
                            </small>
                        </div>
                    </div>
                    <div class="text-end">
                        <div class="text-warning fw-bold fs-4">
                            <i class="bi bi-coin me-1"></i>
                            {{ number_format(Auth::user()->coins, 0, ',', '.') }} Coins
                        </div>
                        <small class="text-muted">LegacySMP Balance</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Quick Stats -->
        <div class="col-md-3">
            <div class="card text-center border-0 shadow-sm h-100">
                <div class="card-body">
                    <i class="bi bi-cart-check-fill fs-1 text-primary"></i>
                    <h3 class="fw-bold mt-2">{{ Auth::user()->purchases()->count() }}</h3>
                    <p class="text-muted mb-0">Total Purchases</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center border-0 shadow-sm h-100">
                <div class="card-body">
                    <i class="bi bi-coin fs-1 text-warning"></i>
                    <h3 class="fw-bold mt-2">Rp {{ number_format(Auth::user()->totalSpent(), 0, ',', '.') }}</h3>
                    <p class="text-muted mb-0">Total Spent</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center border-0 shadow-sm h-100">
                <div class="card-body">
                    <i class="bi bi-puzzle-fill fs-1 text-success"></i>
                    <h3 class="fw-bold mt-2">{{ Auth::user()->mcAccounts()->count() }}</h3>
                    <p class="text-muted mb-0">MC Accounts</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center border-0 shadow-sm h-100">
                <div class="card-body">
                    <i class="bi bi-clock-history fs-1 text-info"></i>
                    <h3 class="fw-bold mt-2">{{ Auth::user()->purchases()->pending()->count() }}</h3>
                    <p class="text-muted mb-0">Pending Orders</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Purchases -->
    <div class="card mt-4 shadow-sm">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Recent Purchases</h5>
            <a href="{{ route('shop.index') }}" class="btn btn-warning btn-sm">Shop More</a>
        </div>
        <div class="card-body">
            @php
                $recentPurchases = Auth::user()->purchases()->latest()->take(10)->get();
            @endphp

            @if ($recentPurchases->count() > 0)
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Items</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($recentPurchases as $purchase)
                        <tr>
                            <td><small class="text-muted">{{ $purchase->payment_reference }}</small></td>
                            <td>
                                @php $items = $purchase->items; @endphp
                                @foreach ($items as $item)
                                <div class="small">{{ $item['product_name'] ?? 'Unknown' }} x{{ $item['quantity'] ?? 1 }}</div>
                                @endforeach
                            </td>
                            <td class="fw-bold">Rp {{ number_format($purchase->total_amount, 0, ',', '.') }}</td>
                            <td><span class="badge bg-info">{{ ucfirst($purchase->payment_method) }}</span></td>
                            <td>{!! $purchase->status_badge !!}</td>
                            <td><small class="text-muted">{{ $purchase->created_at->diffForHumans() }}</small></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-5">
                <i class="bi bi-inbox display-1 text-muted"></i>
                <p class="mt-3">No purchases yet</p>
                <a href="{{ route('shop.index') }}" class="btn btn-warning">Start Shopping</a>
            </div>
            @endif
        </div>
    </div>

    <!-- MC Accounts -->
    <div class="card mt-4 shadow-sm">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-puzzle-fill me-2"></i>Linked Minecraft Accounts</h5>
            <button class="btn btn-outline-light btn-sm" data-bs-toggle="modal" data-bs-target="#addAccountModal">
                <i class="bi bi-plus-lg"></i> Add Account
            </button>
        </div>
        <div class="card-body">
            <div class="row g-3">
                @foreach (Auth::user()->mcAccounts as $account)
                <div class="col-md-4">
                    <div class="card border-{{ $account->is_primary ? 'warning' : 'secondary' }}">
                        <div class="card-body text-center">
                            <img src="{{ $account->skin_url }}" alt="{{ $account->username }}"
                                 class="rounded mb-2" style="width: 64px; height: 64px;">
                            <h6 class="mb-0">{{ $account->username }}</h6>
                            <div>
                                @if ($account->is_verified)
                                <span class="badge bg-success"><i class="bi bi-check-circle"></i> Verified</span>
                                @else
                                <span class="badge bg-warning"><i class="bi bi-clock"></i> Pending</span>
                                @endif
                                @if ($account->is_primary)
                                <span class="badge bg-primary">Primary</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Add Account Modal -->
<div class="modal fade" id="addAccountModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">Link Minecraft Account</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('player.dashboard') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Minecraft Username</label>
                        <input type="text" class="form-control" placeholder="Enter your IGN" maxlength="16" required>
                    </div>
                    <p class="small text-muted">Make sure you are logged into the server to verify ownership.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Link Account</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

