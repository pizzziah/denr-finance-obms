@extends('layouts.app')

@php
$pageTitle = 'Log Book';
@endphp

@section('content')

<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <form method="GET" action="{{ route('budget.logbook') }}">
            <input type="text"name="search"class="form-control"placeholder="Search ORS, Payee, Office..."value="{{ request('search') }}">
            <select name="year"
                    class="form-select"
                    onchange="this.form.submit()">
                <option value="all"
                    {{ $year == 'all' ? 'selected' : '' }}>
                    All (2025-2026)
                </option>
                <option value="2026"
                    {{ $year == '2026' ? 'selected' : '' }}>
                    2026
                </option>
                <option value="2025"
                    {{ $year == '2025' ? 'selected' : '' }}>
                    2025
                </option>
            </select>
        </form>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div style="max-height: 800px; max-width: 1900px; overflow-y: auto; overflow-x: auto;">
                <table class="table table-bordered table-hover table-striped">
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
                            <td>₱{{ number_format((float) str_replace(',', '', $record->amount ?? 0), 2) }}</td>
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
                            <td colspan="18" class="text-center">
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
@endsection