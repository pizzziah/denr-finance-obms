@extends('layouts.app')

@section('title', 'Accounting Log Book')

@section('content')

<div class="container mt-5">

    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">

        @include('layouts.subtab')

        <div class="d-flex gap-2">

            {{-- SEARCH --}}
            <form method="GET"
                  action="{{ route('accounting.logbook') }}"
                  class="d-flex gap-2 align-items-center">

                <input
                    type="text"
                    name="search"
                    class="form-control"
                    placeholder="Search DV, Payee, Office..."
                    value="{{ request('search') }}">

                {{-- Preserve Filters --}}
                <input type="hidden"
                       name="month"
                       value="{{ request('month', 'all') }}">

                <input type="hidden"
                       name="status"
                       value="{{ request('status', 'all') }}">

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

    <div class="card shadow-sm">

        <div class="card-body">

            <div class="table-responsive"
                 style="max-height:70vh; overflow:auto;">

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

{{-- FILTER MODAL --}}
<div class="modal fade" id="filterModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <form method="GET"
                  action="{{ route('accounting.logbook') }}">

                {{-- Preserve Search --}}
                <input type="hidden"
                       name="search"
                       value="{{ request('search') }}">

                <div class="modal-header">
                    <h5 class="modal-title">
                        Filter Logbook
                    </h5>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal">
                    </button>
                </div>

                <div class="modal-body">

                    {{-- MONTH --}}
                    <div class="mb-3">

                        <label class="form-label">
                            Month
                        </label>

                        <select name="month"
                                class="form-select">

                            <option value="all"
                                @selected(request('month','all')=='all')>
                                All Months
                            </option>

                            <option value="january"
                                @selected(request('month')=='january')>
                                January
                            </option>

                            <option value="february"
                                @selected(request('month')=='february')>
                                February
                            </option>

                            <option value="march"
                                @selected(request('month')=='march')>
                                March
                            </option>

                            <option value="april"
                                @selected(request('month')=='april')>
                                April
                            </option>

                            <option value="may"
                                @selected(request('month')=='may')>
                                May
                            </option>

                            <option value="june"
                                @selected(request('month')=='june')>
                                June
                            </option>

                        </select>

                    </div>

                    {{-- STATUS --}}
                    <div class="mb-3">

                        <label class="form-label">
                            Status
                        </label>

                        <select name="status"
                                class="form-select">

                            <option value="all"
                                @selected(request('status','all')=='all')>
                                All Status
                            </option>

                            <option value="Pending"
                                @selected(request('status')=='Pending')>
                                Pending
                            </option>

                            <option value="Processing"
                                @selected(request('status')=='Processing')>
                                Processing
                            </option>

                            <option value="Completed"
                                @selected(request('status')=='Completed')>
                                Completed
                            </option>

                        </select>

                    </div>

                </div>

                <div class="modal-footer">

                    <a href="{{ route('accounting.logbook') }}"
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
</div>

@endsection

@php
$pageTitle = 'Logbook';
@endphp