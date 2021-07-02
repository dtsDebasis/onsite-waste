@extends('admin.customer.create.createlayout')


@section('create-customer-content')
<!-- tab4 -->
<div class="tab-pane active" id="messages-1" role="tabpanel">
    @php($frequency_types = ['1'=>'Days','2'=>'Weeks','3'=>'Months','4'=>'Years'])
    @php($yes_no_arr = ['0' => 'No','1' => 'Yes'])
    @php($service_types = ['TE-5000' => 'TE-5000','Pick-up' => 'Pick-up', 'Hybrid' => 'Hybrid'])
    <div class="card">
        <div class="card-body mb-4">                
            @if($transactionalpackages)
                {!! Form::model($transactionalpackages, [
                'method' => 'PATCH',
                'route' => 'packages.transaction-package', 
                'class' => 'form-horizontal ',
                'id'=>'transactional-form',
                'enctype'=>'multipart/form-data'
                ]) !!}
            @else
                {!! Form::open(array('route' => 'packages.transaction-package','method'=>'POST', 'enctype'=>'multipart/form-data','id'=>'transactional-form')) !!}
            @endif
                <input type="hidden" name="id" id="package_id" value="{{($transactionalpackages)?$transactionalpackages->id:0}}">
                <input type="hidden" name="company_id" value="{{($company)?$company->id:0}}">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="custom-heading">Default Pricing</h3>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('additional_trip_cost', 'Additional Trip Cost <span class="span-req">*</span>:',array('class'=>'','for'=>'additional_trip_cost'),false) !!}
                            {!! Form::number('additional_trip_cost',null,['class'=>'form-control','placeholder'=>'Enter amount','id'=>'additional_trip_cost','required'=>'required','step'=>'0.01']) !!}
                            @if ($errors->has('additional_trip_cost'))
                                <span class="help-block">{{ $errors->first('additional_trip_cost') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('additional_box_cost', 'Additional Box Cost <span class="span-req">*</span>:',array('class'=>'','for'=>'additional_box_cost'),false) !!}
                            {!! Form::number('additional_box_cost',null,['class'=>'form-control','placeholder'=>'Enter amount','id'=>'additional_box_cost','required'=>'required','step'=>'0.01']) !!}
                            @if ($errors->has('additional_box_cost'))
                                <span class="help-block">{{ $errors->first('additional_box_cost') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('te_5000_rental_cost', 'TE-5000 Rental <span class="span-req">*</span>:',array('class'=>'','for'=>'te_5000_rental_cost'),false) !!}
                            {!! Form::number('te_5000_rental_cost',null,['class'=>'form-control','placeholder'=>'Enter amount','id'=>'te_5000_rental_cost','required'=>'required','step'=>'0.01']) !!}
                            @if ($errors->has('te_5000_rental_cost'))
                                <span class="help-block">{{ $errors->first('te_5000_rental_cost') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('te_5000_purchase_cost', 'TE-5000 Purchase <span class="span-req">*</span>:',array('class'=>'','for'=>'te_5000_purchase_cost'),false) !!}
                            {!! Form::number('te_5000_purchase_cost',null,['class'=>'form-control','placeholder'=>'Enter amount','id'=>'te_5000_purchase_cost','required'=>'required','step'=>'0.01']) !!}
                            @if ($errors->has('te_5000_purchase_cost'))
                                <span class="help-block">{{ $errors->first('te_5000_purchase_cost') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('containers_cost', 'Containers Rate<span class="span-req">*</span>:',array('class'=>'','for'=>'containers_cost'),false) !!}
                            {!! Form::number('containers_cost',null,['class'=>'form-control','placeholder'=>'Enter amount','id'=>'containers_cost','required'=>'required','step'=>'0.01']) !!}
                            @if ($errors->has('containers_cost'))
                                <span class="help-block">{{ $errors->first('containers_cost') }}</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('shipping_cost', 'Shipping Rate<span class="span-req">*</span>:',array('class'=>'','for'=>'shipping_cost'),false) !!}
                            {!! Form::number('shipping_cost',null,['class'=>'form-control','placeholder'=>'Enter amount','id'=>'shipping_cost','required'=>'required','step'=>'0.01']) !!}
                            @if ($errors->has('shipping_cost'))
                                <span class="help-block">{{ $errors->first('shipping_cost') }}</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('setup_initial_cost', 'Setup-Initial <span class="span-req">*</span>:',array('class'=>'','for'=>'setup_initial_cost'),false) !!}
                            {!! Form::number('setup_initial_cost',null,['class'=>'form-control','placeholder'=>'Enter amount','id'=>'setup_initial_cost','required'=>'required','step'=>'0.01']) !!}
                            @if ($errors->has('setup_initial_cost'))
                                <span class="help-block">{{ $errors->first('setup_initial_cost') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('setup_additional_cost', 'Setup-Additional <span class="span-req">*</span>:',array('class'=>'','for'=>'setup_additional_cost'),false) !!}
                            {!! Form::number('setup_additional_cost',null,['class'=>'form-control','placeholder'=>'Enter amount','id'=>'setup_additional_cost','required'=>'required','step'=>'0.01']) !!}
                            @if ($errors->has('setup_additional_cost'))
                                <span class="help-block">{{ $errors->first('setup_additional_cost') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('compliance_training_cost', 'Compliance Training <span class="span-req">*</span>:',array('class'=>'','for'=>'compliance_training_cost'),false) !!}
                            {!! Form::number('compliance_training_cost',null,['class'=>'form-control','placeholder'=>'Enter amount','id'=>'compliance_training_cost','required'=>'required','step'=>'0.01']) !!}
                            @if ($errors->has('compliance_training_cost'))
                                <span class="help-block">{{ $errors->first('compliance_training_cost') }}</span>
                            @endif
                        </div>
                    </div>                        
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('quarterly_review_cost', 'Quarterly Volume Analysis <span class="span-req">*</span>:',array('class'=>'','for'=>'quarterly_review_cost'),false) !!}
                            {!! Form::number('quarterly_review_cost',null,['class'=>'form-control','placeholder'=>'Enter amount','id'=>'quarterly_review_cost','required'=>'required','step'=>'0.01']) !!}
                            @if ($errors->has('quarterly_review_cost'))
                                <span class="help-block">{{ $errors->first('quarterly_review_cost') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('container_brackets_cost ', 'Container Brackets <span class="span-req">*</span>:',array('class'=>'','for'=>'container_brackets_cost'),false) !!}
                            {!! Form::number('container_brackets_cost',null,['class'=>'form-control','placeholder'=>'Enter amount','id'=>'container_brackets_cost','required'=>'required','step'=>'0.01']) !!}
                            @if ($errors->has('container_brackets_cost'))
                                <span class="help-block">{{ $errors->first('container_brackets_cost') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('pharmaceutical_containers_rate', 'Pharmaceutical Containers <span class="span-req">*</span>:',array('class'=>'','for'=>'pharmaceutical_containers_rate'),false) !!}
                            {!! Form::number('pharmaceutical_containers_rate',null,['class'=>'form-control','placeholder'=>'Enter amount','id'=>'pharmaceutical_containers_rate','required'=>'required','step'=>'0.01']) !!}
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
    
    <div class="card {{(Request::get('fnc') && (Request::get('fnc') == 'create' || Request::get('fnc') == 'edit'))?'':'d-none'}}" id="package-form-dev">
        <div class="card-body mb-4">   
            @if((isset($editPackage) && $editPackage))             
                {!! Form::model($editPackage, [
                'method' => 'PATCH',
                'route' => ['cutomers.update-package',[$id,$editPackage->id]], 
                'class' => 'form-horizontal ',
                'id'=>'package-form',
                'enctype'=>'multipart/form-data'
                ]) !!}
            @else
                    {!! Form::open(array('route' => ['cutomers.store-package',$id],'method'=>'POST', 'enctype'=>'multipart/form-data','id'=>'package-form')) !!}
            @endif
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="custom-heading">{{(isset($editPackage) && $editPackage)?'Edit Package':'Create Package'}}</h3>
                    </div>
                    <input type="hidden" name="company_id" value="{{$id}}">
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('name', 'Package Name <span class="span-req">*</span>:',array('class'=>'','for'=>'name'),false) !!}
                            {!! Form::select('name',$packagenames,null,['class'=>'form-control select2','placeholder'=>'Choose ...','id'=>'name','required'=>'required']) !!}
                            
                            @if ($errors->has('name'))
                                <span class="help-block">{{ $errors->first('name') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('service_type', 'Service Type<span class="span-req">*</span>:',array('class'=>'','for'=>'service_type'),false) !!}
                            {!! Form::select('service_type',$service_types,null,['class'=>'form-control select2','placeholder'=>'Choose ...','id'=>'service_type','required'=>'required']) !!}
                            @if ($errors->has('service_type'))
                                <span class="help-block">{{ $errors->first('service_type') }}</span>
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
                    <div class="col-md-2">
                        <div class="form-group">
                            {!! Form::label('compliance', 'Compliance Included <span class="span-req">*</span>:',array('class'=>'','for'=>'compliance'),false) !!}
                            {!! Form::select('compliance',$yes_no_arr,null,['class'=>'form-control select2','placeholder'=>'Choose ...','id'=>'compliance','required'=>'required']) !!}
                            @if ($errors->has('compliance'))
                                <span class="help-block">{{ $errors->first('compliance') }}</span>
                            @endif
                        </div>
                    </div>
                    @php($service_dnone = (isset($editPackage->service_type) && ($editPackage->service_type == 'TE-5000')) ? 'd-none':'')
                    <div class="col-md-5 service-based-view {{$service_dnone}}">
                        <div class="form-group">
                            {!! Form::label('frequency', 'Frequency :',array('class'=>'','for'=>'frequency'),false) !!}
                            <div class="col-md-12 row">
                                {!! Form::number('frequency_number',null,['class'=>'form-control col-md-7','placeholder'=>'Enter number','id'=>'frequency_number','step'=>'0.01']) !!}
                                @if ($errors->has('frequency_number'))
                                    <span class="help-block">{{ $errors->first('frequency_number') }}</span>
                                @endif
                                <div class="col-md-5">
                                    {!! Form::select('frequency_type',$frequency_types,null,['class'=>'form-control select2','placeholder'=>'Choose ...','id'=>'frequency_type']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 service-based-view {{$service_dnone}}">
                        <div class="form-group">
                            {!! Form::label('boxes_included', 'Number of Boxes Included :',array('class'=>'','for'=>'boxes_included'),false) !!}
                            {!! Form::number('boxes_included',null,['class'=>'form-control','placeholder'=>'Enter number','id'=>'boxes_included','step'=>'0']) !!}
                            @if ($errors->has('boxes_included'))
                                <span class="help-block">{{ $errors->first('boxes_included') }}</span>
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
    <div class="card">
        <div class="card-body mb-4">
            <div class="col-md-12">
                <div class="col-sm-12">
                    <div class="d-flex flex-column flex-md-row justify-content-between custom-heading-wrapper align-items-center">  
                        <h3>Package List</h3>
                        <a href="{{ route('customers.create.package',[$id,'fnc'=>'create']) }}" class="btn btn-primary w-md" id="add_package">Add Package</a>
                    </div>        
                </div>
                <div class="table-responsive">
                    <table class="table table-centered table-nowrap mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Package Name</th>
                                <th>Service Type</th>
                                <th>Monthly Rate</th>
                                <th>Frequency</th>
                                <th>Number Of Boxes Included</th>                                
                                <th>Compliance</th>
                                {{--<th>Assign Location</th>--}}
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($companypackages as $pval )
                            <tr class="">
                                <td>{{ $pval->name }} </td>
                                <td>{{ isset($service_types[$pval->service_type]) ? $service_types[$pval->service_type] : 'NA' }}
                                <td>{{ $pval->monthly_rate}}</td>
                                <td>{{ ($pval->frequency_number)?$pval->frequency_number.'/'.$frequency_types[$pval->frequency_type]:'' }} </td>
                                <td>{{ $pval->boxes_included }}</td>
                               
                                <td>{{ (array_key_exists($pval->compliance,$yes_no_arr)) ?$yes_no_arr[$pval->compliance]: '' }}</td>
                                
                                {{--<td>
                                    @if( $pval->companybranch )
                                        {{ $pval->companybranch->name ?? '' }}
                                    @else
                                        <button type="button" class="btn btn-outline-primary waves-effect waves-light assign-btn" data-companypackage_id="{{ $pval->id }}"  >Assign</button>
                                    @endif
                                </td>
                                --}}
                                <td> 
                                    <a href="{{ route('customers.create.package',[$id,'package_id'=>$pval->id,'fnc'=>'edit']) }}" class="btn btn-primary btn-sm btn-rounded waves-effect waves-light"><i class="bx bx-edit-alt"></i></a>
                                    <a class="btn btn-sm btn-rounded btn-danger waves-effect" data-toggle="tooltip" title="" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="event.preventDefault();
                                        document.getElementById('delete-form-{{$pval->id}}').submit();" data-original-title="Delete">{!! \Config::get('settings.icon_delete') !!}</a>
                                        {!! Form::open([
                                            'method' => 'DELETE',
                                            'route' => [
                                             'customers.package-destroy', 
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

</div>




<!--  Modal content for the above example -->
<div class="modal fade add-package-modal-bs" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="myLargeModalLabel">Add Package</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-centered table-nowrap mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Package Name</th>
                                <th>Monthly Rate</th>
                                <th>Frequency</th>
                                <th>Number Of Boxes Included</th>
                                <th>TE-5000</th>
                                <th>Compliance</th>
                                {{--<th>Duration</th>--}}
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($packages as $pval )
                                <tr>
                                    <td>{{ $pval->name }} </td>
                                    <td>{{ $pval->monthly_rate}}</td>
                                    <td>{{ ($pval->frequency_number)?$pval->frequency_number.'/'.$frequency_types[$pval->frequency_type]:'' }} </td>
                                    <td>{{ $pval->boxes_included }}</td>
                                    <td>{{ (array_key_exists($pval->te_500,$yes_no_arr)) ?$yes_no_arr[$pval->te_500]: '' }}</td>
                                    <td>{{ (array_key_exists($pval->compliance,$yes_no_arr)) ?$yes_no_arr[$pval->compliance]: '' }}</td>
                                    {{--<td>{{ ($pval->duration_number)?$pval->duration_number.'/'.$frequency_types[$pval->duration_type]:'' }}</td> --}}
                                    <td>
                                        <button type="button" class="btn btn-outline-primary waves-effect waves-light clone-package" packageid="{{ $pval->id }}" >Clone</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">                 
                 <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>  
                 <a href="javascript:;" class="btn btn-success" id="cloned_submit">Submit</a>                         
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
{!! Form::open(array('route' => 'cutomers.clone-package','method'=>'POST', 'enctype'=>'multipart/form-data','id'=>'package-clone-form')) !!}
    <input type="hidden" name="package_id" id="clone_package_id" value="">
    <input type="hidden" name="company_id" id="clone_company_id" value="">
{!! Form::close() !!}


<!--  Modal content for the above example -->
<div class="modal fade assign-site-branch-package-modal-bs" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="myLargeModalLabel">Add Package</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Branch Name</th>
                                    <th>Address</th>
                                    <!-- <th>Contact Person</th>
                                    <th>Phone</th>
                                    <th>Email</th> -->
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($companybranch_list as $companybranch )
                                <tr class="">
                                    <td>{{  $companybranch->name ?? '' }}</td>

                                    <td>{{ $companybranch->addressdata->addressline1  ?? '' }}</td>

                                    {{--<td> {{ $companybranch->branchusers->user->FullName ?? '' }} </td>
                                    <td> {{ $companybranch->branchusers->user->phone ?? '' }}</a></td>
                                    <td> {{ $companybranch->branchusers->user->email ?? '' }} </td>--}}

                                    <td>

                                        <button type="button" class="btn btn-outline-primary waves-effect waves-light    assign-branch"  branchid="{{ $companybranch->id }}"   >Assign</button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- edit-companypackage-modal-bs  modal -->
<div class="modal fade edit-companypackage-modal-bs" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="myLargeModalLabel">Add Package</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                   BODY

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->




@endsection('create-customer-content')


@section('create-customer-content-js')
<script>
$(document).ready(function () {

    let loc = window.location.href;
    console.log(loc);
    $('.nav-link.active').removeClass('active');
    if(/create\/package/.test(loc)) {
        $('.nav-link.package').addClass('active');
    }

    //clone-package

    $('body').on('change','#service_type', function(){
        let value = $(this).val();
        if(value == 'TE-5000'){
            $('.service-based-view').addClass('d-none');
        }
        else{
            $('.service-based-view').removeClass('d-none');
        }
    });
    $('body').on("click",".clone-package",function () {
        let packageid = $(this).attr('packageid');
        $(this).toggleClass('cloned');
        if(($(this).text() == "Clone")){
            $(this).html('<i class="fa fa-check"></i> Cloned');
        }
        else{
            $(this).text("Clone");
        }
        let ids = [];
        $(".cloned").each(function(index,value) {
            ids.push($(this).attr('packageid'));
        });
        $('#package-clone-form').find('#clone_package_id').val(ids.toString());
        $('#package-clone-form').find('#clone_company_id').val('{{$id}}');
    });
    $('body').on("click","#cloned_submit",function () {  
        var packages = $('#package-clone-form').find('#clone_package_id').val();
        if(packages == null || packages == undefined || packages == ''){
            bootbox.alert({
                title:"Add Package",
                message: 'Please select packages.' ,
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
                        $('#package-clone-form').submit();
                    }
                }
            });
        }
    });

    var companypackage_id = 0;
    $( ".assign-btn" ).click(function(){
        
        $(".assign-site-branch-package-modal-bs").modal();
        companypackage_id = $(this).attr('companypackage_id');
        console.log(companypackage_id);

    });
    //assign-branch
    $(".assign-branch").on("click", function () {
       
       //packageid
       let branchid = $(this).attr('branchid');

       console.log(branchid);
       let r = confirm("Are you sure!");
       if (r == true) {
           
           let assignBranch = $.ajax({
               type: 'POST',
               url: "{{ route('customers.create.package', ['id' => $id] ) }}",
               data: {"_token": "{{ csrf_token() }}", "task": "assign-branch", "companypackage_id": companypackage_id, "branchid": branchid },
               success: function(resultData) {
                   //console.log("teswt", resultData.data.success ); 
                   window.location.reload();
               }
           });
           assignBranch.error(function() { console.log("Something went wrong"); });

       } else {
           
       }

    });


    //edit-company-package
    $(".edit-company-package").on("click", function () {

        //packageid
        let companypackage_id = $(this).attr('companypackage_id');
        console.log(companypackage_id);

        $(".edit-companypackage-modal-bs").modal();

    });


    //delete-company-package
    $(".delete-company-package").on("click", function () {

        //packageid
        let companypackage_id = $(this).attr('companypackage_id');


        let r = confirm("Are you sure!");
        if (r == true) {
            /// delete
            console.log(companypackage_id);
            let deleteCompanyPackage = $.ajax({
                type: 'POST',
                url: "{{ route('customers.create.package', ['id' => $id] ) }}",
                data: {"_token": "{{ csrf_token() }}", "task": "deletecompanypackage", "companypackage_id": companypackage_id,  },
                success: function(resultData) {
                    //console.log("teswt", resultData.data.success ); 
                    window.location.reload();
                }
            });
            deleteCompanyPackage.error(function() { console.log("Something went wrong"); });
        }

    });

});
</script>
@endsection('create-customer-content')