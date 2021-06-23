<div class="col-lg-12 col-xs-12 col-sm-12 col-md-12 m-t-15">
    @if($id)
    <div class="card">
        <div class="card-body row"> 
               
                <div class="col-md-6">
                    <strong> Company Name </strong>:@if(isset($company) && isset($company->company_name) && $company->company_name) {{$company->company_name}} @else NA @endif
                </div>
                
                <div class="col-md-6">
                    <Strong> Company ID <strong>: @if(isset($company) && isset($company->company_number) && $company->company_number) {{$company->company_number}}  @else NA @endif
                </div>
        </div>
    </div>
    @endif
    @if(count($companybranch_list))
    <div class="row">
        @foreach($companybranch_list as $companybranch )
        <div class="col-lg-6 col-md-6 col-sm-7 col-xs-6">
            <div class="card">
                <div class="col-sm-12 row text-right">
                    <div class="col-sm-3"><strong>Details</strong>:</div>
                    @php($branchUsers = [])
                    @if(isset($companybranch->branchusers) && count($companybranch->branchusers))
                        @foreach($companybranch->branchusers as $sp)
                            @php($branchUsers[] = $sp->user_id)
                        @endforeach
                    @endif
                    <div class="col-sm-9">
                        <a href="javascript:;" id="assigned_contact_list" data-add_contacts="{{($branchUsers)?implode(',',$branchUsers):''}}" data-id="{{$companybranch->id}}" class="btn btn-primary"><i class="fa fa-user"></i></a>
                        <a href="javascript:;" id="transaction_details" data-id="{{$companybranch->id}}" class="btn btn-primary"><i class="bx bxs-package"></i></a>
                        <a href="javascript:;" id="package_listing" data-id="{{$companybranch->id}}" class="btn btn-primary"><i class="fa fa-gift"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <strong>Name:</strong>
                        {{ $companybranch->name }}
                    </div>
                    <div class="form-group">
                        <strong>Address Line 1:</strong>
                        {{ ($companybranch->addressdata)?$companybranch->addressdata->addressline1:'NA' }}
                    </div>
                    <div class="form-group">
                        <strong>Address Line 2:</strong>
                        {{ ($companybranch->addressdata)?$companybranch->addressdata->address1:'NA' }}
                    </div>
                    <div class="form-group">
                        <strong>Specialty:</strong>
                        @php($comSpecialt = [])
                        @if(isset($companybranch->branchspecialty) && count($companybranch->branchspecialty))
                            @foreach($companybranch->branchspecialty as $sp)
                                @if(isset($sp->speciality_details->name) && ($sp->speciality_details->name))
                                    @php($comSpecialt[] = $sp->speciality_details->name)
                                @endif
                            @endforeach
                        @endif
                        {{($comSpecialt)?implode(',',$comSpecialt):'NA'}}
                    </div>
                    
                </div> 
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="card col-md-12">
        <div class="card-head">Branch not found</div>
    </div>
    @endif
</div>
@include('admin.customer.create.tab4.modal-content')
<input type="hidden" id="page_type" value="view_details">
@push('pagejs')
    @include('admin.customer.create.tab4.modal-script')
@endpush