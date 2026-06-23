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
        $month = $request->month ?? null;
        $status = $request->status ?? 'all';
        $search = $request->search ?? null;
        $sort = $request->sort ?? 'latest';

        $statusText = match ($status) {
            'for_obligation' => 'For Obligation',
            'forwarded_to_accounting' => 'Forwarded to Accounting',
            'all' => null,
            default => ucwords(str_replace('_', ' ', $status))
        };

        $records = collect();

        // ================= ALL YEARS =================
        if ($year == 'all') {

            $query2025 = DB::table('odms_budget_2025');
            $query2026 = DB::table('odms_budget_2026');

            // STATUS FILTER
            if ($statusText) {
                $query2025->where('status', $statusText);
                $query2026->where('status', $statusText);
            }

            // MONTH FILTER
            if ($month) {
                $query2025->whereRaw("CAST(SUBSTRING_INDEX(date_received, '/', 1) AS UNSIGNED) = ?",[(int)$month]);
                $query2026->whereRaw("CAST(SUBSTRING_INDEX(date_received, '/', 1) AS UNSIGNED) = ?",[(int)$month]);
            }

            // SEARCH FILTER
            if ($search) {

                $query2025->where(function ($q) use ($search) {
                    $q->where('ors_no', 'like', "%{$search}%")
                        ->orWhere('payee', 'like', "%{$search}%")
                        ->orWhere('issuing_office', 'like', "%{$search}%")
                        ->orWhere('classification', 'like', "%{$search}%")
                        ->orWhere('particulars', 'like', "%{$search}%")
                        ->orWhere('status', 'like', "%{$search}%")
                        ->orWhere('final_remarks', 'like', "%{$search}%")
                        ->orWhere('date_returned_1', 'like', "%{$search}%")
                        ->orWhere('date_received_1', 'like', "%{$search}%")
                        ->orWhere('date_forwarded_1', 'like', "%{$search}%")
                        ->orWhere('date_ors_received', 'like', "%{$search}%")
                        ->orWhere('date_returned_2', 'like', "%{$search}%")
                        ->orWhere('date_received_2', 'like', "%{$search}%")
                        ->orWhere('date_forwarded_accounting', 'like', "%{$search}%")
                        ->orWhere('total_time_budget', 'like', "%{$search}%")
                        ->orWhere('total_time', 'like', "%{$search}%");
                });

                $query2026->where(function ($q) use ($search) {
                    $q->where('ors_no', 'like', "%{$search}%")
                        ->orWhere('payee', 'like', "%{$search}%")
                        ->orWhere('issuing_office', 'like', "%{$search}%")
                        ->orWhere('classification', 'like', "%{$search}%")
                        ->orWhere('particulars', 'like', "%{$search}%")
                        ->orWhere('particulars_remark', 'like', "%{$search}%")
                        ->orWhere('status', 'like', "%{$search}%")
                        ->orWhere('final_remarks', 'like', "%{$search}%")
                        ->orWhere('date_returned_1', 'like', "%{$search}%")
                        ->orWhere('date_received_1', 'like', "%{$search}%")
                        ->orWhere('date_forwarded_1', 'like', "%{$search}%")
                        ->orWhere('date_ors_received', 'like', "%{$search}%")
                        ->orWhere('date_returned_2', 'like', "%{$search}%")
                        ->orWhere('date_received_2', 'like', "%{$search}%")
                        ->orWhere('date_forwarded_accounting', 'like', "%{$search}%")
                        ->orWhere('total_time_budget', 'like', "%{$search}%")
                        ->orWhere('total_time', 'like', "%{$search}%");
                });
            }

            $records = $query2025->get()
                ->concat($query2026->get());

            if ($sort == 'ors_asc') {

                $records = $records->sortBy(function ($record) {
                    return strtoupper($record->ors_no ?? '');
                });

            } elseif ($sort == 'ors_desc') {

                $records = $records->sortByDesc(function ($record) {
                    return strtoupper($record->ors_no ?? '');
                });

            } else {

                $records = $records->sortByDesc('date_received');
            }
        }

        // ================= SINGLE YEAR =================
        else {

            $table = $year == '2025'
                ? 'odms_budget_2025'
                : 'odms_budget_2026';

            $query = DB::table($table);

            // STATUS FILTER
            if ($statusText) {
                $query->where('status', $statusText);
            }

            // MONTH FILTER
            if ($month) {
                $query->whereRaw("CAST(SUBSTRING_INDEX(date_received, '/', 1) AS UNSIGNED) = ?",[(int)$month]);
            }
 
            // SEARCH FILTER
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('ors_no', 'like', "%{$search}%")
                        ->orWhere('payee', 'like', "%{$search}%")
                        ->orWhere('issuing_office', 'like', "%{$search}%")
                        ->orWhere('classification', 'like', "%{$search}%")
                        ->orWhere('particulars', 'like', "%{$search}%")
                        ->orWhere('particulars_remark', 'like', "%{$search}%")
                        ->orWhere('status', 'like', "%{$search}%")
                        ->orWhere('final_remarks', 'like', "%{$search}%")
                        ->orWhere('date_returned_1', 'like', "%{$search}%")
                        ->orWhere('date_received_1', 'like', "%{$search}%")
                        ->orWhere('date_forwarded_1', 'like', "%{$search}%")
                        ->orWhere('date_ors_received', 'like', "%{$search}%")
                        ->orWhere('date_returned_2', 'like', "%{$search}%")
                        ->orWhere('date_received_2', 'like', "%{$search}%")
                        ->orWhere('date_forwarded_accounting', 'like', "%{$search}%")
                        ->orWhere('total_time_budget', 'like', "%{$search}%")
                        ->orWhere('total_time', 'like', "%{$search}%");
                });
            }

            if ($sort == 'ors_asc') {

                $records = $query
                    ->orderBy('ors_no', 'asc')
                    ->get();

            } elseif ($sort == 'ors_desc') {

                $records = $query
                    ->orderBy('ors_no', 'desc')
                    ->get();

            } else {

                $records = $query
                    ->orderByDesc('date_received')
                    ->get();
            }
        }

        return view('budget.logbook',compact('records','year','month','status','search', 'sort'));
    }
}