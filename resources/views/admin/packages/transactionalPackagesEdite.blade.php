
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
                <form method="post" action="{{route('package.edittransactionalpackages',['id'=> $id] )}}">
                {{csrf_field()}}
                    <div class="row">

                        <div class="col-sm-12">
                            <h3 class="custom-heading">Transactional Pricing</h3>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Additional Trip Name</label>
                                <input name="name" type="text" class="form-control" value="{{ $transactionalpackage->name ?? '' }}"  required>
                            </div>
                        </div>

                        

                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Additional Trip Cost</label>
                                <input name="add_trip_cost" type="number"  min="1" class="form-control" value="{{ $transactionalpackage->add_trip_cost ?? '' }}"  required>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Additional Box Cost</label>
                                <input name="add_box_cost" type="number"  min="1" class="form-control" value="{{ $transactionalpackage->add_box_cost ?? '' }}"  required>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Additional Box Cost</label>
                                <input name="container_rate" type="number"  min="1" class="form-control" value="{{ $transactionalpackage->container_rate ?? '' }}"  required>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Shipping Charge</label>
                                <input name="shipping_charge" type="number"  min="1" class="form-control" value="{{ $transactionalpackage->shipping_charge ?? '' }}"  required>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Setup Charge</label>
                                <input name="setup_charge" type="number"  min="1" class="form-control" value="{{ $transactionalpackage->setup_charge ?? '' }}"  required>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>complaince_training</label>
                                <input name="complaince_training" type="text" class="form-control" value="{{ $transactionalpackage->complaince_training ?? '' }}"  required>
                            </div>
                        </div>



                        {{--

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="d-block mb-3">Waste Type</label>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input
                                    type="radio"
                                    id="customRadioInline1"
                                    name="waste_type"
                                    class="custom-control-input"
                                    value="redbag"
                                    {{ $transactionalpackage->waste_type == 'redbag' ? 'checked' : '' }}
                                    >
                                    <label class="custom-control-label" for="customRadioInline1">Red Bag</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input 
                                        type="radio"
                                        id="customRadioInline2"
                                        name="waste_type"
                                        class="custom-control-input"
                                        value="sharp"
                                        {{ $transactionalpackage->waste_type == 'sharp' ? 'checked' : '' }}
                                        >
                                    <label class="custom-control-label" for="customRadioInline2">Sharp</label>
                                </div>
                            </div>
                        </div>
                        --}}


                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Box Size</label>
                                <select name="box_size" class="form-control">
                                    <option value="">Select</option>
                                    <option  {{ $transactionalpackage->box_size == '1' ? 'selected' : ''  }} value="1">1</option>
                                    <option  {{ $transactionalpackage->box_size == '2' ? 'selected' : ''  }} value="2">2</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Per Box Price</label>
                                <input  name="price" type="text" class="form-control" value="{{  $transactionalpackage->price  ??  '' }}" >
                            </div>
                        </div>
                        <div class="col-md-12">
                            <hr>
                            <!-- <a href="#" class="btn btn-outline-secondary waves-effect"><i class="fa fa-plus"></i> Add More</a> -->
                            <hr>
                        </div>


                    </div>
                    <div class="row">

                    </div>
                    <div class="text-right">
                        <button type="submit" class="btn btn-primary w-md">Save</button>
                    </div> 

                </form>






            </div>
        </div>
    </div>
</div>

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