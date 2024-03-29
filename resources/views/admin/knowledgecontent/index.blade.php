
@extends('admin.layouts.layout')


@section('content')
<div class="card-body card mb-4">
    <div class="d-flex flex-column flex-md-row justify-content-between">
    @if (can('Knowledge Content Search'))
        <form class="form-inline checkediting" method="get" action="{{route('knowledgecontent.index')}}">
        <div class="input-group mw-30">
            <input value="{{$search}}" name="title" type="text" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="button-addon2">
            <button class="btn btn-primary" type="sybmit" id="button-addon2">Search</button>
        </div>
    </form>
    @endif

    @if (can('Knowledge Content Add'))
        <a href="{{route('knowledgecontent.create',$routeParams)}}" class="btn btn-primary w-md">Add New</a>

    @endif
    </div>

    <div class="tab-content mt-3 text-muted">
        <div class="tab-pane active" id="home1" role="tabpanel">
            <div class="table-responsive">
                @if (can('Knowledge Content List'))
                <table class="table table-centered table-nowrap mb-0">
                    <thead class="thead-light">
                    <tr>
                      <th>Title {!! \App\Helpers\Helper::sort($routePrefix . '.index', 'title', $orderBy, $routeParams) !!}</th>
                      <th>Category {!! \App\Helpers\Helper::sort($routePrefix . '.index', 'c__title', $orderBy, $routeParams) !!}</th>
                      <th>Rank {!! \App\Helpers\Helper::sort($routePrefix . '.index', 'rank', $orderBy, $routeParams) !!}</th>
                      <th>Type {!! \App\Helpers\Helper::sort($routePrefix . '.index', 'type', $orderBy, $routeParams) !!}</th>
                      <th>Status {!! \App\Helpers\Helper::sort($routePrefix . '.index', 'status', $orderBy, $routeParams) !!}</th>
                      @if(can('Knowledge Content Edit') || can('Knowledge Content Delete'))
                      <th width="15%" style="text-align: right;">Action</th>
                      @endif
                      </tr>
                    </thead>
                    <tbody>
                    @if(count($data) != 0)
                      @foreach ($data as $key => $val)
                      @php($routeParams = [
                            'id' => $val->id
                          ])

                      <tr>
                        <td>{{ $val->title }}</td>
                        <td>{{ $val->category_name }}</td>
                        <td>{{ $val->rank }}</td>
                        <td>{{ $val->type }}</td>
                        <td><span class="badge badge-pill badge-soft-{{ $val->statuses[$val->status]['badge'] }} font-size-12">{!! $val->statuses[$val->status]['name'] !!}</span></td>
                        @if(can('Knowledge Content Edit') || can('Knowledge Content Delete'))
                        <td class="text-right">
                          @if(can('Knowledge Content Edit'))
                          <a href="{{ route($routePrefix . '.edit', $routeParams) }}" class="btn btn-primary btn-sm btn-rounded waves-effect waves-light" data-toggle="tooltip" title="" data-original-title="Edit">{!! \Config::get('settings.icon_edit') !!}</a>
                          @endif
                          @if(can('Knowledge Content Delete'))
                        <a class="btn btn-danger btn-sm btn-rounded waves-effect waves-light" data-toggle="tooltip" title="" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="event.preventDefault();
                            document.getElementById('delete-form-{{$val->id}}').submit();" data-original-title="Delete">{!! \Config::get('settings.icon_delete') !!}</a>
                          {!! Form::open([
                            'method' => 'DELETE',
                            'route' => [
                              $routePrefix . '.destroy',
                              $routeParams
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
                @endif

            </div>
            <div style="margin-top: 15px;">
              {{$data->links()}}
            </div>
        </div>
    </div>
</div>
@include('admin.components.pagination')


@endsection

