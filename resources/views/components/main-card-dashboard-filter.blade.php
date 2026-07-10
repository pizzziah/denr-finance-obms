@props([
  'selectedYear' => null,
  'selectedMonth' => null,
  'emptyMonthLabel' => '—',
])

<div class="bg-white p-2 rounded shadow-sm d-flex flex-column flex-sm-row align-items-sm-center gap-2 m-0" style="min-width: 320px;">
  <form method="GET" class="m-0 d-flex gap-2 flex-grow-1 align-items-center">
    <select name="year" class="form-select form-select-sm border-0 fw-bold bg-light" style="color: #044709; cursor: pointer;">
      @for($year = now()->year; $year >= 2025; $year--)
      <option value="{{ $year }}" {{ ($selectedYear ?? '') == $year ? 'selected' : '' }}>
        {{ $year }}
      </option>
      @endfor
    </select>
    
    <select name="month" class="form-select form-select-sm border-0 fw-bold bg-light" style="color: #044709; cursor: pointer;">
      <option value="">{{ $emptyMonthLabel }}</option>
      @for($m = 1; $m <= 12; $m++)
      @php $mVal = sprintf('%02d', $m); @endphp
      <option value="{{ $mVal }}" {{ ($selectedMonth ?? '') == $mVal ? 'selected' : '' }}>
        {{ DateTime::createFromFormat('!m', $m)->format('F') }}
      </option>
      @endfor
    </select>
    
    <x-button variant="primary" type="submit" class="fw-bold">
      Filter
    </x-button>
  </form>
</div>