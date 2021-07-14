@extends('admin.customer.create.createlayout')


@section('create-customer-content')
<!-- tab3 -->
@php($rb_container_type = ['Rocker' => 'Rocker', 'Open' => 'Open'])
@php($sh_container_type = ['Spinner' => 'Spinner', 'Rocker' => 'Rocker'])
<?php
if ($sh_container && !in_array($sh_container, $sh_container_type))
{
    $sh_container_type[$sh_container] = $sh_container;
}

if ($rb_container && !in_array($rb_container, $rb_container_type))
{
    $rb_container_type[$rb_container] = $rb_container;
}
?>
<div class="tab-pane active" id="profile-1" role="tabpanel">
    <div class="card">
        <div class="card-body mb-4">
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            @if($companybranch)
                {!! Form::model($companybranch, [
                'method' => 'PATCH',
                'route' => ['customers.create.branch-store-update',[$id,'brnch'=>$companybranch->id]],
                'class' => 'form-horizontal ',
                'id'=>'branch-form',
                'enctype'=>'multipart/form-data'
                ]) !!}
            @else
                {!! Form::open(array('route' => ['customers.create.branch-store-update',[$id]],'method'=>'POST', 'enctype'=>'multipart/form-data','id'=>'branch-form')) !!}
            @endif
                <input type="hidden" name="id" value="{{($companybranch)?$companybranch->id:0}}">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="custom-heading">LOCATION</h3>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            @php($specialites = \App\Helpers\Helper::getCompanySpecialitiesDropdown($id))
                            @php($brnSpe = (Request::old('specialities')) ? Request::old('specialities') : \App\Helpers\Helper::getCompnyBranchSpeciality($company->id,($companybranch)?$companybranch->id:null))
                            {!! Form::label('speciality', 'Specialty *:',array('class'=>'','for'=>'speciality'),false) !!}
                            {!! Form::select('specialities[]',$specialites,$brnSpe,['class'=>'form-control select2','required' => 'required','multiple' =>'multiple','id'=>'speciality','data-placeholder'=>'Choose ...']) !!}
                            @if ($errors->has('speciality'))
                                <span class="help-block">{{ $errors->first('speciality') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            {!! Form::label('name', 'Name *:',array('class'=>'','for'=>'name'),false) !!}
                            {!! Form::text('name',null,['class'=>'form-control','id' => 'name','required' => 'required']) !!}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            {!! Form::label('phone', 'Phone :',array('class'=>'','for'=>'phone'),false) !!}
                            {!! Form::number('phone',null,['class'=>'form-control','id' => 'phone','min'=>0]) !!}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            {!! Form::label('uniq_id', 'Branch Code *:',array('class'=>'','for'=>'uniq_id'),false) !!}
                            {!! Form::number('uniq_id',$branch_code,['class'=>'form-control','id' => 'uniq_id','required' => 'required']) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('sh_rop', 'SH ROP :',array('class'=>'','for'=>'sh_rop'),false) !!}
                            {!! Form::number('sh_rop',null,['class'=>'form-control','id' => 'sh_rop']) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('sh_container_type', 'SH Container Type :',array('class'=>'','for'=>'sh_container_type'),false) !!}
                            {!! Form::select('sh_container_type',$sh_container_type,null,['class'=>'form-control','id' => 'sh_container_type','placeholder' => 'Choose ...']) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('rb_rop', 'RB ROP :',array('class'=>'','for'=>'rb_rop'),false) !!}
                            {!! Form::number('rb_rop',null,['class'=>'form-control','id' => 'rb_rop']) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('rb_container_type', 'RB Container Type :',array('class'=>'','for'=>'rb_container_type'),false) !!}
                            {!! Form::select('rb_container_type',$rb_container_type,null,['class'=>'form-control','id' => 'rb_container_type','placeholder' => 'Choose ...']) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('status', 'Status *:',array('class'=>'','for'=>'loc_status'),false) !!}
                            {!! Form::select('status',['1' => 'Active','0' => 'Inactive'],null,['class'=>'form-control','id' => 'loc_status','required' => 'required']) !!}
                        </div>
                    </div>
                    <input type="hidden" name="package_price" id="form_package_price" value="">
                    @include('includes.address-auto-fill')
                    @include('includes.billing-address-auto-fill')
                    <div class="col-md-4"></div>
                    @php($includedContact_ids = [])
                    <div class="row col-md-12" id="contact_list_append">
                        @if(isset($companybranch->branchusers) && $companybranch->branchusers)
                            @foreach($companybranch->branchusers as $contact)
                                @if($contact->user)
                                    @php($includedContact_ids[] = $contact->user->id)
                                    <div class="col-md-4 bg-gradient appended_contact appendafter-{{$contact->user->id}}" id="appended_contact_{{$contact->user->id}}">
                                        <input type="hidden" name="branch_users[]"  value="{{$contact->user->id}}" >
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center ">
                                                    <div class="avatar-xs mr-3">
                                                        <span class="avatar-title rounded-circle bg-soft-primary text-primary font-size-18   ">
                                                            <i class="bx bx-user-circle"></i>
                                                        </span>
                                                    </div>
                                                    <h6 class="font-size-14 mb-0">{{$contact->user->fullname}}
                                                        <span class=" text-danger font-size-12 add_contact_delete_item del-item" data-user_id="{{$contact->user->id}}">
                                                            <i class="bx bx-trash-alt"></i>
                                                        </span>
                                                    </h6>
                                                </div>
                                                <div class="text-muted">
                                                    <div class="d-flex">
                                                        <span class="ml-2 text-truncate">Role: {{$contact->user->designation}}</span>
                                                    </div>
                                                    <div class="d-flex">
                                                        <span class="ml-2 text-truncate">Email: {{$contact->user->email}}</span>
                                                    </div>
                                                    <div class="d-flex">
                                                        <span class="ml-2 text-truncate">Phone: {{$contact->user->phone}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        @endif
                    </div>
                    <div class="col-md-12 mt-3 ">
                        <div class="form-group">
                            <a href="javascript:void(0);" class="btn btn-outline-secondary waves-effect" data-toggle="modal" data-target=".add-contact-site-branch-bs-center"><i class="fa fa-plus"></i> Add Contact</a>
                        </div>
                    </div>
                    @if(!isset($companybranch->id))
                    <div class="col-md-4"></div>
                    <div class="row col-md-12" id="package_list_append">
                    </div>
                    <div class="col-md-12 mt-3 ">
                        <div class="form-group">
                            <a href="javascript:void(0);" class="btn btn-outline-secondary waves-effect" id="add_package"><i class="fa fa-plus"></i> Add Package</a>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="text-right">
                    <button type="submit" class="btn btn-primary w-md"> Save </button>
                </div>
            {!! Form::close() !!}
            <div class="row mt-5">
                <div class="col-md-12">
                    <div class="col-sm-12">
                        <h3 class="custom-heading">List</h3>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Start Date</th>
                                    <th>OnSite Partner</th>
                                    <th>Phone No.</th>
                                    <th>Address</th>
                                    <th>Specialty</th>
                                    <th>Contacts</th>
                                    <th>Pricing</th>
                                    <th>Package</th>
                                    <th>Inventory</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($companybranch_list as $companybranch )
                                    <tr class="">
                                        <td>{{ $companybranch->uniq_id ?? '' }}</td>
                                        <td>{{ $companybranch->name ?? '' }}</td>
                                        <td>{{($companybranch->created_at)?\App\Helpers\Helper::showdate($companybranch->created_at):'NA'}}</td>
                                        @php($onsitePartners = \App\Helpers\Helper::getOnsitePartners($companybranch->company_id,$companybranch->id))
                                        <td> {{($onsitePartners)?implode(',',$onsitePartners):'NA'}}</td>
                                        <td>{{ $companybranch->phone  ?? '' }}</td>
                                        <td>{{ $companybranch->addressdata->addressline1  ?? '' }}</td>


                                        @php($comSpecialt = [])
                                        @if(isset($companybranch->branchspecialty) && count($companybranch->branchspecialty))
                                            @foreach($companybranch->branchspecialty as $sp)

                                                @if(isset($sp->speciality_details->name) && ($sp->speciality_details->name))
                                                    @php($comSpecialt[] = $sp->speciality_details->name)
                                                @endif
                                            @endforeach
                                        @endif
                                        <td>{{($comSpecialt)?implode(',',$comSpecialt):'NA'}}</td>
                                        @php($branchUsers = [])
                                        @if(isset($companybranch->branchusers) && count($companybranch->branchusers))
                                            @foreach($companybranch->branchusers as $sp)
                                                @php($branchUsers[] = $sp->user_id)
                                            @endforeach
                                        @endif
                                        <td><a href="javascript:;" id="contact_details" data-add_contacts="{{($branchUsers)?implode(',',$branchUsers):''}}" data-id="{{$companybranch->id}}" class="btn btn-sm btn-outline-warning waves-effect">Details</a></td>
                                        <td><a href="javascript:;" id="transaction_details" data-id="{{$companybranch->id}}" class="btn btn-sm btn-outline-warning waves-effect">Details</a></td>
                                        <td><a href="javascript:;" id="package_listing" data-id="{{$companybranch->id}}" class="btn btn-sm btn-outline-warning waves-effect">List</a></td>
                                        <td><a href="javascript:;" data-location_number="{{$companybranch->uniq_id}}" data-id="{{$companybranch->id}}" id="inventory-{{$companybranch->id}}" class="btn btn-sm btn-outline-warning waves-effect inventory-details">View</a></td>
                                        <td>
                                            <a href="{{route('customers.create.location', $id )}}/?edit=1&companybranchid={{$companybranch->id}}" class="btn btn-primary btn-sm btn-rounded waves-effect waves-light edit-site-branch" companybranchid="{{ $companybranch->id }}">
                                                <i class="bx bx-edit-alt"></i>
                                            </a>
                                            <a class="btn btn-sm btn-rounded btn-danger waves-effect" data-toggle="tooltip" title="" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="event.preventDefault();
                                            document.getElementById('delete-form-{{$companybranch->id}}').submit();" data-original-title="Delete">{!! \Config::get('settings.icon_delete') !!}</a>
                                            {!! Form::open([
                                                'method' => 'DELETE',
                                                'route' => [
                                                'customers.branch-destroy',[$companybranch->company_id,
                                                $companybranch->id
                                                ]],
                                                'style'=>'display:inline',
                                                'id' => 'delete-form-' . $companybranch->id
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
    </div>
</div>
<input type="hidden" id="page_type" value="create_edit">

<!-- modal 1 -->
<div class="modal fade add-contact-site-branch-bs-center" tabindex="-1" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">Add Contact</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-centered table-nowrap mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Role</th>
                                <th>Email</th>
                                <th>Phone Number</th>
                                <th>Assign Site/Branch</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($contact_list as $contact )
                                <tr class="">
                                    <td>{{ $contact->first_name ?? '' }}</td>
                                    <td>{{ $contact->last_name ?? '' }}</td>
                                    <td>{{ $contact->designation ?? '' }}</td>
                                    <td>{{ $contact->email ?? '' }}</td>
                                    <td>{{ $contact->phone ?? '' }}</td>

                                    <td>
                                        <button type="button" id="contact_list_td_{{$contact->id}}" class="{{!in_array($contact->id,$includedContact_ids)?'btn btn-outline-primary waves-effect waves-light assign-contact':'btn btn-outline-primary assign-contact pointer-none'}}" user_id = "{{ $contact->id }}" company_id = "{{ $id }}" user_name="{{ $contact->fullname ?? '' }}" user_email="{{ $contact->email }}" user_phone="{{ $contact->phone}}" user_designation="{{ $contact->designation}}">{{!in_array($contact->id,$includedContact_ids)?'Assign':'Assigned'}}</button>
                                            <span class="check-icon" style="display:none;" ><i class="bx bx-check"></i> </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="all_assingn_unassign_contact_list" tabindex="-1" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">Contact List</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-centered table-nowrap mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Role</th>
                                <th>Email</th>
                                <th>Phone Number</th>
                                <th>Assign Site/Branch</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($contact_list as $contact )
                                <tr class="">
                                    <td>{{ $contact->first_name ?? '' }}</td>
                                    <td>{{ $contact->last_name ?? '' }}</td>
                                    <td>{{ $contact->designation ?? '' }}</td>
                                    <td>{{ $contact->email ?? '' }}</td>
                                    <td>{{ $contact->phone ?? '' }}</td>

                                    <td>
                                        <button type="button" id="existing_contact_list_td_{{$contact->id}}" class="btn btn-outline-primary waves-effect waves-light existing-assign-contact" user_id = "{{ $contact->id }}" company_id = "{{ $id }}" user_name="{{ $contact->fullname ?? '' }}" user_email="{{ $contact->email }}" user_phone="{{ $contact->phone}}" user_designation="{{ $contact->designation}}">Assign</button>
                                            <span class="check-icon" style="display:none;" ><i class="bx bx-check"></i> </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                 <a href="javascript:;" class="btn btn-success" id="contact_reassign_submit">Submit</a>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
@include('admin.customer.create.tab4.modal-content')
@include('admin.customer.create.tab4.inventory-modal')

{!! Form::open([
    'method' => 'POST',
    'route' => [
        'customers.branch-assign-to-contact'
    ],
    'style'=>'display:inline',
    'id' => 'branch-assign-to-contact-form'
    ]) !!}
    <input type="hidden" name="company_id" value="{{$id}}" id="assign_company_id">
    <input type="hidden" name="user_ids" value="" id="assign_user_ids">
    <input type="hidden" name="branch_id" value="" id="selected_branch_id">
{!! Form::close() !!}

@include('includes.address-auto-fill-js')
@include('includes.billing-address-auto-fill-js')
@endsection('create-customer-content')
@section('create-customer-content-js')
@include('admin.customer.create.tab4.modal-script')
<script>
$('body').on('click','.inventory-details', function(){
    var location = $(this).data('id');
    var unique_id = $(this).data('location_number');
    $('#inventory_modal_head').text('Inventory for Location #'+unique_id);
    $.ajax({
        url: '{{url("admin/customers/partial-inventory-details")}}',
        data: {'unique_id':unique_id},
        type: 'POST',
        beforeSend: function() {
            $('.loader').show();
        },
        success: function(data) {
            $('.loader').hide();
            $('#inventory_modal_body').html(data);
            $('#inventory_modal').modal('show');
        },
        error: function(data) {

        }
    });

})
</script>
<script>
$(document).ready(function () {
    let loc = window.location.href;
    console.log(loc);
    $('.nav-link.active').removeClass('active');
    if(/create\/location/.test(loc)) {
        $('.nav-link.site').addClass('active');
    }
    $('body').on('change','#service_type', function(){
        let value = $(this).val();
        if(value == 'TE-5000'){
            $('.service-based-view').addClass('d-none');
        }
        else{
            $('.service-based-view').removeClass('d-none');
        }
    });
});
</script>
@endsection('create-customer-content')
