@extends('staff.layouts.master')
@section('content')
<div class="content p-4">
    <h2 class="mb-4" style="text-transform: uppercase;">Search Currier
    </h2>
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('currier.deliver.search') }}" method="POST" id="form">
                @csrf
                <div class="row">                
                    <div class="col-md-10">
                        <div class="form-group">
                            <input type="text" class="form-control form-control-lg search" name="search" placeholder="Barcode/Phone" value="{{request()->search}}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-block btn-lg search-btn"><i class='fa fa-search'></i></button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @if(isset($currierList))
    <div class="card mb-4">
        <div class="card-body">
            <table id="table" class="table table-hover table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Invoice Id</th>
                        <th>Currier Code</th>
                        <th>Sender Name</th>
                        <th>Sender Phone</th>
                        <th>Recipient Branch</th>
                        <th>Recipient Name</th>
                        <th>Recipient Phone</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($currierList as $currier)
                    <tr>
                        <td>{{ $currier->invoice_id }}</td>
                        <td>
                            {{ $currier->code }}
                        </td>
                        <td>{{ $currier->sender_name }}</td>
                        <td>{{ $currier->sender_phone }}</td>
                        <td>{{ $currier->receiver_branch->name }}</td>
                        <td>{{ $currier->receiver_name }}</td>
                        <td>{{ $currier->receiver_phone }}</td>
                        <td>{{ $currier->payment_status }}</td>
                        <td>
                            @if($currier->receiver_branch_id == Auth::user()->branch_id)
                            @if($currier->status  == 'Received' && $currier->payment_status=='Paid')
                            <button type="button" class="btn btn-primary btn-sm delete_button" data-toggle="modal" data-target="#receive{{ $currier->id }}">
                                <i class="fa fa-cart-arrow-down"></i>  Deliver Currier
                            </button>
                            <div class="modal fade" id="receive{{ $currier->id }}" role="dialog" aria-labelledby="#" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <form method="POST" action="{{ route('currier.receive') }}">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="receive{{ $currier->id }}"><i class="fa fa-download"></i>&nbsp;Deliver Currier</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p><strong>Are you sure! You deliver this currier ?</strong></p>
                                                <input type="hidden" name="id" value="{{ $currier->id }}">
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Yes,&nbsp;Deliver</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @elseif($currier->status=='Received' && $currier->payment_status=='Unpaid')
                            <button type="button" class="btn btn-primary btn-sm delete_button" data-toggle="modal" data-target="#receive{{ $currier->id }}">
                                <i class="fa fa-cart-arrow-down"></i>  Deliver Currier
                            </button>
                            <div class="modal fade" id="receive{{ $currier->id }}" role="dialog" aria-labelledby="#" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <form method="POST" action="{{ route('currier.receive') }}">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="receive{{ $currier->id }}"><i class="fa fa-money-bill-alt"></i>&nbsp;Make Payment</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <input type="hidden" name="id" value="{{ $currier->id }}">
                                                <input type="hidden" name="payment_status" value="Paid">
                                                 <h5 class="text-danger text-center font-weight-bold">Please Collect {{ $currier->currier_product_infos->sum('currier_fee') }}&nbsp;{{ $gs->base_currency }}</h5>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Paid & Delivered</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @else
                            <span class="btn btn-sm btn-success text-uppercase">{{ $currier->status }}</span>
                            @endif
                            @else
                            <span class="btn btn-sm btn-success text-uppercase">{{ $currier->status }}</span>
                            @endif
                            <a href="{{ route('currier.invoice',$currier->invoice_id) }}"><button class="btn btn-primary btn-sm"> <i class="fa fa-eye"></i> Currier Invoice</button></a>                           
                        </td> 
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10">No Information</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
<script type="text/javascript">
    $("#deliver").addClass("active");
</script>
@endsection