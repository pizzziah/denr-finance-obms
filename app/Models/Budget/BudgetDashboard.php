<?php

namespace App\Models\Budget;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Schema;

class BudgetDashboard
{
    public static function getMetrics()
    {
        // Define tables to query
        $tables = ['odms_budget_2025', 'odms_budget_2026'];
        
        $totalRequests = 0;
        $totalRequestedAmount = 0;
        $amountInProcess = 0;
        $amountForwarded = 0;

        // Initialize count array matching the specific layout indicators
        $statusCounts = [
            'for_review' => 0,
            'pending' => 0,
            'processing' => 0,
            'for_obligation' => 0,
            'returned' => 0,
            'cancelled' => 0,
            'forwarded' => 0,
            'paid' => 0,
        ];

        foreach ($tables as $table) {
            if (!Schema::hasTable($table)) continue;

            $rows = DB::table($table)->get();
            $totalRequests += $rows->count();

            foreach ($rows as $row) {
                $debit = (float)($row->debit ?? 0);
                $status = strtolower(trim($row->status ?? ''));

                $totalRequestedAmount += $debit;

                // Map metrics based on workflow/status columns
                if (in_array($status, ['processing', 'pending', 'for review', 'for obligation'])) {
                    $amountInProcess += $debit;
                }
                if (str_contains($status, 'forwarded to accounting') || $status === 'forwarded') {
                    $amountForwarded += $debit;
                }

                // Increment detailed layout workflow rings
                if ($status === 'for review') $statusCounts['for_review']++;
                elseif ($status === 'pending') $statusCounts['pending']++;
                elseif ($status === 'processing') $statusCounts['processing']++;
                elseif ($status === 'for obligation') $statusCounts['for_obligation']++;
                elseif ($status === 'returned') $statusCounts['returned']++;
                elseif ($status === 'cancelled') $statusCounts['cancelled']++;
                elseif ($status === 'forwarded') $statusCounts['forwarded']++;
                elseif ($status === 'paid') $statusCounts['paid']++;
            }
        }

        // Fetch recent records preview for the Log Book preview pane
        $recentLogs = collect();
        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                $recentLogs = $recentLogs->concat(DB::table($table)->get());
            }
        }
        $recentLogs = $recentLogs->sortByDesc('date_received')->take(5);

        return [
            'totalRequests' => $totalRequests,
            'totalRequestedAmount' => $totalRequestedAmount,
            'amountInProcess' => $amountInProcess,
            'amountForwarded' => $amountForwarded,
            'statusCounts' => $statusCounts,
            'recentLogs' => $recentLogs
        ];
    }
}