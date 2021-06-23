
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



            <form method="post" action="{{route('package.editpackage', ['id'=> $id ] )}}">
                    {{csrf_field()}}
                    <div class="row">
                        <div class="col-sm-12">
                            <h3 class="custom-heading">Package</h3>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Package Name*</label>
                                <input name="name" type="text" class="form-control" value="{{ $package->name ?? '' }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Package Amount/month</label>
                                <input name="price" type="text" class="form-control" value="{{ $package->price ?? '' }}">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Frequency of Type</label>
                                <select name="frequency_type" class="form-control">
                                    <option value="">Select</option>
                                    <option {{ $package->frequency_type == 'weekly' ?  'selected' : ''  }} value="weekly">weekly</option>
                                    <option {{ $package->frequency_type == 'monthly' ?  'selected' : ''  }} value="monthly">monthly</option>
                                    <option {{ $package->frequency_type == 'yearly' ?  'selected' : ''  }} value="monthly">yearly</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Number of Frequency</label>
                                <input name="frequency_number" type="number"  min="1" class="form-control" value="{{ $package->frequency_number ?? '' }}" >
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Box Included</label>
                                <input name="boxes_included" type="number"  min="1" class="form-control" value="{{ $package->boxes_included ?? '' }}"  >
                            </div>
                        </div>


                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="includes_te"> Includes Te </label>
                                <select name="includes_te" class="form-control">
                                    <option value="">Select</option>
                                    <option {{ $package->includes_te == '1' ? 'selected' : '' }}  value="1">Yes</option>
                                    <option {{ $package->includes_te == '0' ? 'selected' : '' }}  value="0">No</option>
                                </select>
                            </div>
                        </div>


                        
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="container_type"> Container Type</label>
                                <input name="container_type" type="text" class="form-control" value="{{ $package->container_type ?? '' }}"  >
                               
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="includes_compliance"> Includes Compliance</label>
                                <select name="includes_compliance" class="form-control">
                                    <option value="">Select</option>
                                    <option {{ $package->includes_compliance == '1' ? 'selected' : '' }}  value="1">Yes</option>
                                    <option {{ $package->includes_compliance == '0' ? 'selected' : '' }}  value="0">No</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="te_monthly_rate"> Monthly Rate</label>
                                <input name="te_monthly_rate" type="number"  min="1" class="form-control" value="{{ $package->te_monthly_rate ?? '' }}"  > 
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="container_monthly_rate"> Container Monthly Rate</label>
                                <input name="container_monthly_rate" type="number"  min="1" class="form-control"  value="{{ $package->container_monthly_rate  ?? '' }}"  > 
                            </div>
                        </div>



                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Duration of Type</label>
                                <select name="duration_type" class="form-control">
                                    <option value="">Select</option>
                                    <option  {{ $package->duration_type == 'weekly' ?  'selected' : ''}} value="weekly">weekly</option>
                                    <option  {{ $package->duration_type == 'monthly' ?  'selected' : ''}} value="monthly">monthly</option>
                                    <option  {{ $package->duration_type == 'yearly' ?  'selected' : ''}} value="monthly">yearly</option>
                                </select>
                            </div>
                        </div>


                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Number of Pickup</label>
                                <input name="duration_number" type="number"  min="1" class="form-control" value="{{ $package->duration_number ?? '' }}"  >
                            </div>
                        </div>


                        

                    </div>

                    <!-- <div class="repeater">
                        <div data-repeater-list="group-a">
                            <div data-repeater-item class="row">
                                <div class="form-group col-lg-3">
                                    <label class="d-block mb-3">Waste Type</label>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input  name="waste_type_p" type="radio" id="waste_type_p1" class="custom-control-input" value="redbag">
                                        <label class="custom-control-label" for="waste_type_p1">Red Bag.</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input name="waste_type_p" type="radio" id="waste_type_p2"  class="custom-control-input" value="sharp">
                                        <label class="custom-control-label" for="waste_type_p2">Sharp.</label>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <hr>



                    </div> -->


                    <div class="text-right">
                        <hr>
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