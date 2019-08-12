@extends('staff.layouts.master')
@section('content')
<div class="content p-4">
    <h2 class="mb-4" style="text-transform: uppercase;">All Currier List
        <a href="{{ route('currier.create') }}" class="btn btn-primary btn-md float-right">
            <i class="fa fa-list"></i>   Add Currier
        </a>
    </h2>
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-8 col-sm-6 col-xs-12">
                    <form method="GET" action="{{ route('currier.index') }}" class="form-inline">
                        @csrf
                        <div class="form-group mb-1">
                            <label for="date1">Start Date :</label>
                            &nbsp;<input type="text" class="form-control" name="start_date" id="date1" placeholder="yyyy-mm-dd" value="{{request()->start_date}}">
                        </div>&nbsp;
                        <div class="form-group mb-1">
                            <label for="date2">End Date :</label>
                            &nbsp;<input type="text" class="form-control" name="end_date" id="date2" placeholder="yyyy-mm-dd" value="{{request()->end_date}}">
                        </div>&nbsp;
                        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>View Currier</button>
                    </form>
                </div>
                <div class="col-md-4  mb-2">
                    <form method="GET" action="{{ route('currier.index') }}" class="form-inline float-right">
                        @csrf
                        <div class="form-group">
                            &nbsp;<input type="text" class="form-control" name="search" placeholder="search" value="{{request()->search}}">
                        </div>&nbsp;
                        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>Search</button>
                    </form>
                </div>
            </div>
            <table id="table" class="table table-hover table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>SL</th>
                        <th>Invoice Id</th>
                        <th>Currier Code</th>
                        <th>Sender Name</th>
                        <th>Sender Phone</th>
                        <th>Recipient Branch</th>
                        <th>Recipient Name</th>
                        <th>Recipient Phone</th>
                        <th>Receive</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($currierList as $key=>$currier)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ $currier->invoice_id }}</td>
                        <td>
                            {{ App\Model\CurrierProductInfo::where('currier_info_id',$currier->id)->first()->currier_code }}
                        </td>
                        <td>{{ $currier->sender_name }}</td>
                        <td>{{ $currier->sender_phone }}</td>
                        <td>{{ $currier->branch->name }}</td>
                        <td>{{ $currier->receiver_name }}</td>
                        <td>{{ $currier->receiver_phone }}</td>
                        @if($currier->receiver_branch_id == Auth::user()->branch_id && $currier->status== 'Delivered')
                        <td>
                            <button type="button" class="btn btn-danger btn-sm delete_button" data-toggle="modal" data-target="#receive{{ $currier->id }}">
                                <i class="fa fa-check"></i>  Receive Currier
                            </button>
                            <div class="modal fade" id="receive{{ $currier->id }}" role="dialog" aria-labelledby="#" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <form method="POST" action="{{ route('currier.receive') }}">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="receive{{ $currier->id }}"><i class="fa fa-download"></i>&nbsp;Receive Currier</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p><strong>Are you sure! You receive this currier ?</strong></p>
                                                <input type="hidden" name="id" value="{{ $currier->id }}">
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-danger">Yes,&nbsp;Receive</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                        @else
                        <td class="text-center">
                            @if($currier->status== 'Delivered')
                            <i class="fa fa-times fa-2x" style="color:grey;"></i>
                            @else
                            <p>Received at <br>[ {{ $currier->created_at->toDateString() }} ]</p>
                            @endif
                        </td>
                        @endif
                        <td>
                            @if($currier->status  == 'Delivered')
                            <span class="btn btn-sm btn-danger text-uppercase">{{ $currier->status }}</span>
                            @else
                            <span class="btn btn-sm btn-success text-uppercase">{{ $currier->status }}</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('currier.invoice',$currier->invoice_id) }}"><button class="btn btn-primary btn-sm"> <i class="fa fa-eye"></i> Currier Invoice</button></a>                           
                        </td> 
                    </tr>
                    @empty
                    <tr>
                        <td colspan="15" class="text-center">No Information</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $currierList->appends(['search'=>request()->search,'start_date'=>request()->start_date,'end_date'=>request()->end_date])->links() }}
        </div>
    </div>
</div>
<script type="text/javascript">
    $("#currierInfo").addClass("show");
    $("#currierInfo li:nth-child(2)").addClass("active");

</script>
@endsection