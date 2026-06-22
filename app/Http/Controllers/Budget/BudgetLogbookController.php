<?php

namespace App\Http\Controllers\Budget;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BudgetLogbookController extends Controller
{
    public function logbook(Request $request)
    {
        $year = $request->year ?? 'all';
        $status = $request->status ?? 'all';
        $search = $request->search ?? null;

        $statusText = match ($status) {
            'for_obligation' => 'For Obligation',
            'forwarded_to_accounting' => 'Forwarded to Accounting',
            'all' => null,
            default => ucwords(str_replace('_', ' ', $status))
        };

        $records = collect(); // always initialize

        // ALL YEARS
        if ($year == 'all') {

            $query2025 = DB::table('odms_budget_2025');
            $query2026 = DB::table('odms_budget_2026');

            if ($statusText) {
                $query2025->where('status', $statusText);
                $query2026->where('status', $statusText);
            }

            if ($search) {
                $query2025->where(function ($q) use ($search) {
                    $q->where('ors_no', 'like', "%$search%")
                    ->orWhere('payee', 'like', "%$search%")
                    ->orWhere('issuing_office', 'like', "%$search%")
                    ->orWhere('classification', 'like', "%$search%")
                    ->orWhere('particulars', 'like', "%$search%")
                    ->orWhere('particulars_remark', 'like', "%$search%")
                    ->orWhere('status', 'like', "%$search%")
                    ->orWhere('final_remarks', 'like', "%$search%")
                    ->orWhere('date_returned_1', 'like', "%$search%")
                    ->orWhere('date_received_1', 'like', "%$search%")
                    ->orWhere('date_forwarded_1', 'like', "%$search%")
                    ->orWhere('date_ors_received', 'like', "%$search%")
                    ->orWhere('date_returned_2', 'like', "%$search%")
                    ->orWhere('date_received_2', 'like', "%$search%")
                    ->orWhere('date_forwarded_accounting', 'like', "%$search%")
                    ->orWhere('total_time_budget', 'like', "%$search%")
                    ->orWhere('total_time', 'like', "%$search%");
                });

                $query2026->where(function ($q) use ($search) {
                    $q->where('ors_no', 'like', "%$search%")
                    ->orWhere('payee', 'like', "%$search%")
                    ->orWhere('issuing_office', 'like', "%$search%")
                    ->orWhere('classification', 'like', "%$search%")
                    ->orWhere('particulars', 'like', "%$search%")
                    ->orWhere('particulars_remark', 'like', "%$search%")
                    ->orWhere('status', 'like', "%$search%")
                    ->orWhere('final_remarks', 'like', "%$search%")
                    ->orWhere('date_returned_1', 'like', "%$search%")
                    ->orWhere('date_received_1', 'like', "%$search%")
                    ->orWhere('date_forwarded_1', 'like', "%$search%")
                    ->orWhere('date_ors_received', 'like', "%$search%")
                    ->orWhere('date_returned_2', 'like', "%$search%")
                    ->orWhere('date_received_2', 'like', "%$search%")
                    ->orWhere('date_forwarded_accounting', 'like', "%$search%")
                    ->orWhere('total_time_budget', 'like', "%$search%")
                    ->orWhere('total_time', 'like', "%$search%");
                });
            }

            $records = $query2025->get()
                ->concat($query2026->get())
                ->sortByDesc('date_received');
        }

        // SINGLE YEAR
        else {

            $table = $year == '2025'
                ? 'odms_budget_2025'
                : 'odms_budget_2026';

            $query = DB::table($table);

            if ($statusText) {
                $query->where('status', $statusText);
            }

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('ors_no', 'like', "%$search%")
                    ->orWhere('payee', 'like', "%$search%")
                    ->orWhere('issuing_office', 'like', "%$search%")
                    ->orWhere('classification', 'like', "%$search%")
                    ->orWhere('particulars', 'like', "%$search%")
                    ->orWhere('status', 'like', "%$search%");
                });
            }

            $records = $query->orderByDesc('date_received')->get();
        }

        return view('budget.logbook', compact('records', 'year', 'status', 'search'));
    }
}