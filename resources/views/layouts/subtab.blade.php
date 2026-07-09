<div class="d-flex flex-wrap gap-3 mb-4">
    {{-- ACCOUNTING --}}
    @if(in_array(strtolower(Auth::user()->role), ['accountant', 'bookkeeper']))
        <x-subtab-link status="all" label="All"/>
        <x-subtab-link status="pending" label="Pending"/>
        <x-subtab-link status="processing" label="Processing"/>
        <x-subtab-link status="returned" label="Returned"/>
        <x-subtab-link status="Forwarded to Cashier" label="Forwarded to Cashier"/>
        <x-subtab-link status="paid" label="Paid"/>
    @endif

    {{-- BUDGET --}}
    @if(strtolower(Auth::user()->role) == 'budget')
        <x-subtab-link status="all" label="All"/>
        <x-subtab-link status="pending" label="Pending"/>
        <x-subtab-link status="processing" label="Processing"/>
        <x-subtab-link status="for_obligation" label="For Obligation"/>
        {{-- RENAMED: Changed from "Returned" to "Returned to End User" --}}
        <x-subtab-link status="returned" label="Returned to End User"/>
        {{-- ADDED: New structural layout tracking filter tag link component --}}
        <x-subtab-link status="returned_by_accounting" label="Returned by Accounting"/>
        <x-subtab-link status="forwarded_to_accounting" label="Forwarded to Accounting"/>
        <x-subtab-link status="paid" label="Paid"/>
    @endif
</div>