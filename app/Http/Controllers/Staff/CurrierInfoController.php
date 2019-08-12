<?php

namespace App\Http\Controllers\Staff;

use App\Model\CurrierInfo;
use App\Model\CourierType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Branch;
use Illuminate\Support\Facades\Auth;
use App\Model\GeneralSetting;
use App\Model\CurrierProductInfo;
use Illuminate\Support\Str;
use App\User;
use DNS1D;
use Carbon\Carbon;
use DB;

class CurrierInfoController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {

        $searchtext = $request->search;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        if (!empty($start_date) && !empty($end_date)) {
            $currierList = CurrierInfo::where('sender_branch_id', Auth::user()->branch_id)
                    ->where(function($q) use($start_date, $end_date) {
                        $q->whereBetween('created_at', [$start_date, $end_date]);
                    })
                    ->orWhere('receiver_branch_id', Auth::user()->branch_id)
                    ->paginate(10);
        } else {
            $currierList = CurrierInfo::where('sender_branch_id', Auth::user()->branch_id)
                    ->where(function($q) use($searchtext) {
                        $q->orWhere('invoice_id', 'LIKE', "%$searchtext%")
                        ->orWhere('payment_date', 'LIKE', "%$searchtext%")
                        ->orWhere('sender_name', 'LIKE', "%$searchtext%")
                        ->orWhere('receiver_name', 'LIKE', "%$searchtext%")
                        ->orWhere('status', 'LIKE', "%$searchtext%");
                    })
                    ->orWhere('receiver_branch_id', Auth::user()->branch_id)
                    ->paginate(10);
        }
        return view('staff.currierInfo.list', compact('currierList'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {

        $gs = GeneralSetting::first();
        $branchList = Branch::where([['status', 'Active'], ['id', '!=', Auth::user()->branch_id]])->get();
        $courierTypeList = CourierType::where('status', 'Active')->get();
        return view('staff/currierInfo/add', compact('branchList', 'courierTypeList', 'gs'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {

        $request->validate([
            'sender_name' => 'required|max:50',
            'sender_phone' => 'required|max:50',
            'receiver_name' => 'required|max:50',
            'receiver_phone' => 'required|max:50',
            'currier_type' => 'required',
            'currier_quantity.*' => 'required|numeric',
            'currier_fee.*' => 'required|numeric'
        ]);


        $data = $request->except('_token','currier_type','currier_quantity','currier_fee');

        if (CurrierInfo::first()) {

            $lastInvoice = CurrierInfo::latest()->first()->id;
        } else {

            $lastInvoice = 0;
        }

        $data['invoice_id'] = $lastInvoice + 1;

        $data['status'] = 'Received';

        $data['payment_balance'] = array_sum($request->currier_fee);

        $currier_code = strtoupper(Str::random(12));

        $data['code'] = $currier_code;

        $currierInfo = CurrierInfo::create($data);

        $currier_type = $request->currier_type;

        for ($i = 0; $i < count($currier_type); $i++) {
            $currierProductInfo = new CurrierProductInfo();
            $currierProductInfo->currier_code = $currier_code;
            $currierProductInfo->currier_info_id = $currierInfo->id;
            $currierProductInfo->currier_type = $request->currier_type[$i];
            $currierProductInfo->currier_quantity = $request->currier_quantity[$i];
            $currierProductInfo->currier_fee = $request->currier_fee[$i];
            $currierProductInfo->save();
        }

        return redirect()->route('currier.invoice', $currierInfo->id)->withSuccess("Currier created successfully");
    }

    public function currierInvoice(CurrierInfo $currierInfo) {

        $currier_code = CurrierProductInfo::where('currier_info_id', $currierInfo->id)->first()->currier_code;

        $code = '<img src="data:image/png;base64,' . DNS1D::getBarcodePNG($currier_code, 'C128') . '" alt="barcode"   />' . "<br>" . $currier_code;

        $gs = GeneralSetting::first();
        $currierProductInfoList = CurrierProductInfo::where('currier_info_id', $currierInfo->id)->get();

        return view('staff.currierInfo.invoice', compact('currierInfo', 'currierProductInfoList', 'gs', 'code'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\CurrierInfo  $currierInfo
     * @return \Illuminate\Http\Response
     */
    public function show(CurrierInfo $currierInfo) {
//
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\CurrierInfo  $currierInfo
     * @return \Illuminate\Http\Response
     */
    public function edit(CurrierInfo $currierInfo) {
//
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\CurrierInfo  $currierInfo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CurrierInfo $currierInfo) {
//
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\CurrierInfo  $currierInfo
     * @return \Illuminate\Http\Response
     */
    public function destroy(CurrierInfo $currierInfo) {
//
    }

    public function receiveCurrier(Request $request) {

        $id = $request->get('id');
        $currierInfo = CurrierInfo::find($id);
        if ($request->payment_status == "Paid") {
            $currierInfo->payment_date = Carbon::now()->toDateString();
            $currierInfo->payment_branch_id = Auth::user()->branch_id;
            $currierInfo->payment_receiver_id = Auth::user()->id;
            $currierInfo->payment_status = "Paid";
        }
        $currierInfo->receiver_branch_staff_id = Auth::user()->id;
        $currierInfo->status = 'Delivered';
        $currierInfo->save();
        return back()->withSuccess("Currier received successfully");
    }

    public function searchDeliverCurrier() {

        return view('staff.deliver.searchDeliver');
    }

    public function showDeliverCurrier(Request $request) {

        $currierList = CurrierInfo::where('code', $request->search)->orWhere('receiver_phone', $request->search)->orWhere('sender_phone', $request->search)->get();

        return view('staff.deliver.searchDeliver', compact('currierList'));
    }

    public function notifyView() {
        return view('staff.deliver.notifyView');
    }

    public function findCurrier(Request $request) {

        $code = $request->code;

        $currier = CurrierInfo::where('code', $code)->orWhere('receiver_phone', $code)->orWhere('sender_phone', $code)->with('branch')->get();

        if ($currier) {
            $response = array('output' => 'success', 'msg' => 'data found', 'currier' => $currier);
        } else {
            $response = array('output' => 'error', 'msg' => 'data not found');
        }
        return response()->json($response);
    }

    public function sendNotification(Request $request) {

        $codeList = $request->all();
        $gs = GeneralSetting::first();

        if (empty($codeList["code"])) {
            return back()->withErrors("Please add currier first");
        }

        foreach ($codeList["code"] as $invoice) {
            $sendNotify = CurrierInfo::where('code', $invoice)->first();

            if ($sendNotify->receiver_email != null && $gs->email_verification == 1) {
                $to = $sendNotify->receiver_email;
                $name = $sendNotify->receiver_name;

                $subject = 'Your Currier Arrived';

                $message = "Hello Your Currier Arrived";

                send_email($to, $name, $subject, $message);
            }

            if ($sendNotify->receiver_phone != null && $gs->sms_verification == 1) {
                $to = $sendNotify->receiver_phone;
                $message = 'Hello Your Currier Arrived';
                send_sms($to, $message);
            }
        }
        return back()->withSuccess("Notification send successfully");
    }

    public function staffCasheCollection(Request $request) {

        $start_date = $request->start_date;
        $end_date = $request->end_date;

        if (!empty($start_date) && !empty($end_date)) {
            $branchIncomeList = CurrierInfo::where('payment_receiver_id', Auth::user()->id)
                    ->where(function($q) use($start_date, $end_date) {
                        $q->whereBetween('payment_date', [$start_date, $end_date]);
                    })
                    ->select(DB::raw("*,SUM(payment_balance) as total_balance"))
                    ->groupBy('payment_date')
                    ->paginate(10);
        } else {
            $branchIncomeList = CurrierInfo::where('payment_receiver_id', Auth::user()->id)
                            ->select(DB::raw("*,SUM(payment_balance) as total_balance"))
                            ->groupBy('payment_date')->paginate(10);
        }

        $gs = GeneralSetting::first();

        return view('staff.branchIncome.list', compact('branchIncomeList', 'gs'));
    }

    public function printSlipView($id) {

        $currierInfo = CurrierInfo::find($id);
        $currier_code = CurrierProductInfo::where('currier_info_id', $currierInfo->id)->first()->currier_code;
        $code = '<img src="data:image/png;base64,' . DNS1D::getBarcodePNG($currier_code, 'C128') . '" alt="barcode"   />' . "<br>" . $currier_code;
        $gs = GeneralSetting::first();

        return view('staff.currierInfo.slip', compact('currier_code', 'code', 'gs', 'currierInfo'));
    }

    public function paidCurrier(Request $request) {

        $id = $request->get('id');
        $currierInfo = CurrierInfo::find($id);
        $currierInfo->payment_date = Carbon::now()->toDateString();
        $currierInfo->payment_branch_id = Auth::user()->branch_id;
        $currierInfo->payment_receiver_id = Auth::user()->id;
        $currierInfo->payment_status = "Paid";
        $currierInfo->save();
        return back()->withSuccess("Currier payment successfully");
    }

}
