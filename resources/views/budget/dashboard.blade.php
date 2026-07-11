@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
@php
  $selectedYear = request('year', now()->year);
  $selectedMonth = request('month');
    
  $monthName = $selectedMonth ? DateTime::createFromFormat('!m', $selectedMonth)->format('F') : '';
  $timelineLabel = $selectedMonth ? "$monthName $selectedYear" : "$selectedYear";
@endphp

<div class="container-fluid mt-4 px-4">
  <div class="row">
    <div class="col-lg-9">
      {{-- WELCOME CARD --}}
      <x-db-main-card :user="$user">
        <x-db-main-card-filter :selected-year="$selectedYear" :selected-month="$selectedMonth" />
      </x-db-main-card>

      {{-- METRICS CARD --}}
      <div class="row mb-4">
        <x-db-amount-card title="Amount in Process" :value="$metrics['amountInProcess'] ?? 0" icon="bi-database-exclamation" :timeline-label="$timelineLabel" color-var="primary" />
        <x-db-amount-card title="Forwarded to Accounting" :value="$metrics['amountForwarded'] ?? 0" icon="bi-database-fill-up" :timeline-label="$timelineLabel" color-var="secondary" :cancelled-amount="$metrics['totalAmountCancelled'] ?? 0" />
        <x-db-amount-card title="Total Amount Paid" :value="$metrics['totalAmountPaid'] ?? 0" icon="bi-database-fill-check" :timeline-label="$timelineLabel" color-var="primary-variant" />
      </div>

      {{-- WORKFLOW STATUS --}}
      @php
      $budgetStatuses = [
        ['key' => 'pending', 'label' => 'Pending', 'color' => '#9D6B0B', 'bg' => '#FFFBF3'],
        ['key' => 'processing', 'label' => 'Processing', 'color' => '#fd7e14', 'bg' => '#FFF6EF'],
        ['key' => 'for_obligation', 'label' => 'For Obligation', 'color' => '#271ECE', 'bg' => '#BCC3F6'],
        ['key' => 'returned', 'label' => 'Returned to End User', 'color' => '#6f42c1', 'bg' => '#EFDFFF'],
        ['key' => 'cancelled', 'label' => 'Cancelled', 'color' => '#C61919', 'bg' => '#FFC2C2'],
        ['key' => 'forwarded', 'label' => 'Forwarded to Accounting', 'color' => 'var(--primary)', 'bg' => '#E5F2D7'],
        ['key' => 'paid', 'label' => 'Paid', 'color' => 'var(--secondary)', 'bg' => '#EDFADF'],
      ];
      @endphp

      <x-db-workflow-status :statuses="$budgetStatuses" :metrics="$metrics" :timeline-label="$timelineLabel" row-class="row g-3" col-class="col-3" />
    </div>

    {{-- RIGHT-SIDE COLUMN --}}
    <div class="col-lg-3">
      {{-- TOTAL COUNT CARD --}}
      <div class="card glass-card-hover card-b p-3 border-0 text-center mb-4">
        <h6 class="fw-bold mb-0 text-uppercase" style="color: var(--primary)">
          Total Transactions
        </h6>
        <p class="mb-0">
          <small><i>{{ $timelineLabel }}</i></small>
        </p>
        <h2 class="display-4 fw-bold p-0 m-0" style="color: var(--primary)">
          {{ $metrics['totalTransactions'] ?? 0 }}
        </h2>
      </div>

      {{-- VISUALIZATION CARD --}}
      <div class="card glass-card card-f p-3 m-0">
        <div class="text-center mb-3">
          <h6 class="fw-bold m-0 text-uppercase" style="color: var(--primary)">
            Amount Per Office
          </h6>
          <p class="m-0 mt-1">
            <small class="text-muted"><i>{{ $timelineLabel }}</i></small>
          </p>
        </div>
        <div class="p-1" style="height: 350px; position: relative;">
          <canvas id="officeChart"></canvas>
        </div>
      </div>
      
    </div>
  </div>
</div>
@endsection

@section('scripts')
  @include('budget.partials.dashboard-payee')
@endsection

@php
  $pageTitle = 'Dashboard';
@endphp