
@extends('admin.layouts.layout')
@push('pageplugincss')

@endpush

@section('content')
@php($tab = Request::get('tab')?Request::get('tab'):'guest_info')
<div class="row">
    <div class="col-lg-12">
        <ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
            <li class="nav-item waves-effect waves-light">
                <a class="nav-link {!! ($tab == 'guest_info')?'active':'' !!}" href="{{route($routePrefix.'.index',['tab'=>'guest_info'])}}"  aria-selected="true">
                    <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                    <span class="d-none d-sm-block">Guest Information</span> 
                </a>
            </li>
            <li class="nav-item waves-effect waves-light">
                <a class="nav-link {!! ($tab == 'request_info')?'active':'' !!}" href="{{route($routePrefix.'.index',['tab'=>'request_info'])}}" aria-selected="false">
                    <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                    <span class="d-none d-sm-block">Request From Guest<i class="rqst-no">{{$newRequestCount}}</i></span> 
                </a>
            </li>
        </ul>

        <div class="tab-content pt-3 text-muted">
            @if($tab == 'guest_info')
            <div class="tab-pane {!! ($tab == 'guest_info')?'active':'' !!}" id="home-1" role="tabpanel">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-column flex-md-row justify-content-between">
                            <div class="input-group mw-30">
                            {!! Form::open(array('route' => $routePrefix.'.index','method'=>'GET', 'enctype'=>'multipart/form-data','id'=>'guest_info_srch_form')) !!}
                                <input type="hidden" name="tab" value="{{$tab}}">
                                <input type="text" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="button-addon2" name="search_by" value="{{(Request::get('search_by'))?Request::get('search_by'):null}}">
                                <button class="btn btn-primary" type="submit" id="button-addon2">Search</button>
                                <a href="{{route($routePrefix.'.index',['tab' => $tab])}}" class="btn btn-danger">Reset</a>
                                {!! Form::close() !!}
                            </div>
                        </div>
                        <div class="tab-content mt-3 text-muted">
                            <div class="tab-pane {!! ($tab == 'guest_info')?'active':'' !!}" id="home1" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table table-centered table-nowrap mb-0">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Name</th>
                                                <th>Company Name</th>
                                                <th>E-mail Id</th>
                                                <th>Phone</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(count($data))
                                                @foreach($data as $val)
                                                @php($company_name = ($val->guest_company)?$val->guest_company->company_name:'')
                                                @php($company_email = ($val->guest_company)?$val->guest_company->company_email:'')
                                                @php($company_phone = ($val->guest_company)?$val->guest_company->company_phone:'')
                                                <tr class="">
                                                    <td>{{$val->full_name}}</td>
                                                    <td>{{ ($val->guest_company)?$val->guest_company->company_name:'NA' }}</td>
                                                    <td>{{ $val->email }}</td>
                                                    <td><a href="tel:{{ $val->phone }}">{{ $val->phone }}</a></td>
                                                    <td>{!! ($val->status == 0)?'<span class="badge badge-pill badge-soft-primary">Pending</span>':(($val->status == 1)?'<span class="badge badge-pill badge-soft-success">Active</span>':'<span class="badge badge-pill badge-soft-danger">Inactive</span>') !!}</td>
                                                    <td><a href="{{route('customers.create',['0','guest'=>$val->id,'company_name'=>$company_name,'company_phone'=>$company_phone,'compnay_email'=>$company_email])}}" class="btn btn-outline-primary waves-effect waves-light">Convert To Customer</a></td>
                                                </tr>
                                                @endforeach
                                            @else
                                            <tr class="">
                                                <td colspan="6">No record found</td>
                                            </tr>
                                            @endif
                                           
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        @if(count($data))
                        {!! $data->appends(request()->input())->links() !!}
                        @endif
                        <!-- <div class="row">
                            <div class="col-lg-12">
                                <ul class="pagination pagination-rounded justify-content-center mt-4">
                                    <li class="page-item disabled">
                                        <a href="#" class="page-link"><i class="mdi mdi-chevron-left"></i></a>
                                    </li>
                                    <li class="page-item">
                                        <a href="#" class="page-link">1</a>
                                    </li>
                                    <li class="page-item active">
                                        <a href="#" class="page-link">2</a>
                                    </li>
                                    <li class="page-item">
                                        <a href="#" class="page-link">3</a>
                                    </li>
                                    <li class="page-item">
                                        <a href="#" class="page-link">4</a>
                                    </li>
                                    <li class="page-item">
                                        <a href="#" class="page-link">5</a>
                                    </li>
                                    <li class="page-item">
                                        <a href="#" class="page-link"><i class="mdi mdi-chevron-right"></i></a>
                                    </li>
                                </ul>
                            </div>
                        </div> -->
                        <!-- end table-responsive -->
                    </div>
                </div>
            </div>
            @endif
            @if($tab == 'request_info')
            <div class="tab-pane {!! ($tab == 'request_info')?'active':'' !!}" id="profile-1" role="tabpanel">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-column flex-md-row justify-content-between">
                            <div class="input-group mw-30">
                            {!! Form::open(array('route' => $routePrefix.'.index','method'=>'GET', 'enctype'=>'multipart/form-data','id'=>'guest_info_srch_form')) !!}
                                <input type="hidden" name="tab" value="{{$tab}}">
                                <input type="text" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="button-addon2" name="search_by" value="{{(Request::get('search_by'))?Request::get('search_by'):null}}">
                                <button class="btn btn-primary" type="submit" id="button-addon2">Search</button>
                                <a href="{{route($routePrefix.'.index',['tab' => $tab])}}" class="btn btn-danger">Reset</a>
                            {!! Form::close() !!}
                            </div>
                        </div>
                        @php($request_types = ['1' => 'Emission calculator','2'=>'Cost Analysis'])
                        <div class="tab-content mt-3 text-muted">
                            <div class="tab-pane {!! ($tab == 'request_info')?'active':'' !!}" id="home1" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table table-centered table-nowrap mb-0">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Request Type</th>
                                                <th>Name</th>
                                                <th>Company Name</th>
                                                <th>E-mail Id</th>
                                                <th>Phone</th>
                                                <th>Invoices</th>
                                                <th>Manifests</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(count($data))
                                                @foreach($data as $val)
                                                <tr class="">
                                                    <td>{!! isset($request_types[$val->request_type])?$request_types[$val->request_type]:'NA' !!}</td>
                                                    <td>{!! ($val->user)?$val->user->full_name:'NA' !!}</td>
                                                    <td>{!! ($val->company_name)?$val->company_name:'NA' !!}</td>
                                                    <td>{{ $val->company_email }}</td>
                                                    <td><a href="tel:{{ $val->company_phone }}">{{ $val->company_phone }}</a></td>
                                                    <td class="font-22"><a href="{{route($routePrefix.'.request-file-download',['invoice',$val->id])}}" class="" data-id="{{$val->id}}"><i class="bx bx-cloud-download"></i></a></td>
                                                    <td class="font-22"><a href="{{route($routePrefix.'.request-file-download',['manifest',$val->id])}}" class="" data-id="{{$val->id}}"><i class="bx bx-cloud-download"></i></a></td>
                                                    <td><button type="button" class="btn btn-outline-primary waves-effect waves-light reply_to_button" data-id="{{$val->id}}" data-email="{{$val->company_email}}">Reply</button></td>
                                                </tr>
                                                <tr class="d-none" id="file_details_{{$val->id}}">
                                                    <td colspan="7">
                                                        <div class="table-responsive">
                                                            <table class="table table-centered table-nowrap mb-0">
                                                                <thead class="thead-light">
                                                                    <tr>
                                                                        <th>File Name</th>
                                                                        <th>Size</th>
                                                                        <th>Action</th>                                                                    
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @if(is_array($val->request_info_document) && count($val->request_info_document))
                                                                        @foreach($val->request_info_document as $flVal)
                                                                        <tr>
                                                                            <td>{{$flVal['file_name_original']}}</td>
                                                                            <td>{{$flVal['file_size']}}</td>
                                                                            <td><a href="{{$flVal['original']}}" target="_blank"><i class="fa fa-eye"></i></a></td>
                                                                        </tr>
                                                                        @endforeach
                                                                    @else
                                                                        <tr>
                                                                            <td colspan="3">No file</td>
                                                                        </tr>
                                                                    @endif
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            @else
                                            <tr class="">
                                                <td colspan="7">No record found</td>
                                            </tr>
                                            @endif
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        @if(count($data))
                        {!! $data->appends(request()->input())->links() !!}
                        @endif
                        <!-- end table-responsive -->
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
<div class="modal fade" id="all_reply_modal" tabindex="-1" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="reply_mod_title">Reply</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card">  
                    {!! Form::open(array('route' => 'request-info.reply','method'=>'POST', 'enctype'=>'multipart/form-data','id'=>'reply-form')) !!}     
                    <input type="hidden" name="request_id" value="" id="request_id">
                    <div class="card-body mb-4"> 
                        <div class="row">            
                            <div class="col-md-12">
                                <div class="form-group">
                                    {!! Form::label('description', 'Description <span class="span-req">*</span>:',array('class'=>'','for'=>'description'),false) !!}
                                    {!! Form::textarea('description',null,['class'=>'form-control','placeholder'=>'Enter description','id'=>'description','required'=>'required']) !!}
                                    @if ($errors->has('description'))
                                        <span class="help-block">{{ $errors->first('description') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    {!! Form::label('upload_file', 'File <span class="span-req">*</span>:',array('class'=>'','for'=>'upload_file'),false) !!}
                                    {!! Form::file('upload_file',null,['class'=>'form-control','placeholder'=>'Please upload file','id'=>'upload_file','required'=>'required']) !!}
                                    @if ($errors->has('upload_file'))
                                        <span class="help-block">{{ $errors->first('upload_file') }}</span>
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div> 
                    <div class="card-footer text-right">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>  
                        <button type="submit" class="btn btn-success" id="reply_submit">Submit</button>                         
                    </div> 
                    {!! Form::close() !!}
                </div>                               
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
@endsection

@push('pagejs')
<script>
$(document).ready(function () {
    $('body').on('click','.download-file', function(){
        let id = $(this).attr('data-id');
        $('#file_details_'+id).toggleClass('d-none');
    });
    $('body').on('click','.reply_to_button',function(){
        var id = $(this).attr('data-id');
        $('#request_id').val(id);
        $('#reply_mod_title').html('Reply To '+$(this).attr('data-email'));
        $('#all_reply_modal').modal('show');
    });
});
</script>
@endpush

