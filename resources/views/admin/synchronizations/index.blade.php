
@extends('admin.layouts.layout')
@push('pageplugincss')

@endpush

@section('content')
<div class="card">
    <div class="card-header">     
            <h3>Recurring Synchronization</h3>
        </div> 
    <div class="card-body">                         
        <div class="col-md-12">                          
                {!! Form::open(['method' => 'POST','route' => $routePrefix.'.recurring-sync','id' => 'srch-form','class' => '']) !!}
                <div class="row">
                    <div class="col-md-3">                    
                        <div class="form-group">
                            <label>Start date</label>
                            {!! Form::date('begin_date',null,['class'=>'form-control','placeholder'=> 'select start date','id'=>'begin_date','required' => 'required']) !!}
                        </div>
                    </div>
                    <div class="col-md-3">                    
                        <div class="form-group">
                            <label>End date</label>
                            {!! Form::date('end_date',null,['class'=>'form-control','placeholder'=> 'select end date','id'=>'end_date','required' => 'required']) !!}
                        </div>
                    </div>
                    <div class="col-md-3">                         
                        <button class="btn btn-primary" type="submit" id="button-addon2"><i class="fa fa-sync"></i></button>
                    </div>
                </div>
                
                {!! Form::close() !!}
            
        </div>
    </div>
</div>



@endsection

@push('pagejs')
<script>
$(document).ready(function () {
    
    
});
</script>
@endpush

