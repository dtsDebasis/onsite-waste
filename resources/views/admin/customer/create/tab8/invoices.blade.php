@extends('admin.customer.create.createlayout')
@section('create-customer-content')
<!-- tab8 -->
<div class="tab-pane active" id="invoices-1" role="tabpanel">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-12">
                    <h3 class="custom-heading">List of customer invoices</h3>
                </div>
            </div>
            <div class="">
            {!! Form::open(['method' => 'GET','route' => ['customers.create.invoices',$id],'id' => 'srch-form']) !!}
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
                    <div class="col-md-4">                    
                        <div class="form-group">
                            <label>Location</label>
                            @php($location = Request::get('location')?Request::get('location'):null)
                            {!! Form::select('location',$branches,$location,['class'=>'form-control select2','placeholder'=> 'Choose ...','id'=>'location',]) !!}
                        </div>
                    </div>
                    <div class="col-md-2"> 
                        <button class="btn btn-primary" type="submit" id="button-addon2">Search</button>
                        <a class="btn btn-danger" href="{{route('customers.create.invoices',$id)}}">Reset</a>                        
                    </div>
                </div>
                
                {!! Form::close() !!}
                
            </div>
            <div class="row">                
                {{--<div class="table-responsive">
                    <table class="table table-centered table-nowrap mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Code</th>
                                <th>Invoice Id</th>
                                <th>Location</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Created Date</th>
                                <th>Line items</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($invoices as $inv)
                            <tr class="">
                                <td>{{ $inv['code'] }} </td>
                                <td>{{ $inv['id'] }}</td>
                                <td>{{ $inv['company'] }}</td>
                                <td>{{ $inv['total'] }}</td>
                                <td>{{ $inv['state'] }}</td>                                
                                <td>{{ ($inv['created_at'])?\App\Helpers\Helper::dateConvert($inv['created_at']):'NA' }}</td>
                                <td><a href="javascript:;" data-line_items="{{implode('##',$inv['line_items'])}}" data-invoice_id="{{$inv['id']}}" class="btn btn-primary line_items"><i class="fa fa-eye"></i></td>
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
                        </tbody>
                    </table>
                </div>
                --}}
            </div>
        </div>
    </div>
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
                
            </div>
            <div class="modal-footer">                 
                 <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>  
                
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
@endsection('create-customer-content')


@section('create-customer-content-js')
<script>
$(document).ready(function () {

    let loc = window.location.href;
    console.log(loc);
    $('.nav-link.active').removeClass('active');
    if(/create\/invoices/.test(loc)) {
        $('.nav-link.invoices').addClass('active');
    }
    $('body').on('click','.line_items', function(){
        let id = $(this).attr('data-id');
        let lineItems = $(this).attr('data-line_items');
        lineItems = lineItems.split('##');
        let html = '<ul class="list-inline mb-0">';
        $.each(lineItems,function(index,value){
            html +=`<li class="list-inline-item mr-3">
                    <h5 class="font-size-14" data-toggle="tooltip" data-placement="top" title="" data-original-title="Item">${value}</h5>
                </li><br>`;
        });
        html += '</ul>';
        $('#lineItemModalLabel').html('Invoice : #'+id);
        $('#line_item_modal_body').html(html);
        $('.invoice_line_item').modal('show');
    });

});
</script>
@endsection('create-customer-content')