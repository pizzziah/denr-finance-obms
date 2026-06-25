<?php

namespace App\Models\Accounting;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AccountingDashboard {
  public static function getMetrics() {
    $table = 'odms_accounting';
        
    $totalRequests = 0;
    $totalRequestedAmount = 0; // mapped from gross_amount or total_amount/debit if applicable. Let's inspect column named 'debit' or 'amount' safely using structural fallbacks.
    $amountInProcess = 0;
    $amountPaid = 0;

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

        if (Schema::hasTable($table)) {
            $rows = DB::table($table)->get();
            $totalRequests = $rows->count();

            foreach ($rows as $row) {
                // Adjust to fallback checking common numeric layout columns like 'debit' or 'amount'
                $amount = (float)($row->debit ?? $row->amount ?? 0);
                $status = strtolower(trim($row->status ?? ''));

                $totalRequestedAmount += $amount;

                // Map general workflow blocks
                if (in_array($status, ['processing', 'pending', 'for review'])) {
                    $amountInProcess += $amount;
                }
                if ($status === 'paid' || $status === 'completed') {
                    $amountPaid += $amount;
                }

                // Map specific status counts 
                if ($status === 'for review') $statusCounts['for_review']++;
                elseif ($status === 'pending') $statusCounts['pending']++;
                elseif ($status === 'processing') $statusCounts['processing']++;
                elseif ($status === 'for obligation') $statusCounts['for_obligation']++;
                elseif ($status === 'returned') $statusCounts['returned']++;
                elseif ($status === 'cancelled') $statusCounts['cancelled']++;
                elseif (str_contains($status, 'forwarded')) $statusCounts['forwarded']++;
                elseif ($status === 'paid' || $status === 'completed') $statusCounts['paid']++;
            }
        }

        // Fetch top recent log items sorted by processing date
        $recentLogs = collect();
        if (Schema::hasTable($table)) {
            $recentLogs = DB::table($table)
                ->orderByDesc('date_processed')
                ->take(5)
                ->get();
        }

        return [
            'totalRequests' => $totalRequests,
            'totalRequestedAmount' => $totalRequestedAmount,
            'amountInProcess' => $amountInProcess,
            'amountPaid' => $amountPaid,
            'statusCounts' => $statusCounts,
            'recentLogs' => $recentLogs
        ];
    }
}