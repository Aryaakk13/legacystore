@extends('layouts.admin')

@section('title', 'Detail Pengguna: ' . $user->name)

@section('header', 'Detail Pengguna')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <!-- Profile Info -->
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center">
                        <img class="profile-user-img img-fluid img-circle"
                             src="{{ $user->avatar_url ?? 'https://crafatar.com/avatars/steve?size=128' }}"
                             alt="User profile picture">
                    </div>
                    <h3 class="profile-username text-center">{{ $user->name }}</h3>
                    <p class="text-muted text-center">
                        @if($user->role === 'admin')
                            <span class="badge bg-danger">Administrator</span>
                        @else
                            <span class="badge bg-info">Player</span>
                        @endif
                    </p>

                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>Email</b> <a class="float-right">{{ $user->email }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Registered</b> <a class="float-right">{{ $user->created_at->format('d M Y') }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Last Login</b> <a class="float-right">{{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Last IP</b> <a class="float-right">{{ $user->last_login_ip ?? 'N/A' }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Discord</b> <a class="float-right">{{ $user->discord_id ?? 'Not linked' }}</a>
                        </li>
                    </ul>

                    @if($user->isBanned())
                        <div class="alert alert-danger">
                            <h5><i class="icon fas fa-ban"></i> Banned!</h5>
                            <p>{{ $user->ban_reason ?? 'No reason provided' }}</p>
                            <small>Banned at: {{ $user->banned_at ? $user->banned_at->format('d M Y H:i') : 'N/A' }}</small>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Minecraft Accounts -->
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Minecraft Accounts</h3>
                </div>
                <div class="card-body">
                    @forelse($user->mcAccounts as $account)
                    <div class="d-flex align-items-center mb-3">
                        <img src="{{ $account->skin_url }}" alt="{{ $account->username }}" class="img-circle img-size-32 mr-2">
                        <div>
                            <strong>{{ $account->username }}</strong>
                            @if($account->is_primary)
                                <span class="badge bg-success">Primary</span>
                            @endif
                            @if($account->is_verified)
                                <span class="badge bg-info">Verified</span>
                            @else
                                <span class="badge bg-warning">Unverified</span>
                            @endif
                            <br>
                            <small class="text-muted">UUID: {{ $account->uuid ?? 'N/A' }}</small>
                        </div>
                    </div>
                    @empty
                    <p class="text-muted">No Minecraft accounts linked</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <!-- Stats Cards -->
            <div class="row">
                <div class="col-lg-4 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $user->purchases->count() }}</h3>
                            <p>Total Purchases</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>Rp {{ number_format($totalSpent, 0, ',', '.') }}</h3>
                            <p>Total Spent</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $user->coins }}</h3>
                            <p>Coins</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-coins"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Purchase History -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Purchase History</h3>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>Reference</th>
                                <th>Items</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($user->purchases as $purchase)
                            <tr>
                                <td>{{ $purchase->payment_reference }}</td>
                                <td>
                                    @foreach(json_decode($purchase->items, true) as $item)
                                        <span class="badge bg-info">{{ $item['product_name'] }} x{{ $item['quantity'] }}</span>
                                    @endforeach
                                </td>
                                <td>Rp {{ number_format($purchase->total_amount, 0, ',', '.') }}</td>
                                <td><span class="badge bg-secondary">{{ ucfirst($purchase->payment_method) }}</span></td>
                                <td>{!! $purchase->status_badge !!}</td>
                                <td>{{ $purchase->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">No purchases yet</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

