@extends('layouts.app')

@section('title', 'Accounting Log Book')

@section('content')

<div class="container mt-5">

    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">

        @include('layouts.subtab')
        <form method="GET"
              action="{{ route('accounting.logbook') }}"
              class="d-flex gap-2 align-items-center">

            <input
                type="text"
                name="search"
                class="form-control"
                placeholder="Search DV, Payee, Office..."
                value="{{ $search ?? request('search') }}">

            <select
                name="month"
                class="form-select"
                onchange="this.form.submit()">

                <option value="all" {{ $month == 'all' ? 'selected' : '' }}>
                    All Months
                </option>

                <option value="january" {{ $month == 'january' ? 'selected' : '' }}>
                    January
                </option>

                <option value="february" {{ $month == 'february' ? 'selected' : '' }}>
                    February
                </option>

                <option value="march" {{ $month == 'march' ? 'selected' : '' }}>
                    March
                </option>

                <option value="april" {{ $month == 'april' ? 'selected' : '' }}>
                    April
                </option>

                <option value="may" {{ $month == 'may' ? 'selected' : '' }}>
                    May
                </option>

                <option value="june" {{ $month == 'june' ? 'selected' : '' }}>
                    June
                </option>

            </select>

            <button type="submit" class="btn btn-primary">
                <i class="bi bi-search"></i>
            </button>

        </form>

    </div>

    <div class="card shadow-sm">

        <div class="card-body">

            <!-- SCROLL CONTAINER -->
            <div class="table-responsive" style="max-height: 70vh; overflow: auto;">

                <table class="table table-sm table-bordered table-hover table-striped align-middle">

                    <thead class="table-dark sticky-top">
                        <tr>
                            <th>Date Received</th>
                            <th>Date Processed</th>
                            <th>OBR Date</th>
                            <th>OBR No.</th>
                            <th>DV No.</th>
                            <th>Payee</th>
                            <th>Particulars</th>
                            <th>UACS Code</th>
                            <th>Debit</th>
                            <th>Credit</th>
                            <th>Tax %</th>
                            <th>Status</th>
                            <th>Date Signed</th>
                            <th>Date Forwarded</th>
                        </tr>
                    </thead>

                    <tbody>

                    @forelse($records as $record)

                        <tr>
                            <td>{{ $record->date_received ?? '-' }}</td>
                            <td>{{ $record->date_processed ?? '-' }}</td>
                            <td>{{ $record->obr_date ?? '-' }}</td>
                            <td>{{ $record->obr_no ?? '-' }}</td>
                            <td>{{ $record->dv_no ?? '-' }}</td>
                            <td>{{ $record->payee ?? '-' }}</td>
                            <td>{{ $record->particulars ?? '-' }}</td>
                            <td>{{ $record->uacs_code ?? '-' }}</td>

                            <td>
                                ₱{{ number_format((float) str_replace(',', '', $record->debit ?? 0), 2) }}
                            </td>

                            <td>
                                ₱{{ number_format((float) str_replace(',', '', $record->credit ?? 0), 2) }}
                            </td>

                            <td>{{ $record->tax_percent ?? '-' }}</td>
                            <td>{{ $record->status ?? '-' }}</td>
                            <td>{{ $record->date_signed ?? '-' }}</td>
                            <td>{{ $record->date_forwarded ?? '-' }}</td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="14" class="text-center">
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
    $pageTitle = 'Logbook';
@endphp