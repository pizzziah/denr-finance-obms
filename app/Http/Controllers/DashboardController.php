<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Budget\BudgetDashboard;
use App\Models\Accounting\AccountingDashboard;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller {
    public function index() {
        $user = Auth::user();

        return match ($user->role) {
            'admin' => view('admin.dashboard'),
            'accountant', 'bookkeeper' => view('accounting.dashboard', [
                'user'    => $user,
                'metrics' => AccountingDashboard::getMetrics()
            ]),
            'budget' => view('budget.dashboard', [
                'user' => $user,
                'metrics' => BudgetDashboard::getMetrics()
            ]),
            default => abort(403, 'Unauthorized role'),
        };
    }
}