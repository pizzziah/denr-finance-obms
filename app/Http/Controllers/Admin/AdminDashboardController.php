<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\AdminDashboard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller {
  public function index() {
    $metrics = AdminDashboard::getDashboardMetrics();
    $currentUser = Auth::user();

    $pendingUnlocks = DB::table('odms_admin_quarter_locks')->where('requires_admin_unlock', true)->get();
    return view('admin.dashboard', compact('metrics', 'currentUser', 'pendingUnlocks'));
  }
}