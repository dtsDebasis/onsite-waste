@extends('admin.customer.create.createlayout')


@section('create-customer-content')

<!-- tab2 -->
<div class="tab-pane active" id="contacts-1" role="tabpanel">
    <div class="card">
    @php($permission_levels = ['1' => 'Corporate Level','2' => 'Branch Level'])
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <div class="card-body mb-4">
            @php($relationshipRoles = \App\Helpers\Helper::getRelationshiRoles())
            <form method="post"  class="checkediting" action="{{route('customers.create.contact', ['id'=> $id  ])}}">
                {{csrf_field()}}
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="custom-heading">Contacts</h3>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>First Name*</label>
                            <input name="first_name" type="text" class="form-control"    required >
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>last Name*</label>
                            <input name="last_name" type="text" class="form-control"  required >
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Phone</label>
                            <input name="phone" type="text" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Email*</label>
                            <input name="email" type="email" class="form-control" required >
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Position </label>
                            <input name="designation" type="text" class="form-control">

                            {{--<select name="designation" id="designation" class="form-control" >

                                @foreach ($designations as $designation )
                                <option  >{{ $designation->name ??  "" }}</option>
                                @endforeach
                            </select>--}}

                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{--<label>Role*</label>
                            <select name="company_relationship" class="form-control select2">
                                <option value="">Choose ...</option>
                                @foreach($relationshipRoles as $rRole)
                                <option value="{{$rRole}}">{{$rRole}}</option>
                                @endforeach
                            </select>
                            --}}


                            {!! Form::label('company_relationship', 'Role *:',array('class'=>'','for'=>'company_relationship'),false) !!}
                            {!! Form::select('company_relationship',$relationshipRoles,null,['class'=>'form-control select2','id'=>'company_relationship','placeholder'=>'Choose ...','required'=>'required']) !!}
                            @if ($errors->has('company_relationship'))
                                <span class="help-block">{{ $errors->first('company_relationship') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('permission_level', 'Permission Level *:',array('class'=>'','for'=>'permission_level'),false) !!}
                            {!! Form::select('permission_level',$permission_levels,null,['class'=>'form-control select2','id'=>'permission_level','placeholder'=>'Choose ...','required'=>'required']) !!}
                            @if ($errors->has('permission_level'))
                                <span class="help-block">{{ $errors->first('permission_level') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <hr>
                <div class="text-right">
                    <button type="submit" class="btn btn-primary w-md">Save</button>
                </div>
            </form>


            <div class="row">
                <div class="col-md-12">
                    <div class="">
                        <h3 class="custom-heading">Contact List</h3>
                    </div>
                    <form class="form-inline checkediting mb-4" method="GET" action="{{url('admin/customers/create/contact/'.$id)}}">

                            <input type="text" class="form-control mr-2" value="{{request()->get('srch_params')}}" name="srch_params" placeholder="Search by name,email and phone">
                            <button type="submit" class="btn btn-primary mr-2">Filter</button>
                            <a href="{{url('admin/customers/create/contact/'.$id)}}" class="btn btn-danger">Reset</a>
                    </form>
                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Designation</th>
                                    <th>Role</th>
                                    {{--<th>Assign Site/Branch</th>--}}
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($contact_list as $contact )
                                <tr class="">
                                    <td>{{ $contact->first_name ?? '' }} </td>
                                    <td>{{ $contact->last_name ?? '' }}</td>
                                    <td>
                                        <a href="tel:{{ $contact->phone ?? '' }}"> {{ $contact->phone ?? '' }}</a>
                                    </td>
                                    <td>{{ $contact->email ?? '' }}</td>
                                    <td> {{ $contact->designation ?? '' }}</td>
                                    <td>{{  (array_key_exists($contact->company_relationship,$relationshipRoles)) ?$relationshipRoles[$contact->company_relationship]: '' }}</td>
                                    {{--<td><a href="javascript:void(0);" class="btn btn-outline-secondary waves-effect  assign-btn" user_id="{{$contact->id}}" data-company_id="{{$contact->id}}">Assign</a></td>--}}
                                    <td>
                                        <button type="button" class="btn btn-primary btn-sm btn-rounded waves-effect waves-light    edit-contact-user" user_id="{{ $contact->id ?? ''  }}" first_name="{{ $contact->first_name ?? '' }}" last_name="{{ $contact->last_name ?? '' }}" phone="{{ $contact->phone ?? '' }}" email="{{ $contact->email ?? '' }}" designation="{{ $contact->designation ?? '' }}" company_relationship="{{ $contact->company_relationship ?? '' }}" permission_level="{{$contact->permission_level ?? ''}}"><i class="bx bx-edit-alt"></i></button>
                                        <a href="{{route('customers.assign_password',$contact->id)}}" class="btn btn-primary btn-sm btn-rounded">Assign Password</a>

                                        <a class="btn btn-sm btn-rounded btn-danger waves-effect" data-toggle="tooltip" title="" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="event.preventDefault();
                                        document.getElementById('delete-form-{{$contact->id}}').submit();" data-original-title="Delete">{!! \Config::get('settings.icon_delete') !!}</a>
                                        {!! Form::open([
                                            'method' => 'DELETE',
                                            'route' => [
                                             'customers.contact-delete',[$id,$contact->id]
                                            ],
                                            'style'=>'display:inline',
                                            'id' => 'delete-form-' . $contact->id
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
{!! Form::open([
    'method' => 'POST',
    'route' => [
        'customers.assign-contact-to-branch'
    ],
    'style'=>'display:inline',
    'id' => 'assign-contact-form'
    ]) !!}
    <input type="hidden" name="company_id" value="{{$id}}" id="assign_company_id">
    <input type="hidden" name="user_id" value="" id="assign_user_id">
    <input type="hidden" name="branch_ids" value="" id="assign_branch_id">
{!! Form::close() !!}

<!-- modal 1 -->
<div class="modal fade assign-site-branch-bs-center" tabindex="-1" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">Assign Contact</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <h3 class="custom-heading">Branch List</h3>
                <div class="table-responsive">
                    <table class="table table-centered table-nowrap mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Location</th>
                                <th>Address</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($companybranch_list as $companybranch )
                            <tr class="">
                                <td>{{  $companybranch->name ?? '' }}</td>
                                <td>{{ $companybranch->addressdata->addressline1  ?? '' }}</td>
                                <td>
                                    <button type="button" class="btn btn-outline-primary waves-effect waves-light  assign-contact " company_id = "{{ $companybranch->company->id ?? '' }}" companybranch_id="{{ $companybranch->id }}"> Assign </button>
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
                <a href="javascript:;" class="btn btn-success" id="branch_assigned_submit">Submit</a>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>


<!-- modal 2 -->
<div class="modal fade edit-contact-bs-center" tabindex="-1" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">Edit Contact</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">


            <!-- <form id="modal-frm" > -->
            <form class="checkediting" id="modal-frm" method="post" action="{{route('customers.create.contact', ['id'=> $id  ])}}">
                {{csrf_field()}}

                <input type="hidden" class="user_id" name="user_id" value="" >

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>First Name*</label>
                            <input name="first_name" type="text" class="form-control  first_name"    required >
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>last Name*</label>
                            <input name="last_name" type="text" class="form-control last_name"  required >
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Phone</label>
                            <input name="phone" type="text" class="form-control  phone">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Email*</label>
                            <input name="email" type="email" class="form-control  email" required >
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Position </label>
                            <!--  enum('m', 's', 'ex', 'exa') -->
                            <input name="designation" type="text" class="form-control designation">

                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('company_relationship', 'Role *:',array('class'=>'','for'=>'company_relationship'),false) !!}
                            {!! Form::select('company_relationship',$relationshipRoles,null,['class'=>'form-control select2 company_relationship','id'=>'company_relationship','placeholder'=>'Choose ...','required'=>'required']) !!}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('permission_level', 'Permission Level *:',array('class'=>'','for'=>'permission_level_mod'),false) !!}
                            {!! Form::select('permission_level',$permission_levels,null,['class'=>'form-control select2 permission_level','id'=>'permission_level_mod','placeholder'=>'Choose ...','required'=>'required']) !!}

                        </div>
                    </div>
                </div>
                <hr>
                <!-- <div class="text-right">
                    <a href="javascript:void(0)" class="btn btn-primary w-md     save-btn">Save</a>
                </div> -->
                <div class="text-right">
                    <button type="submit" class="btn btn-primary w-md">Save</button>
                </div>
            </form>




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
    if(/create\/contact/.test(loc)) {
        $('.nav-link.contact').addClass('active');
    }


    var user_id = 0;
    $( ".assign-btn" ).click(function(){
        $('.assign-contact').removeClass('assigned');
        $('.assign-contact').text('Assign');
        $('#assign-contact-form').find('#assign_user_id').val($(this).attr('user_id'));
        //$('#assign-contact-form').find('#assign_company_id').val($(this).attr('data-company_id'));
        $('#assign-contact-form').find('#assign_branch_id').val('');
        $(".assign-site-branch-bs-center").modal('show');

    });
    $('body').on('click','.assign-contact',function(){
        var companybranch_id = $(this).attr('companybranch_id');
        $(this).toggleClass('assigned');
        if(($(this).text() == "Assign")){
            $(this).html('<i class="fa fa-check"></i> Assigned');
        }
        else{
            $(this).text("Assign");
        }
        let ids = [];
        $(".assigned").each(function(index,value) {
            ids.push($(this).attr('companybranch_id'));
        });
        $('#assign-contact-form').find('#assign_branch_id').val(ids.toString());
    });
    $('body').on('click','#branch_assigned_submit',function(){
        var packages = $('#assign-contact-form').find('#assign_branch_id').val();
        if(packages == null || packages == undefined || packages == ''){
            bootbox.alert({
                title:"Branch Assign",
                message: 'Please select branch.' ,
                type:"error"
            });
        }
        else{
            bootbox.confirm({
                message: "Are You Sure? Do you want to continue this action?",
                buttons: {
                    confirm: {
                        label: 'Yes',
                        className: 'btn-success'
                    },
                    cancel: {
                        label: 'No',
                        className: 'btn-danger'
                    }
                },
                callback: function (result) {
                if(result){
                        $('#assign-contact-form').submit();
                    }
                }
            });
        }
    });
    $(".assign-contact1").on("click", function () {
        console.log("dddd");

        let that = $(this);

        //let user_id = $(this).attr('user_id');
        let company_id = $(this).attr('company_id');
        let companybranch_id = $(this).attr('companybranch_id');

        console.log(user_id, company_id, companybranch_id);

        let assign = $.ajax({
            type: 'PUT',
            url: "{{ route('customers.create.contact', ['id' => $id] ) }}",
            data: {"_token": "{{ csrf_token() }}", "user_id": user_id, "company_id": company_id, "companybranch_id":companybranch_id },
            success: function(resultData) {
                console.log("teswt", resultData.data.success );
                //
                //that.parent().find(".check-icon").show();
                that.text("Assigned");
                that.removeClass("btn-outline-secondary  waves-effect waves-light");
                that.addClass("btn-outline-primary");
                window.location.reload();
            }
        });
        assign.error(function() { console.log("Something went wrong"); });



    });



    ////  edit-contact-user
    // modal click on
    $( ".edit-contact-user" ).click(function(){
        console.log("ttttt");
        $(".edit-contact-bs-center").modal();
        user_id = $(this).attr('user_id');


        $(".edit-contact-bs-center .user_id").val(user_id);
        $(".edit-contact-bs-center .first_name").val( $(this).attr('first_name') );
        $(".edit-contact-bs-center .last_name").val( $(this).attr('last_name') );
        $(".edit-contact-bs-center .phone").val($(this).attr('phone'));
        $(".edit-contact-bs-center .email").val($(this).attr('email'));
        $(".edit-contact-bs-center .designation").val($(this).attr('designation'));
        $(".edit-contact-bs-center .company_relationship").val( $(this).attr('company_relationship') );
        $('.company_relationship').select2().trigger('change');
        $(".edit-contact-bs-center .permission_level").val( $(this).attr('permission_level') );
        $('.permission_level').select2().trigger('change');
    });

    /*
    //edit-contact-user save-btn
    $( ".edit-contact-bs-center  .save-btn" ).click(function(){

        //let data = $('.edit-contact-bs-center #modal-frm').serialize();
        //let data = $('.edit-contact-bs-center #modal-frm').serializeArray();



        let user_id = $('.edit-contact-bs-center #modal-frm .user_id').val();
        let first_name = $('.edit-contact-bs-center #modal-frm .first_name').val();
        let last_name = $('.edit-contact-bs-center #modal-frm .last_name').val();
        let phone = $('.edit-contact-bs-center #modal-frm .phone').val();
        let email = $('.edit-contact-bs-center #modal-frm .email').val();
        let designation = $('.edit-contact-bs-center #modal-frm .designation').val();
        let company_relationship = $('.edit-contact-bs-center #modal-frm .company_relationship').val();

        console.log("wwwwwww", user_id);


        let assign = $.ajax({
            type: 'POST',
            url: "{{ route('customers.create.contact', ['id' => $id] ) }}",
            data:  { "_token": "{{ csrf_token() }}",
             "user_id": user_id,
             "first_name": first_name,
             "last_name":last_name,
             "phone":phone,
             "email":email,
             "designation": designation,
             "company_relationship":company_relationship
            },
            success: function(resultData) {
                //console.log("teswt", resultData.data.success );
                window.location.reload();
                //that.parent().find(".check-icon").show();


            }
        });
        assign.error(function() { console.log("Something went wrong"); });
    });
    */



});
</script>
@endsection('create-customer-content')
