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

        if ($year == '2025') {

            $records = DB::table('odms_budget_2025')
                ->orderByDesc('date_received')
                ->get();

        } elseif ($year == '2026') {

            $records = DB::table('odms_budget_2026')
                ->orderByDesc('date_received')
                ->get();

        } else {

            $records2025 = DB::table('odms_budget_2025')->get();
            $records2026 = DB::table('odms_budget_2026')->get();

            $records = $records2025
                ->concat($records2026)
                ->sortByDesc('date_received');
        }

        return view('budget.logbook', compact('records', 'year'));
    }
}