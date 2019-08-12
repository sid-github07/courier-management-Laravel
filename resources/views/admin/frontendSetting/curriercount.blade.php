@extends('admin.layouts.master')
@section('content')
<div class="content p-4">
    <h2 class="mb-4" style="text-transform: uppercase;">Currier Count Info Setting</h2>
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('frontend.curriercountupdate',$setting->id) }}" method="post">
                @csrf
                @method('PUT')
                <div class="row  container-fluid">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="departure_currier" style="text-transform: uppercase;"><strong>Departure Currier</strong></label>
                            <input class="form-control form-control-lg mb-3" name="departure_currier" value="{{ $setting->departure_currier ?? old('departure_currier') }}" type="text">
                        </div>
                    </div> 
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="upcoming_currier" style="text-transform: uppercase;"><strong>Upcoming Currier</strong></label>
                            <input class="form-control form-control-lg mb-3" name="upcoming_currier" value="{{ $setting->upcoming_currier ?? old('upcoming_currier') }}"  type="text">
                        </div>
                    </div> 
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="total_deliver" style="text-transform: uppercase;"><strong>Total Deliver Currier</strong></label>
                            <input class="form-control form-control-lg mb-3" name="total_deliver" value="{{ $setting->total_deliver ?? old('total_deliver') }}" type="text">
                        </div>
                    </div> 
                </div>
                <br>
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary btn-block btn-lg">UPDATE</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    $("#frontend").addClass("show");
    $("#frontend li:nth-child(5)").addClass("active");
</script>
@endsection
