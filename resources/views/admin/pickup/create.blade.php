
@extends('admin.layouts.layout')
@push('pageplugincss')

@endpush

@section('content')
<!-- <div class="row"> -->
        <!-- <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0 font-size-18">Add PickUp Schedule</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboards</a></li>
                        <li class="breadcrumb-item active">Add PickUp Schedule</li>
                    </ol>
                </div>

            </div>
        </div>
    </div> -->
    <!-- end page title -->

    <div class="card-body card mb-4">
        <form method="post" action="{{route('pickup.create',  $pickupid )}}" >
                {{csrf_field()}}
            <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="formrow-email-input">Customer Name*</label>
                    <select class="form-control" name="eqp_ownership_id" id="eqp_ownership_id">
                    <option value="">Select</option>
                    <option value="1" data-attr="COMPANY_OWNED" class="eqp_ownership_id">William</option>
                    <option value="2" data-attr="LEASED" class="eqp_ownership_id">Isabella</option>
                    <option value="3" data-attr="OWNER_OPERATED" class="eqp_ownership_id">James</option>
                </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="formrow-email-input">Location*</label>
                    <select class="form-control" name="eqp_ownership_id" id="eqp_ownership_id">
                    <option value="">Select</option>
                    <option value="1" data-attr="COMPANY_OWNED" class="eqp_ownership_id">Vital Health and Hope</option>
                    <option value="2" data-attr="LEASED" class="eqp_ownership_id">Health and Hope</option>
                    <option value="3" data-attr="OWNER_OPERATED" class="eqp_ownership_id">Vital Health</option>
                </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="formrow-email-input">Date</label>
                    <input type="date" class="form-control" id="formrow-email-input">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="formrow-email-input">Provider Name</label>
                    <input type="email" class="form-control" id="formrow-email-input">
                </div>
            </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="formrow-email-input">Address </label>
                <textarea name="" style="max-height: 80px;" id="" class="form-control" cols="10" rows="10"></textarea>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="formrow-email-input">Note </label>
                <textarea name="" style="max-height: 80px;" id="" class="form-control" cols="10" rows="10"></textarea>
            </div>
        </div>
            </div>
        </form>
        <div class="text-right">
            <button type="submit" class="btn btn-primary w-md">Submit</button>
        </div>
</div>

@endsection

@push('pagejs')

@endpush

