@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard Overview')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50">Total Users</h6>
                        <h3 class="fw-bold mb-0">{{ $stats['total_users'] }}</h3>
                    </div>
                    <i class="bi bi-people-fill fs-1 text-white-50"></i>
                </div>
                <small class="text-white-50">+{{ $stats['new_users_this_month'] }} this month</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50">Total Revenue</h6>
                        <h3 class="fw-bold mb-0">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</h3>
                    </div>
                    <i class="bi bi-coin fs-1 text-white-50"></i>
                </div>
                <small class="text-white-50">Rp {{ number_format($stats['monthly_revenue'], 0, ',', '.
