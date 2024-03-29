
@extends('admin.layouts.layout')


@section('content')
<div class="card-body card mb-4">
    <div class="d-flex flex-column flex-md-row justify-content-between">
        <div class="input-group mw-30">
            <input type="text" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="button-addon2">
            <button class="btn btn-primary" type="button" id="button-addon2">Search</button>
        </div>
        
        <a href="{{route('location.countries.create')}}" class="btn btn-primary w-md">Add New</a>
    </div>

    <div class="tab-content mt-3 text-muted">
        <div class="tab-pane active" id="home1" role="tabpanel">
            <div class="table-responsive">
                <table class="table table-centered table-nowrap mb-0">
                    <thead class="thead-light">
                    <tr>
                      <th>Name {!! \App\Helpers\Helper::sort($routePrefix . '.index', 'country_name', $orderBy) !!}</th>
                      <th>Code {!! \App\Helpers\Helper::sort($routePrefix . '.index', 'country_code', $orderBy) !!}</th>
                      <th>Phone Code {!! \App\Helpers\Helper::sort($routePrefix . '.index', 'phone_code', $orderBy) !!}</th>
                      <th>Status {!! \App\Helpers\Helper::sort($routePrefix . '.index', 'status', $orderBy) !!}</th>
                      @if($permission['edit'] || $permission['destroy'] || $permisssionState['index'])
                      <th width="15%" style="text-align: right;">Action</th>
                      @endif
                      </tr>
                    </thead>
                    <tbody>
                      @if(count($data) != 0)
                        @foreach ($data as $key => $val)
                        <tr>
                          <td>{{ $val->country_name }}</td>
                          <td>{{ $val->country_code }}</td>
                          <td>{{ $val->phone_code }}</td>
                          <td><span class="badge badge-pill badge-soft-{{ $val->statuses[$val->status]['badge'] }} font-size-12">{!! $val->statuses[$val->status]['name'] !!}</span></td>
                          @if($permission['edit'] || $permission['destroy'] || $permisssionState['index'])
                          <td class="text-right">
                            @if($permisssionState['index'])
                            <a href="{{ route($routePrefix . '.states.index', $val->id) }}" class="btn btn-primary btn-sm btn-rounded waves-effect waves-light" data-toggle="tooltip" title="View States" data-original-title="View States"><i class="bx bx-list-ul"></i></a>
                            @endif
                            @if($permission['edit'])
                            <a href="{{ route($routePrefix . '.edit',$val->id) }}" class="btn btn-primary btn-sm btn-rounded waves-effect waves-light" data-toggle="tooltip" title="" data-original-title="Edit">{!! \Config::get('settings.icon_edit') !!}</a>
                            @endif
                            @if($permission['destroy'])
                          <a class="btn btn-danger btn-sm btn-rounded waves-effect waves-light" data-toggle="tooltip" title="" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="event.preventDefault();
                              document.getElementById('delete-form-{{$val->id}}').submit();" data-original-title="Delete">{!! \Config::get('settings.icon_delete') !!}</a>
                            {!! Form::open([
                              'method' => 'DELETE',
                              'route' => [
                                $routePrefix . '.destroy',
                                $val->id
                                ],
                              'id' => 'delete-form-'.$val->id
                            ]) !!}
                            {!! Form::close() !!}
                            @endif
                          </td>
                          @endif
                        </tr>
                        @endforeach
                      @else
                      <tr><td colspan="25"><div class="alert alert-danger">No Data</div></td></tr>
                      @endif
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
@include('admin.components.pagination')


@endsection

