@extends('layouts.app')

@section('title', 'Logbook')

@section('content')

<div class="container-lg mt-5">
{{-- TOP BAR --}}
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">

    @include('layouts.subtab')

    <div class="d-flex gap-2">

        {{-- SEARCH --}}
        <form method="GET"
              action="{{ route('budget.logbook') }}"
              class="d-flex gap-2 align-items-center">

            <input
                type="text"
                name="search"
                class="form-control"
                placeholder="Search ORS, Payee, Office..."
                value="{{ request('search') }}">

            {{-- Preserve filters when searching --}}
            <input type="hidden" name="year" value="{{ request('year', 'all') }}">
            <input type="hidden" name="month" value="{{ request('month') }}">
            <input type="hidden" name="status" value="{{ request('status', 'all') }}">

            <button type="submit" class="btn btn-primary">
                <i class="bi bi-search"></i>
            </button>

        </form>

        {{-- FILTER BUTTON --}}
        <button class="btn btn-outline-secondary"
                data-bs-toggle="modal"
                data-bs-target="#filterModal">
            <i class="bi bi-funnel"></i> Filter
        </button>

    </div>

</div>

{{-- CARD --}}
<div class="card">

    <h5 class="px-3 pt-3 fw-bold pb-0 m-0">
        Log Book
    </h5>

    <div class="card-body">

        <div class="table-responsive"
             style="max-height:60vh; overflow:auto;">

            <table class="table table-bordered table-hover table-striped align-middle">

                <thead class="table-dark sticky-top">
                    <tr>
                        <th>ORS No.</th>
                        <th>Date Received</th>
                        <th>Issuing Office</th>
                        <th>Payee</th>
                        <th>Classification</th>
                        <th>Particulars</th>
                        <th>Particulars Remark</th>
                        <th>Amount</th>
                        <th>Date Returned</th>
                        <th>Date Received</th>
                        <th>Date Forwarded</th>
                        <th>Date ORS Received</th>
                        <th>Date Returned</th>
                        <th>Date Receive</th>
                        <th>Date Forwarded Accounting</th>
                        <th>Status</th>
                        <th>Total Time in Budget</th>
                        <th>Total Time</th>
                        <th>Final Remark</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($records as $record)

                        <tr>
                            <td>{{ $record->ors_no ?? '-' }}</td>
                            <td>{{ $record->date_received ?? '-' }}</td>
                            <td>{{ $record->issuing_office ?? '-' }}</td>
                            <td>{{ $record->payee ?? '-' }}</td>
                            <td>{{ $record->classification ?? '-' }}</td>
                            <td>{{ $record->particulars ?? '-' }}</td>
                            <td>{{ $record->particulars_remark ?? '-' }}</td>

                            <td>
                                ₱{{ number_format((float) str_replace(',', '', $record->amount ?? 0), 2) }}
                            </td>

                            <td>{{ $record->date_returned_1 ?? '-' }}</td>
                            <td>{{ $record->date_received_1 ?? '-' }}</td>
                            <td>{{ $record->date_forwarded_1 ?? '-' }}</td>
                            <td>{{ $record->date_ors_received ?? '-' }}</td>
                            <td>{{ $record->date_returned_2 ?? '-' }}</td>
                            <td>{{ $record->date_received_2 ?? '-' }}</td>
                            <td>{{ $record->date_forwarded_accounting ?? '-' }}</td>
                            <td>{{ $record->status ?? '-' }}</td>
                            <td>{{ $record->total_time_budget ?? '-' }}</td>
                            <td>{{ $record->total_time ?? '-' }}</td>
                            <td>{{ $record->final_remarks ?? '-' }}</td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="19" class="text-center">
                                No records found.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>
</div>

{{-- FILTER MODAL --}}

<div class="modal fade" id="filterModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

        <form method="GET" action="{{ route('budget.logbook') }}">

            <input type="hidden"
                   name="search"
                   value="{{ request('search') }}">

            <div class="modal-header">
                <h5 class="modal-title">Filter Logbook</h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                {{-- YEAR --}}
                <div class="mb-3">
                    <label class="form-label">Year</label>

                    <select name="year" class="form-select">
                        <option value="all" @selected(request('year', 'all') == 'all')>
                            All (2025-2026)
                        </option>

                        <option value="2026" @selected(request('year') == '2026')>
                            2026
                        </option>

                        <option value="2025" @selected(request('year') == '2025')>
                            2025
                        </option>
                    </select>
                </div>

                {{-- MONTH --}}
                <div class="mb-3">
                    <label class="form-label">Month</label>

                    <select name="month" class="form-select">
                        <option value="">All Months</option>

                        <option value="01" @selected(request('month') == '01')>January</option>
                        <option value="02" @selected(request('month') == '02')>February</option>
                        <option value="03" @selected(request('month') == '03')>March</option>
                        <option value="04" @selected(request('month') == '04')>April</option>
                        <option value="05" @selected(request('month') == '05')>May</option>
                        <option value="06" @selected(request('month') == '06')>June</option>
                        <option value="07" @selected(request('month') == '07')>July</option>
                        <option value="08" @selected(request('month') == '08')>August</option>
                        <option value="09" @selected(request('month') == '09')>September</option>
                        <option value="10" @selected(request('month') == '10')>October</option>
                        <option value="11" @selected(request('month') == '11')>November</option>
                        <option value="12" @selected(request('month') == '12')>December</option>
                    </select>
                </div>

                {{-- STATUS --}}
                <div class="mb-3">
                    <label class="form-label">Status</label>

                    <select name="status" class="form-select">

                        <option value="all" @selected(request('status', 'all') == 'all')>
                            All Status
                        </option>

                        <option value="for_obligation"
                            @selected(request('status') == 'for_obligation')>
                            For Obligation
                        </option>

                        <option value="forwarded_to_accounting"
                            @selected(request('status') == 'forwarded_to_accounting')>
                            Forwarded to Accounting
                        </option>

                    </select>
                </div>

            </div>

            <div class="modal-footer">

                <a href="{{ route('budget.logbook') }}"
                   class="btn btn-secondary">
                    Reset
                </a>

                <button type="submit"
                        class="btn btn-success">
                    Apply Filters
                </button>

            </div>

        </form>

    </div>
</div>
```

</div>

@endsection

@php
$pageTitle = 'Logbook';
@endphp
