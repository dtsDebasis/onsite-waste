@extends('admin.layouts.layout')


@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="tab-content mt-3 text-muted">
                    <div class="tab-pane active" id="home1" role="tabpanel">
                        <div class="d-flex flex-column flex-md-row justify-content-between">
                            <div class="col-md-12">
                                @if (can('Customer Search'))
                                {!! Form::open(['method' => 'GET','route' => ['analytics.companydata', $company_id,
                                $category_id],'id' => 'srch-form','class'
                                =>
                                'form-controll']) !!}
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <input type="month" class="form-control" name="start_date"
                                                value="{{Request::get('start_date')?Request::get('start_date'):$start_date}}"
                                                placeholder="Search by ID" aria-label="Search">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <input type="month" class="form-control" name="end_date"
                                                value="{{Request::get('end_date')?Request::get('end_date'):$end_date}}"
                                                placeholder="Search by name" aria-label="Search">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <button class="btn btn-primary" type="submit" id="button-addon2">Search</button>
                                        <a class="btn btn-danger"
                                            href="{{route('analytics.companydata',[$company_id, $category_id])}}">Reset</a>
                                    </div>
                                </div>
                                {!! Form::close() !!}
                                @endif

                                <!-- <input type="text" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="button-addon2">
                        <button class="btn btn-primary" type="button" id="button-addon2">Search</button> -->
                            </div>
                        </div>
                        @forelse ($groups as $group)
                        <div class="table-responsive">
                            <table class="table table-centered table-condensed table-striped table-nowrap mb-4">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Group Name </th>
                                        <th>Location</th>
                                        <th>Normalization Type </th>
                                        <th>Trips</th>
                                        <th>Boxes</th>
                                        <th>Weight</th>
                                        <th>Spend</th>
                                        <th>SH Cycles</th>
                                        <th>RB Cycles</th>
                                        <th>Details</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{$group->name}}</td>
                                        <td>{{$group->grouplocationmap ? $group->grouplocationmap->count() : 0}}</td>
                                        <td>{{$group->normalization_details->name}}</td>
                                        <td>{{$group->analyticsCount($group->grouplocationmap,'trips',$start_date_filter,$end_date_filter)}}
                                        </td>
                                        <td>{{$group->analyticsCount($group->grouplocationmap,'boxes',$start_date_filter,$end_date_filter)}}
                                        </td>
                                        <td>{{$group->analyticsCount($group->grouplocationmap,'weight',$start_date_filter,$end_date_filter)}}
                                        </td>
                                        <td>{{$group->analyticsCount($group->grouplocationmap,'spend',$start_date_filter,$end_date_filter)}}
                                        </td>
                                        <td>{{$group->analyticsCount($group->grouplocationmap,'sb_cycles',$start_date_filter,$end_date_filter)}}
                                        </td>
                                        <td>{{$group->analyticsCount($group->grouplocationmap,'rb_cycles',$start_date_filter,$end_date_filter)}}
                                        </td>

                                        <td>
                                            @if ($group_id && $group_id == $group->id)
                                            <a href="{{route('analytics.companydata',[$company_id, $category_id,0,$start_date,$end_date])}}" class="btn"><i class="fa {{$group_id && $group_id == $group->id ? 'fa-arrow-down' : 'fa-arrow-right'}}"></i></a>

                                            @else
                                            <a href="{{route('analytics.companydata',[$company_id, $category_id,$group->id,$start_date,$end_date])}}" class="btn"><i class="fa {{$group_id && $group_id == $group->id ? 'fa-arrow-down' : 'fa-arrow-right'}}"></i></a>

                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            @if ($group_id && $group_id == $group->id)
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Location</th>
                                        <th scope="col">Trips</th>
                                        <th scope="col">Boxes</th>
                                        <th scope="col">Weight</th>
                                        <th scope="col">Spend</th>
                                        <th scope="col">SH Cycles</th>
                                        <th scope="col">RB Cycles</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($group->analyticsData($group->grouplocationmap,$start_date_filter,$end_date_filter) as $key=>$data)
                                    <tr>
                                        <th scope="row">{{$key + 1}}</th>
                                        <td>{{date('M,Y', strtotime(date($data->date)))}}</td>
                                        <td>{{$data->branch->name}}</td>
                                        <td>{{$data->trips}}</td>
                                        <td>{{$data->boxes}}</td>
                                        <td>{{$data->weight}}</td>
                                        <td>{{$data->spend}}</td>
                                        <td>{{$data->sb_cycles}}</td>
                                        <td>{{$data->rb_cycles}}</td>
                                    </tr>
                                    @empty
                                        <div class="alert alert-primary" role="alert">
                                            No Data
                                        </div>
                                    @endforelse
                                </tbody>
                            </table>
                            @endif
                        </div>
                        @empty
                        <div class="alert alert-primary" role="alert">
                            No Data
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@push('pagejs')
<script>

</script>
@endpush
