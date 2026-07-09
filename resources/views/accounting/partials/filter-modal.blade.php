{{-- FILTER MODAL --}}
<div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <form method="GET" action="{{ route('accounting.logbook') }}">

                {{-- Preserve Search --}}
                <input type="hidden" name="search" value="{{ request('search') }}">

                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="filterModalLabel">
                        <i class="bi bi-funnel"></i> Filter Accounting Logbook
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    {{-- YEAR --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">Year</label>

                        <select name="year" class="form-select">

                            <option value="all"
                                @selected(request('year','all')=='all')>
                                All Years
                            </option>

                            <option value="2026"
                                @selected(request('year')=='2026')>
                                2026
                            </option>

                            <option value="2025"
                                @selected(request('year')=='2025')>
                                2025
                            </option>

                        </select>
                    </div>


                    {{-- MONTH --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">Month</label>

                        <select name="month" class="form-select">

                            <option value="all"
                                @selected(request('month','all')=='all')>
                                All Months
                            </option>

                            <option value="1" @selected(request('month')=='1')>January</option>
                            <option value="2" @selected(request('month')=='2')>February</option>
                            <option value="3" @selected(request('month')=='3')>March</option>
                            <option value="4" @selected(request('month')=='4')>April</option>
                            <option value="5" @selected(request('month')=='5')>May</option>
                            <option value="6" @selected(request('month')=='6')>June</option>
                            <option value="7" @selected(request('month')=='7')>July</option>
                            <option value="8" @selected(request('month')=='8')>August</option>
                            <option value="9" @selected(request('month')=='9')>September</option>
                            <option value="10" @selected(request('month')=='10')>October</option>
                            <option value="11" @selected(request('month')=='11')>November</option>
                            <option value="12" @selected(request('month')=='12')>December</option>

                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <a href="{{ route('accounting.logbook') }}"
                       class="btn btn-outline-secondary">
                        Reset
                    </a>

                    <button type="submit"
                            class="btn btn-success">
                        <i class="bi bi-check-circle"></i>
                        Apply Filters
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
