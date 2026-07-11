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
        <x-db-amount-card
          title="Amount in Process" 
          :value="$metrics['amountInProcess'] ?? 0"
          icon="bi-database-exclamation"
          :timeline-label="$timelineLabel"
          color-var="primary"
        />
        
        <x-db-amount-card
          title="Forwarded to Cashier" 
          :value="$metrics['amountForwarded'] ?? 0"
          icon="bi-database-fill-up"
          :timeline-label="$timelineLabel"
          color-var="secondary"
          :cancelled-amount="$metrics['totalAmountCancelled'] ?? 0"
        />
        
        <x-db-amount-card
          title="Total Amount Paid" 
          :value="$metrics['totalAmountPaid'] ?? 0"
          icon="bi-database-fill-check"
          :timeline-label="$timelineLabel"
          color-var="primary-variant"
        />
      </div>
      
      {{-- WORKFLOW STATUS --}}
      @php
      $accountingStatuses = [
        ['key' => 'pending', 'label' => 'Pending', 'color' => '#9D6B0B', 'bg' => '#FFFBF3'],
        ['key' => 'processing', 'label' => 'Processing', 'color' => '#fd7e14', 'bg' => '#FFF6EF'],
        ['key' => 'returned', 'label' => 'Returned', 'color' => '#6f42c1', 'bg' => '#EFDFFF'],
        ['key' => 'forwarded', 'label' => 'Forwarded to Cashier', 'color' => 'var(--primary)', 'bg' => '#E5F2D7'],
        ['key' => 'paid', 'label' => 'Paid', 'color' => 'var(--secondary)', 'bg' => '#EDFADF'],
        ['key' => 'cancelled', 'label' => 'Cancelled', 'color' => '#dc3545', 'bg' => '#FDF2F4'],
      ];
      @endphp

      <x-db-workflow-status 
        :statuses="$accountingStatuses" 
        :metrics="$metrics" 
        :timeline-label="$timelineLabel" 
      />
    </div>

    {{-- RIGHT-SIDE COLUMN --}}
    <div class="col-lg-3">
      {{-- CARD B --}}
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

      {{-- ROW 3/VISUALIZATION CARD --}}
      <div class="card glass-card card-f p-3 m-0">
        <div class="text-center mb-3">
          <h6 class="fw-bold m-0 text-uppercase" style="color: var(--primary)">
            Top 10 Payees Breakdown
          </h6>
          <p class="m-0 mt-1">
            <small class="text-muted"><i>{{ $timelineLabel }}</i></small>
          </p>
        </div>

        <div class="p-1" style="height: 350px; position: relative;">
          <canvas id="payeeChart"></canvas>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
  Chart.defaults.font.family = "'Montserrat', 'Inter', sans-serif";

  const payeeCtx = document.getElementById('payeeChart');
  if (payeeCtx) {
    const payeeAmountsData = {!! json_encode($metrics['payeeAmounts'] ?? json_decode('{}')) !!};
    
    const payeeLabels = Object.keys(payeeAmountsData);
    const payeeData = Object.values(payeeAmountsData);

    if (payeeLabels.length === 0) {
      payeeCtx.style.display = 'none';
      const noDataDiv = document.createElement('div');
      noDataDiv.className = 'text-center py-5 text-muted';
      noDataDiv.innerHTML = '<i class="bi bi-graph-down display-6 d-block mb-2"></i> No data recorded for this filtered timeline.';
      payeeCtx.parentNode.appendChild(noDataDiv);
    } else {
      new Chart(payeeCtx, {
        type: 'bar',
        data: {
          labels: payeeLabels,
          datasets: [{
            label: 'Total Combined Amount (Debit)',
            data: payeeData,
            backgroundColor: 'rgb(240, 255, 230)',
            borderColor: '#044709',
            borderWidth: 2
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: { display: false },
            tooltip: {
              callbacks: {
                label: function(context) {
                  return '₱' + Number(context.raw).toLocaleString('en-PH', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                  });
                }
              }
            }
          },
          scales: {
            y: {
              beginAtZero: true,
              ticks: {
                callback: function(value) {
                  return '₱' + Number(value).toLocaleString();
                }
              }
            }
          }
        }
      });
    }
  }
});
</script>
@endsection

@php
  $pageTitle = 'Dashboard';
@endphp