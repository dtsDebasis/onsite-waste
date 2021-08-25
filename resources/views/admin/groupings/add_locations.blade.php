@extends('admin.layouts.layout')


@section('content')
<div class="card-body card mb-4">


    <div class="tab-content mt-3 text-muted">
        <div class="tab-pane active" id="home1" role="tabpanel">
        <!-- <h4 class="text-primary">GROUP INFORMATION</h4>     -->
        <div class="table-responsive">
            <table class="table table-centered table-condensed table-striped table-nowrap mb-4">
                <thead class="thead-light">
                    <tr>
                        <th>Customer </th>
                        <th>Category </th>
                        <th>Group Name </th>
                        <th>Location Count </th>
                        <th>Color Code </th>
                        <th>Normalization Type </th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{(isset($group_det->customer_details) && $group_det->customer_details)?$group_det->customer_details->company_name:'NA'}}</td>
                        <td>{{ $group_det->groupcategory->name }}</td>
                        <td>{{ $group_det->name }}</td>
                        <td>{{count($group_det->grouplocationmap)}}</td>
                        <td><span style="height:10px;padding: 6px;display:block;width:50px;background:{{ $group_det->colorcode }}"></span></td>
                        <td>{{(isset($group_det->normalization_details) && $group_det->normalization_details)?$group_det->normalization_details->name:'NA'}}</td>
                        <td><span
                                class="badge badge-pill badge-soft-{{ $group_det->statuses[$group_det->status]['badge'] }} font-size-12">{!!
                                $group_det->statuses[$group_det->status]['name'] !!}</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <h4 class="text-primary">CUSTOMER LOCATIONS</h4>
        <div class="row">
            <div class="col-9">
            <form class="form-inline checkediting mb-3" method="get" action="{{route('groupings.add-locations', $group_det->id)}}">
            <div class="form-group mx-sm-1">
                <input value="{{isset($searchParams['name'])?$searchParams['name']:''}}" name="name" type="text" class="form-control" placeholder="Location Name"
                    aria-label="Search" aria-describedby="button-addon2">
            </div>
            <div class="form-group mx-sm-1">
                <input value="{{isset($searchParams['unique_code'])?$searchParams['unique_code']:''}}" name="unique_code" type="text" class="form-control" placeholder="Unique Code"
                    aria-label="Search" aria-describedby="button-addon2">
            </div>
            <div class="form-group mx-sm-1">
                <button class="btn btn-primary" type="sybmit" id="button-addon2">Search</button>
            </div>
            </form>
            </div>
            <!-- <div class="col-3">
                <button class="btn btn-warning float-right" type="button" id="button-addon2">Update Locations</button>
            </div> -->
        </div>
        <div class="table-responsive">
            <form class="checkediting" method="post" id="updatelocations">
                <table class="table table-centered table-condensed table-striped table-nowrap mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th><input type="checkbox" class="checkall" name="checkall"></th>
                            <th>Location Code {!! \App\Helpers\Helper::sort($routePrefix . '.add-locations', 'uniq_id', $orderBy, $searchParams) !!}</th>
                            <th>Name/Address {!! \App\Helpers\Helper::sort($routePrefix . '.add-locations', 'name', $orderBy, $searchParams) !!}</th>
                            <th>Assigned Groups</th>
                            <th>Normalization Factor</th>
                            <th>Specialty</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($companybranch_list))
                            @foreach($companybranch_list as $companybranch )
                                <tr
                                    @if(isset($companybranch->group)))
                                    style = "background: "
                                    @endif
                                >
                                    <td>
                                        @php($checked = false)
                                        @foreach($companybranch->group as $gr)
                                            @if($gr->id == $group_det->id)
                                                @php($checked = true)
                                            @endif
                                        @endforeach
                                        <input type="checkbox" @if($checked) checked @endif value="{{ $companybranch->id }}" data-location="{{ $companybranch->id }}" data-group="{{$group_det->id}}" id="locationcheckbox{{ $companybranch->id }}" class="locationcheckbox" name="addgroup">
                                    </td>
                                    <td>{{ $companybranch->uniq_id }}</td>
                                    <td><span class="strong text-info">{{ $companybranch->name }}</span><br>{{ ($companybranch->addressdata)?$companybranch->addressdata->addressline1:'NA' }}</td>


                                    @php($comSpecialt = [])
                                    @if(isset($companybranch->branchspecialty) && count($companybranch->branchspecialty))
                                        @foreach($companybranch->branchspecialty as $sp)
                                            @if(isset($sp->speciality_details->name) && ($sp->speciality_details->name))
                                                @php($comSpecialt[] = $sp->speciality_details->name)
                                            @endif
                                        @endforeach
                                    @endif
                                    <td class="grouplabels{{ $companybranch->id }}">
                                        @if(isset($companybranch->group) && count($companybranch->group))
                                            @foreach($companybranch->group as $gr)
                                                <label data-group="{{$gr->id}}"  data-category="{{$gr->category_id}}" data-location="{{$companybranch->id}}" class="custom-label" style="background:{{$gr->colorcode}}">{{$gr->groupcategory->name}} || {{$gr->name}} <span class="remove"> <i class="fa fa-trash"></i> </span></label>
                                            @endforeach
                                        @endif
                                    </td>
                                    <td><input class="form-control" name="normalization" value="{{$companybranch->normalization_fact}}" onKeyUp="saveNormalisationData($(this).val(),{{$companybranch->id}})" type="number"></td>
                                    <td>{{($comSpecialt)?implode(',',$comSpecialt):'NA'}}</td>

                                </tr>
                            @endforeach
                        @else
                        <tr>
                            <td colspan="25">No record found</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </form>
        </div>
            <div style="margin-top: 15px;">
                {{$companybranch_list->links()}}
            </div>
        </div>
    </div>

</div>
@include('admin.components.pagination')
@push('pagejs')
<script>
    $(document).ready(function () {
        $('body').on('click', '.checkall', function () {
            var location = "";
            if ($(this).is(':checked')) {
                var type= "add";
                $('.locationcheckbox').each(function(e,elem){
                    location = $(elem).data('location');
                    $(elem).prop('checked',true);
                    saveGroupLocations(location, type);
                });
            } else {
                var type= "remove";
                $('.locationcheckbox').each(function(e,elem){
                    location = $(elem).data('location');
                    $(elem).prop('checked',false);
                    saveGroupLocations(location, type);
                });
            }
        });
        $('body').on('click', '.locationcheckbox', function () {
            var location = $(this).data('location');
            if ($(this).is(':checked')) {
                var type= "add";
            } else {
                var type= "remove";
            }
            saveGroupLocations(location, type);
        });

        $('body').on('click', '.custom-label > .remove', function () {
            var location = $(this).parent().data('location');
            var cat = $(this).parent().data('category');
            var type= "remove";
            saveGroupLocations(location, type, cat);
            $(this).closest('tr').find('.locationcheckbox').prop('checked',false);
        });

    });
    function saveGroupLocations(location_id, type, cat='') {
        var group = {{ $group_det->id }};
        var groupname = '{{ $group_det->name }}';
        var group_cat = {{ $group_det->category_id }};
        if (cat) {
            group_cat =  cat;
        }
        var group_cat_name = '{{ $group_det->groupcategory->name }}';
        var colorcode = '{{ $group_det->colorcode }}';
        $('#cover-spin').show(0);
        $.ajax({
            url:"{{route('groupings.save-locations', $group_det->id)}}",
            method:"post",
            dataType:'json',
            data: JSON.stringify({location_id: location_id, type:type, category_id: group_cat}),
            processData:false,
            contentType:'application/json',
            success:function(data)
            {
                $('#cover-spin').hide(0);
                if(type == 'add') {
                    $('#locationcheckbox'+location_id ).closest('tr').find('label').each(function(e, elem){
                        var catid = $(elem).data('category');
                        if (catid == group_cat) {
                            $(elem).remove();
                        }
                    });
                    $('.grouplabels' + location_id).append('<label data-group="'+group+'"  data-category="'+group_cat+'" data-location="'+location_id+'" class="custom-label" style="background:'+colorcode+'">'+group_cat_name + ' || ' +groupname+' <span class="remove"> <i class="fa fa-trash"></i> </span></label>');
                } else {
                    $('#locationcheckbox'+location_id ).closest('tr').find('label').each(function(e, elem){
                        var catid = $(elem).data('category');
                        if (catid == group_cat) {
                            $(elem).remove();
                        }
                    });
                }

            },
            error: function (textStatus, errorThrown) {
                $('#cover-spin').hide(0);

            }
        })
    }
    function saveNormalisationData(val, id) {
        $.ajax({
            url:"{{route('groupings.save_normalization')}}",
            method:"post",
            dataType:'json',
            data: JSON.stringify({location_id: id, normalization_fact:val}),
            processData:false,
            contentType:'application/json',
            success:function(data)
            {


            },
            error: function (textStatus, errorThrown) {

            }
        })
    }
</script>
@endpush

@endsection
