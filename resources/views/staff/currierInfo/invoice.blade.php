@extends('staff.layouts.master')
@section('content')
<div class="content p-4">
    <h2 class="mb-4" style="text-transform: uppercase;">Currier Invoice<hr></h2>
    <div class="card">
        <div  id="printDiv">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6">
                        <strong>Invoice No :
                            {{ $currierInfo->invoice_id }}
                        </strong> 
                    </div>
                    <div class="col-md-6">
                        <span class="float-right"> <strong>Status:&nbsp;&nbsp;</strong>
                            @if($currierInfo->status == 'Delivered')
                            <span class="btn btn-sm btn-danger text-uppercase">{{ $currierInfo->status }}</span>
                            @else
                            <span class="btn btn-sm btn-success text-uppercase">{{ $currierInfo->status }}</span>
                            @endif
                        </span>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="container">
                    <div class="col-md-12 text-center mb-4">
                        <div class="row">
                            <div class="col-md-4 barcode">
                                {!! $code !!}
                            </div>
                            <div class="col-md-4">
                                <strong  style="font-size:18px;"> <img src="{{asset('assets/frontend/images/logo.png')}}" alt="" style="width:100%;"></strong>
                            </div>
                            <div class="col-md-4">
                               <strong>Receive Date : </strong><p style="font-size:16px;">{{ $currierInfo->created_at->toDateString() }}</p>
                            </div>
                        </div>
                    </div><hr>
                </div>
                <div class="row mb-4">
                    <div class="col-md-4 offset-md-2">
                        <h6 class="mb-2">Sender Details:</h6>
                        <div><strong>Branch&nbsp;&nbsp;:</strong>&nbsp;{{ $currierInfo->branch->name }}</div>
                        <div><strong>Name&nbsp;&nbsp;&nbsp;&nbsp;:</strong>&nbsp;{{ $currierInfo->sender_name }}</div>
                        @if($currierInfo->sender_email)
                        <div><strong>Email&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</strong>&nbsp;{{ $currierInfo->sender_email }}</div>
                        @endif
                        <div><strong>Phone&nbsp;&nbsp;&nbsp;&nbsp;:</strong>&nbsp;{{ $currierInfo->sender_phone }}</div>
                        @if($currierInfo->sender_address)
                        <div><strong>Address&nbsp;:</strong>&nbsp;{!! $currierInfo->sender_address !!}</div>
                        @endif
                    </div>
                    <div class="col-md-4 offset-md-2">
                        <h6 class="mb-2">Receiver Details:</h6>
                        <div><strong>Branch&nbsp;&nbsp;:</strong>&nbsp;{{ $currierInfo->branch->name }}</div>
                        <div><strong>Name&nbsp;&nbsp;&nbsp;&nbsp;:</strong>&nbsp;{{ $currierInfo->receiver_name }}</div>
                        @if($currierInfo->receiver_email)
                        <div><strong>Email&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</strong>&nbsp;{{ $currierInfo->receiver_email }}</div>
                        @endif
                        <div><strong>Phone&nbsp;&nbsp;&nbsp;&nbsp;:</strong>&nbsp;{{ $currierInfo->receiver_phone }}</div>
                        @if($currierInfo->receiver_address)
                        <div><strong>Address&nbsp;:</strong>&nbsp;{{ $currierInfo->receiver_address }}</div>
                        @endif
                    </div>
                </div>
                <div class="container">
                    <div class="table-responsive-sm">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th class="center">#</th>
                                    <th>Currier Type</th>
                                    <th>Sending Date</th>
                                    <th>Currier Quantity</th>
                                    <th>Currier Fee</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($currierProductInfoList as $key=>$currierProductInfo)
                                <tr>
                                    <td class="center">{{ $key+1 }}</td>
                                    <td class="left">{{ $currierProductInfo->courier_type->name }}</td>
                                    <td class="right">{{ $currierProductInfo->created_at->toDateTimeString() }}</td>
                                    <td class="right">{{ $currierProductInfo->currier_quantity }}&nbsp;{{ $currierProductInfo->courier_type->unit->name }} </td>
                                    <td class="center fee">{{ $currierProductInfo->currier_fee }} {{ $gs->base_currency_symbol }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-sm-5">

                        </div>
                        <div class="col-lg-4 col-sm-5 ml-auto">
                            <table class="table table-clear">
                                <tbody>
                                    <tr>
                                        <td class="left">
                                            <strong>Total</strong>
                                        </td>
                                        <td class="right">
                                            <strong><span class="totalPrice">450</span> {{ $gs->base_currency_symbol }}</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="left">
                                            <strong>Payment Status</strong>
                                        </td>
                                        <td class="right">
                                            @if($currierInfo->payment_status=='Unpaid')
                                            <strong><span class="text-uppercase">{{ $currierInfo->payment_status }}</span></strong>
                                            @else
                                            <strong><span class="text-uppercase">{{ $currierInfo->payment_status }}</span></strong>
                                            @endif
                                        </td>
                                    </tr>
                                    @if($currierInfo->payment_status =='Paid')
                                    <tr>
                                        <td class="left">
                                            <strong>Payment Receiver Name</strong>
                                        </td>
                                        <td class="right">
                                            <strong><span class="text-uppercase">{{ $currierInfo->payment_receiver->name }}</span></strong>&nbsp;<br>[{{$currierInfo->payment_receiver->type}}]
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="left">
                                            <strong>Branch</strong>
                                        </td>
                                        <td class="right">
                                            <strong><span class="text-uppercase">{{ $currierInfo->payment_receiver->branch->name }}</span></strong>
                                        </td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-2 ml-2">
            <div class="container">
                <button type="button" onclick="printDiv();" value="print" class="btn btn-info razu"><i class="fa fa-print"></i>&nbsp;Print Currier</button>
                <a href="{{ route('staff.currier.slip',$currierInfo->id) }}" target="_blank"><button class="btn btn-primary"><i class="fa fa-print"></i> Print Slip</button></a> 
                @if($currierInfo->payment_status == 'Unpaid')
                @if($currierInfo->sender_branch_staff_id==Auth::user()->id || $currierInfo->receiver_branch_staff_id == Auth::user()->id)
                <button type="button" class="btn btn-success btn-md delete_button" data-toggle="modal" data-target="#receive{{ $currierInfo->id }}">
                                <i class="fa fa-money-bill-alt"></i>  Make Payment
                            </button>
                            <div class="modal fade" id="receive{{ $currierInfo->id }}" role="dialog" aria-labelledby="#" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <form method="POST" action="{{ route('currier.payment.staff') }}">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="receive{{ $currierInfo->id }}"><i class="fa fa-download"></i>&nbsp;Make Payment</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <h5 class="text-danger text-center font-weight-bold">Please Collect {{ $currierInfo->payment_balance }}&nbsp;{{ $gs->base_currency }}</h5>
                                                <input type="hidden" name="id" value="{{ $currierInfo->id }}">
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Yes,&nbsp;Paid</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                @endif
                @endif
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $("#home").addClass("active");
    $(document).ready(function () {
        totalPrice = 0;
        $(".fee").each(function () {
            totalPrice += parseInt($(this).html());
        });
        $(".totalPrice").text(totalPrice);
    });

    function printDiv()
    {

        var divToPrint = document.getElementById('printDiv');
        var newWin = window.open('', 'Print-Window');
        newWin.document.open();
        newWin.document.write('<html><head><title>eCourier Management System</title></head>');
        newWin.document.write('<body onload="window.print()">' + divToPrint.innerHTML + '</body></html>');
        newWin.document.write('<link rel="stylesheet" href="{{asset('assets/user/css/bootstrap.min.css')}}">');
        newWin.document.close();
        setTimeout(function () {
            newWin.close();
        }, 1000);

    }
</script>
@endsection

