@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4 px-4">
    <div class="row">
        
        <div class="col-lg-9">
            
            <div class="card glass-card-green card-a p-4 mb-4" style="color: var(--background);">
                <h4 class="fw-bold mb-1">
                    Welcome Back,
                    {{ ucwords(str_replace('_', ' ', $user->role ?? 'Budget')) }}!
                </h4>
                <h6 class="date mb-0 opacity-75">
                    <i class="bi bi-calendar3 me-2"></i>
                    {{ now()->format('F d, Y') }}
                </h6>
            </div>

            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card glass-card card-c p-4 h-100">
                        <h6 class="text-uppercase fw-bold p-0 m-0">Total Amount Requested</h6>
                        <p class="mb-2"><i>1st Quarter</i></p>
                        <h2 class="fw-bold fs-3">₱{{ number_format($metrics['totalRequestedAmount'], 2) }}</h2>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card glass-card card-d p-4 h-100">
                        <h6 class="text-uppercase fw-bold p-0 m-0">Amount In Process</h6>
                        <p class="mb-2"><i>1st Quarter</i></p>
                        <h2 class="fw-bold fs-3">₱{{ number_format($metrics['amountInProcess'], 2) }}</h2>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card glass-card card-e p-4 h-100">
                        <h6 class="text-uppercase fw-bold p-0 m-0">Forwarded to Accounting</h6>
                        <p class="mb-2"><i>1st Quarter</i></p>
                        <h2 class="fw-bold fs-3">₱{{ number_format($metrics['amountForwarded'], 2) }}</h2>
                    </div>
                </div>
            </div>

            <div class="card glass-card card-f p-4 mb-4">
                <h5 class="fw-bold text-success mb-3">Amount per Office</h5>
                <div class="p-5 text-center text-muted border border-dashed rounded">
                     Office Visualizations/Graph Insertion Area
                </div>
            </div>

            <div class="card glass-card-green card-g p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="fw-bold m-0" style="color: var(--background);">Recent Log Entries</h4>
                </div>
                
                <div class="table-responsive bg-white rounded shadow-sm p-2">
                    <table class="table table-striped align-middle text-center m-0" style="font-size: 0.85rem;">
                        <thead class="text-white" style="background-color: #0b5929;">
                            <tr>
                                <th>Date Received</th>
                                <th>Date Processed</th>
                                <th>OBR Date</th>
                                <th>OBR No.</th>
                                <th>DV No.</th>
                                <th>Status</th>
                                <th>Payee</th>
                                <th>Particulars</th>
                                <th>Debit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($metrics['recentLogs'] as $row)
                            <tr>
                                <td>{{ $row->date_received ?? '-' }}</td>
                                <td>{{ $row->date_processed ?? '-' }}</td>
                                <td>{{ $row->date_ors_received ?? '-' }}</td>
                                <td>{{ $row->ors_no ?? '-' }}</td>
                                <td><span class="badge bg-dark px-2 py-1">{{ $row->dv_no ?? $row->id ?? '-' }}</span></td>
                                <td>
                                    @php
                                        $badgeColor = match(strtolower($row->status ?? '')) {
                                            'processing' => 'bg-warning text-dark',
                                            'forwarded to cashier', 'forwarded' => 'bg-success',
                                            'returned' => 'bg-purple text-white',
                                            'pending' => 'bg-secondary',
                                            default => 'bg-info text-dark'
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeColor }}">{{ $row->status ?? 'N/A' }}</span>
                                </td>
                                <td class="fw-bold text-secondary">{{ Str::limit($row->payee ?? '-', 15) }}</td>
                                <td><small class="text-muted">{{ Str::limit($row->particulars ?? '-', 25) }}</small></td>
                                <td class="fw-bold text-dark">₱{{ number_format($row->debit ?? 0, 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center py-4 text-muted">No recent workflows encountered.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-end mt-3">
                    <a href="{{ route('budget.logbook') }}" class="btn btn-light px-4 py-2 fw-bold shadow-sm" style="border-radius: 6px; color: #0b5929;">
                        View Full Log Book
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-3">
            
            <div class="card glass-card card-b p-4 pb-2 border-0 text-center mb-4">
                <h4 class="fw-bold mb-0">Total Requests</h4>
                <small><i>January 2026</i></small>
                <h2 class="display-4 fw-bold p-0 m-0 text-dark">{{ $metrics['totalRequests'] }}</h2>
            </div>

            <div class="card glass-card card-h p-4">
                <h6 class="fw-bold mb-4 text-secondary text-uppercase tracking-wider text-center">Workflow Status</h6>
                
                <div class="row g-3 text-center">
                    <div class="col-6">
                        <div class="p-2 border rounded bg-white h-100 d-flex flex-column align-items-center justify-content-center" style="border-color: #17a2b8 !important;">
                            <div class="rounded-circle d-flex align-items-center justify-content-center border border-3 border-info fw-bold mb-1 text-info" style="width: 45px; height: 45px; font-size: 1.1rem;">
                                {{ $metrics['statusCounts']['for_review'] }}
                            </div>
                            <span class="small text-muted d-block" style="font-size: 0.75rem;">For Review</span>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="p-2 border rounded bg-white h-100 d-flex flex-column align-items-center justify-content-center" style="border-color: #ffc107 !important;">
                            <div class="rounded-circle d-flex align-items-center justify-content-center border border-3 border-warning fw-bold mb-1 text-warning" style="width: 45px; height: 45px; font-size: 1.1rem;">
                                {{ $metrics['statusCounts']['pending'] }}
                            </div>
                            <span class="small text-muted d-block" style="font-size: 0.75rem;">Pending</span>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="p-2 border rounded bg-white h-100 d-flex flex-column align-items-center justify-content-center" style="border-color: #fd7e14 !important;">
                            <div class="rounded-circle d-flex align-items-center justify-content-center border border-3 fw-bold mb-1" style="width: 45px; height: 45px; font-size: 1.1rem; color: #fd7e14; border-color: #fd7e14 !important;">
                                {{ $metrics['statusCounts']['processing'] }}
                            </div>
                            <span class="small text-muted d-block" style="font-size: 0.75rem;">Processing</span>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="p-2 border rounded bg-white h-100 d-flex flex-column align-items-center justify-content-center" style="border-color: #0d6efd !important;">
                            <div class="rounded-circle d-flex align-items-center justify-content-center border border-3 border-primary fw-bold mb-1 text-primary" style="width: 45px; height: 45px; font-size: 1.1rem;">
                                {{ $metrics['statusCounts']['for_obligation'] }}
                            </div>
                            <span class="small text-muted d-block" style="font-size: 0.75rem;">For Obligation</span>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="p-2 border rounded bg-white h-100 d-flex flex-column align-items-center justify-content-center" style="border-color: #6f42c1 !important;">
                            <div class="rounded-circle d-flex align-items-center justify-content-center border border-3 fw-bold mb-1" style="width: 45px; height: 45px; font-size: 1.1rem; color: #6f42c1; border-color: #6f42c1 !important;">
                                {{ $metrics['statusCounts']['returned'] }}
                            </div>
                            <span class="small text-muted d-block" style="font-size: 0.75rem;">Returned</span>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="p-2 border rounded bg-white h-100 d-flex flex-column align-items-center justify-content-center" style="border-color: #dc3545 !important;">
                            <div class="rounded-circle d-flex align-items-center justify-content-center border border-3 border-danger fw-bold mb-1 text-danger" style="width: 45px; height: 45px; font-size: 1.1rem;">
                                {{ $metrics['statusCounts']['cancelled'] }}
                            </div>
                            <span class="small text-muted d-block" style="font-size: 0.75rem;">Cancelled</span>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="p-2 border rounded bg-white h-100 d-flex flex-column align-items-center justify-content-center" style="border-color: #198754 !important;">
                            <div class="rounded-circle d-flex align-items-center justify-content-center border border-3 border-success fw-bold mb-1 text-success" style="width: 45px; height: 45px; font-size: 1.1rem;">
                                {{ $metrics['statusCounts']['forwarded'] }}
                            </div>
                            <span class="small text-muted d-block" style="font-size: 0.75rem;">Forwarded</span>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="p-2 border rounded bg-white h-100 d-flex flex-column align-items-center justify-content-center" style="border-color: #198754 !important;">
                            <div class="rounded-circle d-flex align-items-center justify-content-center border border-3 border-success fw-bold mb-1 text-success" style="width: 45px; height: 45px; font-size: 1.1rem;">
                                {{ $metrics['statusCounts']['paid'] }}
                            </div>
                            <span class="small text-muted d-block" style="font-size: 0.75rem;">Paid</span>
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