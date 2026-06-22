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

        if ($month == 'january') {

            $records = DB::table('odms_accounting_january')
                ->orderByDesc('date_processed')
                ->get();

        } elseif ($month == 'february') {

            $records = DB::table('odms_accounting_february')
                ->orderByDesc('date_processed')
                ->get();

        } elseif ($month == 'march') {

            $records = DB::table('odms_accounting_march')
                ->orderByDesc('date_processed')
                ->get();

        } elseif ($month == 'april') {

            $records = DB::table('odms_accounting_april')
                ->orderByDesc('date_processed')
                ->get();

        } elseif ($month == 'may') {

            $records = DB::table('odms_accounting_may')
                ->orderByDesc('date_processed')
                ->get();

        } elseif ($month == 'june') {

            $records = DB::table('odms_accounting_june')
                ->orderByDesc('date_processed')
                ->get();

        } else {

            $recordsjan = DB::table('odms_accounting_january')->get();
            $recordsfeb = DB::table('odms_accounting_february')->get();
            $recordsmarch = DB::table('odms_accounting_march')->get();
            $recordsapril = DB::table('odms_accounting_april')->get();
            $recordsmay = DB::table('odms_accounting_may')->get();
            $recordsjune = DB::table('odms_accounting_june')->get();

            $records = $recordsjan
                ->concat($recordsfeb)
                ->concat($recordsmarch)
                ->concat($recordsapril)
                ->concat($recordsmay)
                ->concat($recordsjune)
                ->sortByDesc('date_processed');
        }
        return view('accounting.logbook', compact('records', 'month'));
    }

}
