@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid mt-4 px-4">
  <div class="row g-4">

    <div class="col-12 col-lg-9 d-flex flex-column gap-3">

      <div class="card glass-card-green card-a p-4" style="color: var(--background);">
        <div class="card-body">
          <h4 class="fw-bold mb-1">
            Welcome Back,
            {{ ucwords(str_replace('_', ' ', $currentUser->role ?? 'Admin')) }}!
          </h4>

          <h6 class="date mb-0 opacity-75">
            <i class="bi bi-calendar3 me-2"></i>
            {{ now()->format('F d, Y') }}
          </h6>
        </div>
      </div>

      <div class="card shadow glass-card p-4 flex-grow-1">
        <div class="card-body">
          <h5 class="fw-bold text-warning mb-3">
            <i class="bi bi-shield-lock-fill me-2"></i>Pending Unlock Requests
          </h5>
          
          @if(isset($pendingUnlocks) && $pendingUnlocks->count() > 0)
            <div class="d-flex flex-column gap-2" style="max-height: 400px; overflow-y: auto;">
              @foreach($pendingUnlocks as $lock)
                <div class="p-3 border rounded d-flex align-items-center justify-content-between bg-light">
                  <span class="small text-dark"><strong>Year {{ $lock->year }} - Q{{ $lock->quarter }}</strong></span>
                  <div class="d-flex align-items-center gap-2">
                    <form action="{{ route('admin.unlock-quarter', $lock->id) }}" method="POST" class="m-0">
                      @csrf
                      <button type="submit" class="btn btn-sm btn-warning fw-bold px-3">
                        Grant Unlock
                      </button>
                    </form>
                  </div>
                </div>
              @endforeach
            </div>
          @else
            <div class="text-center py-5 text-muted">
              <i class="bi bi-check-circle fs-2 d-block mb-2 text-success"></i>
              No pending unlock requests found.
            </div>
          @endif
        </div>
      </div>

    </div>
      
    <div class="col-12 col-lg-3">
      <div class="card shadow glass-card h-100 rounded">
        <div class="card-header bg-transparent border-0 pt-3 px-3 pb-0">
          <h5 class="fw-bold mb-0 fs-6 text-dark">
            System Metrics Overview
          </h5>
        </div>

        <div class="card-body d-flex flex-column gap-4">
          <div>
            <span class="text-muted d-block small mb-2 fw-semibold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">User Distribution</span>
            @if(count($metrics['by_department']) > 0)
              <div style="width: 100%; max-width: 180px; margin: 0 auto;">
                <canvas id="deptDistributionChart"></canvas>
              </div>
            @else
              <div class="text-center py-3 small text-muted">
                <i class="bi bi-exclamation-circle d-block mb-1 fs-5"></i>
                No breakdown data located.
              </div>
            @endif
          </div>

          <hr class="my-0 opacity-10">

          <div class="d-flex flex-column gap-3">
            <span class="text-muted d-block small mb-2 fw-semibold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">User Count</span>
            <div class="d-flex align-items-center justify-content-between">
              <div class="d-flex align-items-center gap-2">
                <div class="fs-4 rounded px-2" style="color: var(--primary); background-color: rgba(11, 135, 157, 0.1);">
                  <i class="bi bi-people-fill"></i>
                </div>
                <span class="small fw-bold text-secondary">Total Accounts</span>
              </div>
              <h4 class="fw-bold mb-0 fs-5 text-dark">{{ $metrics['total_users'] }}</h4>
            </div>

            <div class="d-flex align-items-center justify-content-between">
              <div class="d-flex align-items-center gap-2">
                <div class="fs-4 rounded px-2" style="color: #9D6B0B; background-color: rgba(157, 107, 11, 0.1);">
                  <i class="bi bi-person-check-fill"></i>
                </div>
                <span class="small fw-bold text-secondary">Active Sessions</span>
              </div>
              <h4 class="fw-bold mb-0 fs-5 text-dark">{{ $metrics['active_users'] }}</h4>
            </div>

            <div class="d-flex align-items-center justify-content-between">
              <div class="d-flex align-items-center gap-2">
                <div class="fs-4 rounded px-2" style="color: var(--error); background-color: rgba(220, 53, 69, 0.1);">
                  <i class="bi bi-person-x-fill"></i>
                </div>
                <span class="small fw-bold text-secondary">Deactivated</span>
              </div>
              <h4 class="fw-bold mb-0 fs-5 text-dark">{{ $metrics['inactive_users'] }}</h4>
            </div>

          </div>
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
                backgroundColor: ['#75B432', '#0B879D', '#044709'],
                borderWidth: 1,
                borderColor: 'transparent'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 8,
                        font: { size: 9, family: 'Montserrat' },
                        padding: 8
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return `${context.label}: ${context.raw} user(s)`;
                        }
                    }
                }
            }
        }
    });
});
</script>
@endsection