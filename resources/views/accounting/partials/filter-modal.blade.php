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

                    {{-- MONTH --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Month</label>

                        <select name="month" class="form-select">
                            <option value="all" @selected(request('month','all')=='all')>
                                All Months
                            </option>

                            <option value="1" @selected(request('month')==1)>January</option>
                            <option value="2" @selected(request('month')==2)>February</option>
                            <option value="3" @selected(request('month')==3)>March</option>
                            <option value="4" @selected(request('month')==4)>April</option>
                            <option value="5" @selected(request('month')==5)>May</option>
                            <option value="6" @selected(request('month')==6)>June</option>
                            <option value="7" @selected(request('month')==7)>July</option>
                            <option value="8" @selected(request('month')==8)>August</option>
                            <option value="9" @selected(request('month')==9)>September</option>
                            <option value="10" @selected(request('month')==10)>October</option>
                            <option value="11" @selected(request('month')==11)>November</option>
                            <option value="12" @selected(request('month')==12)>December</option>
                        </select>
                    </div>

                    {{-- STATUS --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Status</label>

                        <select name="status" class="form-select">
                            <option value="all" @selected(request('status','all')=='all')>
                                All Status
                            </option>

                            <option value="Pending" @selected(request('status')=='Pending')>
                                Pending
                            </option>

                            <option value="Processing" @selected(request('status')=='Processing')>
                                Processing
                            </option>

                            <option value="Returned" @selected(request('status')=='Returned')>
                                Returned
                            </option>

                            <option value="Forwarded to Cashier" @selected(request('status')=='Forwarded to Cashier')>
                                Forwarded to Cashier
                            </option>

                            <option value="Paid" @selected(request('status')=='Paid')>
                                Paid
                            </option>

                            <option value="Canceled" @selected(request('status')=='Canceled')>
                                Canceled
                            </option>
                        </select>
                    </div>

                    {{-- SORT --}}
                    <div class="mb-2">
                        <label class="form-label fw-semibold">Sort By</label>

                        <select name="sort" class="form-select">

                            <option value="latest"
                                @selected(request('sort','latest')=='latest')>
                                Latest Date Processed
                            </option>

                            <option value="obr_asc"
                                @selected(request('sort')=='obr_asc')>
                                DV No. (Ascending)
                            </option>

                            <option value="obr_desc"
                                @selected(request('sort')=='obr_desc')>
                                DV No. (Descending)
                            </option>

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
