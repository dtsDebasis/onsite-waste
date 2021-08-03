<div class="card">  
@if($manifest)
    {!! Form::model($manifest, [
        'method' => 'PATCH',
        'url' => 'javascript:;', 
        'class' => 'form-horizontal ',
        'id'=>'manifest-form',
        'enctype'=>'multipart/form-data'
        ]) 
    !!}
    @else
        {!! Form::open(array('url' => 'javascript:;','method'=>'POST', 'enctype'=>'multipart/form-data','id'=>'manifest-form','class' => 'form-horizontal')) !!}
    @endif
        <input type="hidden" name="id" value="" id="manifest_id" value="{{isset($manifest->id)?$manifest->id:null}}">
        <input type="hidden" name="hauling_id" value="{{($haulingDetails)?$haulingDetails->id:null}}" id="manifest_hauling_id">
        <div class="card-body mb-4"> 
            <div class="row">            
                <div class="col-md-3">
                    <div class="form-group">
                        @php($person_name = isset($manifest->person_name)?$manifest->person_name:((isset($haulingDetails->driver_name) && $haulingDetails->driver_name)?$haulingDetails->driver_name:null))
                        {!! Form::label('person_name', 'Provider Name <span class="span-req">*</span>:',array('class'=>'','for'=>'driver_name'),false) !!}
                        {!! Form::text('person_name',$person_name,['class'=>'form-control','placeholder'=>'Enter name','id'=>'driver_name','required'=>'required']) !!}
                        @if ($errors->has('person_name'))
                            <span class="help-block">{{ $errors->first('person_name') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('date', 'Date <span class="span-req">*</span>:',array('class'=>'','for'=>'manifest_date'),false) !!}
                        {!! Form::date('date',null,['class'=>'form-control','placeholder'=>'Select date','id'=>'manifest_date','required'=>'required']) !!}
                        @if ($errors->has('date'))
                            <span class="help-block">{{ $errors->first('date') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-3">                    
                    <div class="form-group">
                        {!! Form::label('number_of_container', 'Number Of Boxes <span class="span-req">*</span>:',array('class'=>'','for'=>'number_of_container'),false) !!}
                        {!! Form::number('number_of_container',null,['class'=>'form-control','placeholder'=>'Enter box number','id'=>'number_of_container','required' => 'required']) !!}
                        @if ($errors->has('number_of_container'))
                            <span class="help-block">{{ $errors->first('number_of_container') }}</span>
                        @endif
                    </div>
                </div>

                <div class="col-md-3">                    
                    <div class="form-group">
                        {!! Form::label('items_weight', 'Weight Of Boxes <span class="span-req">*</span>:',array('class'=>'','for'=>'items_weight'),false) !!}
                        {!! Form::number('items_weight',null,['class'=>'form-control','placeholder'=>'Enter Weight','id'=>'items_weight','required' => 'required']) !!}
                        @if ($errors->has('items_weight'))
                            <span class="help-block">{{ $errors->first('items_weight') }}</span>
                        @endif
                    </div>
                </div>
                
                <div class="col-md-12">
                    <div class="form-group">
                    @php($branch_address = isset($manifest->branch_address)?$manifest->branch_address:((isset($haulingDetails->branch_details->addressdata) && $haulingDetails->branch_details->addressdata)?$haulingDetails->branch_details->addressdata->addressline1:null))
                        {!! Form::label('branch_address', 'Branch Address <span class="span-req">*</span>:',array('class'=>'','for'=>'branch_address'),false) !!}
                        {!! Form::text('branch_address',$branch_address,['class'=>'form-control','placeholder'=>'Enter name','id'=>'branch_address','required'=>'required']) !!}
                        @if ($errors->has('branch_address'))
                            <span class="help-block">{{ $errors->first('branch_address') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">                    
                    <div class="form-group">
                        {!! Form::label('manifest_doc', 'Upload proof of pick up :',array('class'=>'','for'=>'manifest_doc'),false) !!}
                        
                        <input type="file" name="manifest_doc[]" class="form-control" id="manifest_doc" multiple>
                    </div>
                </div>
                @if(isset($manifest->manifest_doc) && count($manifest->manifest_doc))
                <div class="col-md-4">                    
                    <div class="form-group">
                        {!! Form::label('manifest_document', 'Uploaded proof of pick up :',array('class'=>'','for'=>'manifest_document'),false) !!}
                        @foreach($manifest->manifest_document as $mkey => $mval)
                            <a href="{{$mval['original']}}" target="_blank">{{ $mval['file_name_original'] }}</a>
                        @endforeach
                    </div>
                </div>
                @endif

            </div>
        </div> 
        <div class="card-footer text-right">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>  
            <button type="submit" class="btn btn-success" id="manifest_submit">Submit</button>                         
        </div> 
    {!! Form::close() !!}
</div> 