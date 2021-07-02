<style>
.pointer-none{
    pointer-events: none;
}
</style>
@php($waste_type = ['spinner' => 'Spinner','rocker' => 'Rocker'])
@php($inventoryType = ['sharps' => 'Sharps','redbag'=>'Redbag'])
<div class="">
    @if($company)    
    <div class="card">
        <div class="card-body row"> 
                <div class="col-md-3">
                    <strong>Company ID </strong>: @if(isset($company) && isset($company->company_number) && $company->company_number) {{$company->company_number}} @else NA @endif
                </div>
                <div class="col-md-4">
                    <strong>Company Name</strong>: @if(isset($company) && isset($company->company_name) && $company->company_name) <a href="{{route('customers.branches',$company->id)}}">{{$company->company_name}}</a> @else NA @endif
                </div>
                <div class="col-md-3">
                    <strong>Phone</strong>: @if(isset($company) && isset($company->phone) && $company->phone) {{$company->phone}} @else NA @endif
                </div>
                @if(isset($company) && isset($company->id) && $company->id)
                <div class="col-md-2">
                    <a class="btn btn-primary btn-sm btn-rounded waves-effect waves-light" href="{{ route('customers.create', ['id' => $company->id  ] ) }}" >Edit Company</a>
                </div>
                @endif
        </div>
    </div>
    @endif
    @if($companybranch)    
    <div class="card">
        <div class="card-body row"> 
                <div class="col-md-4">
                    <strong>Location Code </strong>: @if(isset($companybranch->uniq_id) && $companybranch->uniq_id) {{$companybranch->uniq_id}} @else NA @endif
                </div>
                
                <div class="col-md-4">
                   <strong> Location Name</strong> :@if(isset($companybranch->name) && $companybranch->name) {{$companybranch->name}} @else NA @endif
                </div>
                <div class="col-md-4">
                    <strong>Location Address : </strong>@if(isset($companybranch->addressdata) && $companybranch->addressdata) {{$companybranch->addressdata->addressline1}} @else NA @endif
                </div>
        </div>
    </div>
    @php($tab = Request::get('tab')?Request::get('tab'):'contact')
    <div class="row">
        <div class="col-lg-12">
            <ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
                <li class="nav-item waves-effect waves-light">
                    <a class="nav-link {{($tab == 'contact')?'active':''}}"  href="{{route('customers.branche-info-details',[$company->id,$id,'tab'=>'contact'])}}" role="tab"  aria-selected="true">
                        <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                        <span class="d-none d-sm-block">Contact Information</span> 
                    </a>
                </li>
                <li class="nav-item waves-effect waves-light">
                    <a class="nav-link {{($tab == 'pricing')?'active':''}}"  href="{{route('customers.branche-info-details',[$company->id,$id,'tab'=>'pricing'])}}" role="tab" aria-selected="false">
                        <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                        <span class="d-none d-sm-block">Pricing Details</span> 
                    </a>
                </li>
                <li class="nav-item waves-effect waves-light">
                    <a class="nav-link {{($tab == 'package')?'active':''}}"  href="{{route('customers.branche-info-details',[$company->id,$id,'tab'=>'package'])}}" role="tab" aria-selected="false">
                        <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                        <span class="d-none d-sm-block">Package Details</span> 
                    </a>
                </li>
                <li class="nav-item waves-effect waves-light">
                    <a class="nav-link {{($tab == 'inventory')?'active':''}}"  href="{{route('customers.branche-info-details',[$company->id,$id,'tab'=>'inventory'])}}" role="tab" aria-selected="false">
                        <span class="d-block d-sm-none"><i class="far fa-envelope"></i></span>
                        <span class="d-none d-sm-block">Inventory </span>
                    </a>
                </li>
                <li class="nav-item waves-effect waves-light">
                    <a class="nav-link {{($tab == 'hauling')?'active':''}}"  href="{{route('customers.branche-info-details',[$company->id,$id,'tab'=>'hauling'])}}" role="tab" aria-selected="false">
                        <span class="d-block d-sm-none"><i class="fas fa-cog"></i></span>
                        <span class="d-none d-sm-block">Pickup </span>
                    </a>
                </li>
                <!-- <li class="nav-item waves-effect waves-light">
                    <a class="nav-link {{($tab == 'document')?'active':''}}"  href="{{route('customers.branche-info-details',[$company->id,$id,'tab'=>'document'])}}" role="tab" aria-selected="false">
                        <span class="d-block d-sm-none"><i class="fas fa-cog"></i></span>
                        <span class="d-none d-sm-block">Document</span>
                    </a>
                </li> -->
                <li class="nav-item waves-effect waves-light">
                    <a class="nav-link {{($tab == 'invoices')?'active':''}}"  href="{{route('customers.branche-info-details',[$company->id,$id,'tab'=>'invoices'])}}" role="tab" aria-selected="false">
                        <span class="d-block d-sm-none"><i class="fas fa-cog"></i></span>
                        <span class="d-none d-sm-block">Invoices</span>
                    </a>
                </li>
            </ul>
            <div class="tab-content pt-3 text-muted">
                @if($tab == 'contact')
                <div class="tab-pane {{($tab == 'contact')?'active':''}}" id="contact" role="tabpanel">
                    <div class="card">
                        <div class="card-body">
                            <div class="tab-content mt-3 text-muted">
                                <div class="tab-pane active" id="home1" role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="table table-centered table-nowrap mb-0">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>First Name</th>
                                                    <th>Last Name</th>
                                                    <th>Role</th>
                                                    <th>Email</th>
                                                    <th>Phone Number</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(count($branchusers))
                                                    @foreach($branchusers as $contact )
                                                        @if($contact->user)
                                                            <tr class="">
                                                                <td>{{ $contact->user->first_name ?? '' }}</td>
                                                                <td>{{ $contact->user->last_name ?? '' }}</td>
                                                                <td>{{ $contact->user->designation ?? '' }}</td>
                                                                <td>{{ $contact->user->email ?? '' }}</td>
                                                                <td>{{ $contact->user->phone ?? '' }}</td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                @else
                                                <tr class="">
                                                    <td colspan="5">No record found</td>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!-- end table-responsive -->
                        </div>
                    </div>
                </div>
                @endif
                @if($tab == 'pricing')
                <div class="tab-pane {{($tab == 'pricing')?'active':''}}" id="pricing" role="tabpanel">
                    @php($page_type = isset($input['page_type'])?$input['page_type']:'view_details')
                    @php($pointNone = ($page_type == "create_edit")?'':'pointer-none')
                    <div class="card">
                        <div class="card-body mb-4">                
                            @if($transactionalpackages)
                                {!! Form::model($transactionalpackages, [
                                'method' => 'PATCH',
                                'url' => 'javascript:;', 
                                'class' => 'form-horizontal ',
                                'id'=>'transactional-form',
                                'enctype'=>'multipart/form-data'
                                ]) !!}
                            @else
                                {!! Form::open(array('url' => 'javascript:;','method'=>'POST', 'enctype'=>'multipart/form-data','id'=>'transactional-form','class' => 'form-horizontal ')) !!}
                            @endif
                                <input type="hidden" name="id" id="tran_package_id" value="{{($transactionalpackages)?$transactionalpackages->id:0}}">
                                <input type="hidden" name="company_id" value="{{($transactionalpackages)?$transactionalpackages->company_id:0}}">
                                <input type="hidden" name="branch_id" value="{{($transactionalpackages)?$transactionalpackages->branch_id:0}}">
                                <div class="row {{$pointNone}}">
                                    
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
                                    @if($page_type =="create_edit")
                                        <button type="button" id="transactional_update" class="btn btn-primary w-md">Save</button>
                                    @endif
                                </div>
                            {!! Form::close() !!}                
                        </div>
                    </div>
                </div>
                @endif
                @if($tab == 'package')
                @php($service_types = ['TE-5000' => 'TE-5000','Pick-up' => 'Pick-up', 'Hybrid' => 'Hybrid'])
                <div class="tab-pane {{($tab == 'package')?'active':''}}" id="package" role="tabpanel">
                    @php($page_type = isset($input['page_type'])?$input['page_type']:'view_details')
                    @php($pointNone = ($page_type == "create_edit")?'':'pointer-none')
                    @php($frequency_types = ['1'=>'Days','2'=>'Weeks','3'=>'Months','4'=>'Years'])
                    @php($yes_no_arr = ['0' => 'No','1' => 'Yes'])
                    <div class="card">
                        @if($page_type =="create_edit")
                        <div class="card-head text-right">
                            <a href="javascript:;" data-id="{{($package)?$package->id:0}}" data-company_id="{{($package)?$package->company_id:0}}" data-branch_id="{{($package)?$package->branch_id:0}}" class="btn btn-danger btn-sm btn-rounded waves-effect waves-light delete-branch-package"><i class="bx bx-trash-alt"></i></a>
                        </div>
                        @endif
                        <div class="card-body mb-4">                
                            @if($package)
                                {!! Form::model($package, [
                                'method' => 'PATCH',
                                'url' => 'javascript:;', 
                                'class' => 'form-horizontal ',
                                'id'=>'branch-package-form',
                                'enctype'=>'multipart/form-data'
                                ]) !!}
                            @else
                                {!! Form::open(array('url' => 'javascript:;','method'=>'POST', 'enctype'=>'multipart/form-data','id'=>'branch-package-form','class' => 'form-horizontal ')) !!}
                            @endif
                                <input type="hidden" name="id" id="tran_package_id" value="{{($package)?$package->id:0}}">
                                <input type="hidden" name="company_id" value="{{($package)?$package->company_id:0}}">
                                <input type="hidden" name="branch_id" value="{{($package)?$package->branch_id:0}}">
                                <div class="row {{$pointNone}}">                                
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
                                @php($service_dnone = (isset($package->service_type) && ($package->service_type == 'TE-5000')) ? 'd-none':'')
                                <div class="col-md-5 service-based-view {{$service_dnone}}">                                
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
                                <div class="col-md-3 service-based-view {{$service_dnone}}">
                                    <div class="form-group">
                                        {!! Form::label('boxes_included', 'Number of Boxes Included <span class="span-req">*</span>:',array('class'=>'','for'=>'boxes_included'),false) !!}
                                        {!! Form::number('boxes_included',null,['class'=>'form-control','placeholder'=>'Enter number','id'=>'boxes_included','required'=>'required','step'=>'0']) !!}
                                        @if ($errors->has('boxes_included'))
                                            <span class="help-block">{{ $errors->first('boxes_included') }}</span>
                                        @endif
                                    </div>
                                </div>
                                </div>
                                <div class="text-right">
                                    @if($page_type =="create_edit")
                                    <button type="button" id="brnch_pck_update" class="btn btn-primary w-md">Save</button>
                                    @endif
                                </div>
                            {!! Form::close() !!}                
                        </div>
                    </div>
                </div>
                @endif
                @if($tab == 'inventory')
                <div class="tab-pane {{($tab == 'inventory')?'active':''}}" id="inventory" role="tabpanel">
                    <div class="card">
                        <div class="card-body mb-4">
                            <div class="row mb-4">

                                <div class="col-sm-12">
                                    <div class="d-flex flex-column flex-md-row justify-content-between custom-heading-wrapper align-items-center">
                                    <h3>TE 5000</h3>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="table-responsive">
                                        <table class="table table-centered table-nowrap mb-0">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>IMEI</th>
                                                    <th>Serial Number</th>
                                                    <th>Firmware</th>
                                                    <th>Status</th> 
                                                    <th>Last run</th>                                                        
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(count($te5000))
                                                    @foreach($te5000['results'] as $key => $val) 
                                                        <tr>
                                                            <td>{{isset($val['imei']) ? $val['imei'] : 'NA'}}</td>
                                                            <td>{{isset($val['serialNumber']) ? $val['serialNumber'] : 'NA'}}</td>
                                                            <td>{{isset($val['currentFirmwareVersion']) ? $val['currentFirmwareVersion'] : 'NA'}}</td>
                                                            <td>{{isset($val['status']) ? $val['status'] : 'NA'}}</td>
                                                            <td>{{isset($val['lastAnnounceDateTime']) ? \App\Helpers\Helper::showdate($val['lastAnnounceDateTime'],true,'m-d-Y h:i A') : 'NA'}}</td>
                                                            
                                                            <td>
                                                                <button type="button" class="btn btn-sm btn-outline-primary waves-effect waves-light last_run_info" data-location_id="{{isset($val['locationId']) ? $val['locationId'] : ''}}" data-imie_no="{{isset($val['imei']) ? $val['imei'] : ''}}">Last Run Info</button>
                                                            </td>
                                                        </tr>
                                                    @endforeach   
                                                @else
                                                <tr>
                                                    <td colspan="25">No record found</td>
                                                </tr>
                                                @endif
                                                
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-4 d-none" id="last_run_info_row">
                                <div class="col-sm-12">
                                    <div class="d-flex flex-column flex-md-row justify-content-between custom-heading-wrapper align-items-center">
                                        <div class="col-sm-6">
                                            <h3>Last Run-info</h3>
                                        </div>
                                        <div class="col-sm-6 text-right">
                                            <a href="javascript:;" id="close_last_run_info" class="btn btn-sm btn-danger"><i class="fa fa-times"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="table-responsive">
                                        <table class="table table-centered table-nowrap mb-0">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>Start DateTime</th>
                                                    <th>End DateTime</th>
                                                    <th>Low Temp</th>
                                                    <th>High Temp</th>
                                                    <th>Type</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr id="last_run_ifo_tr">
                                                    <td>4542313</td>
                                                    <td>E56SS6777677</td>
                                                    <td>24/02/21</td>
                                                    <td>
                                                        <button type="button" class="btn btn-sm btn-outline-primary waves-effect waves-light machine-ping" data-location_id="{{'223453'}}" data-imie_no="{{'E56SS6777677'}}">Ping Machine</button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-4">

                                <div class="col-sm-12">
                                    <div class="d-flex flex-column flex-md-row justify-content-between custom-heading-wrapper align-items-center">
                                        <h3>Container Inventory</h3>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="table-responsive">
                                        
                                        <table class="table table-centered table-nowrap mb-0">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Location</th>
                                                <th>SH Inventory</th>
                                                <th>SH ROP</th>
                                                <th>SH Container Type</th>
                                                <th>RB Inventory</th>
                                                <th>RB ROP</th>
                                                <th>RB Container Type</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>                                               
                                                <tr class="">
                                                    <td>@if(isset($companybranch->name) && $companybranch->name) {{$companybranch->name}} @else NA @endif</td>
                                                    @php($uniq_id = (isset($companybranch->uniq_id)) ? $companybranch->uniq_id : 1)
                                                    @php($inventory_details = (array_key_exists("canisterInventory",$containerInventory))?$containerInventory['canisterInventory']: array())
                                                    <td><input class="form-control" type="number" name="sh_inventory[]" id="sh_inventory_{{$uniq_id}}" value="{{isset($inventory_details[1]['availableInventory'])?$inventory_details[1]['availableInventory']:0}}"> </td>
                                                    <td><input class="form-control" type="number" name="sh_rop[]" id="sh_rop_{{$uniq_id}}" value="{{isset($inventory_details[1]['reorderPoint'])?$inventory_details[1]['reorderPoint']:0}}"> </td>
                                                    <td>{!! Form::select('sh_container_type',['Spinner'=>'Spinner','Rocker'=>'Rocker'],isset($inventory_details[1]['canisterType'])?$inventory_details[1]['canisterType']:null,['class'=>'form-control select2','id'=>'sh_container_type','placeholder'=>'Choose ...']) !!}</td>
                                                    <!-- <td>{{isset($inventory_details[1]['canisterType'])?$inventory_details[1]['canisterType']:'NA'}} </td> -->
                                                    <td><input class="form-control" type="number" name="rb_inventory[]" id="rb_inventory_{{$uniq_id}}" value="{{isset($inventory_details[0]['availableInventory'])?$inventory_details[0]['availableInventory']:0}}"> </td>
                                                    <td><input class="form-control" type="number" name="rb_rop[]" id="rb_rop_{{$uniq_id}}" value="{{isset($inventory_details[0]['reorderPoint'])?$inventory_details[0]['reorderPoint']:0}}"> </td>
                                                    <!-- <td>{{isset($inventory_details[0]['canisterType'])?$inventory_details[0]['canisterType']:'NA'}} </td> -->
                                                    <td>{!! Form::select('rb_container_type',['Rocker'=>'Rocker','Open'=>'Open'],isset($inventory_details[0]['canisterType'])?$inventory_details[1]['canisterType']:null,['class'=>'form-control select2','id'=>'rb_container_type','placeholder'=>'Choose ...']) !!}</td>
                                                    <td>
                                                    <a href="javascript:;" data-toggle="tooltip" data-id="{{$companybranch->uniq_id}}" data-placement="top" title="" data-original-title="Update" type="button" class="btn btn-primary btn-sm btn-rounded waves-effect waves-light update-inventory-info">
                                                        <i class="fa fa-save"></i>
                                                    </a>
                                                    </td>
                                                </tr>                                                
                                        </tbody>
                                    </table>
                                    </div>
                                </div>
                            </div>
                            
                        </div>

                    </div>

                </div>
                @endif
                @if($tab == 'hauling')
                <div class="tab-pane {{($tab == 'hauling')?'active':''}}" id="hauling" role="tabpanel">
                    <div class="card">
                        <div class="card-body mb-4">
                            <div class="col-sm-12">
                                <h3 class="custom-heading">Listing</h3>
                                <div class="tab-content text-muted">
                                    <div class="tab-pane active" id="home1" role="tabpanel">
                                        <div class="table-responsive">
                                            <table class="table table-centered table-nowrap mb-0">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th>Branch Name</th>
                                                        <th>Location</th>
                                                        <th>Number Of Boxes</th>
                                                        <th>Package</th>
                                                        <th>Driver Name</th>
                                                        <th>Date</th>
                                                        <th>Status</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if(count($hauling_list))
                                                        @foreach($hauling_list as $hkey => $hval) 
                                                            <tr class="">
                                                                <td>{{(isset($hval->branch_details) && $hval->branch_details)?$hval->branch_details->name:'NA'}}</td>
                                                                @if(isset($hval->branch_details) && isset($hval->branch_details->addressdata) && $hval->branch_details->addressdata)
                                                                <td> <span class="color-b">{{$hval->branch_details->addressdata->locality}} {{$hval->branch_details->addressdata->state}}</span> <br> <i class="bx bx-map"></i> {{$hval->branch_details->addressdata->addressline1}} </td>
                                                                @else
                                                                    <td> NA </td>
                                                                @endif
                                                                <td> {{($hval->number_of_boxes)?$hval->number_of_boxes.' Box':'NA'}}</td>
                                                                @if(isset($hval->package_details) && $hval->package_details)
                                                                    <td> {{$hval->package_details->name}}</td>                                                        
                                                                @else
                                                                    <td> NA </td>
                                                                @endif
                                                                <td>{{($hval->driver_name)?$hval->driver_name:'NA'}}</td>
                                                                <td>{{($hval->date)?\App\Helpers\Helper::dateConvert($hval->date):'NA'}}</td>
                                                                <td><span class="badge badge-pill badge-soft-success">Confirm</span></td>
                                                                <td>
                                                                    <a href="javascript:;" data-hauling_id="{{$hval->id}}" data-branch_id="{{$hval->branch_id}}" data-toggle="tooltip" data-placement="top" title="" data-original-title="Add Manifest" type="button" class="btn btn-primary btn-sm btn-rounded waves-effect waves-light add_edit_manifest">
                                                                        <i class="bx bx-plus-medical"></i>
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @else
                                                    <tr class="">
                                                        <td colspan="25">No Record found</td>                                        
                                                    </tr>
                                                    @endif
                                                </tbody>
                                            </table>                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>                    
                </div>
                @endif
                @if($tab == 'document')
                <div class="tab-pane {{($tab == 'document')?'active':''}}" id="document" role="tabpanel">
                    <div class="card">
                        <div class="card-body mb-4">
                            <form>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <h3 class="custom-heading">Document </h3>
                                    </div>
                                    <div class="col-sm-4">
                                        <h5 class="font-size-14">Upload Documents</h5>
                                        <div class="input-group mb-3">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="inputGroupFile03" aria-describedby="inputGroupFileAddon03">
                                                <label class="custom-file-label" for="inputGroupFile03">Choose file</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div class="mt-2">
                                <h4 class="card-title">List of documents</h4>
                                <div class="row">
                                    <div class="col-xl-3 col-sm-6">
                                        <div class="card shadow-none border">
                                            <div class="card-body p-3">
                                                <div class="">
                                                    <div class="float-right ml-2">
                                                        <div class="dropdown mb-2">
                                                            <a class="font-size-16 text-muted dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <i class="mdi mdi-dots-horizontal"></i>
                                                            </a>

                                                            <div class="dropdown-menu dropdown-menu-right" style="">
                                                                <a class="dropdown-item" href="#">Open/Download</a>
                                                                <!-- <a class="dropdown-item" href="#">Edit</a> -->
                                                                <a class="dropdown-item" href="#">Rename</a>
                                                                <div class="dropdown-divider"></div>
                                                                <a class="dropdown-item text-danger" href="#">Remove</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="avatar-xs avatar-bx mr-3 mb-3">
                                                        <img src="assets/images/dd-1.jpg" alt="">
                                                    </div>
                                                    <div class="d-flex">
                                                        <div class="overflow-hidden mr-auto">
                                                            <h5 class="font-size-14 text-truncate mb-1">
                                                                <a href="javascript: void(0);" class="text-body">Files Name</a>
                                                            </h5>
                                                        </div>
                                                        <div class="align-self-end ml-2">
                                                            <p class="text-muted mb-0">6GB</p>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-sm-6">
                                        <div class="card shadow-none border">
                                            <div class="card-body p-3">
                                                <div class="">
                                                    <div class="float-right ml-2">
                                                        <div class="dropdown mb-2">
                                                            <a class="font-size-16 text-muted dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <i class="mdi mdi-dots-horizontal"></i>
                                                            </a>

                                                            <div class="dropdown-menu dropdown-menu-right" style="">
                                                                <a class="dropdown-item" href="#">Open/Download</a>
                                                                <!-- <a class="dropdown-item" href="#">Edit</a> -->
                                                                <a class="dropdown-item" href="#">Rename</a>
                                                                <div class="dropdown-divider"></div>
                                                                <a class="dropdown-item text-danger" href="#">Remove</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="avatar-xs avatar-bx mr-3 mb-3">
                                                        <img src="assets/images/dd-1.jpg" alt="">
                                                    </div>
                                                    <div class="d-flex">
                                                        <div class="overflow-hidden mr-auto">
                                                            <h5 class="font-size-14 text-truncate mb-1">
                                                                <a href="javascript: void(0);" class="text-body">Files Name</a>
                                                            </h5>
                                                        </div>
                                                        <div class="align-self-end ml-2">
                                                            <p class="text-muted mb-0">6GB</p>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-sm-6">
                                        <div class="card shadow-none border">
                                            <div class="card-body p-3">
                                                <div class="">
                                                    <div class="float-right ml-2">
                                                        <div class="dropdown mb-2">
                                                            <a class="font-size-16 text-muted dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <i class="mdi mdi-dots-horizontal"></i>
                                                            </a>

                                                            <div class="dropdown-menu dropdown-menu-right" style="">
                                                                <a class="dropdown-item" href="#">Open/Download</a>
                                                                <!-- <a class="dropdown-item" href="#">Edit</a> -->
                                                                <a class="dropdown-item" href="#">Rename</a>
                                                                <div class="dropdown-divider"></div>
                                                                <a class="dropdown-item text-danger" href="#">Remove</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="avatar-xs avatar-bx mr-3 mb-3">
                                                        <img src="assets/images/dd-1.jpg" alt="">
                                                    </div>
                                                    <div class="d-flex">
                                                        <div class="overflow-hidden mr-auto">
                                                            <h5 class="font-size-14 text-truncate mb-1">
                                                                <a href="javascript: void(0);" class="text-body">Files Name</a>
                                                            </h5>
                                                        </div>
                                                        <div class="align-self-end ml-2">
                                                            <p class="text-muted mb-0">6GB</p>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-sm-6">
                                        <div class="card shadow-none border">
                                            <div class="card-body p-3">
                                                <div class="">
                                                    <div class="float-right ml-2">
                                                        <div class="dropdown mb-2">
                                                            <a class="font-size-16 text-muted dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <i class="mdi mdi-dots-horizontal"></i>
                                                            </a>

                                                            <div class="dropdown-menu dropdown-menu-right" style="">
                                                                <a class="dropdown-item" href="#">Open/Download</a>
                                                                <!-- <a class="dropdown-item" href="#">Edit</a> -->
                                                                <a class="dropdown-item" href="#">Rename</a>
                                                                <div class="dropdown-divider"></div>
                                                                <a class="dropdown-item text-danger" href="#">Remove</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="avatar-xs avatar-bx mr-3 mb-3">
                                                        <img src="assets/images/dd-1.jpg" alt="">
                                                    </div>
                                                    <div class="d-flex">
                                                        <div class="overflow-hidden mr-auto">
                                                            <h5 class="font-size-14 text-truncate mb-1">
                                                                <a href="javascript: void(0);" class="text-body">Files Name</a>
                                                            </h5>
                                                        </div>
                                                        <div class="align-self-end ml-2">
                                                            <p class="text-muted mb-0">6GB</p>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="text-right">
                                <button type="submit" class="btn btn-primary w-md">Next</button>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                @if($tab == 'invoices')
                <div class="tab-pane {{($tab == 'invoices')?'active':''}}" id="invoices" role="tabpanel">
                    <div class="row">
                        <div class="col-sm-12">
                            <h3 class="custom-heading">List of customer invoices</h3>
                        </div>
                    </div>
                    <div class="col-md-12">
                        {!! Form::open(['method' => 'GET','route' => ['customers.branche-info-details',[$company->id,$id]],'id' => 'srch-form','class' => 'form-horizontal ']) !!}
                        <input type="hidden" value="{{$tab}}" name="tab">
                        <div class="row">                     
                            <div class="col-md-3">                    
                                <div class="form-group">
                                    <label>Start date</label>
                                    @php($begin_date = (isset($srch_params['begin_date']))?$srch_params['begin_date']:null)
                                    {!! Form::date('begin_date',$begin_date,['class'=>'form-control','placeholder'=> 'select start date','id'=>'begin_date',]) !!}
                                </div>
                            </div>
                            <div class="col-md-3">                    
                                <div class="form-group">
                                    <label>End date</label>
                                    @php($end_date = (isset($srch_params['end_date']))?$srch_params['end_date']:null)
                                    {!! Form::date('end_date',$end_date,['class'=>'form-control','placeholder'=> 'select end date','id'=>'end_date',]) !!}
                                </div>
                            </div>                            
                            <div class="col-md-2"> 
                                <button class="btn btn-primary" type="submit" id="button-addon2">Search</button>
                                <a class="btn btn-danger" href="{{route('customers.branche-info-details',[$company->id,$id,'tab'=>'invoices'])}}">Reset</a>                        
                            </div>
                        </div>
                        
                        {!! Form::close() !!}
                    </div>
                    <div class="row">
                        <div class="table-responsive">
                        <table class="table table-centered table-nowrap mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Code</th>
                                    <th>Invoice Id</th>
                                    <th>Location</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Created Date</th>
                                    <th>View Details</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($invoices))
                                    @foreach($invoices as $inv)
                                    <tr class="">
                                        <td>{{ $inv['code'] }} </td>
                                        <td>{{ $inv['id'] }}</td>
                                        <td>{{ $inv['company'] }}</td>
                                        <td>{{ $inv['total'] }}</td>
                                        <td>{{ $inv['state'] }}</td>                                
                                        <td>{{ ($inv['created_at'])?\App\Helpers\Helper::dateConvert($inv['created_at']):'NA' }}</td>
                                        <td><a href="javascript:;" data-line_items="{{json_encode($inv)}}" data-invoice_id="{{$inv['id']}}" class="btn btn-primary line_items"><i class="fa fa-eye"></i></td>
                                        <td> 
                                            @if($inv['state'] != "paid")
                                            <a class="btn btn-sm btn-rounded btn-danger waves-effect" data-toggle="tooltip" title="" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="event.preventDefault();
                                                document.getElementById('paid-form-{{$inv['id']}}').submit();" data-original-title="Pay">Pay</a>
                                                {!! Form::open([
                                                    'method' => 'DELETE',
                                                    'route' => [
                                                    'customers.package-destroy', 
                                                    $inv['id']
                                                    ], 
                                                    'style'=>'display:inline', 
                                                    'id' => 'paid-form-' . $inv['id']
                                                    ]) !!}
                                                {!! Form::close() !!}
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                <tr>
                                    <td colspan="25">No record found</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    </div>
                </div>
                @endif
                
            </div>
        </div>
    </div>
    @endif
</div>
<div class="modal fade invoice_line_item" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="lineItemModalLabel">Invoice</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="line_item_modal_body">
                <h4>Invoice info</h4>
                <table class="table table-striped invoice-table">
                   <thead>
                       <tr>
                            <th>Invoice number</th>
                            <th>Amount</th>
                            <th>Discount</th>
                            <th>Status</th>
                            <th>Invoice Date</th>
                       </tr>                             
                   </thead> 
                   <tbody>
                   </tbody>                                
                </table>
                <h4>Line Items</h4>
                <table class="table table-striped line-item-table">
                <thead>
                       <tr>
                            <th>Description</th>
                            <th>Amount</th>
                       </tr>                             
                   </thead> 
                   <tbody>
                   </tbody>                                
                </table>
            </div>
            <div class="modal-footer">                 
                 <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>  
                
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<div class="modal fade" id="all_manifest_modal" tabindex="-1" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="manifest_mod_title">Add/Edit Manifest</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" id="manifesstBody">
                                              
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div class="modal fade" id="all_reorder_modal" tabindex="-1" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="manifest_mod_title">Inventory Re-order</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" id="reorder-body">
            {!! Form::open(array('url' => 'javascript:;','method'=>'POST', 'enctype'=>'multipart/form-data','id'=>'reorder-form','class' => 'form-horizontal')) !!}
                <div class="row">
                    <input type="hidden" id="reorder_index" value=""> 
                    <input type="hidden" id="reorder_location_id" value="">  
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('waste_type', 'Type *:',array('class'=>'','for'=>'waste_type_rorder_modal'),false) !!}
                            {!! Form::select('waste_type',$inventoryType,null,['class'=>'form-control select2','id'=>'waste_type_rorder_modal','placeholder'=>'Choose ...','required'=>'required']) !!}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('container_type', 'Container Type *:',array('class'=>'','for'=>'container_type_rorder_modal'),false) !!}
                            {!! Form::select('container_type',$waste_type,null,['class'=>'form-control select2','id'=>'container_type_rorder_modal','placeholder'=>'Choose ...','required'=>'required']) !!}
                        </div>
                    </div>
                </div> 
                <div class="text-right">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" id="inventory_reorder_submit" class="btn btn-primary w-md">Save</button>
                    
                </div>
                {!! Form::close() !!}                    
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
@push('pagejs')
<script src="{{ asset('/administrator/assets/libs/jquery.validate.min.js')}}"></script>
<script src="{{ asset('/administrator/assets/libs/additional-methods.min.js')}}"></script>
<script>

$(document).ready(function () {
    $('body').on('click','.re-order',function(){
        let index = $(this).attr('data-id');
        let location_id = $(this).attr('data-location_id');
        $('#reorder_index').val(index);
        $('#reorder_location_id').val(location_id);
        $('#all_reorder_modal').modal('show');
    });
    $('body').on('click','#close_last_run_info',function(){
        $('#last_run_info_row').addClass('d-none');
    });
    $('body').on('click','.last_run_info',function(){
        let imie_no = $(this).attr('data-imie_no');
        let location_id = $(this).attr('data-location_id');
        $.ajax({
            url: '{{url("admin/customers/ajax-get-last-run-info")}}',
            type: 'GET',
            data: {imie_no:imie_no,location_id:location_id},
            beforeSend: function () {
                $('.loader').show();
            },
            success: function (data) {
                $('.loader').hide();                       
                if (data.success) {
                    let html = `
                        <td>${data.data.startDateTime}</td>
                        <td>${data.data.endDateTime}</td>
                        <td>${data.data.lowTemp}</td>
                        <td>${data.data.highTemp}</td>
                        <td>${data.data.type}</td>
                        <td><button type="button" class="btn btn-sm btn-outline-primary waves-effect waves-light machine-ping" data-location_id="${location_id}" data-imie_no="${imie_no}">Ping Machine</button></td>
                    `;
                    $('#last_run_ifo_tr').html(html);
                    $('#last_run_info_row').removeClass('d-none');
                    // bootbox.alert({
                    //     title:"Last Info Details",
                    //     message: data.msg ,
                    //     type:"success"                   
                    // });
                } else {
                    bootbox.alert({
                        title:"Last Info Details",
                        message:  data.msg ,
                        type:"error"                   
                    });
                }
            },
            error: function(data){
                $('.loader').hide();
                bootbox.alert({
                    title:"Last Info Details",
                    message:  data.msg ,
                    type:"error"                   
                });
            }
        });
    });
    $('body').on('click','.machine-ping',function(){
        let imie_no = $(this).attr('data-imie_no');
        let location_id = $(this).attr('data-location_id');
        $.ajax({
            url: '{{url("admin/customers/ajax-post-machine-ping")}}',
            type: 'POST',
            data: {imie_no:imie_no,location_id:location_id},
            beforeSend: function () {
                $('.loader').show();
            },
            success: function (data) {
                $('.loader').hide();                       
                if (data.success) {
                    
                    bootbox.alert({
                        title:"Machine Ping",
                        message: data.msg ,
                        type:"success"                   
                    });
                } else {
                    bootbox.alert({
                        title:"Machine Ping",
                        message:  data.msg ,
                        type:"error"                   
                    });
                }
            },
            error: function(data){
                $('.loader').hide();
                bootbox.alert({
                    title:"Machine Ping",
                    message:  data.msg ,
                    type:"error"                   
                });
            }
        });
    });
    $('body').on('click','.update-inventory-info',function(){
        // var inputs = $(this).closest('tr').find('input,select');
        var location = $(this).data('id');
        var updatedata = {
            "locationId": location,
            "canisterInventory": [
                {
                    "canisterType": "redbag",
                    "reorderPoint": $('#rb_rop_'+location).val(),
                    "availableInventory": $('#rb_inventory_'+location).val()
                },
                {
                    "canisterType": "sharps",
                    "reorderPoint": $('#sh_rop_'+location).val(),
                    "availableInventory": $('#sh_inventory_'+location).val()
                }
            ]
        };
        $.ajax({
            url: '{{url("admin/inventory/ajax-post-inventory-update")}}',
            type: 'POST',
            data: updatedata,
            beforeSend: function () {
                $('.loader').show();
            },
            success: function (data) {
                $('.loader').hide();                       
                if (data.success) {
                    bootbox.alert({
                        title:"Inventory Update Successful",
                        message: data.msg ,
                        size: 'small',
                        type:"success"                   
                    });
                } else {
                    bootbox.alert({
                        title:"Inventory update failed",
                        message:  data.msg ,
                        size: 'small',
                        type:"error"                   
                    });
                }
            },
            error: function(data){
                $('.loader').hide();
                bootbox.alert({
                    title:"Inventory update",
                    message:  data.msg ,
                    type:"error"                   
                });
            }
        });
    });
    
    $('#reorder-form').validate({
        rules: {
            type: {
                required: true
            },
            container_type: {
                required: true
            }
        },
        messages: {
            type: {
                required: "Please select type",
            },
            container_type: {
                required: "Please select container type",
            }
        },
        errorPlacement: function (error, element) {
            if (element.attr("name") == "end_time") {
                error.insertAfter("#end_time-error");
            }
            if (element.attr("name") == "status") {
                error.insertAfter("#status-error");
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function (form) {
            let waste_type = $('#waste_type_rorder_modal').val();
            let container_type = $('#container_type').val();
            let index = $('#reorder_index').val();
            let location_id = $('#reorder_location_id').val();
            let inventory = $('#inventory_'+index).val();
            let re_order_point = $('#re_order_point_'+index).val();;
            $.ajax({
                url: '{{url("admin/customers/ajax-post-inventory-reorder")}}',
                type: 'POST',
                data: {waste_type:waste_type,container_type:container_type,location_id:location_id,inventory:inventory,re_order_point:re_order_point},
                beforeSend: function () {
                    $('.loader').show();
                },
                success: function (data) {
                    $('#all_reorder_modal').modal('hide');
                    $('.loader').hide();                       
                    if (data.success) {
                        bootbox.alert({
                            title:"Inventory Re-order",
                            message: data.msg ,
                            type:"success"                   
                        });
                    } else {
                        bootbox.alert({
                            title:"Inventory Re-order",
                            message:  data.msg ,
                            type:"error"                   
                        });
                    }
                },
                error: function(data){
                    $('.loader').hide();
                    bootbox.alert({
                        title:"Inventory Re-order",
                        message:  data.msg ,
                        type:"error"                   
                    });
                }
            });
        }
    });
    $('body').on('click','.line_items', function(){
        let id = $(this).attr('data-invoice_id');
        let lineItems = $(this).attr('data-line_items');
        lineItems = JSON.parse(lineItems);
        console.log(lineItems);
        var invDet = '<tr><td>'+lineItems.id+'</td><td>'+lineItems.total+'</td><td>'+lineItems.discount+'</td><td>'+lineItems.state+'</td><td>'+lineItems.created_at+'</td></tr>';
        $('.invoice-table tbody').html(invDet);
        let html = '';
        $.each(lineItems.line_items,function(index,value){
            html +='<tr><td>'+value.description+'</td><td>'+value.amount+'</td></tr>';
        });
        $('#lineItemModalLabel').html('Invoice : #'+id);
        $('.line-item-table tbody').html(html);
        $('.invoice_line_item').modal('show');
    });
    $('body').on('click','.add_edit_manifest', function(e){
        let id = $(this).attr('data-hauling_id');
        let branch_id = $(this).attr('data-branch_id');
        $.ajax({
            url: '{{url("admin/pickups/ajax-get-manifest-details")}}',
            data: {
                hauling_id: id,branch_id:branch_id
            },
            type: 'GET',
            beforeSend: function() {
                $('.loader').show();
            },
            success: function(data) {
                $('.loader').hide();
                if (data.success) {                    
                    $('#manifesstBody').html(data.html);
                    $('#all_manifest_modal').modal('show');
                } 
                else {                                        
                    bootbox.alert({
                        title:"Manifest Details",
                        message: data.msg ,
                        type:"error"                   
                    });
                }
            },
            error: function(data) {
                $('.loader').hide();
                bootbox.alert({
                    title:"Manifest Details",
                    message: data.msg ,
                    type:"error"                   
                });
            }
        });
    });

    $('body').on('click','#manifest_submit', function(){
        //let form_data = $("#manifest-form").serialize();
        var form_data = new FormData($('#manifest-form')[0]);
        $.ajax({
            url: '{{url("admin/pickups/ajax-update-manifest-details")}}',
            data: form_data,
            type: 'POST',
            processData: false,
            contentType: false,
            beforeSend: function() {
                $('.loader').show();
            },
            success: function(data) {
                $('.loader').hide();
                $('#all_manifest_modal').modal('hide');
                if (data.success) {
                    bootbox.alert({
                        title:"Manifest Details",
                        message: data.msg ,
                        type:"success"                   
                    });
                } 
                else {
                    bootbox.alert({
                        title:"Manifest Details",
                        message: data.msg ,
                        type:"error"                   
                    });
                }
            },
            error: function(data) {
                $('.loader').hide();
                    bootbox.alert({
                    title:"Manifest Assign",
                    message: data.msg ,
                    type:"error"                   
                });
            }
        });
    });

});
</script>
@endpush