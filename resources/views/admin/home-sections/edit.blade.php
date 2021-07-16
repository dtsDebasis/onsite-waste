@extends('admin.layouts.layout')
@push('pageplugincss')
<link href="{{asset('/assets/libs/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css" />
@endpush

@section('content')
<div class="row">
    <div class="col-md-12">
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        @if (can('Home Selection Update'))
        <div class="card">
            <div class="card-body mb-4">
                @php($customer_types = ['0' => 'All','1'=>'Customer','2' => 'Guest'])
                @php($locations = \App\Helpers\Helper::companyBranches())
                {!! Form::open(array('route' => [$routePrefix.'.store'],'method'=>'POST',
                'enctype'=>'multipart/form-data','id'=>'branch-form')) !!}
                @foreach($data as $val)
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('section_name', 'Section Name
                            :',array('class'=>'','for'=>'section_name'.$val->id),false) !!}
                            {!! Form::text('section_name',$val->name,['class'=>'form-control','id' =>
                            'section_name'.$val->id,'readonly' => 'readonly']) !!}
                            {!! Form::hidden('section_ids['.$val->id.']',$val->id,['class'=>'form-control','readonly' =>
                            'readonly','id'=>'section_id'.$val->id]) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            @php($customer_type =
                            isset($val->settings_details->customer_type)?$val->settings_details->customer_type:null)
                            {!! Form::label('customer_type', 'Customer Type
                            :',array('class'=>'','for'=>'customer_type'.$val->id),false) !!}
                            {!!
                            Form::select('customer_types['.$val->id.']',$customer_types,$customer_type,['class'=>'form-control','id'
                            => 'customer_type'.$val->id]) !!}

                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('location_id', 'Locations
                            :',array('class'=>'','for'=>'location_id'.$val->id),false) !!}
                            @php($location = (isset($val->settings_details->locations) &&
                            $val->settings_details->locations)?explode(',',$val->settings_details->locations):null)
                            {!! Form::select('location_ids['.$val->id.'][]',$locations,$location,['class'=>'form-control
                            select2','id' => 'location_id'.$val->id,'multiple' => 'multiple','data-placeholder' =>
                            'Choose ...']) !!}
                            <p class="help-info"><small>Not select means all</small></p>
                        </div>
                    </div>
                </div>
                @endforeach
                <div class="text-right">
                    <button type="submit" class="btn btn-primary w-md">Save</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
        @endif

    </div>
</div>
@endsection
@push('pagejs')
<!-- <script src="{{asset('/assets/libs/select2/js/select2.min.js')}}"></script>
<script>
$(document).ready(function () {
    $('.select2').select2();
}); -->
</script>
@endpush
