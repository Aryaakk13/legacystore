@extends('layouts.app')

@section('title', 'Profile')

@section('content')
<div class="container">
    <h2 class="fw-bold mb-4"><i class="bi bi-person-gear me-2"></i>Profile Settings</h2>

    <div class="row g-4">
        <!-- Profile Info -->
        <div class="col-lg-4">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <img src="https://crafatar.com/avatars/{{ Auth::user()->primaryMcAccount->username ?? 'steve' }}?size=128&overlay"
                         alt="Avatar" class="rounded-circle border border-3 border-warning mb-3"
                         style="width: 128px; height: 128px;">
                    <h4 class="fw-bold">{{ Auth::user()->name }}</h4>
                    <p class="text-muted">{{ Auth::user()->email }}</p>
                    <div class="d-flex justify-content-center gap-2 mb-3">
                        <span class="badge bg-{{ Auth::user()->role === 'admin' ? 'danger' : 'primary' }}">
                            {{ ucfirst(Auth::user()->role) }}
                        </span>
                        <span class="badge bg-info">{{ Auth::user()->mcAccounts()->count() }} MC Accounts</span>
                    </div>
                    <hr>
                    <div class="text-start small">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Member since</span>
                            <span>{{ Auth::user()->created_at->format('d M Y') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Last login</span>
                            <span>{{ Auth::user()->last_login_at ? Auth::user()->last_login_at->diffForHumans() : 'Never' }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Total purchases</span>
                            <span>{{ Auth::user()->purchases()->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Discord Link -->
            <div class="card mt-3 shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-discord fs-1 text-primary"></i>
                    <h6 class="mt-2">Link Discord Account</h6>
                    @if (Auth::user()->discord_id)
                    <span class="badge bg-success">Connected</span>
                    @else
                    <button class="btn btn-primary btn-sm mt-2">
                        <i class="bi bi-link-45deg"></i> Connect Discord
                    </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Edit Profile -->
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="bi bi-pencil-square me-2"></i>Edit Profile</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('player.profile') }}">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Full Name</label>
                                <input type="text" class="form-control" value="{{ Auth::user()->name }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email Address</label>
                                <input type="email" class="form-control" value="{{ Auth::user()->email }}" required>
                            </div>
                        </div>

                        <hr>
                        <h6>Change Password</h6>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Current Password</label>
                                <input type="password" class="form-control" placeholder="Leave blank to keep current">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">New Password</label>
                                <input type="password" class="form-control" placeholder="Min. 8 characters">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Confirm New Password</label>
                                <input type="password" class="form-control" placeholder="Repeat new password">
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-warning">
                                <i class="bi bi-check-lg me-1"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Linked MC Accounts -->
            <div class="card mt-3 shadow-sm">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-puzzle-fill me-2"></i>Minecraft Accounts</h5>
                </div>
                <div class="card-body">
                    @foreach (Auth::user()->mcAccounts as $account)
                    <div class="d-flex align-items-center justify-content-between py-2 border-bottom">
                        <div class="d-flex align-items-center">
                            <img src="{{ $account->skin_url }}" alt="" class="rounded me-2"
                                 style="width: 36px; height: 36px;">
                            <div>
                                <span class="fw-bold">{{ $account->username }}</span>
                                @if ($account->is_verified)
                                <i class="bi bi-patch-check-fill text-success ms-1" title="Verified"></i>
                                @endif
                            </div>
                        </div>
                        <div>
                            @if ($account->is_primary)
                            <span class="badge bg-warning text-dark me-2">Primary</span>
                            @endif
                            @if ($account->is_verified)
                            <span class="badge bg-success">Verified</span>
                            @else
                            <button class="btn btn-sm btn-outline-warning">Verify</button>
                            @endif
                        </div>
                    </div>
                    @endforeach

                    <button class="btn btn-outline-light btn-sm mt-3 w-100" data-bs-toggle="modal" data-bs-target="#addAccountModal">
                        <i class="bi bi-plus-lg"></i> Link New Account
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

