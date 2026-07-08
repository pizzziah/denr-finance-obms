<?php

namespace App\Models\Accounting;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AccountingDashboard {
  public static function getMetrics() {
    $currentYear = intval(request('year', now()->year));
    $currentMonth = request('month'); 
    $table = 'odms_accounting';

    $payeeAmounts = [];
    $totalTransactions = 0;
    $totalRequestedAmount = 0;
    $amountInProcess = 0;
    $amountForwarded = 0;
    $totalAmountPaid = 0;
    $totalAmountCancelled = 0; 

    $statusCounts = [
      'pending' => 0,
      'processing' => 0,
      'returned' => 0,
      'cancelled' => 0,
      'forwarded' => 0,
      'paid' => 0,
    ];

    if (! Schema::hasTable($table)) {
      return self::emptyMetrics($statusCounts);
    }

    $rows = DB::table($table)->get();
    
    foreach ($rows as $row) {
      if (empty($row->date_received)) {          
        continue;
      }

      $cleanedDate = trim($row->date_received);
      $parsedDate = null;
      $recordYear = null;

      if (preg_match('/\b(20\d{2})\b/', $cleanedDate, $matches)) {
        $recordYear = (int) $matches[1];
      }

      try {
        $parsedDate = Carbon::parse($cleanedDate);
      } catch (\Exception $e) {
        if (! $recordYear) {
          continue;
        }
      }

      $actualYear = $parsedDate ? $parsedDate->year : $recordYear;

      if ($actualYear !== $currentYear) {
        continue;
      }

      if (!empty($currentMonth)) {
        if ($parsedDate) {
          if ($parsedDate->format('m') !== $currentMonth) {
            continue;
          }
        } else {
            continue;
          }
      }

      if ($actualYear >= 2026 && $parsedDate && $parsedDate->greaterThanOrEqualTo(Carbon::create(2026, 5, 1, 0, 0, 0))) {
        continue;
      }

      $totalTransactions++;

      $rawDebit = trim($row->debit ?? '0');
      $debit = (float) str_replace([',', '₱', ' '], '', $rawDebit);
      $combinedAmount = $debit;

      $totalRequestedAmount += $combinedAmount;
      $payee = strtoupper(trim($row->payee ?? ''));
      $status = strtolower(trim($row->status ?? ''));

      if ($status === 'cancelled' || $status === 'canceled') {
        $totalAmountCancelled += $combinedAmount;
        $statusCounts['cancelled']++;
        continue; 
      }

      if ($actualYear === 2025) {
        $totalAmountPaid += $combinedAmount;
        $statusCounts['paid']++;

        if (! empty($payee)) {
          $payeeAmounts[$payee] = ($payeeAmounts[$payee] ?? 0) + $combinedAmount;
        }
          continue; 
        }

        if (($status === 'forwarded to cashier' || $status === 'forwarded') && ! empty($payee)) {
          $payeeAmounts[$payee] = ($payeeAmounts[$payee] ?? 0) + $combinedAmount;
        }

        if (in_array($status, ['pending', 'processing', 'returned'])) {
          $amountInProcess += $combinedAmount;
        }

        if ($status === 'forwarded to cashier' || $status === 'forwarded') {
          $amountForwarded += $combinedAmount;
        }

        $isPaid = ($status === 'paid');
    
        if ($isPaid && $parsedDate && $parsedDate->greaterThanOrEqualTo(Carbon::create(2026, 3, 1, 0, 0, 0))) {
          $isPaid = false;
        }

        if ($isPaid) {
          $totalAmountPaid += $combinedAmount;
        }

        if ($status === 'pending') {
          $statusCounts['pending']++;
        } elseif ($status === 'processing') {
          $statusCounts['processing']++;
        } elseif (str_contains($status, 'returned')) {
            $statusCounts['returned']++;
        } elseif ($status === 'forwarded to cashier' || $status === 'forwarded') {
            $statusCounts['forwarded']++;
        } elseif ($isPaid) {
          $statusCounts['paid']++;
        }
      }

      arsort($payeeAmounts);
      $topPayees = array_slice($payeeAmounts, 0, 10, true);

      return [
        'totalTransactions' => $totalTransactions,
        'totalRequestedAmount' => $totalRequestedAmount,
        'amountInProcess' => $amountInProcess,
        'amountForwarded' => $amountForwarded,
        'totalAmountPaid' => $totalAmountPaid,
        'totalAmountCancelled' => $totalAmountCancelled,
        'statusCounts' => $statusCounts,
        'payeeAmounts' => $topPayees,
      ];
  }

  private static function emptyMetrics($statusCounts) {
    return [
      'totalTransactions' => 0,
      'totalRequestedAmount' => 0,
      'amountInProcess' => 0,
      'amountForwarded' => 0,
      'totalAmountPaid' => 0,
      'totalAmountCancelled' => 0,
      'statusCounts' => $statusCounts,
      'payeeAmounts' => [],
    ];
  }
}