
@extends('admin.layouts.layout')
@push('pageplugincss')
<link href="{{asset('/administrator/assets/css/jquery.dataTables.css')}}" rel="stylesheet" type="text/css" />
@endpush

@section('content')
@php($status = ['0' => 'Requested','1' => 'Fulfilled'])
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="col-sm-12">
                    <div class="d-flex flex-column flex-md-row justify-content-between">
                        <div class="col-md-12">                            
                            {!! Form::open(['method' => 'GET','route' => $routePrefix.'.index','id' => 'srch-form']) !!}
                            
                            <div class="row">
                                                                   
                                <div class="col-md-3">                    
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="location" value="{{Request::get('location')?Request::get('location'):null}}" placeholder="Search by location" aria-label="Search">
                                    </div>
                                </div>
                                <div class="col-md-3">                    
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="te_5000_imei" value="{{Request::get('location')?Request::get('te_5000_imei'):null}}" placeholder="Search by TE-5000 IMEI" aria-label="Search">
                                    </div>
                                </div>
                                <div class="col-md-3">                    
                                    <div class="form-group">
                                        @php($selectedStatus = Request::get('status')?Request::get('status'):null)
                                        {!! Form::select('status',$status,$selectedStatus,['class'=>'form-control select2','placeholder'=>'Status choose ...']) !!}
                                    </div>
                                </div>
                                <div class="col-md-3"> 
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
                                    <th>Package</th>
                                    <th>TE-5000 IMEI</th>
                                    <!-- <th>TE-5000 Information</th> -->
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                            
                                @if(count($data))
                                    @foreach($data as $key => $val) 
                                        <tr class="">
                                            <td>{{ ($val->branch_details)? $val->branch_details->name:'NA' }}</td>
                                            <td> {{ ($val->package_details)? $val->package_details->name:'NA' }} </td>
                                            <td>{{$val->te_5000_imei}}</td>
                                            {{--<td><a href="javascript:;" data-id="{{$val->id}}" class="te_info"><i class="fa fa-eye"></i></a></td>--}}
                                            <td>{{ (isset($status[$val->status]))?$status[$val->status]:'NA' }}</td>
                                            
                                        </tr>
                                    @endforeach
                                @else
                                    <tr class="">
                                        <td colspan="25">No record found</td>
                                    </tr>
                                @endif                                
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-lg-12">
                                @include('admin.components.pagination')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="te_5000_modal" tabindex="-1" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="te_500_mod_title">TE-5000 Information</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" id="te_500_Body">
                                              
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
    $('body').on('click','.te_info', function(){
        var id = $(this).attr('data-id');
        $.ajax({
            url: '{{url("admin/te-500-info-details")}}',
            data: {
                id:id
            },
            type: 'GET',
            beforeSend: function() {
                $('.loader').show();
            },
            success: function(data) {
                $('.loader').hide();
                if (data.success) {                    
                    $('#te_500_Body').html(data.html);
                    $('#te_5000_modal').modal('show');
                } 
                else {                                        
                    bootbox.alert({
                        title:"TE-5000 Information",
                        message: data.msg ,
                        type:"error"                   
                    });
                }
            },
            error: function(data) {
                $('.loader').hide();
                bootbox.alert({
                    title:"TE-5000 Information",
                    message: data.msg ,
                    type:"error"                   
                });
            }
        });
    });
});
</script>
@endpush

