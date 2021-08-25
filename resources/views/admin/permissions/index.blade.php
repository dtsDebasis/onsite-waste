
@extends('admin.layouts.layout')


@section('content')
<div class="card-body card mb-4">
<form class="checkediting" method="get" action="{{route('permissions.index')}}">
    <div class="d-flex flex-column flex-md-row justify-content-between">
        <div class="input-group mw-30">
            <input type="text" name="search" value="{{$search}}" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="button-addon2">
            <button class="btn btn-primary" type="submit" id="button-addon2">Search</button>
        </div>

        <a href="{{route('permissions.create')}}" class="btn btn-primary w-md">Add New</a>
    </div>
</form>
    <div class="tab-content mt-3 text-muted">
        <div class="tab-pane active" id="home1" role="tabpanel">
            <div class="table-responsive">
                <table class="table table-centered table-nowrap mb-0">
                    <thead class="thead-light">
                    <tr>
                      <th>Permission types {!! \App\Helpers\Helper::sort($routePrefix . '.index', 'permissions__p_type', $orderBy) !!}</th>
                      <th>Module {!! \App\Helpers\Helper::sort($routePrefix . '.index', 'permissions__class', $orderBy) !!}</th>
                      <th>Function {!! \App\Helpers\Helper::sort($routePrefix . '.index', 'permissions__method', $orderBy) !!}</th>
                      @if($permission['edit'] || $permission['destroy'])
                      <th width="15%" style="text-align: right;">Action</th>
                      @endif
                    </tr>
                    </thead>
                    <tbody>
                  @foreach ($data as $value)
                  <tr>
                      <td>{{ $value->p_type }}</td>
                      <td>{{ $value->class }}</td>
                      <td>{{ $value->method }}</td>
                      @if($permission['edit'] || $permission['destroy'])
                      <td class="text-right">
                        @if($permission['edit'])
                        <a href="{{ route($routePrefix . '.edit', $value->id, $srch_params) }}" class="btn btn-primary btn-sm btn-rounded waves-effect waves-light" data-toggle="tooltip" title="" data-original-title="Edit">{!! \Config::get('settings.icon_edit') !!}</a>
                        @endif
                        @if($permission['destroy'])
                      <a class="btn btn-danger btn-sm btn-rounded waves-effect waves-light" data-toggle="tooltip" title="" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="event.preventDefault();
                          document.getElementById('delete-form-{{$value->id}}').submit();" data-original-title="Delete">{!! \Config::get('settings.icon_delete') !!}</a>
                        {!! Form::open([
                          'method' => 'DELETE',
                          'route' => [
                            $routePrefix . '.destroy',
                            $value->id
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
@include('admin.components.pagination')


@endsection

