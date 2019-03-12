<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Reference;
use App\Models\Accounting\TempJournalAccounts;
use App\Models\Accounting\TempJournalInfo;
use DB;
use Carbon\Carbon;

class ArPaymentController extends Controller
{
    public function getJournalInfo($start_date, $end_date)
    {
        return DB::select('CALL get_payment_to_accounting_info("'.$start_date.'","'.$end_date.'")');
    }

    public function insertJournalInfo(Request $request)
    {
        $infos = $request->input('info');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $info_dataSet = [];
        foreach($infos as $info)
        {
            $journal_info = new TempJournalInfo;
            $journal_info->txn_no = $info['txn_no'];
            $journal_info->department_id = $info['department_id'];
            $journal_info->customer_id = $info['customer_id'];
            $journal_info->date_txn = Carbon::now();
            $journal_info->payment_method_id = $info['payment_method'];
            $journal_info->ref_no = $info['ref_no'];
            $journal_info->amount = $info['amount'];
            $journal_info->is_sales = $info['is_sales'];
            $journal_info->journal_id = $info['journal_id'];

            if($journal_info->save())
            {
                $temp_journal_id = $journal_info->temp_journal_id;
                DB::select('CALL insert_payment_to_account_details('.$temp_journal_id.', "'.$start_date.'", "'.$end_date.'", '.$info['tenant_id'].', '.$info['payment_id'].')');
            }
        }
        return response()->json(['message' => 'Successfully sent to accounting.']);
    }
}
