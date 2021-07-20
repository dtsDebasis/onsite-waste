@extends('admin.layouts.layout')
@push('pageplugincss')
<link href="{{asset('/assets/libs/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets/libs/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}"rel="stylesheet" type="text/css">
<link href="{{asset('/assets/libs/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css')}}" rel="stylesheet" type="text/css">
<link href="{{asset('/assets/libs/bootstrap-timepicker/css/bootstrap-timepicker.min.css')}}" rel="stylesheet" type="text/css">
<link href="{{asset('/assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css')}}"rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="{{asset('/assets/libs/@chenfengyuan/datepicker/datepicker.min.css')}}">
<link href="{{asset('/assets/css/style.css')}}" id="app-style" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />

@endpush
@section('content')
<style>
.pointer-none{
    pointer-events: none;
}
</style>
@if($id)
    <div class="card-body card">
        <div class="row">
            <div class="col-md-4">
                <strong> Company Name </strong>:@if(isset($company) && isset($company->company_name) && $company->company_name) {{$company->company_name}} @else NA @endif
            </div>
            <div class="col-md-3">
                <strong> Company ID </strong>: @if(isset($company) && isset($company->company_number) && $company->company_number) {{$company->company_number}}  @else NA @endif
            </div>
            <div class="col-md-3">
                <strong> Phone </strong>: @if(isset($company) && isset($company->phone) && $company->phone) {{$company->phone}}  @else NA @endif
            </div>
            <div class="col-md-2">
                 @if(isset($company))
                    <a class="btn btn-primary btn-sm btn-rounded waves-effect waves-light" href="{{ route('customers.branches', ['id' => $company->id  ] ) }}">View Company</a>
                 @endif
            </div>
        </div>
    </div>
@endif
<div class="row">

    <div class="col-md-12">
        <ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
            <li class="nav-item waves-effect waves-light">
                <a class="nav-link {{ $navlink_isactive ? 'active' : '' }}  create"  href="{{ route('customers.create', ['id' => $id ] ) }}"  >
                    <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                    <span class="d-none d-sm-block">GENERAL INFORMATION</span>
                </a>
            </li>
            <li class="nav-item waves-effect waves-light">

                <a class="nav-link {{ $navlink_isactive ? 'active' : '' }} contact"  @if($id !=0 ) href="{{ route('customers.create.contact',  ['id' => $id ] ) }}" @else href="#" @endif   >
                    <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                    <span class="d-none d-sm-block">CONTACT</span>
                </a>
            </li>
            <li class="nav-item waves-effect waves-light">

                <a class="nav-link {{ $navlink_isactive ? 'active' : '' }} package"  @if($id !=0 ) href="{{ route('customers.create.package',  ['id' => $id ]) }}" @else href="#" @endif >
                    <span class="d-block d-sm-none"><i class="far fa-envelope"></i></span>
                    <span class="d-none d-sm-block">PACKAGE </span>
                </a>
            </li>
            <li class="nav-item waves-effect waves-light">

                <a class="nav-link {{ $navlink_isactive ? 'active' : '' }} site"  @if($id !=0 ) href="{{ route('customers.create.location',  ['id' => $id ]) }}" @else href="#" @endif  >
                    <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                    <span class="d-none d-sm-block">LOCATION</span>
                </a>
            </li>

            {{--<li class="nav-item waves-effect waves-light">
                <a class="nav-link {{ $navlink_isactive ? 'active' : '' }} inventory"  href="#" >
                    <span class="d-block d-sm-none"><i class="far fa-envelope"></i></span>
                    <span class="d-none d-sm-block">INVENTORY </span>
                </a>
            </li> --}}
            @if($id !=0 )
            @if (can('Pickup Edit') && can('Pickup Add') && can('Pickup Delete') && can('Pickup Add Manifest'))
                <li class="nav-item waves-effect waves-light">
                <a class="nav-link {{ $navlink_isactive ? 'active' : '' }} hauling" @if($id !=0 ) href="{{ route('customers.create.hauling',  ['id' => $id ] ) }}" @else href="#" @endif >
                    <span class="d-block d-sm-none"><i class="fas fa-cog"></i></span>
                    <span class="d-none d-sm-block">PICKUP </span>
                </a>
            </li>
            @endif

            @endif
            {{--<li class="nav-item waves-effect waves-light">
                <a class="nav-link {{ $navlink_isactive ? 'active' : '' }} document" href="#">
                    <span class="d-block d-sm-none"><i class="fas fa-cog"></i></span>
                    <span class="d-none d-sm-block">DOCUMENT</span>
                </a>
            </li>
            <li class="nav-item waves-effect waves-light">
                <a class="nav-link {{ $navlink_isactive ? 'active' : '' }} invoices"  @if($id !=0 ) href="{{ route('customers.create.invoices',  ['id' => $id ]) }}" @else href="#" @endif>
                    <span class="d-block d-sm-none"><i class="fas fa-cog"></i></span>
                    <span class="d-none d-sm-block">INVOICE</span>
                </a>
            </li>--}}
        </ul>
        <div class="tab-content text-muted">


            {{-- content starts --}}
            @yield('create-customer-content')
            {{--  content ends --}}
        </div>
    </div>
</div>



<!-- Modal -->
<div class="modal fade exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel">Add Manifest</h4>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                    </button>
            </div>
            <div class="modal-body">
                <div class="sec-row">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="formrow-email-input">They will mention the person’s name who will pick up the boxes</label>
                                <input type="email" class="form-control" id="formrow-email-input">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="formrow-email-input">Date</label>
                                <input type="date" class="form-control" id="formrow-email-input">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="formrow-email-input">Waste Type</label>
                                <input type="email" class="form-control" id="formrow-email-input">
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="formrow-email-input">Container Type</label>
                                <select class="form-control" name="eqp_ownership_id" id="eqp_ownership_id">
                                    <option value="">Select</option>
                                    <option value="1" data-attr="COMPANY_OWNED" class="eqp_ownership_id">Additional Trip Price</option>
                                    <option value="2" data-attr="LEASED" class="eqp_ownership_id">Additional Box Price</option>
                                    <option value="3" data-attr="OWNER_OPERATED" class="eqp_ownership_id">Additional Waste Type + Box Type</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="formrow-email-input">Number of Container</label>
                                <input type="email" class="form-control" id="formrow-email-input">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <h5 class="font-size-13">Upload proof of pick up</h5>
                            <div class="input-group mb-3">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="inputGroupFile03" aria-describedby="inputGroupFileAddon03">
                                    <label class="custom-file-label" for="inputGroupFile03">Choose file</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="formrow-email-input">Branch Address </label>
                                <textarea name="" style="max-height: 80px;" id="" class="form-control" cols="10" rows="10"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="reset" class="btn btn-secondary">Reset</button>
                <button type="button" class="btn btn-primary">Add Manifest</button>
            </div>
        </div>
    </div>
</div>
<!-- end modal -->




@endsection
@push('pagejs')
<script src="{{asset('/assets/libs/select2/js/select2.min.js')}}"></script>
<script src="{{asset('/assets/libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
<script src="{{asset('/assets/libs/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js')}}"></script>
<script src="{{asset('/assets/libs/bootstrap-timepicker/js/bootstrap-timepicker.min.js')}}"></script>
<script src="{{asset('/assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js')}}"></script>
<script src="{{asset('/assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js')}}"></script>
<script src="{{asset('/assets/libs/@chenfengyuan/datepicker/datepicker.min.js')}}"></script>

<!-- form advanced init -->
<script src="{{asset('/assets/js/pages/form-advanced.init.js')}}"></script>

<script src="{{asset('/assets/js/app.js')}}"></script>

{{-- content starts --}}
@yield('create-customer-content-js')
{{--  content ends --}}


@endpush
