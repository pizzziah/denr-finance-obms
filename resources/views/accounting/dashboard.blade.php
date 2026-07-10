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
      <x-main-card-dashboard :user="$user">
        <x-main-card-dashboard-filter :selected-year="$selectedYear" :selected-month="$selectedMonth" />
      </x-main-card-dashboard>

      {{-- METRICS CARD --}}
      <div class="row mb-4">
        <x-amount-card-dashboard
          title="Amount in Process" 
          :value="$metrics['amountInProcess'] ?? 0"
          icon="bi-database-exclamation"
          :timeline-label="$timelineLabel"
          color-var="primary"
        />
        
        <x-amount-card-dashboard
          title="Forwarded to Cashier" 
          :value="$metrics['amountForwarded'] ?? 0"
          icon="bi-database-fill-up"
          :timeline-label="$timelineLabel"
          color-var="secondary"
          :cancelled-amount="$metrics['totalAmountCancelled'] ?? 0"
        />
        
        <x-amount-card-dashboard
          title="Total Amount Paid" 
          :value="$metrics['totalAmountPaid'] ?? 0"
          icon="bi-database-fill-check"
          :timeline-label="$timelineLabel"
          color-var="primary-variant"
        />
      </div>

      {{-- CARD H --}}
      <div class="card glass-card card-h p-3">
        <h6 class="fw-bold mb-0 p-0 text-center text-uppercase" style="color: var(--primary)">
          Workflow Status
        </h6>
        <p class="mb-3 text-center">
          <small><i>{{ $timelineLabel }}</i></small>
        </p>

        <div class="row row-cols-2 row-cols-sm-3 row-cols-md-6 g-2 text-center justify-content-center">
          @php
            $statuses = [
              ['key' => 'pending', 'label' => 'Pending', 'color' => '#9D6B0B', 'bg' => '#FFFBF3'],
              ['key' => 'processing', 'label' => 'Processing', 'color' => '#fd7e14', 'bg' => '#FFF6EF'],
              ['key' => 'returned', 'label' => 'Returned', 'color' => '#6f42c1', 'bg' => '#EFDFFF'],
              ['key' => 'forwarded', 'label' => 'Forwarded to Cashier', 'color' => 'var(--primary)', 'bg' => '#E5F2D7'],
              ['key' => 'paid', 'label' => 'Paid', 'color' => 'var(--secondary)', 'bg' => '#EDFADF'],
              ['key' => 'cancelled', 'label' => 'Cancelled', 'color' => '#dc3545', 'bg' => '#FDF2F4'],
            ];

            $totalSum = array_sum($metrics['statusCounts'] ?? []);
            $totalSum = $totalSum > 0 ? $totalSum : 1;
          @endphp

          @foreach($statuses as $status)
            @php
              $count = $metrics['statusCounts'][$status['key']] ?? 0;
              $percentage = ($count / $totalSum) * 100;
              $offset = 113 - (113 * $percentage) / 100;
            @endphp
            <div class="col">
              <div class="p-0 py-2 border rounded h-100 d-flex flex-column align-items-center justify-content-center" 
                  style="border-color: {{ $status['color'] }} !important; background: {{ $status['bg'] }}; min-height: 110px;">
                <div class="position-relative mb-2" style="width: 60px; height: 60px;">
                  <svg class="w-100 h-100" viewBox="0 0 40 40" style="transform: rotate(-90deg);">
                    <circle cx="20" cy="20" r="18" fill="transparent" stroke="rgba(0,0,0,0.05)" stroke-width="3"></circle>
                    <circle cx="20" cy="20" r="18" fill="transparent" 
                            stroke="{{ $status['color'] }}" 
                            stroke-width="3" 
                            stroke-dasharray="113" 
                            stroke-dashoffset="{{ $offset }}"
                            stroke-linecap="round"
                            style="transition: stroke-dashoffset 0.5s ease-in-out;">
                    </circle>
                  </svg>
                  <div class="position-absolute top-50 start-50 translate-middle fw-bold" style="color: {{ $status['color'] }}; font-size: 1.1rem;">
                    {{ $count }}
                  </div>
                </div>
                 <span class="small text-muted d-block fw-semibold text-center px-1" style="font-size: 0.70rem; color: {{ $status['color'] }} !important; line-height: 1.1;">
                  {{ $status['label'] }}
                </span>
              </div>
            </div>
          @endforeach
        </div>
      </div>
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