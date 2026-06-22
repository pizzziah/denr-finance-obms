@extends('layouts.app')

@section('content')

<div class="container-fluid mt-4">
    @include('layouts.subtab')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Budget Log Book</h3>
        <form method="GET" action="{{ route('budget.logbook') }}">
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
            <div style="max-height: 600px; overflow-y: auto; overflow-x: auto;">
                <table class="table table-bordered table-hover table-striped">
                    <thead class="table-dark sticky-top">
                        <tr>
                            <th>ORS No.</th>
                            <th>Date Received</th>
                            <th>Issuing Office</th>
                            <th>Payee</th>
                            <th>Classification</th>
                            <th>Particulars</th>
                            <th>Amount</th>
                            <th>Status</th>
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
                            <td>
                                ₱{{ number_format((float) str_replace(',', '', $record->amount ?? 0), 2) }}
                            </td>
                            <td>{{ $record->status ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">
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

@php
    $pageTitle = 'Log Book';
@endphp