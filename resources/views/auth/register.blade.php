@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow border-0">
                <div class="card-header bg-dark text-white text-center py-3">
                    <i class="bi bi-person-plus-fill fs-1 text-warning"></i>
                    <h4 class="mb-0 mt-2">Create Account</h4>
                    <small>Join the LegacySMP community</small>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="row">
                            <!-- Name -->
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Full Name</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                                    <input id="name" type="text"
                                           class="form-control @error('name') is-invalid @enderror"
                                           name="name" value="{{ old('name') }}" required autofocus
                                           placeholder="Your name">
                                    @error('name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Minecraft Username -->
                            <div class="col-md-6 mb-3">
                                <label for="minecraft_username" class="form-label">Minecraft Username</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-puzzle-fill"></i></span>
                                    <input id="minecraft_username" type="text"
                                           class="form-control @error('minecraft_username') is-invalid @enderror"
                                           name="minecraft_username" value="{{ old('minecraft_username') }}" required
                                           placeholder="Minecraft IGN" maxlength="16">
                                    @error('minecraft_username')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                                <input id="email" type="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       name="email" value="{{ old('email') }}" required
                                       placeholder="your@email.com">
                                @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-key-fill"></i></span>
                                    <input id="password" type="password"
                                           class="form-control @error('password') is-invalid @enderror"
                                           name="password" required placeholder="Min. 8 characters">
                                    @error('password')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                <small class="text-muted">Minimum 8 characters</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-key-fill"></i></span>
                                    <input id="password_confirmation" type="password"
                                           class="form-control"
                                           name="password_confirmation" required
                                           placeholder="Repeat password">
                                </div>
                            </div>
                        </div>

                        <!-- Terms -->
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input @error('terms') is-invalid @enderror"
                                       type="checkbox" name="terms" id="terms" required>
                                <label class="form-check-label" for="terms">
                                    I agree to the <a href="#" class="text-decoration-none">Terms of Service</a> and
                                    <a href="#" class="text-decoration-none">Privacy Policy</a>
                                </label>
                                @error('terms')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Turnstile -->
                        <div class="mb-3">
                            <div class="cf-turnstile" data-sitekey="{{ env('CLOUDFLARE_TURNSTILE_SITE_KEY') }}"></div>
                        </div>

                        <button type="submit" class="btn btn-warning w-100 fw-bold py-2">
                            <i class="bi bi-person-check me-2"></i> Create Account
                        </button>
                    </form>
                </div>
                <div class="card-footer bg-light text-center py-3">
                    <span>Already have an account?</span>
                    <a href="{{ route('login') }}" class="text-decoration-none fw-bold">Login here</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

