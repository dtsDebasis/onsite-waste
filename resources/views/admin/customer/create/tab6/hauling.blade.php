@extends('admin.customer.create.createlayout')


@section('create-customer-content')
<!-- tab6 -->
@php($status_arr = ['1'=> 'Confirmed','2' => 'Pickup Done', '4' => 'Declined','5' => 'Requested','3' => 'Completed'])

<div class="tab-pane active" id="settings-1" role="tabpanel">
    @if (can('Pickup Add') || ($hauling && can('Pickup Edit')))
    <div class="card">
        <div class="card-body mb-4">
            @if($hauling)
            {!! Form::model($hauling, [
            'method' => 'PATCH',
            'route' => ['customers.hauling-store-upadte',$company->id],
            'class' => 'form-horizontal checkediting',
            'id'=>'branch-hauling-form',
            'enctype'=>'multipart/form-data'
            ]) !!}
            @else
            {!! Form::open(array('route' => ['customers.hauling-store-upadte',$company->id],'method'=>'POST',
            'enctype'=>'multipart/form-data','id'=>'branch-hauling-form','class' => 'form-horizontal')) !!}
            @endif
            <input type="hidden" name="company_id" value="{{$id}}">
            <input type="hidden" name="hauling_id" value="{{($hauling && isset($hauling->id))?$hauling->id:null}}">
            <div class="row">
                <div class="col-sm-12">
                    <h3 class="custom-heading">Pickup </h3>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('driver_name', 'Driver Name :',array('class'=>'','for'=>'driver_name'),false)
                        !!}
                        {!! Form::text('driver_name',null,['class'=>'form-control','placeholder'=>'Enter
                        name','id'=>'driver_name']) !!}
                        @if ($errors->has('driver_name'))
                        <span class="help-block">{{ $errors->first('driver_name') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('date', 'Date <span
                            class="span-req">*</span>:',array('class'=>'','for'=>'date'),false) !!}
                        {!! Form::date('date',null,['class'=>'form-control','placeholder'=>'Select
                        date','id'=>'date','required'=>'required']) !!}
                        @if ($errors->has('date'))
                        <span class="help-block">{{ $errors->first('date') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        @php($company_branches = \App\Helpers\Helper::companyBranches($id))
                        @php($branch_id =
                        Request::old('branch_id')?Request::old('branch_id'):(Request::get('branch_id')?Request::get('branch_id'):(($hauling
                        && isset($hauling->branch_id))?$hauling->branch_id:null)))
                        {!! Form::label('branch_id', 'Location <span
                            class="span-req">*</span>:',array('class'=>'','for'=>'branch_id'),false) !!}
                        {!! Form::select('branch_id',$company_branches,$branch_id,['class'=>'form-control
                        select2','placeholder'=>'Choose ...','id'=>'branch_id','required'=>'required']) !!}
                        @if ($errors->has('branch_id'))
                        <span class="help-block">{{ $errors->first('branch_id') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('number_of_boxes', 'Number Of Boxes <span
                            class="span-req">*</span>:',array('class'=>'','for'=>'number_of_boxes'),false) !!}
                        {!! Form::number('number_of_boxes',null,['class'=>'form-control','placeholder'=>'Enter box
                        number','id'=>'number_of_boxes','required' => 'required']) !!}
                        @if ($errors->has('number_of_boxes'))
                        <span class="help-block">{{ $errors->first('number_of_boxes') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        @php($newStAr = $status_arr)
                        @php($newStAr['3'] = 'Completed')
                        {!! Form::label('status', 'Status <span
                            class="span-req">*</span>:',array('class'=>'','for'=>'description'),false) !!}
                        {!! Form::select('status',$newStAr,null,['class'=>'form-control
                        select2','id'=>'status','required'=>'required']) !!}
                        @if ($errors->has('status'))
                        <span class="help-block">{{ $errors->first('status') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        {!! Form::label('description', 'Note :',array('class'=>'','for'=>'description'),false) !!}
                        {!! Form::textarea('description',null,['class'=>'form-control','placeholder'=>'Enter
                        description','id'=>'description']) !!}
                        @if ($errors->has('description'))
                        <span class="help-block">{{ $errors->first('description') }}</span>
                        @endif
                    </div>
                </div>

            </div>
            <div class="text-right">
                <button type="submit" class="btn btn-primary w-md">Next</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
    @endif
    @if (can('Pickup List'))
    <div class="card">
        <div class="card-body">
            <div class="col-sm-12">
                <h3 class="custom-heading">Pickup Listing</h3>
                <div class="tab-content text-muted">
                    <div class="tab-pane active" id="home1" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-centered table-nowrap mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Manifest#</th>
                                        <th>Location</th>
                                        <th>Address</th>
                                        <th>Number Of Boxes</th>
                                        <th>Package</th>
                                        <th>Driver Name</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(count($hauling_list))
                                    @foreach($hauling_list as $hkey => $hval)
                                    <tr class="">
                                    <td>{{(isset($hval->manifest_details) && $hval->manifest_details)?$hval->manifest_details->uniq_id:'NA'}}</td>
                                        <td>{{(isset($hval->branch_details) && $hval->branch_details)?$hval->branch_details->name:'NA'}}
                                        </td>
                                        @if(isset($hval->branch_details) && isset($hval->branch_details->addressdata) &&
                                        $hval->branch_details->addressdata)
                                        <td> <span class="color-b">{{$hval->branch_details->addressdata->locality}}
                                                {{$hval->branch_details->addressdata->state}}</span> <br> <i
                                                class="bx bx-map"></i>
                                            {{$hval->branch_details->addressdata->addressline1}} </td>
                                        @else
                                        <td> NA </td>
                                        @endif
                                        <td> {{($hval->number_of_boxes)?$hval->number_of_boxes.' Box':'NA'}}</td>
                                        @if(isset($hval->package_details) && $hval->package_details)
                                        <td> {{$hval->package_details->name}}</td>
                                        @else
                                        <td> NA </td>
                                        @endif
                                        <td>{{($hval->driver_name)?$hval->driver_name:'NA'}}</td>
                                        <td>{{($hval->date)?\App\Helpers\Helper::dateConvert($hval->date):'NA'}}</td>
                                        <td><a href="javascript:;" data-id="{{$hval->id}}"
                                                data-status="{{$hval->status}}"
                                                data-driver_name="{{$hval->driver_name}}" class="change-status"><span
                                                    class="badge badge-pill badge-soft-success">{{isset($status_arr[$hval->status]) ? $status_arr[$hval->status]: 'NA'}}</span></a>
                                        </td>
                                        <td>
                                            {{--<a href="#" data-toggle="tooltip" data-placement="top" title="" data-original-title="Add Manifest" type="button" class="btn btn-primary btn-sm btn-rounded waves-effect waves-light">
                                                        <i data-toggle="modal" data-target=".exampleModal" class="bx bx-plus-medical"></i>
                                                    </a>--}}
                                            @if (can('Pickup Add Manifest'))
                                            <a href="javascript:;" data-hauling_id="{{$hval->id}}"
                                                data-branch_id="{{$hval->branch_id}}" data-toggle="tooltip"
                                                data-placement="top" title="" data-original-title="Add Manifest"
                                                type="button"
                                                class="btn btn-primary btn-sm btn-rounded waves-effect waves-light add_edit_manifest">
                                                <i class="bx bx-plus-medical"></i>
                                            </a>
                                            @endif
                                            @if (can('Pickup Edit'))
                                            <a href="{{route('customers.create.hauling',[$id,'hid' => $hval->id])}}"
                                                data-toggle="tooltip" data-placement="top" title=""
                                                data-original-title="Edit" type="button"
                                                class="btn btn-primary btn-sm btn-rounded waves-effect waves-light">
                                                <i class="bx bx-edit-alt"></i>
                                            </a>
                                            @endif
                                            @if (can('Pickup Delete'))
                                            <a class="btn btn-sm btn-rounded btn-danger waves-effect"
                                                data-toggle="tooltip" title=""
                                                data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?"
                                                data-confirm-yes="event.preventDefault();
                                                    document.getElementById('delete-form-{{$hval->id}}').submit();"
                                                data-original-title="Delete">{!! \Config::get('settings.icon_delete')
                                                !!}</a>

                                            @endif

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
            </div>
        </div>
    </div>
    @endif

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

@endsection('create-customer-content')


@section('create-customer-content-js')
<script>
    $(document).ready(function () {

        let loc = window.location.href;
        console.log(loc);
        $('.nav-link.active').removeClass('active');
        if (/create\/hauling/.test(loc)) {
            $('.nav-link.hauling').addClass('active');
        }

        $('body').on('click', '.add_edit_manifest', function (e) {
            let id = $(this).attr('data-hauling_id');
            let branch_id = $(this).attr('data-branch_id');
            $.ajax({
                url: '{{url("admin/pickups/ajax-get-manifest-details")}}',
                data: {
                    hauling_id: id,
                    branch_id: branch_id
                },
                type: 'GET',
                beforeSend: function () {
                    $('.loader').show();
                },
                success: function (data) {
                    $('.loader').hide();
                    if (data.success) {
                        $('#manifesstBody').html(data.html);
                        $('#all_manifest_modal').modal('show');
                    } else {
                        bootbox.alert({
                            title: "Manifest Details",
                            message: data.msg,
                            type: "error"
                        });
                    }
                },
                error: function (data) {
                    $('.loader').hide();
                    bootbox.alert({
                        title: "Manifest Details",
                        message: data.msg,
                        type: "error"
                    });
                }
            });
        });

        $('body').on('click', '#manifest_submit', function (e) {
            //let form_data = $("#manifest-form").serialize();
            // e.preventDefault();
            if (!$('#manifest-form')[0].checkValidity()) {
                return;
            } else {
                e.preventDefault();
            }
            var form_data = new FormData($('#manifest-form')[0]);
            $.ajax({
                url: '{{url("admin/pickups/ajax-update-manifest-details")}}',
                data: form_data,
                type: 'POST',
                processData: false,
                contentType: false,
                beforeSend: function () {
                    $('.loader').show();
                },
                success: function (data) {
                    $('.loader').hide();
                    $('#all_manifest_modal').modal('hide');
                    if (data.success) {
                        bootbox.alert({
                            title: "Manifest Details",
                            message: data.msg,
                            type: "success"
                        });
                    } else {
                        bootbox.alert({
                            title: "Manifest Details",
                            message: data.msg,
                            type: "error"
                        });
                    }
                },
                error: function (data) {
                    $('.loader').hide();
                    bootbox.alert({
                        title: "Manifest Assign",
                        message: data.msg,
                        type: "error"
                    });
                }
            });
        });

    });
</script>
@endsection('create-customer-content')
