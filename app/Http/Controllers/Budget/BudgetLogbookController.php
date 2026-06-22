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

        // Convert URL status to database status
        $statusText = match ($status) {
            'for_obligation' => 'For Obligation',
            'forwarded_to_accounting' => 'Forwarded to Accounting',
            'all' => null,
            default => ucwords(str_replace('_', ' ', $status))
        };

        // ALL YEARS
        if ($year == 'all') {

            $records = DB::table('odms_budget_2025')
                ->when($statusText, function ($query) use ($statusText) {
                    $query->where('status', $statusText);
                })
                ->get()

                ->concat(
                    DB::table('odms_budget_2026')
                        ->when($statusText, function ($query) use ($statusText) {
                            $query->where('status', $statusText);
                        })
                        ->get()
                )

                ->sortByDesc('date_received');

        }

        // SINGLE YEAR
        else {

            $table = $year == '2025'
                ? 'odms_budget_2025'
                : 'odms_budget_2026';

            $records = DB::table($table)
                ->when($statusText, function ($query) use ($statusText) {
                    $query->where('status', $statusText);
                })
                ->orderByDesc('date_received')
                ->get();
        }

        return view(
            'budget.logbook',
            compact('records', 'year', 'status')
        );
    }
}