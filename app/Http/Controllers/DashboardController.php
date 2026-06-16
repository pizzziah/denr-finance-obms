<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return match ($user->role) {

            'admin' => view('dashboards.admin'),

            'accountant' => view('dashboards.accountant'),

            'budget' => view('dashboards.budget'),

            'bookkeeper' => view('dashboards.bookkeeper'),

            default => abort(403, 'Unauthorized role'),
        };
    }
}