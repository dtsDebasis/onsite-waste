
@extends('admin.layouts.layout')
@push('pageplugincss')
<link href="{{asset('/assets/libs/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets/libs/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}" rel="stylesheet" type="text/css">
<link href="{{asset('/assets/libs/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css')}}" rel="stylesheet" type="text/css">
<link href="{{asset('/assets/libs/bootstrap-timepicker/css/bootstrap-timepicker.min.css')}}" rel="stylesheet" type="text/css">
<link href="{{asset('/assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css')}}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="{{asset('/assets/libs/@chenfengyuan/datepicker/datepicker.min.css')}}">
<link href="{{asset('/assets/css/style.css')}}" id="app-style" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />

@endpush

@section('content')
@if (can('Package Price Update'))
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
        <div class="card">
            <div class="card-body mb-4">
                @if($transactionalpackages)
                    {!! Form::model($transactionalpackages, [
                    'method' => 'PATCH',
                    'route' => $routePrefix.'.transaction-package',
                    'class' => 'form-horizontal ',
                    'id'=>'transactional-form',
                    'enctype'=>'multipart/form-data'
                    ]) !!}
                @else
                    {!! Form::open(array('route' => $routePrefix.'.transaction-package','method'=>'POST', 'enctype'=>'multipart/form-data','id'=>'transactional-form')) !!}
                @endif
                    <input type="hidden" name="id" id="package_id" value="{{($transactionalpackages)?$transactionalpackages->id:0}}">
                    <div class="row">
                        <div class="col-sm-12">
                            <h3 class="custom-heading">Default Pricing</h3>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::label('te_5000_rental_cost', 'TE-5000 Rental <span class="span-req">*</span>:',array('class'=>'','for'=>'te_5000_rental_cost'),false) !!}
                                {!! Form::number('te_5000_rental_cost',null,['class'=>'form-control','placeholder'=>'Enter amount','id'=>'te_5000_rental_cost','required'=>'required','step'=>'0.01','min'=>'1']) !!}
                                @if ($errors->has('te_5000_rental_cost'))
                                    <span class="help-block">{{ $errors->first('te_5000_rental_cost') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::label('te_5000_purchase_cost', 'TE-5000 Purchase <span class="span-req">*</span>:',array('class'=>'','for'=>'te_5000_purchase_cost'),false) !!}
                                {!! Form::number('te_5000_purchase_cost',null,['class'=>'form-control','placeholder'=>'Enter amount','id'=>'te_5000_purchase_cost','required'=>'required','step'=>'0.01','min'=>'1']) !!}
                                @if ($errors->has('te_5000_purchase_cost'))
                                    <span class="help-block">{{ $errors->first('te_5000_purchase_cost') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::label('containers_cost', 'Containers <span class="span-req">*</span>:',array('class'=>'','for'=>'containers_cost'),false) !!}
                                {!! Form::number('containers_cost',null,['class'=>'form-control','placeholder'=>'Enter amount','id'=>'containers_cost','required'=>'required','step'=>'0.01','min'=>'1']) !!}
                                @if ($errors->has('containers_cost'))
                                    <span class="help-block">{{ $errors->first('containers_cost') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::label('shipping_cost', 'Shipping <span class="span-req">*</span>:',array('class'=>'','for'=>'shipping_cost'),false) !!}
                                {!! Form::number('shipping_cost',null,['class'=>'form-control','placeholder'=>'Enter amount','id'=>'shipping_cost','required'=>'required','step'=>'0.01','min'=>'1']) !!}
                                @if ($errors->has('shipping_cost'))
                                    <span class="help-block">{{ $errors->first('shipping_cost') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::label('setup_initial_cost', 'Setup-Initial <span class="span-req">*</span>:',array('class'=>'','for'=>'setup_initial_cost'),false) !!}
                                {!! Form::number('setup_initial_cost',null,['class'=>'form-control','placeholder'=>'Enter amount','id'=>'setup_initial_cost','required'=>'required','step'=>'0.01','min'=>'1']) !!}
                                @if ($errors->has('setup_initial_cost'))
                                    <span class="help-block">{{ $errors->first('setup_initial_cost') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::label('setup_additional_cost', 'Setup-Additional <span class="span-req">*</span>:',array('class'=>'','for'=>'setup_additional_cost'),false) !!}
                                {!! Form::number('setup_additional_cost',null,['class'=>'form-control','placeholder'=>'Enter amount','id'=>'setup_additional_cost','required'=>'required','step'=>'0.01','min'=>'1']) !!}
                                @if ($errors->has('setup_additional_cost'))
                                    <span class="help-block">{{ $errors->first('setup_additional_cost') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::label('compliance_training_cost', 'Compliance Training <span class="span-req">*</span>:',array('class'=>'','for'=>'compliance_training_cost'),false) !!}
                                {!! Form::number('compliance_training_cost',null,['class'=>'form-control','placeholder'=>'Enter amount','id'=>'compliance_training_cost','required'=>'required','step'=>'0.01','min'=>'1']) !!}
                                @if ($errors->has('compliance_training_cost'))
                                    <span class="help-block">{{ $errors->first('compliance_training_cost') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::label('quarterly_review_cost', 'Quarterly Volume Analysis <span class="span-req">*</span>:',array('class'=>'','for'=>'quarterly_review_cost'),false) !!}
                                {!! Form::number('quarterly_review_cost',null,['class'=>'form-control','placeholder'=>'Enter amount','id'=>'quarterly_review_cost','required'=>'required','step'=>'0.01','min'=>'1']) !!}
                                @if ($errors->has('quarterly_review_cost'))
                                    <span class="help-block">{{ $errors->first('quarterly_review_cost') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::label('container_brackets_cost ', 'Container Brackets <span class="span-req">*</span>:',array('class'=>'','for'=>'container_brackets_cost'),false) !!}
                                {!! Form::number('container_brackets_cost',null,['class'=>'form-control','placeholder'=>'Enter amount','id'=>'container_brackets_cost','required'=>'required','step'=>'0.01','min'=>'1']) !!}
                                @if ($errors->has('container_brackets_cost'))
                                    <span class="help-block">{{ $errors->first('container_brackets_cost') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::label('pharmaceutical_containers_rate', 'Pharmaceutical Containers <span class="span-req">*</span>:',array('class'=>'','for'=>'pharmaceutical_containers_rate'),false) !!}
                                {!! Form::number('pharmaceutical_containers_rate',null,['class'=>'form-control','placeholder'=>'Enter amount','id'=>'pharmaceutical_containers_rate','required'=>'required','step'=>'0.01','min'=>'1']) !!}
                                @if ($errors->has('pharmaceutical_containers_rate'))
                                    <span class="help-block">{{ $errors->first('pharmaceutical_containers_rate') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <button type="submit" class="btn btn-primary w-md">Save</button>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
        @php($frequency_types = ['1'=>'Daily','2'=>'Weekly','3'=>'Monthly','4'=>'Yearly'])
        @php($yes_no_arr = ['0' => 'No','1' => 'Yes'])
        {{--<div class="card">
            <div class="card-body mb-4">
                @if((isset($package) && $package))
                    {!! Form::model($package, [
                    'method' => 'PATCH',
                    'route' => [$routePrefix.'.update',$package->id],
                    'class' => 'form-horizontal ',
                    'id'=>'package-form',
                    'enctype'=>'multipart/form-data'
                    ]) !!}
                @else
                    {!! Form::open(array('route' => $routePrefix.'.store','method'=>'POST', 'enctype'=>'multipart/form-data','id'=>'package-form')) !!}
                @endif
                    <div class="row">
                        <div class="col-sm-12">
                            <h3 class="custom-heading">Package</h3>
                        </div>
                        <input type="hidden" name="id" value="{{(isset($package) && $package)?$package->id:0}}">
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::label('name', 'Package Name <span class="span-req">*</span>:',array('class'=>'','for'=>'name'),false) !!}
                                {!! Form::text('name',null,['class'=>'form-control','placeholder'=>'Enter name','id'=>'name','required'=>'required']) !!}
                                @if ($errors->has('name'))
                                    <span class="help-block">{{ $errors->first('name') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::label('monthly_rate', 'Monthly Rate <span class="span-req">*</span>:',array('class'=>'','for'=>'monthly_rate'),false) !!}
                                {!! Form::number('monthly_rate',null,['class'=>'form-control','placeholder'=>'Enter amount','id'=>'monthly_rate','required'=>'required','step'=>'0.01']) !!}
                                @if ($errors->has('monthly_rate'))
                                    <span class="help-block">{{ $errors->first('monthly_rate') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::label('boxes_included', 'Number of Boxes Included <span class="span-req">*</span>:',array('class'=>'','for'=>'boxes_included'),false) !!}
                                {!! Form::number('boxes_included',null,['class'=>'form-control','placeholder'=>'Enter number','id'=>'boxes_included','required'=>'required','step'=>'0']) !!}
                                @if ($errors->has('boxes_included'))
                                    <span class="help-block">{{ $errors->first('boxes_included') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::label('te_500', 'TE-5000 Included<span class="span-req">*</span>:',array('class'=>'','for'=>'te_500'),false) !!}
                                {!! Form::select('te_500',$yes_no_arr,null,['class'=>'form-control select2','placeholder'=>'Choose ...','id'=>'te_500','required'=>'required']) !!}
                                @if ($errors->has('setup_initial_cost'))
                                    <span class="help-block">{{ $errors->first('setup_initial_cost') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                {!! Form::label('compliance', 'Compliance Included <span class="span-req">*</span>:',array('class'=>'','for'=>'compliance'),false) !!}
                                {!! Form::select('compliance',$yes_no_arr,null,['class'=>'form-control select2','placeholder'=>'Choose ...','id'=>'compliance','required'=>'required']) !!}
                                @if ($errors->has('compliance'))
                                    <span class="help-block">{{ $errors->first('compliance') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                {!! Form::label('frequency', 'Frequency <span class="span-req">*</span>:',array('class'=>'','for'=>'frequency'),false) !!}
                                <div class="col-md-12 row">
                                    {!! Form::number('frequency_number',null,['class'=>'form-control col-md-7','placeholder'=>'Enter number','id'=>'frequency_number','required'=>'required','step'=>'0.01']) !!}
                                    @if ($errors->has('frequency_number'))
                                        <span class="help-block">{{ $errors->first('frequency_number') }}</span>
                                    @endif
                                    <div class="col-md-5">
                                        {!! Form::select('frequency_type',$frequency_types,null,['class'=>'form-control select2','placeholder'=>'Choose ...','id'=>'frequency_type','required'=>'required']) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                {!! Form::label('duration', 'Duration <span class="span-req">*</span>:',array('class'=>'','for'=>'duration'),false) !!}
                                <div class="col-md-12 row">
                                    {!! Form::number('duration_number',null,['class'=>'form-control col-md-7','placeholder'=>'Enter number','id'=>'duration_number','required'=>'required','step'=>'0.01']) !!}
                                    @if ($errors->has('duration_number'))
                                        <span class="help-block">{{ $errors->first('duration_number') }}</span>
                                    @endif
                                    <div class="col-md-5">
                                        {!! Form::select('duration_type',$frequency_types,null,['class'=>'form-control select2','placeholder'=>'Choose ...','id'=>'duration_type','required'=>'required']) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <button type="submit" class="btn btn-primary w-md">Save</button>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
        --}}
        {{--<div class="card">
            <div class="card-body mb-4">
                <div class="col-md-12">
                    <div class="col-sm-12">
                        <h3 class="custom-heading">Package List</h3>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Package Name</th>
                                    <th>Monthly Rate</th>
                                    <th>Number Of Boxes Included</th>
                                    <th>TE-5000</th>
                                    <th>Compliance</th>
                                    <th>Frequency</th>
                                    <th>Duration</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($packages as $pval )
                                <tr class="">
                                    <td>{{ $pval->name }} </td>
                                    <td>{{ $pval->monthly_rate}}</td>
                                    <td>{{ $pval->boxes_included }}</td>
                                    <td>{{ (array_key_exists($pval->te_500,$yes_no_arr)) ?$yes_no_arr[$pval->te_500]: '' }}</td>
                                    <td>{{ (array_key_exists($pval->compliance,$yes_no_arr)) ?$yes_no_arr[$pval->compliance]: '' }}</td>
                                    <td>{{ ($pval->frequency_number)?$pval->frequency_number.'/'.$frequency_types[$pval->frequency_type]:'' }} </td>
                                    <td>{{ ($pval->duration_number)?$pval->duration_number.'/'.$frequency_types[$pval->duration_type]:'' }}</td>
                                    <td>
                                        <a href="{{ route($routePrefix.'.edit',$pval->id) }}" class="btn btn-primary btn-sm btn-rounded waves-effect waves-light"><i class="bx bx-edit-alt"></i></a>
                                        <a class="btn btn-sm btn-rounded btn-danger waves-effect" data-toggle="tooltip" title="" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="event.preventDefault();
                                            document.getElementById('delete-form-{{$pval->id}}').submit();" data-original-title="Delete">{!! \Config::get('settings.icon_delete') !!}</a>
                                            {!! Form::open([
                                                'method' => 'DELETE',
                                                'route' => [
                                                $routePrefix . '.destroy',
                                                $pval->id
                                                ],
                                                'style'=>'display:inline',
                                                'id' => 'delete-form-' . $pval->id
                                                ]) !!}
                                            {!! Form::close() !!}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        --}}
    </div>
</div>
@endif



@endsection

@push('pagejs')
<script src="{{asset('/assets/libs/select2/js/select2.min.js')}}"></script>
<script src="{{asset('/assets/libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
<script src="{{asset('/assets/libs/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js')}}"></script>
<script src="{{asset('/assets/libs/bootstrap-timepicker/js/bootstrap-timepicker.min.js')}}"></script>
<script src="{{asset('/assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js')}}"></script>
<script src="{{asset('/assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js')}}"></script>
<script src="{{asset('/assets/libs/@chenfengyuan/datepicker/datepicker.min.js')}}"></script>
<script src="{{asset('/assets/js/pages/form-advanced.init.js')}}"></script>
<script src="{{asset('/assets/libs/jquery.repeater/jquery.repeater.min.js')}}"></script>
@endpush

