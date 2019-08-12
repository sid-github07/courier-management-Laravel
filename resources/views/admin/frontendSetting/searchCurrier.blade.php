@extends('admin.layouts.master')
@section('content')
<div class="content p-4">
    <h2 class="mb-4" style="text-transform: uppercase;">Search Currier 
    </h2>
    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body">
                    <form action="{{ route('frontend.searchcurrier') }}" method="post">
                        @csrf
                        @method('PUT')
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="search_currier_title" style="text-transform: uppercase;"><strong>Title</strong></label>
                                <input class="form-control form-control-lg mb-3" name="search_currier_title" value="{{ $setting->search_currier_title ?? old('search_currier_title') }}"  type="text">
                                <input class="form-control form-control-lg mb-3" name="id" value="{{ $setting->id }}"  type="hidden">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="search_currier_details" style="text-transform: uppercase;"><strong>Details</strong></label>
                                <textarea class="form-control" id="join_us_details" rows="2" name="search_currier_details">{{ $setting->search_currier_details ?? old('search_currier_details') }}</textarea>
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
        
    </div>
</div>
<script type="text/javascript">
    $("#frontend").addClass("show");
    $("#frontend li:nth-child(10)").addClass("active");
    
</script>
@endsection