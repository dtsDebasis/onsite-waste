@php ($headerOption = [
  'title' => $module,
  'header_buttons' => [
    ($permission['create'] ? '<a class="btn btn-primary waves-effect" href="'. route($routePrefix . '.create', $kw_category_id) .'" data-toggle="tooltip" data-original-title="Add New Record">'. \Config::get('settings.icon_add') .' <span>Add New</span></a>' : '')
  ],
  'filters' => isset($filters) ? $filters : [],
  'data'    => isset($data) ? $data : []
])
@extends('admin.layouts.layout', $headerOption)


@section('content')


<div class="row">
  <div class="col-lg-12">
  <div class="card">
  <div class="card-body">
  <div class="d-flex flex-column flex-md-row justify-content-between">
    <form class="form-inline" method="get" action="{{route('knowledgecategories.index')}}">
          <div class="input-group mw-30">
              <input value="{{$search}}" name="title" type="text" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="button-addon2">
              <button class="btn btn-primary" type="sybmit" id="button-addon2">Search</button>
          </div>
    </form>
      <a href="{{route('knowledgecategories.create', $kw_category_id)}}" type="submit" class="btn btn-primary w-md">Add Knowledge Category</a>
  </div>
  <div class="tab-content mt-3 text-muted">
      <div class="tab-pane active" id="home1" role="tabpanel">

<div class="table-responsive">
  <table class="table table-condensed">
    <thead>
      <tr>
        <th>Title</th>
        <th>Rank</th>
        <th>Short Desc.</th>
        <th>Status</th>
        @if($permission['create'] || $permission['edit'] || $permission['destroy'])
        <th width="25%" style="text-align: right;">Action</th>
        @endif
      </tr>
    </thead>
    <tbody>
          @foreach ($data as $value)
          <tr>
              <td>{{ $value->title }}</td>
              <td>{{ $value->rank }}</td>
              <td>{{ $value->short_desc }}</td>
              <td><span class="badge badge-pill badge-soft-{{ $value->statusColorCodes[$value->status] }} font-size-12">{!! $value->statusList[$value->status] !!}</span></td>
              @if($permission['create'] || $permission['edit'] || $permission['destroy'])
              <td class="text-right">
                <!-- @if(!$value->kw_category_id)
                  @if($permission['create'])
                  <a href="{{ route($routePrefix . '.create', $value->id) }}" class="btn btn-outline-light waves-effect" data-toggle="tooltip" title="" data-original-title="Add New Record">{!! \Config::get('settings.icon_add') !!}</a>
                  @endif
                  <a href="{{ route($routePrefix . '.index', $value->id) }}" class="btn btn-outline-light waves-effect" data-toggle="tooltip" title="" data-original-title="View Sub Category Services"><i class="fa fa-fw fa-bars"></i></a>
                @endif -->
                @if($permission['edit'])
                <a href="{{ route($routePrefix . '.edit', [$value->kw_category_id, $value->id]) }}" class="btn btn-outline-light waves-effect" data-toggle="tooltip" title="" data-original-title="Edit">{!! \Config::get('settings.icon_edit') !!}</a>
                @endif
                @if($permission['destroy'])
               <a class="btn btn-sm btn-rounded btn-danger waves-effect waves-light" data-toggle="tooltip" title="" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="event.preventDefault();
                  document.getElementById('delete-form-{{$value->id}}').submit();" data-original-title="Delete">{!! \Config::get('settings.icon_delete') !!}</a>
                {!! Form::open([
                  'method' => 'DELETE',
                  'route' => [
                    $routePrefix . '.destroy', [
                      $value->kw_category_id,
                      $value->id
                    ]
                    ],
                  'id' => 'delete-form-' . $value->id
                ]) !!}
                {!! Form::close() !!}
                @endif
              </td>
              @endif
          </tr>
          @endforeach
    </tbody>
  </table>
</div>
<div style="margin-top: 15px;">
  {{$data->links()}}                
</div> 
 </div>
                                    </div>
                                    </div>


@endsection