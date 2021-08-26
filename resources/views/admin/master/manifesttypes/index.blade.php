
@extends('admin.layouts.layout')


@section('content')
<div class="card-body card mb-4">
    <div class="d-flex flex-column flex-md-row justify-content-between">
    {{-- @if (can('Manifest Types Search')) --}}

        <form class="form-inline" method="get" action="{{route('master.manifesttypes.index')}}">
        <div class="input-group mw-30">
            <input value="{{$search}}" name="name" type="text" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="button-addon2">
            <button class="btn btn-primary" type="sybmit" id="button-addon2">Search</button>
        </div>
    </form>
    {{--   @endif --}}

    {{-- @if (can('Manifest Types Add'))--}}
        <a href="{{route('master.manifesttypes.create')}}" class="btn btn-primary w-md">Add New</a>

        {{-- @endif--}}
    </div>

    <div class="tab-content mt-3 text-muted">
    {{--   @if (can('Manifest Type List'))--}}
        <div class="tab-pane active" id="home1" role="tabpanel">
            <div class="table-responsive">
                <table class="table table-centered table-nowrap mb-0">
                    <thead class="thead-light">
                    <tr>
                      <th>Name {!! \App\Helpers\Helper::sort($routePrefix . '.index', 'name', $orderBy) !!}</th>
                      <th>Type</th>
                      <th>Status</th>
                     
                      {{--    @if(can('Manifest Type Edit') || can('Manifest Type Delete')) --}}
                      <th width="15%" style="text-align: right;">Action</th>
                      {{--  @endif --}}
                      </tr>
                    </thead>
                    <tbody>
                      @if(count($data) != 0)
                        @foreach ($data as $key => $val)
                        <tr>
                          <td>{{ $val->name }}</td>
                          <td>  @if($val->type==1)
                                  Parent
                                @else 
                                  SubType
                                @endif
                          </td>
                          <td><span class="badge badge-pill badge-soft-{{ $val->statuses[$val->status]['badge'] }} font-size-12">{!! $val->statuses[$val->status]['name'] !!}</span></td>
                          {{--  @if(can('Manifest Type Edit') || can('Manifest Type Delete')) --}}
                          <td class="text-right">
                          {{--    @if(can('Manifest Type Edit')) --}}
                            <a href="{{ route($routePrefix . '.edit',$val->id) }}" class="btn btn-primary btn-sm btn-rounded waves-effect waves-light" data-toggle="tooltip" title="" data-original-title="Edit">{!! \Config::get('settings.icon_edit') !!}</a>
                            {{--  @endif --}}
                            {{--   @if(can('Manifest Type Delete')) --}}
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
                            {{--    @endif --}}
                          </td>
                          {{--  @endif  --}}
                        </tr>
                        @endforeach
                      @else
                      <tr><td colspan="4" align="center"><div class="alert alert-danger">No Data</div></td></tr>
                      @endif
                    </tbody>
                </table>
            </div>
            <div style="margin-top: 15px;">
            {{$data->links()}}
            </div>
        </div>
        {{--   @endif --}}

    </div>

</div>
@include('admin.components.pagination')


@endsection

