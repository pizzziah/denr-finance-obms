@extends('layouts.app')

@php
$pageTitle = 'Accounting Log Book';
@endphp

@section('content')

<div class="container-fluid mt-4">

```
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Accounting Log Book</h3>

    <form method="GET" action="{{ route('accounting.logbook') }}">
        <select name="month"
                class="form-select"
                onchange="this.form.submit()"
                style="width: 200px;">
                <option value="all" @selected($month == 'all')>All Months</option>
                <option value="january" @selected($month ==  'january')>January</option>
                <option value="february" @selected($month == 'february')>February</option>
                <option value="march" @selected($month == 'march')>March</option>
                <option value="april" @selected($month == 'april')>April</option>
                <option value="may" @selected($month == 'may')>May</option>
                <option value="june" @selected($month == 'june')>June</option>

        </select>
    </form>
</div>

<div class="card shadow-sm">

    <div class="card-body">

        <div style="max-height: 600px; overflow-y: auto; overflow-x: auto;">

            <table class="table table-bordered table-hover table-striped">

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
```

</div>

@endsection