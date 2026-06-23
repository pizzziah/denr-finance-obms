@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4 px-4">
  <div class="row g-4">
    <div class="col-12 col-lg-9 d-flex flex-column gap-3">
      <div class="card shadow-sm m-0 text-white" style="background-color: var(--primary);">
        <div class="card-body p-4">
        <h4 class="fw-bold mb-1">
          Welcome Back,
          {{ ucwords(str_replace('_', ' ', $currentUser->role ?? 'Admin')) }}!
        </h4> 
        <h6 class="date mb-0 opacity-75"><i class="bi bi-calendar3 me-2"></i>{{ now()->format('F d, Y') }}</h6>
        </div>
      </div>

      <div class="row g-3 m-0 p-0">
        <div class="col-12 col-md-4 ps-0">
          <div class="card shadow p-2 m-0 rounded" style="background: var(--secondary-variant)">
            <div class="card-body d-flex align-items-center justify-content-between">
              <div>
                <h6 class="text-uppercase mb-1 small fw-bold">Total Accounts</h6>
                <h2 class="fw-bold fs-1 mb-0">{{ $metrics['total_users'] }}</h2>
              </div>
              <div class="fs-1 opacity-60" style="color: var(--primary);">
                <i class="bi bi-people-fill"></i>
              </div>
            </div>
          </div>
        </div>

        <div class="col-12 col-md-4">
          <div class="card shadow p-2 m-0 rounded" style="background: #FFEECC">
            <div class="card-body d-flex align-items-center justify-content-between">
              <div>
                <h6 class="text-uppercase mb-1 small fw-bold">Active Sessions</h6>
                <h2 class="fw-bold fs-1 mb-0">{{ $metrics['active_users'] }}</h2>
              </div>
              <div class="fs-1 opacity-60" style="color: #9D6B0B;">
                <i class="bi bi-person-check-fill"></i>
              </div>
            </div>
          </div>
        </div>

        <div class="col-12 col-md-4 pr-0 pe-0">
          <div class="card shadow p-2 m-0 rounded" style="background: #FFC2C2">
            <div class="card-body d-flex align-items-center justify-content-between">
              <div>
                <h6 class="text-uppercase mb-1 small fw-bold">Deactivated Accounts</h6>
                <h2 class="fw-bold fs-1 mb-0">{{ $metrics['inactive_users'] }}</h2>
              </div>
              <div class="fs-1 opacity-60" style="color: var(--error)">
                <i class="bi bi-person-x-fill"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-12 col-lg-3">
      <div class="card shadow p-2 m-0 rounded" >
        <div class="card-header bg-white py-3 border-0" >
          <h4 class="fw-bold mb-0" style="color: var(--primary)">
            <i class="bi bi-pie-chart-fill me-2" style="color: var(--primary)"></i>
            User Distribution
          </h4>
        </div>

        <div class="card-body d-flex align-items-center justify-content-center pt-0">
          @if(count($metrics['by_department']) > 0)
            <div style="width: 100%; max-width: 240px; margin: 0 auto;">
              <canvas id="deptDistributionChart"></canvas>
            </div>
          @else
            <div class="text-center py-4 small">
              <i class="bi bi-exclamation-circle d-block mb-2 fs-4"></i>No data located.
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  document.addEventListener("DOMContentLoaded", function () {
    const ctx = document.getElementById('deptDistributionChart');
    if (!ctx) return;

    const chartLabels = {!! json_encode($metrics['by_department']->map(fn($d) => $d->department === 'Admin' ? 'System Admin' : $d->department)) !!};
    const chartData = {!! json_encode($metrics['by_department']->pluck('total')) !!};

    new Chart(ctx, {
      type: 'doughnut', 
      data: {
        labels: chartLabels,
        datasets: [{
          data: chartData,
          backgroundColor: [
            '#4CAF50',
            '#E7FFCE', 
            '#0B879D', 
            '#ffc107',
            '#dc3545' 
          ],
          borderWidth: 2,
          borderColor: '#ffffff'
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            position: 'bottom',
            labels: {
              boxWidth: 12,
              font: { size: 11, family: 'Montserrat' },
              padding: 15
            }
          },
          tooltip: {
            callbacks: {
              label: function(context) {
                let label = context.label || '';
                let value = context.raw || 0;
                return ` ${label}: ${value} user(s)`;
              }
            }
          }
        }
      }
    });
  });
</script>
@endsection

@php
  $pageTitle = 'Dashboard';
@endphp