
@extends('admin.layouts.layout')
@push('pageplugincss')

@endpush

@section('content')
@php($tab = Request::get('tab')?Request::get('tab'):'active')
@php($status_arr = ['0'=>'Not Confirm','1'=> 'Confirmed','2' => 'Pickup Done', '4' => 'Declined','5' => 'Requested'])
<div class="row">
    <div class="col-lg-12">
        <ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
            <li class="nav-item waves-effect waves-light">
                <a class="nav-link {{($tab == 'active')?'active':''}}" href="{{route('pickups.index',['tab' => 'active'])}}"  aria-selected="true">
                    <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                    <span class="d-none d-sm-block">Active</span>
                </a>
            </li>
            <li class="nav-item waves-effect waves-light">
                <a class="nav-link {{($tab == 'completed')?'active':''}}" href="{{route('pickups.index',['tab' => 'completed'])}}"  aria-selected="false">
                    <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                    <span class="d-none d-sm-block">Completed</span>
                </a>
            </li>
        </ul>
        <div class="tab-content pt-3 text-muted">
            @if($tab == 'active')
            <div class="tab-pane {{($tab == 'active')?'active':''}}" id="home-1" role="tabpanel">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-column flex-md-row justify-content-between">
                            <div class="col-md-9">
                                {!! Form::open(['method' => 'GET','route' => 'pickups.index','id' => 'srch-form']) !!}
                                <input type="hidden" name="tab" value="{{$tab}}">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="search" value="{{Request::get('search')?Request::get('search'):null}}" placeholder="Search" aria-label="Search">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            @php($stch_st = Request::get('status')?Request::get('status'):null)
                                            {!! Form::select('status',$status_arr,$stch_st,['class'=>'form-control select2','placeholder'=> 'Choose ...','id'=>'srch_status',]) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            @php($stch_dt = Request::get('date')?Request::get('date'):null)
                                            {!! Form::date('date',$stch_dt,['class'=>'form-control','placeholder'=> 'select date','id'=>'srch_date',]) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <button class="btn btn-primary" type="submit" id="button-addon2">Search</button>
                                    <a class="btn btn-danger" href="{{route($routePrefix.'.index',['tab'=>$tab])}}">Reset</a>
                                </div>
                                {!! Form::close() !!}
                            </div>
                            <div class="col-md-9">
                                <a href="{{route('pickups.create')}}"  class="btn btn-primary w-md">Add Pick Up Schedule</a>
                            </div>
                        </div>
                        <div class="tab-content mt-3 text-muted">
                            <div class="tab-pane active" id="home1" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table table-centered table-nowrap mb-0">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Location</th>
                                                <th>Vendor</th>
                                                <th>Package</th>
                                                <th>Date</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(count($data))
                                                @foreach($data as $hkey => $hval)
                                                    <tr class="">

                                                        @if(isset($hval->branch_details) && isset($hval->branch_details->addressdata) && $hval->branch_details->addressdata)
                                                        <td> <span class="color-b">{{$hval->branch_details->addressdata->locality}} {{$hval->branch_details->addressdata->state}}</span> <br> <i class="bx bx-map"></i> {{$hval->branch_details->addressdata->addressline1}} </td>
                                                        @else
                                                            <td> NA </td>
                                                        @endif
                                                        <td>{{($hval->driver_name)?$hval->driver_name:'NA'}}</td>

                                                        @if(isset($hval->package_details) && $hval->package_details)
                                                        <td> {{$hval->package_details->name}}</td>
                                                        @else
                                                            <td> NA </td>
                                                        @endif

                                                        <td>{{($hval->date)?\App\Helpers\Helper::dateConvert($hval->date):'NA'}}</td>
                                                        <td><a href="javascript:;" data-id="{{$hval->id}}" data-status="{{$hval->status}}" data-driver_name="{{$hval->driver_name}}" class="change-status"><span class="badge badge-pill badge-soft-success">{{$status_arr[$hval->status]}}</span></a></td>
                                                        <td>

                                                            <a href="javascript:;" data-hauling_id="{{$hval->id}}" data-branch_id="{{$hval->branch_id}}" data-toggle="tooltip" data-placement="top" title="" data-original-title="Add Manifest" type="button" class="btn btn-primary btn-sm btn-rounded waves-effect waves-light add_edit_manifest">
                                                                <i class="bx bx-plus-medical"></i>
                                                            </a>
                                                            <a href="{{route('pickups.edit',$hval->id)}}" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit" type="button" class="btn btn-primary btn-sm btn-rounded waves-effect waves-light">
                                                                <i class="bx bx-edit-alt"></i>
                                                            </a>
                                                            <a class="btn btn-sm btn-rounded btn-danger waves-effect" data-toggle="tooltip" title="" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="event.preventDefault();
                                                            document.getElementById('delete-form-{{$hval->id}}').submit();" data-original-title="Delete">{!! \Config::get('settings.icon_delete') !!}</a>
                                                            {!! Form::open([
                                                                'method' => 'DELETE',
                                                                'route' => [
                                                                'pickups.destroy',[$hval->id]
                                                                ],
                                                                'style'=>'display:inline',
                                                                'id' => 'delete-form-' . $hval->id
                                                                ]) !!}
                                                            {!! Form::close() !!}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                            <tr class="">
                                                <td colspan="25">No Record found</td>
                                            </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                {{-- {{ $data->appends(request()->input())->links() }} --}}
                            </div>
                        </div>
                         <!-- end table-responsive -->
                    </div>
                </div>
            </div>
            @endif
            @if($tab == 'completed')
            <div class="tab-pane {{($tab == 'completed')?'active':''}}" id="profile-1" role="tabpanel">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-column flex-md-row justify-content-between">
                            <div class="col-md-9">
                                {!! Form::open(['method' => 'GET','route' => 'pickups.index','id' => 'srch-form']) !!}
                                <input type="hidden" name="tab" value="{{$tab}}">
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="search" value="{{Request::get('search')?Request::get('search'):null}}" placeholder="Search" aria-label="Search">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            @php($stch_dt = Request::get('date')?Request::get('date'):null)
                                            {!! Form::date('date',$stch_dt,['class'=>'form-control','placeholder'=> 'select date','id'=>'srch_date',]) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <button class="btn btn-primary" type="submit" id="button-addon2">Search</button>
                                        <a class="btn btn-danger" href="{{route($routePrefix.'.index',['tab'=>$tab])}}">Reset</a>
                                        <a class="btn btn-info" href="{{route($routePrefix.'.generate-excel',$srch_params)}}">Export</a>
                                    </div>
                                </div>
                                {!! Form::close() !!}
                            </div>

                        </div>
                        <div class="tab-content mt-3 text-muted">
                            <div class="tab-pane active" id="home1" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table table-centered table-nowrap mb-0">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Branch Name</th>
                                                <th>Location</th>
                                                <th>Driver Name</th>
                                                <th>Number Of Box</th>
                                                <th>Package</th>
                                                <th>Date</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(count($data))
                                                @foreach($data as $hkey => $hval)
                                                    <tr class="">
                                                        <td>{{(isset($hval->branch_details) && $hval->branch_details)?$hval->branch_details->name:'NA'}}</td>
                                                        @if(isset($hval->branch_details) && isset($hval->branch_details->addressdata) && $hval->branch_details->addressdata)
                                                        <td> <span class="color-b">{{$hval->branch_details->addressdata->locality}} {{$hval->branch_details->addressdata->state}}</span> <br> <i class="bx bx-map"></i> {{$hval->branch_details->addressdata->addressline1}} </td>
                                                        @else
                                                            <td> NA </td>
                                                        @endif
                                                        <td>{{($hval->driver_name)?$hval->driver_name:'NA'}}</td>
                                                        <td> {{($hval->number_of_boxes)?$hval->number_of_boxes.' Box':'NA'}}</td>
                                                        @if(isset($hval->package_details) && $hval->package_details)
                                                        <td> {{$hval->package_details->name}}</td>
                                                        @else
                                                            <td> NA </td>
                                                        @endif

                                                        <td>{{($hval->date)?\App\Helpers\Helper::dateConvert($hval->date):'NA'}}</td>
                                                        <td><span class="badge badge-pill badge-soft-success">Completed</span></td>
                                                        <td>
                                                            <a href="javascript:;" data-hauling_id="{{$hval->id}}" data-branch_id="{{$hval->branch_id}}" data-toggle="tooltip" data-placement="top" title="" data-original-title="Add Manifest" type="button" class="btn btn-primary btn-sm btn-rounded waves-effect waves-light add_edit_manifest">
                                                                <i class="bx bx-plus-medical"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                            <tr class="">
                                                <td colspan="25">No Record found</td>
                                            </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                {{ $data->appends(request()->input())->links() }}
                            </div>
                        </div>

                        <!-- end table-responsive -->
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
<div class="modal fade" id="all_status_update_modal" tabindex="-1" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="reply_mod_title">Status Change</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card">
                    {!! Form::open(array('route' => $routePrefix.'.status-update','method'=>'POST', 'enctype'=>'multipart/form-data','id'=>'reply-form')) !!}
                    <input type="hidden" name="id" value="" id="hoauling_id">
                    <div class="card-body mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    @php($newStAr = $status_arr)
                                    @php($newStAr['3'] = 'Completed')
                                    {!! Form::label('status', 'Status <span class="span-req">*</span>:',array('class'=>'','for'=>'description'),false) !!}
                                    {!! Form::select('status',$newStAr,null,['class'=>'form-control select2','id'=>'status','required'=>'required']) !!}
                                    @if ($errors->has('status'))
                                        <span class="help-block">{{ $errors->first('status') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {!! Form::label('driver_name', 'Driver Name <span class="span-req">*</span>:',array('class'=>'','for'=>'driver_name'),false) !!}
                                    {!! Form::text('driver_name',null,['class'=>'form-control','placeholder'=>'Enter provider name','id'=>'driver_name','required'=>'required']) !!}
                                    @if ($errors->has('driver_name'))
                                        <span class="help-block">{{ $errors->first('driver_name') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" id="reply_submit">Submit</button>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div class="modal fade" id="all_manifest_modal" tabindex="-1" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="manifest_mod_title">Add/Edit Manifest</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" id="manifesstBody">

            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

@endsection

@push('pagejs')
<script>
$(document).ready(function () {

    $('body').on('click','.change-status',function(){
        var id = $(this).attr('data-id');
        $('#all_status_update_modal').find('#hoauling_id').val(id);
        $('#all_status_update_modal').find('#status').val($(this).attr('data-status'));
        $('#all_status_update_modal').find('#driver_name').val($(this).attr('data-driver_name'));
        $('#all_status_update_modal').find('#status').select2();
        $('#all_status_update_modal').modal('show');
    });
    $('body').on('click','.add_edit_manifest', function(e){
        let id = $(this).attr('data-hauling_id');
        let branch_id = $(this).attr('data-branch_id');
        $.ajax({
            url: '{{url("admin/pickups/ajax-get-manifest-details")}}',
            data: {
                hauling_id: id,branch_id:branch_id
            },
            type: 'GET',
            beforeSend: function() {
                $('.loader').show();
            },
            success: function(data) {
                $('.loader').hide();
                if (data.success) {
                    $('#manifesstBody').html(data.html);
                    $('#all_manifest_modal').modal('show');
                }
                else {
                    bootbox.alert({
                        title:"Manifest Details",
                        message: data.msg ,
                        type:"error"
                    });
                }
            },
            error: function(data) {
                $('.loader').hide();
                bootbox.alert({
                    title:"Manifest Details",
                    message: data.msg ,
                    type:"error"
                });
            }
        });
    });

    $('body').on('click','#manifest_submit', function(){
        //let form_data = $("#manifest-form").serialize();
        var form_data = new FormData($('#manifest-form')[0]);
        $.ajax({
            url: '{{url("admin/pickups/ajax-update-manifest-details")}}',
            data: form_data,
            type: 'POST',
            processData: false,
            contentType: false,
            beforeSend: function() {
                $('.loader').show();
            },
            success: function(data) {
                $('.loader').hide();
                $('#all_manifest_modal').modal('hide');
                if (data.success) {
                    bootbox.alert({
                        title:"Manifest Details",
                        message: data.msg ,
                        type:"error"
                    });
                }
                else {
                    bootbox.alert({
                        title:"Manifest Details",
                        message: data.msg ,
                        type:"error"
                    });
                }
            },
            error: function(data) {
                $('.loader').hide();
                    bootbox.alert({
                    title:"Manifest Assign",
                    message: data.msg ,
                    type:"error"
                });
            }
        });
    });

});
</script>
@endpush

