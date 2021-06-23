<style>
.pointer-none{
    pointer-events: none;
}
</style>
@php($page_type = isset($input['page_type'])?$input['page_type']:'create_edit')
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
            {!! Form::open(array('url' => 'javascript:;','method'=>'POST', 'enctype'=>'multipart/form-data','id'=>'transactional-form','class'=>'form-horizontal')) !!}
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
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                @if($page_type =="create_edit")
                    <button type="button" id="transactional_update" class="btn btn-primary w-md">Save</button>
                @endif
            </div>
        {!! Form::close() !!}                
    </div>
</div>
