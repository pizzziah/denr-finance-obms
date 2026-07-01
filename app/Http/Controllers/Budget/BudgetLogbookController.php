<?php

namespace App\Http\Controllers\Budget;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BudgetLogbookController extends Controller
{
    public function logbook(Request $request)
    {
        $year   = $request->year ?? 'all';
        $month  = $request->month;
        $status = $request->status ?? 'all';
        $search = $request->search;
        $sort   = $request->sort ?? 'latest';

        $statusText = match ($status) {
            'for_obligation' => 'For Obligation',
            'forwarded_to_accounting' => 'Forwarded to Accounting',
            'forwarded_to_cashier' => 'Forwarded to Cashier',
            'all' => null,
            default => ucwords(str_replace('_', ' ', $status)),
        };

        $query = DB::table('odms_budget');

        // YEAR
        if ($year != 'all') {
            $query->whereYear('date_received', $year);
        }

        // MONTH
        if ($month) {
            $query->whereMonth('date_received', $month);
        }

        // STATUS
        if ($statusText) {
            $query->where('status', $statusText);
        }

        // SEARCH
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('ors_no', 'like', "%{$search}%")
                ->orWhere('payee', 'like', "%{$search}%")
                ->orWhere('issuing_office', 'like', "%{$search}%")
                ->orWhere('classification', 'like', "%{$search}%")
                ->orWhere('uac_codes', 'like', "%{$search}%")
                ->orWhere('particulars', 'like', "%{$search}%")
                ->orWhere('status', 'like', "%{$search}%")
                ->orWhere('final_remarks', 'like', "%{$search}%");
            });
        }

        // SORT
        switch ($sort) {

            case 'ors_asc':
                $query->orderBy('ors_no');
                break;

            case 'ors_desc':
                $query->orderByDesc('ors_no');
                break;

            default:
                $query->orderByDesc('date_received');
                break;
        }

        $records = $query->get();

        return view(
            'budget.logbook',
            compact(
                'records',
                'year',
                'month',
                'status',
                'search',
                'sort'
            )
        );
    }
    
    public function show($budget_id)
    {
        $record = DB::table('odms_budget')
            ->where('budget_id', $budget_id)
            ->first();

        return response()->json($record);
    }

    public function details($budget_id)
    {
        $record = DB::table('odms_budget')
            ->where('budget_id', $budget_id)
            ->first();

        if (!$record) {
            return response()->json([
                'message' => 'Record not found'
            ], 404);
        }

        return response()->json($record);
    }

    public function update(Request $request, $budget_id)
    {
        $request->validate([
            'status' => 'required'
        ]);

        DB::table('odms_budget')
            ->where('budget_id', $budget_id)
            ->update([
                'status' => $request->status
            ]);

        return redirect()
            ->route('budget.logbook')
            ->with('success', 'Record updated successfully.');
    }
    public function destroy($budget_id)
    {
        DB::table('odms_budget')
            ->where('budget_id', $budget_id)
            ->delete();

        return redirect()
            ->route('budget.logbook')
            ->with('success', 'Record deleted successfully.');
    }
}