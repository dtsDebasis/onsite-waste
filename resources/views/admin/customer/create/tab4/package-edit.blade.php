<style>
.pointer-none{
    pointer-events: none;
}
</style>
@php($page_type = isset($input['page_type'])?$input['page_type']:'create_edit')
@php($pointNone = ($page_type == "create_edit")?'':'pointer-none')
@php($frequency_types = ['1'=>'Days','2'=>'Weeks','3'=>'Months','4'=>'Years'])
@php($yes_no_arr = ['0' => 'No','1' => 'Yes'])
@php($service_types = ['TE-5000' => 'TE-5000','Pick-up' => 'Pick-up', 'Hybrid' => 'Hybrid'])
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
            {!! Form::open(array('url' => 'javascript:;','method'=>'POST', 'enctype'=>'multipart/form-data','id'=>'branch-package-form','class' => 'form-horizontal')) !!}
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
            @php($service_dnone = (isset($editPackage->service_type) && ($editPackage->service_type == 'TE-5000')) ? 'd-none':'')
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
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                @if($page_type =="create_edit")
                <button type="button" id="brnch_pck_update" class="btn btn-primary w-md">Save</button>
                @endif
            </div>
        {!! Form::close() !!}                
    </div>
</div>
