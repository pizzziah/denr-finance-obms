@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4 px-4">
  <div class="row">

  {{-- WELCOME CARD --}}
    <div class="col-lg-9">
      <div class="card glass-card-green card-a p-4 mb-4 text-white">
        <h4 class="fw-bold mb-1">
          Welcome Back,
          {{ ucwords(str_replace('_', ' ', $user->role ?? 'Budget')) }}!
        </h4>
        <h6 class="date mb-0 opacity-75">
          <i class="bi bi-calendar3 me-2"></i>
          {{ now()->format('F d, Y') }}
        </h6>
      </div>

      {{-- ROW 2/METRICS CARD --}}
        {{-- CARD C --}}
      <div class="row mb-4">
        <div class="col-md-4">
          <div class="card glass-card card-c p-1 h-80">
            <div class="card-body d-flex align-items-center justify-content-between">
              <div>
                <h6 class="text-uppercase fw-bold p-0 m-0" style="color: var(--primary)">
                  Amount in Process
                </h6>
                <p class="mb-2">
                  <small><i>{{ now()->year }}</i></small>
                </p>
                <h2 class="fw-bold fs-2 m-0" style="color: var(--primary)">
                  ₱{{ number_format($metrics['amountInProcess'], 2) }}
                </h2>
              </div>
              <div class="fs-1 opacity-60" style="color: var(--primary);">
                <i class="bi bi-database-exclamation"></i>
              </div>  
            </div>
          </div>
        </div>

        {{-- CARD D --}}
        <div class="col-md-4">
          <div class="card glass-card card-d p-1 h-80">
            <div class="card-body d-flex align-items-center justify-content-between">
              <div>
                <h6 class="text-uppercase fw-bold p-0 m-0" style="color: var(--secondary)">
                  Forwarded to Accounting
                </h6>
                <p class="mb-2">
                  <small><i>{{ now()->year }}</i></small>
                </p>
                <h2 class="fw-bold fs-2 m-0" style="color: var(--secondary)">
                  ₱{{ number_format($metrics['amountForwarded'], 2) }}
                </h2>
              </div>
              <div class="fs-1 opacity-60" style="color: var(--secondary);">
                <i class="bi bi-database-fill-up"></i>
              </div>  
            </div>
          </div>
        </div>

        {{-- CARD E --}}
        <div class="col-md-4">
          <div class="card glass-card card-e p-1 h-80">
            <div class="card-body d-flex align-items-center justify-content-between">
              <div>
                <h6 class="text-uppercase fw-bold p-0 m-0" style="color: var(--primary-variant)">
                  Total Amount Paid
                </h6>
                <p class="mb-2">
                  <small><i>{{ now()->year }}</i></small>
                </p>
                <h2 class="fw-bold fs-2 m-0" style="color: var(--primary-variant)">
                  ₱{{ number_format($metrics['totalAmountPaid'], 2) }}
                </h2>
              </div>
              <div class="fs-1 opacity-60" style="color: var(--primary-variant);">
                <i class="bi bi-database-fill-check"></i>
              </div>  
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>
@endsection

@php
    $pageTitle = 'Dashboard';
@endphp