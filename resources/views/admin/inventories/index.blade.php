
@extends('admin.layouts.layout')
@push('pageplugincss')
<link href="{{asset('/administrator/assets/css/jquery.dataTables.css')}}" rel="stylesheet" type="text/css" />
@endpush

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="col-sm-12">
                    <div class="d-flex flex-column flex-md-row justify-content-between">
                        <div class="col-md-9">                            
                            {!! Form::open(['method' => 'GET','route' => $routePrefix.'.index','id' => 'srch-form']) !!}
                            
                            <div class="row">
                                <div class="col-md-5">                    
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="company" value="{{Request::get('company')?Request::get('company'):null}}" placeholder="Search by company" aria-label="Search">
                                    </div>
                                </div>                                    
                                <div class="col-md-3">                    
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="location" value="{{Request::get('location')?Request::get('location'):null}}" placeholder="Search by location" aria-label="Search">
                                    </div>
                                </div>
                                <div class="col-md-4"> 
                                    <button class="btn btn-primary" type="submit" id="button-addon2">Search</button>
                                    <a class="btn btn-danger" href="{{route($routePrefix.'.index')}}">Reset</a>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                        
                    </div>
                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap mb-0" id="inventories_table">
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
                            @if(count($inventories))
                                @foreach($inventories as $ikey => $ival) 
                                    <tr class="">
                                        <td>{{$ival->name}}</td>
                                        @php($inventory_details = (array_key_exists("canisterInventory",$ival->inventory_details))?$ival->inventory_details['canisterInventory']: array())
                                        <td><input class="form-control" type="number" name="sh_inventory[]" id="sh_inventory_{{$ival->uniq_id}}" value="{{isset($inventory_details[1]['availableInventory'])?$inventory_details[1]['availableInventory']:0}}"> </td>
                                        <td><input class="form-control" type="number" name="sh_rop[]" id="sh_rop_{{$ival->uniq_id}}" value="{{isset($inventory_details[1]['reorderPoint'])?$inventory_details[1]['reorderPoint']:0}}"> </td>
                                        <td>{!! Form::select('sh_container_type',['Spinner'=>'Spinner','Rocker'=>'Rocker'],isset($inventory_details[1]['canisterType'])?$inventory_details[1]['canisterType']:null,['class'=>'form-control select2','id'=>'sh_container_type','placeholder'=>'Choose ...']) !!}</td>
                                        <!-- <td>{{isset($inventory_details[1]['canisterType'])?$inventory_details[1]['canisterType']:'NA'}} </td> -->
                                        <td><input class="form-control" type="number" name="rb_inventory[]" id="rb_inventory_{{$ival->uniq_id}}" value="{{isset($inventory_details[0]['availableInventory'])?$inventory_details[0]['availableInventory']:0}}"> </td>
                                        <td><input class="form-control" type="number" name="rb_inventory[]" id="rb_inventory_{{$ival->uniq_id}}" value="{{isset($inventory_details[0]['reorderPoint'])?$inventory_details[0]['reorderPoint']:'NA'}}"> </td>
                                        <!-- <td>{{isset($inventory_details[0]['canisterType'])?$inventory_details[0]['canisterType']:'NA'}} </td> -->
                                        <td>{!! Form::select('rb_container_type',['Rocker'=>'Rocker','Open'=>'Open'],isset($inventory_details[0]['canisterType'])?$inventory_details[1]['canisterType']:null,['class'=>'form-control select2','id'=>'rb_container_type','placeholder'=>'Choose ...']) !!}</td>
                                        <td>
                                            <a href="javascript:;" data-toggle="tooltip" data-id="{{$ival->uniq_id}}" data-placement="top" title="" data-original-title="Update" type="button" class="btn btn-primary btn-sm btn-rounded waves-effect waves-light">
                                                <i class="fa fa-save"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                <td colspan="5">No record found</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            {{ $inventories->appends(request()->input())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="cycle_modal" tabindex="-1" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="manifest_mod_title">Last Cycle Run Information</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" id="cyclingBody">
                                              
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>


@endsection

@push('pagejs')
<script src="{{ asset('/administrator/assets/js/pages/jquery.dataTables.js')}}"></script>
<script>
$(document).ready(function () {
    //$('#inventories_table').DataTable();
    $('body').on('click','.cycling_details', function(){
        var company_id = $(this).attr('data-id');
        $.ajax({
            url: '{{url("admin/inventory/cycling-details")}}',
            data: {
                branch_id:company_id
            },
            type: 'GET',
            beforeSend: function() {
                $('.loader').show();
            },
            success: function(data) {
                $('.loader').hide();
                if (data.success) {                    
                    $('#cyclingBody').html(data.html);
                    $('#cycle_modal').modal('show');
                } 
                else {                                        
                    bootbox.alert({
                        title:"Last Cycle Run Information",
                        message: data.msg ,
                        type:"error"                   
                    });
                }
            },
            error: function(data) {
                $('.loader').hide();
                bootbox.alert({
                    title:"Last Cycle Run Information",
                    message: data.msg ,
                    type:"error"                   
                });
            }
        });
    });
    $('body').on('click','.cycling_details_status_change', function(){
        
        var branch_id = $(this).attr('data-branch_id');
        var info_id = $(this).attr('data-info_id');
        $.ajax({
            url: '{{url("admin/inventory/update-cycling-details")}}',
            data: {
                branch_id:branch_id,info_id:info_id
            },
            type: 'POST',
            beforeSend: function() {
                $('.loader').show();
            },
            success: function(data) {
                $('.loader').hide();
                if (data.success) {                    
                    $('#cycle_modal').find('#ping_data_body').html(data.msg);
                } 
                else {                                        
                    bootbox.alert({
                        title:"Cycling Details",
                        message: data.msg ,
                        type:"error"                   
                    });
                }
            },
            error: function(data) {
                $('.loader').hide();
                bootbox.alert({
                    title:"Cycling Details",
                    message: data.msg ,
                    type:"error"                   
                });
            }
        });
    });

});
</script>
@endpush

