
@extends('admin.layouts.layout')


@section('content')
<div class="card-body card mb-4">
<form method="get" action="{{route('roles.index')}}">
    <div class="d-flex flex-column flex-md-row justify-content-between">
        <div class="input-group mw-30">
            <input name="search" value="{{$search}}" type="text" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="button-addon2">
            <button class="btn btn-primary" type="submit" id="button-addon2">Search</button>
        </div>

        <a href="{{route('roles.create')}}" class="btn btn-primary w-md">Create Access Level</a>
    </div>
</form>
    <div class="tab-content mt-3 text-muted">
        <div class="tab-pane active" id="home1" role="tabpanel">
            <div class="table-responsive">
                <table class="table table-centered table-nowrap mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th width="85%">Access Level</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if(count($data) >0)
                    @foreach ($data as $key => $value)
                    <tr class="">
                        <td>{{ $value->title }}</td>

                        <td>
                           @if($permission['manageRole'])
                           <a href="{{ route('permissions.manage_role',$value->id) }}">
                           <button type="button" class="btn btn-primary btn-sm btn-rounded waves-effect waves-light">
                                <i class="far fa-eye"></i>
                            </button>
                            </a>
                            @endif
                            @if($permission['edit'] && $value['user_id'] == $userId)
                            <a href="{{ route($routePrefix . '.edit',$value->id) }}">
                              <button type="button" class="btn btn-primary btn-sm btn-rounded waves-effect waves-light">
                                  <i class="bx bx-edit-alt"></i>
                              </button>
                            </a>
                            @endif
                            @if($permission['destroy'] && $value['user_id'] == $userId)
                            <a  data-toggle="tooltip" title="" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="event.preventDefault();
              document.getElementById('delete-form-{{$value->id}}').submit();" data-original-title="Delete">
                              <button type="button" class="btn btn-danger btn-sm btn-rounded waves-effect waves-light">
                                  <i class="bx bx-trash-alt"></i>
                              </button>
                            </a>
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

