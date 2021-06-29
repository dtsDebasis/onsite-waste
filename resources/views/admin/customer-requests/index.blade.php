
@extends('admin.layouts.layout')
@push('pageplugincss')
<link href="{{asset('/administrator/assets/css/jquery.dataTables.css')}}" rel="stylesheet" type="text/css" />
@endpush

@section('content')
@php($types = ['1'=>'Request Additional Pickup','2' => 'Cancel Upcoming Pickup','3' => 'Request Package Change', '4'=> 'Request Additional Containers', '5'=> 'Switch Container Types','6'=> 'Adjust Re-order Point','7' =>'Adjust Current Inventory','8' => 'Schedule Spend Consultation'])
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="col-sm-12">
                    <div class="d-flex flex-column flex-md-row justify-content-between">
                        <div class="col-md-12">
                            {!! Form::open(['method' => 'GET','route' => $routePrefix.'.index','id' => 'srch-form']) !!}

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="location" value="{{Request::get('location')?Request::get('location'):null}}" placeholder="Search by location" aria-label="Search">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        @php($type = Request::get('type')?Request::get('type'):null)
                                        {!! Form::select('type',$types,$type,['class'=>'form-control select2','placeholder'=> 'Choose ...','id'=>'type',]) !!}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        @php($stch_dt = Request::get('date')?Request::get('date'):null)
                                        {!! Form::date('date',$stch_dt,['class'=>'form-control','placeholder'=> 'select date','id'=>'srch_date',]) !!}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <button class="btn btn-primary" type="submit" id="button-addon2">Search</button>
                                    <a class="btn btn-danger" href="{{route($routePrefix.'.index')}}">Reset</a>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>

                    </div>
                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap mb-0" id="inventories_table">
                            <thead class="thead-light">
                                <tr>
                                    <th>Location</th>
                                    <th>Customer Details</th>
                                    <th>Type</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            @if(count($data))
                                @foreach($data as $ikey => $ival)
                                    <tr class="">
                                        <td>{{(isset($ival->branch_details->name)) ? $ival->branch_details->name:'NA' }} </td>
                                        <td>{!! (isset($ival->user_details) && $ival->user_details) ? '<strong>'.$ival->user_details->full_name.'</strong><br>'.$ival->user_details->email:'NA' !!}</td>
                                        <td>{{(isset($types[$ival->type])) ? $types[$ival->type] : 'NA'}}</td>
                                        <td>{{isset($ival->created_at) ? \App\Helpers\Helper::showdate($ival->created_at) : 'NA'}}</td>
                                        <td>
                                            <a href="javascript:;" data-toggle="tooltip" data-id="{{$ival->id}}" data-description="{!! $ival->description !!}" data-placement="top" title="" data-original-title="View" type="button" class="btn btn-primary btn-sm btn-rounded waves-effect waves-light desc_details">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                <td colspan="5">No record found</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            {{ $data->appends(request()->input())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="details_modal" tabindex="-1" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="manifest_mod_title">Description</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" id="detailsBody">

            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>


@endsection

@push('pagejs')
<script src="{{ asset('/administrator/assets/js/pages/jquery.dataTables.js')}}"></script>
<script>
$(document).ready(function () {
    //$('#inventories_table').DataTable();
    $('body').on('click','.desc_details', function(){
        var details = $(this).attr('data-description');
        $('#detailsBody').html(details);
        $('#details_modal').modal('show');
    });


});
</script>
@endpush

