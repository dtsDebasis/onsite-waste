
@extends('admin.layouts.layout')

@section('content')
<div class="card-body card mb-4">
    <div class="d-flex flex-column flex-md-row justify-content-between">
        <!-- <form class="form-inline" method="get" action="">
            <div class="input-group mw-30">
                <input value="" name="name" type="text" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="button-addon2">
                <button class="btn btn-primary" type="sybmit" id="button-addon2">Search</button>
            </div>
        </form> -->
        <div>
        <a data-toggle="modal" data-target="#add-folder-modal" class="btn btn-warning w-md"><i class="fa fa-folder-open"></i> New Folder</a>
        <a data-toggle="modal" data-target="#add-file-modal" class="btn btn-primary w-md"><i class="fa fa-file"></i> Add File</a>
        </div>
    </div>
    <div class="tab-content mt-3 text-muted">
        <div class="tab-pane active" id="home1" role="tabpanel">
            <div class="table-responsive">
                <table class="table table-centered table-condensed table-striped table-nowrap mb-0">
                    <thead>
                        <tr>
                            <th>Folder/File</th>
                            <th>Location</th>
                            <th>Upload Date</th>
                            <th>Size</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($location)
                            <tr><td colspan="5">
                                <a href="{{route('storage.index',['browse'=>$prevlocation])}}"><i style="font-size:16px" class="fa fa-angle-double-left"></i> Previous...</a>
                            </td></tr> 
                        @endif
                        @foreach ($objects as $key => $val)  
                            <tr>
                                <td>
                                    @if ($val['type']=='folder')
                                        <i class="fa fa-folder text-warning" style="font-size:20px"></i>
                                    @else
                                        <i class="fa fa-file text-info" style="font-size:20px"></i>   
                                    @endif
                                    
                                </td>
                                <td>
                                    @if ($val['type']=='folder')
                                        <a href="{{route('storage.index',['browse'=>$location.$val['object']])}}">{{$val['object']}}</a>
                                    @else
                                        <span class="text-danger">{{$val['object']}}</span>
                                    @endif
                                    
                                </td>
                                <td>{{$val['createdate']}}</td>
                                <td>
                                    @if ($val['type']=='folder')
                                        0 Bytes
                                    @else
                                        {{$val['size']}} Bytes
                                    @endif
                                </td>
                                <td>
                                    @if ($val['type']=='folder')
                                        <a href="{{route('storage.index',['browse'=>$location.$val['object']])}}" class="btn btn-primary btn-sm btn-rounded waves-effect waves-light" data-toggle="tooltip" title="" data-original-title="Open"><i class="fa fa-folder-open"></i></a>
                                    @else
                                        <a href="{{route('storage.download',['key'=>$location.$val['object']])}}" target="_blank" class="btn btn-primary btn-sm btn-rounded waves-effect waves-light" data-toggle="tooltip" title="" data-original-title="Download"><i class="fa fa-download"></i></a>
                                    @endif
                                    
                                    <a class="btn btn-danger btn-sm btn-rounded waves-effect waves-light" data-toggle="tooltip" title="" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="event.preventDefault();
                                document.getElementById('delete-form-{{$val['object']}}').submit();" data-original-title="Delete">{!! \Config::get('settings.icon_delete') !!}</a>
                                {!! Form::open([
                                    'method' => 'DELETE',
                                    'route' => [
                                        $routePrefix . '.destroy', ['location'=>
                                        $location?$location:'-',
                                        'file' => $val['object']
                                    ]
                                    ],
                                    'id' => 'delete-form-' . $val['object']
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
<div class="modal fade" id="add-file-modal" tabindex="-1" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-info modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="manifest_mod_title">Adding File To <span id="filelocation" class="text-primary">{{$location?$location:'/'}}</span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" id="fileBody">
                <form id="createfileform" type="multipart/formdata" action="" method="post">
                    <input type="hidden" name="filelocationpath" value="{{$location}}"/>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="inputGroupFileAddon01">Upload</span>
                        </div>
                        <div class="custom-file">
                            <input type="file" required class="custom-file-input" name="inputfile" id="inputGroupFile01"
                            aria-describedby="inputGroupFileAddon01">
                            <label class="custom-file-label overme" id="label-inputGroupFile01" for="inputGroupFile01">Choose file</label>
                        </div>
                    </div>
                    <div class="input-group mt-3">
                        <button type="submit" class="btn btn-primary btn-md">Upload</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div class="modal fade" id="add-folder-modal" tabindex="-1" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-info modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="manifest_mod_title">Adding Folder To <span id="folderlocation" class="text-primary">{{$location?$location:'/'}}</span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" id="folderBody">
                <form id="createfolderform" action="" method="post">
                    <input type="hidden" name="locationpath" value="{{$location}}"/>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Folder Name</span>
                        </div>
                        <input type="text" required class="form-control" id="foldername" name="foldername">
                    </div>
                    <div class="input-group mt-3">
                        <button type="submit" class="btn btn-primary btn-md">Create</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

@push('pagejs')
<script>
    $('#inputGroupFile01').on('change', function(event) {
        var filename = $(this).val().split('\\').pop();
        $('#label-inputGroupFile01').text(filename);
    });
    $('#createfileform').on('submit', function(event){
        event.preventDefault();
        $('#cover-spin').show(0);
        $.ajax({
            url:"{{route('storage.uploadfile')}}",
            method:"POST",
            data:new FormData(this),
            dataType:'json',
            contentType:false,
            cache:false,
            processData:false,
                success:function(result)
                    {
                        $('#cover-spin').hide(0);
                        location.reload();
                    }
        });
    });

    $('#createfolderform').on('submit', function(event){
        event.preventDefault();
        $('#cover-spin').show(0);
        var formdata = $('#createfolderform').serializeArray();
        var formObject = {};
        $.each(formdata, function(i, v) {
            formObject[v.name] = v.value;
        });
        $.ajax({
            url:"{{route('storage.createfolder')}}",
            method:"POST",
            data: JSON.stringify(formObject),
            dataType:'json',
            contentType:'application/json',
            processData:false,
                success:function(result)
                    {
                        $('#cover-spin').show(0);
                        location.reload();
                    }
        });
    });

</script>
@endpush
@endsection


