
@extends('admin.customer.create.createlayout')


@section('create-customer-content')

<!-- tab1 -->
<div class="tab-pane active" id="home-1" role="tabpanel">
    <div class="card">
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <form method="post" action="{{route('customers.create', ['id'=> $id  ])}}">
            {{csrf_field()}}
            <div class="card-body mb-4">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="custom-heading">General Information </h3>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Company Name*</label>
                            @php($company_name = Request::old('company_name')?Request::old('company_name'):(Request::get('company_name')?Request::get('company_name'):(isset($company->company_name)?$company->company_name:null)))
                            <input type="text" class="form-control" name="company_name"  value="{{ $company_name }}" >
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Company ID *</label>
                            <input id="company_number" type="text" class="form-control" name="company_number" value="{{ $company->company_number ?? '' }}" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Phone Number</label>
                            @php($company_phone = Request::old('phone')?Request::old('phone'):(Request::get('company_phone')?Request::get('company_phone'):(isset($company->phone)?$company->phone:null)))
                            <input type="text" class="form-control" name="phone" value="{{ $company_phone }}" >
                        </div>
                    </div>
                    {{--<div class="col-md-4">
                        <div class="form-group">
                            <label>Email*</label>
                            @php($company_email = Request::old('email')?Request::old('email'):(Request::get('compnay_email')?Request::get('compnay_email'):(isset($company->email)?$company->email:null)))
                            <input type="text" class="form-control" name="email" value="{{ $company_email }}">
                        </div>
                    </div>--}}

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Website Link</label>
                            <input type="text" class="form-control" name="website" value="{{ $company->website ?? '' }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        @php($company_speciality = (isset($company->speciality) && count($company->speciality)?$company->speciality->pluck('specality_id','id')->toArray():[]))
                        {{--<div class="form-group">
                            <label>Speciality*</label>
                            <select class="select2 form-control select2-multiple" multiple="multiple"
                                data-placeholder="Choose ..." name="specilty[]">
                                @if(!empty($specilities))
                                    @foreach($specilities as $specility)
                                    <option
                                        value="{{ $specility->id  ?? '' }}"
                                        @if( in_array($specility->id, $company_speciality))  selected   @endif
                                    >
                                    {{$specility->name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>--}}
                        <div class="form-group">
                            @php($specialites = \App\Helpers\Helper::getSpecialities())
                            @php($company_speciality = (isset($company->speciality) && count($company->speciality)?$company->speciality->pluck('specality_id','id')->toArray():[]))
                            {!! Form::label('speciality', 'Specialty *:',array('class'=>'','for'=>'speciality'),false) !!}
                            {!! Form::select('specialities[]',$specialites,$company_speciality,['class'=>'form-control select2','required' => 'required','multiple' =>'multiple','id'=>'speciality','data-placeholder'=>'Choose ...']) !!}
                            @if ($errors->has('speciality'))
                                <span class="help-block">{{ $errors->first('speciality') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            @php($company_owners = \App\Helpers\Helper::getCompanyOwners())
                            {{--<label>Company Owner</label>
                            <input type="text" class="form-control" name="owner" value=""> --}}
                            @php($owner = (Request::old('owner')) ? Request::old('owner') : (($company->owner) ? $company->owner : null))
                            {!! Form::label('owner', 'Company Owner *:',array('class'=>'','for'=>'owner'),false) !!}
                            {!! Form::select('owner',$company_owners,$owner,['class'=>'form-control select2','id'=>'owner','placeholder'=>'Choose ...','required' => 'required']) !!}
                            @if ($errors->has('owner'))
                                <span class="help-block">{{ $errors->first('owner') }}</span>
                            @endif
                        </div>
                    </div>


                </div>

                <div class="row">

                    @include('includes.address-auto-fill')

                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="custom-heading">Source</h3>
                    </div>
                    @php($lead_sources1 = \App\Helpers\Helper::getLeadSources(1))
                    @php($lead_sources2 = \App\Helpers\Helper::getLeadSources(2))
                    <div class="col-md-6">
                        <div class="form-group">
                            {{--<label>Lead Source 1</label>
                            <input type="text" class="form-control" name="leadsource_1" value="{{ $company->lead_source ?? '' }}">--}}
                            @php($leadsource_1 = (Request::old('leadsource_1')) ? Request::old('leadsource_1') : (($company->lead_source) ? $company->lead_source : null))
                            {!! Form::label('leadsource_1', 'Lead Source 1 :',array('class'=>'','for'=>'leadsource_1'),false) !!}
                            {!! Form::select('leadsource_1',$lead_sources1,$leadsource_1,['class'=>'form-control select2','id'=>'leadsource_1','placeholder'=>'Choose ...']) !!}
                            @if ($errors->has('leadsource_1'))
                                <span class="help-block">{{ $errors->first('leadsource_1') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {{--<label>Lead Source 2</label>
                            <input type="text" class="form-control" name="leadsource_2" value="{{ $company->leadsource_2 ?? '' }}" > --}}
                            @php($leadsource_2 = (Request::old('leadsource_2')) ? Request::old('leadsource_2') : (($company->leadsource_2) ? $company->leadsource_2 : null))
                            {!! Form::label('leadsource_2', 'Lead Source 2 :',array('class'=>'','for'=>'leadsource_2'),false) !!}
                            {!! Form::select('leadsource_2',$lead_sources2,$leadsource_2,['class'=>'form-control select2','id'=>'leadsource_2','placeholder'=>'Choose ...']) !!}
                            @if ($errors->has('leadsource_2'))
                                <span class="help-block">{{ $errors->first('leadsource_2') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                @php($key = 0)
                @if($id)

                    <div class="row">
                        <div class="col-sm-12">
                            <h3 class="custom-heading">Corporate Contacts</h3>
                        </div>
                        <div class="col-md-12" id="multiple_contact_person_div">
                            @if( count($contacts_list) > 0 )
                                @foreach($contacts_list as $key=>$contact)
                                    @php($selectedUserId = $contacts_list[$key]['user_id'])
                                    @php($pointer_event = 'pointer-none')
                                    @php($selectedUserEmail = isset($contacts_list[$key]['user']['email'])?$contacts_list[$key]['user']['email']:null)
                                    @php($selectedUserPhone = isset($contacts_list[$key]['user']['phone'])?$contacts_list[$key]['user']['phone']:null)
                                    @php($selectedIsOwner = isset($contacts_list[$key]['is_owner'])?$contacts_list[$key]['is_owner']:null)
                                    @include('admin.customer.create.tab1.corporate-contacts')
                                @endforeach
                            @else
                                @include('admin.customer.create.tab1.corporate-contacts')
                            @endif
                        </div>
                        <input type="hidden" id="contact_person_last_row" value="{{$key}}">
                        <div class="col-lg-12 mb-3 mt-3">
                            <a href="javascript:;" id="add_contact_person" class="btn btn-primary w-md" data-target=".bs-example-modal-center"><i class="bx bx-plus"></i>Add</a>

                        </div>
                    </div>
                @elseif(Request::get('guest') && $contacts_list)
                    <div class="row">
                        <div class="col-sm-12">
                            <h3 class="custom-heading">Corporate Contacts</h3>
                        </div>
                        <div class="col-md-12" id="multiple_contact_person_div">
                                @php($selectedUserId = $contacts_list->id)
                                @php($selectedUserEmail = isset($contacts_list->email)?$contacts_list->email:null)
                                @php($selectedUserPhone = isset($contacts_list->phone)?$contacts_list->phone:null)
                                @php($enable_cross = false)
                                @php($selectedCompanyContactUsers = isset($contacts_list->full_name)?[$selectedUserId=>$contacts_list->full_name]:null)
                                @include('admin.customer.create.tab1.corporate-contacts')
                        </div>
                    </div>
                @endif
                <br/>


                {{--
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="custom-heading">Branch Details</h3>
                    </div>


                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Branch Contact Person*</label>
                            <input type="text" class="form-control" name="contact_person">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Designation</label>
                            <input type="text" class="form-control" name="designation">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Branch contact person Email</label>
                            <input type="text" class="form-control" name="contactperson_email">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Branch Contact Number</label>
                            <input type="text" class="form-control" name="contactperson_number">
                        </div>
                    </div>


                    <!-- <div class="col-md-12">
                                    <hr>
                                    <a href="#" class="btn btn-outline-secondary waves-effect"><i
                                            class="fa fa-plus"></i> Add More Contact</a>
                                </div> -->


                    <div class="col-md-4" style="display: none;">
                        <div class="form-group">
                            <label>Is Parent Company Address *</label> <br>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="customRadioInline1" name="outer-group[0][customRadioInline1]"
                                    class="custom-control-input">
                                <label class="custom-control-label" for="customRadioInline1"> Boolean value1</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="customRadioInline2" name="outer-group[0][customRadioInline1]"
                                    class="custom-control-input">
                                <label class="custom-control-label" for="customRadioInline2"> Boolean value2</label>
                            </div>
                        </div>
                    </div>
                </div>
                --}}

                <div class="text-right">
                @if ( $id == 0 )
                    <button type="submit" class="btn btn-primary w-md">Save Company</a>
                @else
                    <button type="submit" class="btn btn-primary w-md">Update Company</a>
                @endif
                </div>
            </div>
        </form>
    </div>

</div>


@include('includes.address-auto-fill-js')



@endsection('create-customer-content')

@section('create-customer-content-js')
<script>
$(document).ready(function () {
    loc = window.location.href;
    console.log(loc);
    $('.nav-link.active').removeClass('active');
    if(/create/.test(loc)) {
        $('.nav-link.create').addClass('active');
    }
    //company_number
    //$("#company_number").on("click", function(){
        let company_number_val = $('#company_number').val();
        if(! company_number_val ){
            /////generate random id
            let a=  Date.now().toString() ;
            console.log(a);
            let shuffled = a.split('').sort(function(){return 0.5-Math.random()}).join('');

            console.log( shuffled.substring(2,12) );

            $('#company_number').val( shuffled.substring(2,12) );
        }
    //});

    $('body').on('click','#add_contact_person',function(){
        let last_row = parseInt($('#contact_person_last_row').val()) + 1;
        let selected_id = [];
        let load_template = true;
        $('.select_contact_person').each(function(index,value){
            var sel_val = $(this).val();
            if(sel_val == null || sel_val == '' || sel_val == undefined){
                load_template = false
            }
            selected_id.push(sel_val);
        });
        selected_id = selected_id.toString();
        if(load_template){
            $.ajax({
                url: '{{url("admin/ajax-get-add-contact-person-template")}}',
                data: {
                    last_row: last_row,id:'{{$id}}',selected_id:selected_id
                },
                type: 'GET',
                beforeSend: function() {
                    $('.loader').show();
                },
                success: function(data) {
                    $('.loader').hide();
                    if (data.success) {
                        $('#multiple_contact_person_div').append(data.html);
                        $('.select2').select2();
                        $('#contact_person_last_row').val(last_row);
                    }
                    else {
                        alert(data.msg);
                    }
                },
                error: function(data) {
                    $('.loader').hide();
                    alert(data.msg);
                }
            });
        }
        else{
            alert('please select contact paerson');
        }
    });
    $('body').on('change','.select_contact_person',function(){
        let user_id = $(this).val();
        let row_no = parseInt($(this).attr('data-key'));
        if(user_id != '' && user_id != null && user_id != undefined){
            $.ajax({
                url: '{{url("admin/ajax-get-add-contact-person-details")}}',
                data: {
                    user_id: user_id
                },
                type: 'GET',
                beforeSend: function() {
                    $('.loader').show();
                },
                success: function(data) {
                    $('.loader').hide();
                    if (data.success) {
                        $('#contact_person_email_'+row_no).val(data.data.email);
                        $('#contact_person_phone_'+row_no).val(data.data.phone);
                        $('#contact_person_div_'+row_no).addClass('pointer-none');

                    }
                    else {
                        $(this).val('');
                        $('.select2').select2();
                        alert(data.msg);
                    }
                },
                error: function(data) {
                    $('.loader').hide();
                    alert(data.msg);
                }
            });
        }
        else{
            $('#contact_person_email_'+row_no).val('');
            $('#contact_person_phone_'+row_no).val('');
        }
    });
    $('body').on('click','.contact_remove_section',function(e){
        e.preventDefault();
        let data_no = $(this).attr('data-no');
        $('#company_contact_section_'+data_no).remove();
    })

});
</script>
@endsection('create-customer-content')
