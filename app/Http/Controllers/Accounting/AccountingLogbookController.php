<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AccountingLogbookController extends Controller
{
    public function logbook(Request $request)
    {
        $month = $request->month ?? 'all';
        $status = $request->status ?? 'all';
        $search = trim($request->search ?? '');
        $sort = $request->sort ?? 'latest';

        $records = collect();

        if ($month === 'all') {
            $records = $this->getMonthRecords('odms_accounting_january', $search, $status)
                ->concat($this->getMonthRecords('odms_accounting_february', $search, $status))
                ->concat($this->getMonthRecords('odms_accounting_march', $search, $status))
                ->concat($this->getMonthRecords('odms_accounting_april', $search, $status))
                ->concat($this->getMonthRecords('odms_accounting_may', $search, $status))
                ->concat($this->getMonthRecords('odms_accounting_june', $search, $status));

            if ($sort == 'obr_asc') {

                $records = $records->sortBy(function ($r) {
                    preg_match('/\d+/', $r->dv_no ?? '', $m);
                    return (int) ($m[0] ?? 0);
                });

            } elseif ($sort == 'obr_desc') {

                $records = $records->sortByDesc(function ($r) {
                    preg_match('/\d+/', $r->dv_no ?? '', $m);
                    return (int) ($m[0] ?? 0);
                });

            } else {

                $records = $records->sortByDesc('date_processed');
            }

        } else {

            $table = match ($month) {
                'january' => 'odms_accounting_january',
                'february' => 'odms_accounting_february',
                'march' => 'odms_accounting_march',
                'april' => 'odms_accounting_april',
                'may' => 'odms_accounting_may',
                'june' => 'odms_accounting_june',
                default => null,
            };

            if ($table) {

            $records = $this->getMonthRecords($table, $search, $status);

            if ($sort == 'obr_asc') {

                $records = $records->sortBy(function ($r) {
                    preg_match('/\d+/', $r->dv_no ?? '', $m);
                    return (int) ($m[0] ?? 0);
                });

            } elseif ($sort == 'obr_desc') {

                $records = $records->sortByDesc(function ($r) {
                    preg_match('/\d+/', $r->dv_no ?? '', $m);
                    return (int) ($m[0] ?? 0);
                });

            } else {

                $records = $records->sortByDesc('date_processed');
            }
        }
        }

        return view('accounting.logbook', compact('records','month','status','search','sort'));
    }

    private function getMonthRecords(
        string $table,
        string $search = '',
        string $status = 'all'
    ) {
        $query = DB::table($table);

        // STATUS FILTER
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        // SEARCH FILTER
        if (!empty($search)) {

            $query->where(function ($q) use ($search) {

                $q->where('dv_no', 'like', "%{$search}%")
                    ->orWhere('obr_no', 'like', "%{$search}%")
                    ->orWhere('payee', 'like', "%{$search}%")
                    ->orWhere('particulars', 'like', "%{$search}%")
                    ->orWhere('uacs_code', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%")
                    ->orWhere('date_received', 'like', "%{$search}%")
                    ->orWhere('date_processed', 'like', "%{$search}%")
                    ->orWhere('date_signed', 'like', "%{$search}%")
                    ->orWhere('date_forwarded', 'like', "%{$search}%");

            });
        }

        return $query
            ->orderByDesc('date_processed')
            ->get();
    }
}