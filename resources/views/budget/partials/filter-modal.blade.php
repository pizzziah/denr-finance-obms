{{-- FILTER MODAL --}}
<div class="modal fade" id="filterModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:700px;">
        <div class="modal-content">
            <form method="GET" action="{{ route('budget.logbook') }}">

                {{-- Preserve current filters --}}
                <input type="hidden" name="status" value="{{ request('status', 'all') }}">
                <input type="hidden" name="search" value="{{ request('search') }}">
                <input type="hidden" name="sort" value="{{ request('sort', 'latest') }}">

                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title d-flex align-items-center gap-2">
                            <i class="bi bi-funnel"></i>
                            <span>Filter Records</span>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="col-md-8 mb-3">
                            <label class="form-label"><strong>Year</strong></label>
                            <select name="year" class="form-select">
                                <option value="all" @selected(request('year','all')=='all')>All</option>
                                <option value="2025" @selected(request('year')=='2025')>2025</option>
                                <option value="2026" @selected(request('year')=='2026')>2026</option>
                            </select>
                        </div>

                        <div class="col-md-8 mb-3">
                            <label class="form-label"><strong>Month</strong></label>
                            <select name="month" class="form-select">
                                <option value="all" @selected(request('month','all')=='all')>All Months</option>

                                @for($m = 1; $m <= 12; $m++)
                                    <option value="{{ $m }}" @selected(request('month') == $m)>
                                        {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                    </option>
                                @endfor
                            </select>
                        </div>

                        <div class="col-md-8 mb-3">
                            <label class="form-label"><strong>Day</strong></label>
                            <select name="day" class="form-select">
                                <option value="all" @selected(request('day','all')=='all')>
                                    All Days
                                </option>

                                @for($d = 1; $d <= 31; $d++)
                                    <option value="{{ $d }}" @selected(request('day') == $d)>
                                        {{ $d }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    

                    <div class="modal-footer">
                        <a href="{{ route('budget.logbook', ['status' => request('status', 'all')]) }}"
                        class="btn btn-outline-secondary">
                            Reset
                        </a>

                        <button class="btn btn-success" type="submit">
                            Apply Filters
                        </button>
                    </div>

                </div>
            </form>
         </div>
    </div>
</div>