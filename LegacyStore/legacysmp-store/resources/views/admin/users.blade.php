@extends('layouts.admin')

@section('title', 'Manajemen Pengguna')

@section('header', 'Manajemen Pengguna')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Pengguna</h3>
            <div class="card-tools">
                <form action="{{ route('admin.users') }}" method="GET" class="input-group input-group-sm" style="width: 300px;">
                    <input type="text" name="search" class="form-control float-right" placeholder="Cari pengguna..." value="{{ request('search') }}">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-default">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Avatar</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>MC Accounts</th>
                        <th>Purchases</th>
                        <th>Total Spent</th>
                        <th>Status</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>
                            <img src="{{ $user->avatar_url ?? 'https://crafatar.com/avatars/steve?size=32' }}" class="img-circle img-size-32" alt="Avatar">
                        </td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if($user->role === 'admin')
                                <span class="badge bg-danger">Admin</span>
                            @else
                                <span class="badge bg-info">Player</span>
                            @endif
                        </td>
                        <td>{{ $user->mc_accounts_count }}</td>
                        <td>{{ $user->purchases_count }}</td>
                        <td>Rp {{ number_format($user->purchases()->where('status', 'completed')->sum('total_amount'), 0, ',', '.') }}</td>
                        <td>
                            @if($user->is_banned)
                                <span class="badge bg-danger">Banned</span>
                            @else
                                <span class="badge bg-success">Active</span>
                            @endif
                        </td>
                        <td>{{ $user->created_at->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('admin.user.detail', $user->id) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if($user->is_banned)
                                <form action="{{ route('admin.user.unban', $user->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Unban user ini?')">
                                        <i class="fas fa-unlock"></i>
                                    </button>
                                </form>
                            @else
                                <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#banModal{{ $user->id }}">
                                    <i class="fas fa-ban"></i>
                                </button>
                            @endif
                        </td>
                    </tr>

                    <!-- Ban Modal -->
                    <div class="modal fade" id="banModal{{ $user->id }}" tabindex="-1" role="dialog" aria-labelledby="banModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <form action="{{ route('admin.user.ban', $user->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header">
                                        <h5 class="modal-title">Ban User: {{ $user->name }}</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label>Alasan Ban</label>
                                            <textarea name="ban_reason" class="form-control" rows="3" required placeholder="Masukkan alasan ban..."></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-danger">Ban User</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="11" class="text-center">Tidak ada pengguna ditemukan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer clearfix">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection

