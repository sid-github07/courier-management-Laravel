<?php

namespace App\Http\Controllers\Manager;

use App\Model\CourierType;
use App\Model\CurrierInfo;
use App\Model\Unit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Branch;
use App\Model\GeneralSetting;
use App\Model\CurrierProductInfo;
use Auth;
use DNS1D;

class CurrierInfoController extends Controller {

    public function departureBranchCurrierList(Request $request) {
        $searchtext = $request->search;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        if (!empty($start_date) && !empty($end_date)) {
            $currierList = CurrierInfo::where([['sender_branch_id', Auth::user()->branch_id], ['status', 'Received']])
                    ->where(function($q) use($start_date, $end_date) {
                        $q->whereBetween('created_at', [$start_date, $end_date]);
                    })
                    ->paginate(10);
        } else {
            $currierList = CurrierInfo::where([['sender_branch_id', Auth::user()->branch_id], ['status', 'Received']])
                    ->where(function($q) use($searchtext) {
                        $q->orWhere('invoice_id', 'LIKE', "%$searchtext%")
                        ->orWhere('payment_date', 'LIKE', "%$searchtext%")
                        ->orWhere('sender_name', 'LIKE', "%$searchtext%")
                        ->orWhere('receiver_name', 'LIKE', "%$searchtext%")
                        ->orWhere('code', 'LIKE', "%$searchtext%");
                    })
                    ->paginate(10);
        }
        return view('manager.currierInfo.departureCurrierList', compact('currierList'));
    }

    public function upcomingBranchCurrierList(Request $request) {
        $searchtext = $request->search;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        if (!empty($start_date) && !empty($end_date)) {
            $currierList = CurrierInfo::where([['receiver_branch_id', Auth::user()->branch_id], ['status', 'Received']])
                    ->where(function($q) use($start_date, $end_date) {
                        $q->whereBetween('created_at', [$start_date, $end_date]);
                    })
                    ->paginate(10);
        } else {
            $currierList = CurrierInfo::where([['receiver_branch_id', Auth::user()->branch_id], ['status', 'Received']])
                    ->where(function($q) use($searchtext) {
                        $q->orWhere('invoice_id', 'LIKE', "%$searchtext%")
                        ->orWhere('payment_date', 'LIKE', "%$searchtext%")
                        ->orWhere('sender_name', 'LIKE', "%$searchtext%")
                        ->orWhere('receiver_name', 'LIKE', "%$searchtext%")
                        ->orWhere('code', 'LIKE', "%$searchtext%");
                    })
                    ->paginate(10);
        }

        return view('manager.currierInfo.upcomingCurrierList', compact('currierList'));
    }

    public function currierInvoice(CurrierInfo $currierInfo) {

        $currier_code = CurrierProductInfo::where('currier_info_id', $currierInfo->id)->first()->currier_code;
        $code = '<img src="data:image/png;base64,' . DNS1D::getBarcodePNG($currier_code, 'C128') . '" alt="barcode"   />' . "<br>" . $currier_code;

        $gs = GeneralSetting::first();

        $currierProductInfoList = CurrierProductInfo::where('currier_info_id', $currierInfo->id)->get();

        return view('manager.currierInfo.invoice', compact('currierInfo', 'currierProductInfoList', 'gs', 'code'));
    }

    public function upcomingCurrierInvoice(CurrierInfo $currierInfo) {
        $gs = GeneralSetting::first();
        $currierProductInfoList = CurrierProductInfo::where('currier_info_id', $currierInfo->id)->get();
        return view('manager.currierInfo.upcomingCurrierInvoice', compact('currierInfo', 'currierProductInfoList', 'gs'));
    }

    public function printSlipView($id) {
        $currierInfo = CurrierInfo::find($id);
        $currier_code = CurrierProductInfo::where('currier_info_id', $currierInfo->id)->first()->currier_code;
        $code = '<img src="data:image/png;base64,' . DNS1D::getBarcodePNG($currier_code, 'C128') . '" alt="barcode"   />' . "<br>" . $currier_code;
        $gs = GeneralSetting::first();
        return view('manager.currierInfo.slip', compact('currier_code', 'code', 'gs', 'currierInfo'));
    }

}
