
@extends('admin.layouts.layout')


@section('content')
<div class="card-body card mb-4">
    <div class="d-flex flex-column flex-md-row justify-content-between">
        <div class="input-group mw-30">
          @if (can('Site Content Search'))
              <form class="form-inline">
            <input name="search" type="text" value="{{Request::get('search')?Request::get('search'):null}}" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="button-addon2">
            <button class="btn btn-primary" type="submit" id="button-addon2">Search</button>
          </form>
          @endif

        </div>
        @if (can('Site Content Add'))
        <a href="{{route('contents.create')}}" class="btn btn-primary w-md">Add New</a>

        @endif
    </div>

    <div class="tab-content mt-3 text-muted">
        @if (can('Site Content List'))
            <div class="tab-pane active" id="home1" role="tabpanel">
            <div class="table-responsive">
                <table class="table table-centered table-nowrap mb-0">
                    <thead class="thead-light">
                    <tr>
                    <th width="15%">Title {!! \App\Helpers\Helper::sort($routePrefix . '.index', 'title', $orderBy) !!}</th>
                    <th>Slug</th>
                    <th>Short Description</th>
                    @if(can('Site Content Edit') || can('Site Content Delete'))
                    <th width="15%" style="text-align: right;">Action</th>
                    @endif
                    </tr>
                    </thead>
                    <tbody>
                @if(count($data) != 0)
                  @foreach ($data as $key => $val)
                  <tr>
                    <td>{{ $val->title }}</td>
                    <td>{{ $val->slug }}</td>
                    <td>{{ (strlen($val->short_description) >80)?substr($val->short_description,0,80).'...': $val->short_description  }}</td>
                    @if(can('Site Content Edit') || can('Site Content Delete'))
                    <td class="text-right">
                      @if(can('Site Content Edit'))
                      <a href="{{ route($routePrefix . '.edit',$val->id) }}" class="btn btn-primary btn-sm btn-rounded waves-effect waves-light" data-toggle="tooltip" title="" data-original-title="Edit">{!! \Config::get('settings.icon_edit') !!}</a>
                      @endif
                      @if(can('Site Content Delete'))
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
        @endif

    </div>
</div>
@include('admin.components.pagination')


@endsection

