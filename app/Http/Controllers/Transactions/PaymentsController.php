<?php

namespace App\Http\Controllers\Transactions;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Transactions\PaymentInfo;
use App\Models\Transactions\PaymentDetails;
use App\Http\Resources\Reference;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use DB;
use Auth;

class PaymentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $payments = PaymentInfo::leftJoin('b_tenants', 'b_tenants.tenant_id', '=', 'b_payment_info.tenant_id')
                            ->where('is_canceled', 0)->orderBy('payment_id', 'desc')->get();
        return Reference::collection($payments);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $payment_info = new PaymentInfo;
        $payment_details = new PaymentDetails;

        $payment_info->transaction_no = DB::raw("CreateTransactionNo()");
        $payment_info->reference_no = $request->input('reference_no');
        $payment_info->tenant_id = $request->input('tenant_id');
        $payment_info->payment_type = $request->input('payment_type');
        $payment_info->amount_paid = $request->input('amount');
        $payment_info->payment_date = date("Y-m-d", strtotime($request->input('payment_date')));
        $payment_info->check_type_id = $request->input('check_type_id');
        $payment_info->check_no = $request->input('check_no');
        $payment_info->check_date = date("Y-m-d", strtotime($request->input('check_date')));
        $payment_info->balance_paid = $request->input('balance_paid');
        $payment_info->advance = $request->input('advance');
        $payment_info->discount = $request->input('discount');
        $payment_info->remarks = $request->input('remarks');

        $payment_info->created_datetime = Carbon::now();
        $payment_info->created_by = Auth::user()->id;
        
        if($payment_info->save()){
            $payment_id = $payment_info->payment_id;
            $payment_details_dataSet = [];
            
            $payment_details = $request->input('payment_details');
            foreach($payment_details as $detail){
                $payment_details_dataSet[] = [
                    'payment_id' => $payment_id,
                    'billing_id' => $detail['billing_id'],
                    'discount' => $detail['discount'],
                    'amount_paid' => $detail['amount_paid']
                ];
            }
            DB::table('b_payment_details')->insert($payment_details_dataSet);
        }
        
        return ( new Reference( $payment_info ) )
                ->response()
                ->setStatusCode(201);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Update the specified resource in storage for deleting.
     * preventing force delete rather update the is_deleted = true
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $payment = PaymentInfo::findOrFail($id);
        $payment->is_canceled = 1;
        $payment->canceled_datetime = Carbon::now();
        $payment->canceled_by = Auth::user()->id;

        //update classification based on the http json body that is sent
        $payment->save();

        return ( new Reference( $payment ) )
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function checkIfUsed($id)
    {
        $exists = 'false';

        // if(BillingPeriod::leftJoin('b_billing_info', 'b_billing_info.period_id', '=', 'b_refbillingperiod.period_id')
        //     ->where('b_billing_info.billing_id', '=', $id)
        //     ->where('b_billing_info.is_deleted', 0)
        //     ->where('b_refbillingperiod.is_closed', 1)
        //     ->exists()) {
        //     $exists = 'true';
        // }
        
        return $exists;
    }
}
