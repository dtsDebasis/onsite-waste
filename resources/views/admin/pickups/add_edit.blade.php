@extends('admin.layouts.layout')
@push('pageplugincss')
<style>
select.select2{
    position: static !important;
    outline:none !important;
}
</style>
@endpush

@section('content')

    <div class="card-body card mb-4">
        @if($data)
            {!! Form::model($data, [
            'method' => 'PATCH',
            'route' => ['pickups.update',$id],
            'class' => 'form-horizontal ',
            'id'=>'branch-hauling-form',
            'enctype'=>'multipart/form-data'
            ]) !!}
        @else
            {!! Form::open(array('route' => ['pickups.store'],'method'=>'POST', 'enctype'=>'multipart/form-data','id'=>'branch-hauling-form','class' => 'form-horizontal')) !!}
        @endif
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        @php($companies = \App\Helpers\Helper::companies())
                        @php($compnay_id = Request::old('company_id')?Request::old('company_id'):((isset($branch_details) && $branch_details)?$branch_details->company_id:((isset($data->company_id) && $data->company_id)?$data->company_id:null)))
                        {!! Form::label('company_id', 'Customer Name <span class="span-req">*</span>:',array('class'=>'','for'=>'company_id'),false) !!}
                        {!! Form::select('company_id',$companies,$compnay_id,['class'=>'form-control select2','placeholder'=>'Choose ...','id'=>'company_id','required'=>'required']) !!}
                        @if ($errors->has('company_id'))
                            <span class="help-block">{{ $errors->first('company_id') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        @php($company_branches = \App\Helpers\Helper::companyBranches($compnay_id))
                        @php($branch_id = Request::old('branch_id')?Request::old('branch_id'):(Request::get('branch_id')?Request::get('branch_id'):(($data && isset($data->branch_id))?$data->branch_id:null)))
                        {!! Form::label('branch_id', 'Location <span class="span-req">*</span>:',array('class'=>'','for'=>'branch_id'),false) !!}
                        {!! Form::select('branch_id',$company_branches,$branch_id,['class'=>'form-control select2','placeholder'=>'Choose ...','id'=>'branch_id','required'=>'required']) !!}
                        @if ($errors->has('branch_id'))
                            <span class="help-block">{{ $errors->first('branch_id') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('date', 'Date <span class="span-req">*</span>:',array('class'=>'','for'=>'date'),false) !!}
                        {!! Form::date('date',null,['class'=>'form-control','placeholder'=>'Select date','id'=>'date','required'=>'required']) !!}
                        @if ($errors->has('date'))
                            <span class="help-block">{{ $errors->first('date') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('driver_name', 'Provider Name :',array('class'=>'','for'=>'driver_name'),false) !!}
                        {!! Form::text('driver_name',null,['class'=>'form-control','placeholder'=>'Enter name','id'=>'driver_name']) !!}
                        @if ($errors->has('driver_name'))
                            <span class="help-block">{{ $errors->first('driver_name') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('request_type', 'Request Type <span class="span-req">*</span>:',array('class'=>'','for'=>'request_type'),false) !!}
                        {!! Form::select('request_type',['1' => 'ASAP','2' => 'ASAP - No Change to Frequency'],null,['class'=>'form-control select2','placeholder'=>'Choose ...','id'=>'request_type','required'=>'required']) !!}
                        @if ($errors->has('request_type'))
                            <span class="help-block">{{ $errors->first('request_type') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('supplies_requested', 'Supplies Requested <span class="span-req">*</span>:',array('class'=>'','for'=>'supplies_requested'),false) !!}
                        {!! Form::select('supplies_requested',['0' => 'No','1' => 'Yes'],null,['class'=>'form-control select2','placeholder'=>'Choose ...','id'=>'supplies_requested','required'=>'required']) !!}
                        @if ($errors->has('supplies_requested'))
                            <span class="help-block">{{ $errors->first('supplies_requested') }}</span>
                        @endif
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        {!! Form::label('description', 'Note :',array('class'=>'','for'=>'description'),false) !!}
                        {!! Form::textarea('description',null,['class'=>'form-control','placeholder'=>'Enter description','id'=>'description']) !!}
                        @if ($errors->has('description'))
                            <span class="help-block">{{ $errors->first('description') }}</span>
                        @endif
                    </div>
                </div>
                @php($status_arr = ['1'=> 'Confirmed','2' => 'Pickup Done', '4' => 'Declined','5' => 'Requested','3' => 'Completed'])
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('status', 'status <span class="span-req">*</span>:',array('class'=>'','for'=>'status'),false) !!}
                        {!! Form::select('status',$status_arr,null,['class'=>'form-control select2','id'=>'status','required'=>'required']) !!}
                        @if ($errors->has('status'))
                            <span class="help-block">{{ $errors->first('status') }}</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="text-right">
                <button type="submit" class="btn btn-primary w-md">Submit</button>
            </div>
        {!! Form::close() !!}
    </div>

@endsection

@push('pagejs')
<script>
$(document).ready(function () {
        var dtToday = new Date();

    var month = dtToday.getMonth() + 1;
    var day = dtToday.getDate();
    var year = dtToday.getFullYear();
    if(month < 10)
        month = '0' + month.toString();
    if(day < 10)
        day = '0' + day.toString();

    var maxDate = year + '-' + month + '-' + day;

    // or instead:
    // var maxDate = dtToday.toISOString().substr(0, 10);

    //alert(maxDate);
    $('#date').attr('min', maxDate);

    let loc = window.location.href;
    console.log(loc);
    $('.nav-link.active').removeClass('active');
    if(/create\/hauling/.test(loc)) {
        $('.nav-link.hauling').addClass('active');
    }

    $('body').on('change','#company_id',function(){
        let company_id = $(this).val();
        $.ajax({
            url: '{{url("admin/ajax-get-branch-list")}}',
            data: {
                company_id: company_id
            },
            type: 'GET',
            beforeSend: function() {
                $('.loader').show();
            },
            success: function(data) {
                $('.loader').hide();
                if (data.success) {
                    $('#branch_id').find('option').remove();
                    html = '<option value="">Choose ...</option>';
                    $.each(data.data,function(index,value){
                        html+='<option value="'+index+'">'+value+'</option>';
                    });
                    $('#branch_id').html(html);
                    $('#branch_id').select2();
                }
                else {
                    bootbox.alert({
                        title:"Branch List",
                        message: data.msg ,
                        type:"error"
                    });
                }
            },
            error: function(data) {
                $('.loader').hide();
                    bootbox.alert({
                    title:"Branch List",
                    message: data.msg ,
                    type:"error"
                });
            }
        });
    });

});
</script>
@endpush

