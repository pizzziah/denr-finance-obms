<?php

namespace App\Http\Controllers\Budget;

use App\Http\Controllers\Controller;
use App\Models\Budget\BudgetDashboard;
use Illuminate\Http\Request;

class BudgetDashboardController extends Controller
{
    public static function dashboard()
    {
        $metrics = BudgetDashboard::getMetrics();

        return view('budget.dashboard', compact('metrics'));
    }
}