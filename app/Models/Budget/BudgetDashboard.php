<?php

namespace App\Models\Budget;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class BudgetDashboard {
  public static function getMetrics() {
    $tables = ['odms_budget_2025', 'odms_budget_2025_2', 'odms_budget_2026'];

    $currentYear = now()->year;
        
    $officeAmounts = [];
    $totalTransactions = 0;
    $totalRequestedAmount = 0;
    $amountInProcess = 0;
    $amountForwarded = 0;
    $totalAmountPaid = 0;

    $statusCounts = [
      'for_review'     => 0,
      'pending'        => 0,
      'processing'     => 0,
      'for_obligation' => 0,
      'returned'       => 0,
      'cancelled'      => 0,
      'forwarded'      => 0,
      'paid'           => 0,
    ];

    foreach ($tables as $table) {
      if (!Schema::hasTable($table)) continue;

      $rows = DB::table($table)->get();
        foreach ($rows as $row) {
          $recordYear = null;

          if (!empty($row->date_received)) {
            try {
              $recordYear = \Carbon\Carbon::parse($row->date_received)->year;
            } catch (\Exception $e) {
              continue; 
            }
          }

          if ($recordYear != $currentYear) {
            continue;
          }

          $totalTransactions++;
          $amount = (float)($row->amount ?? 0);
          $status = strtolower(trim($row->status ?? ''));
          $office = trim($row->issuing_office ?? '');

          // BAR CHART DATA MATRIX
          if ($status === 'forwarded to accounting' && !empty($office)) {
          if (!isset($officeAmounts[$office])) {
            $officeAmounts[$office] = 0;
          }
            $officeAmounts[$office] += $amount;
          }

          $totalRequestedAmount += $amount;

          if (in_array($status, [
            'pending',
            'processing',
            'for obligation',
            'returned to end user',
            'for completion of attachment'
          ])) {
            $amountInProcess += $amount;
          }

          if ($status === 'forwarded to accounting') {
            $amountForwarded += $amount;
          }

          if ($status === 'paid') {
            $totalAmountPaid += $amount;
          }

          if ($status === 'for review') $statusCounts['for_review']++;
          elseif ($status === 'pending') $statusCounts['pending']++;
          elseif ($status === 'processing') $statusCounts['processing']++;
          elseif ($status === 'for obligation') $statusCounts['for_obligation']++;
          elseif (str_contains($status, 'returned')) $statusCounts['returned']++;
          elseif ($status === 'cancelled') $statusCounts['cancelled']++;
          elseif ($status === 'forwarded to accounting' || $status === 'forwarded') $statusCounts['forwarded']++;
          elseif ($status === 'paid') $statusCounts['paid']++;
          }
      }

    arsort($officeAmounts);
    return [
      'totalTransactions'    => $totalTransactions,
      'totalRequestedAmount' => $totalRequestedAmount,
      'amountInProcess'      => $amountInProcess,
      'amountForwarded'      => $amountForwarded,
      'totalAmountPaid'      => $totalAmountPaid,
      'statusCounts'         => $statusCounts,
      'officeAmounts'        => $officeAmounts,
    ];
  }
}