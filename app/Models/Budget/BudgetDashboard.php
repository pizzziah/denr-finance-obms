<?php

namespace App\Models\Budget;

use Illuminate\Support\Facades\DB;

class BudgetDashboard
{
    public static function getMetrics()
    {
        $currentYear = intval(request('year', now()->year));

        // BASE QUERY (NEW DESIGN)=
        $query = DB::table('odms_budget');

        // YEAR FILTER (IMPORTANT CHANGE)
        if (request('year') && request('year') !== 'all') {
            $query->whereYear('date_received', $currentYear);
        }

        // INIT METRICS
        $officeAmounts = [];
        $totalTransactions = 0;
        $totalRequestedAmount = 0;
        $amountInProcess = 0;
        $amountForwarded = 0;
        $totalAmountPaid = 0;

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

        // STREAM DATA (NO TABLE LOOP)
        $query->orderBy('budget_id')
            ->chunk(500, function ($rows) use (
                &$officeAmounts,
                &$totalTransactions,
                &$totalRequestedAmount,
                &$amountInProcess,
                &$amountForwarded,
                &$totalAmountPaid,
                &$statusCounts
            ) {

                foreach ($rows as $row) {

                    $totalTransactions++;
                    // CLEAN AMOUNT
                    $amount = (float) str_replace(
                        [',', '₱', ' '],
                        '',
                        $row->amount ?? 0
                    );

                    $status = strtolower(trim($row->status ?? ''));
                    $office = strtoupper(trim($row->issuing_office ?? ''));

                    $totalRequestedAmount += $amount;

                    // STATUS FLAGS
                    $isForwarded = str_contains($status, 'forwarded');
                    $isReturned = str_contains($status, 'returned');
                    $isForObligation = str_contains($status, 'obligation');
                    $isPaid = str_contains($status, 'paid');

                    // OFFICE TOTALS
                    if ($isForwarded && $office) {
                        $officeAmounts[$office] =
                            ($officeAmounts[$office] ?? 0) + $amount;
                    }
                    // IN PROCESS
                    if (
                        str_contains($status, 'pending') ||
                        str_contains($status, 'processing') ||
                        $isForObligation ||
                        $isReturned ||
                        str_contains($status, 'review')
                    ) {
                        $amountInProcess += $amount;
                    }

                    // FORWARDED
                    if ($isForwarded) {
                        $amountForwarded += $amount;
                        $statusCounts['forwarded']++;
                    }

                    // PAID
                    if ($isPaid) {
                        $totalAmountPaid += $amount;
                        $statusCounts['paid']++;
                    }

                    // STATUS COUNTS
                    if (str_contains($status, 'review')) {
                        $statusCounts['for_review']++;
                    } elseif (str_contains($status, 'pending')) {
                        $statusCounts['pending']++;
                    } elseif (str_contains($status, 'processing')) {
                        $statusCounts['processing']++;
                    } elseif ($isForObligation) {
                        $statusCounts['for_obligation']++;
                    } elseif ($isReturned) {
                        $statusCounts['returned']++;
                    } elseif (str_contains($status, 'cancel')) {
                        $statusCounts['cancelled']++;
                    }
                }
            });

        // SORT OFFICES
        arsort($officeAmounts);

        return [
            'totalTransactions' => $totalTransactions,
            'totalRequestedAmount' => $totalRequestedAmount,
            'amountInProcess' => $amountInProcess,
            'amountForwarded' => $amountForwarded,
            'totalAmountPaid' => $totalAmountPaid,
            'statusCounts' => $statusCounts,
            'officeAmounts' => $officeAmounts,
        ];
    }
}
