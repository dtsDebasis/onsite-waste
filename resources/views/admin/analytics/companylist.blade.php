@extends('admin.layouts.layout')


@section('content')
<style>
.tag_font_size {
    font-size: inherit;
}
</style>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-column flex-md-row justify-content-between">
                    <div class="col-md-12">
                        @if (can('Customer Search'))
                        {!! Form::open(['method' => 'GET','route' => 'analytics.companylist','id' => 'srch-form','class'
                        =>
                        'form-controll']) !!}
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="company_number"
                                        value="{{Request::get('company_number')?Request::get('company_number'):null}}"
                                        placeholder="Search by ID" aria-label="Search">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="company_name"
                                        value="{{Request::get('company_name')?Request::get('company_name'):null}}"
                                        placeholder="Search by name" aria-label="Search">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="address"
                                        value="{{Request::get('address')?Request::get('address'):null}}"
                                        placeholder="Search by address" aria-label="Search">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <button class="btn btn-primary" type="submit" id="button-addon2">Search</button>
                                <a class="btn btn-danger" href="{{route('analytics.companylist')}}">Reset</a>
                            </div>
                        </div>
                        {!! Form::close() !!}
                        @endif

                        <!-- <input type="text" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="button-addon2">
                        <button class="btn btn-primary" type="button" id="button-addon2">Search</button> -->
                    </div>
                </div>
                <div class="tab-content mt-3 text-muted">
                    <div class="tab-pane active" id="home1" role="tabpanel">
                        <div class="table-responsive">
                            @if (can('Customer List'))
                            <table class="table table-centered table-condensed table-striped table-nowrap mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Company ID</th>
                                        <th>Company Name / Address</th> 
                                        <th>Categories</th>
                                        <th>Specialty</th>
                                        {{-- <th>Groups</th>
                                        <th>Locations</th>
                                        <th>Action</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($companies) && count($companies)>0)
                                    @foreach($companies as $company)
                                    <tr class="">
                                        <td> {{$company->company_number}} </td>
                                        <td> <a target="_blank"
                                                href="{{ route('customers.branches', ['id' => $company->id  ] ) }}">{{$company->company_name ?? '' }}</a>
                                            <br>
                                            {{(isset($company->addressdata->addressline1) && $company->addressdata->addressline1)?$company->addressdata->addressline1:'NA'}}
                                        </td>
                                        
                                        <td class="text-justify">
                                          @foreach ($company->categories as $category)
                                          <a class="badge badge-primary tag_font_size" href="{{ route('analytics.companydata', ['company_id' => $company->id,'category_id' => $category->id  ] ) }}">{{$category->name}}</a>

                                          @endforeach

                                        </td>
                                        @php($comSpecialt = [])
                                        @if(isset($company->speciality) && count($company->speciality))
                                        @foreach($company->speciality as $sp)
                                        @if(isset($sp->speciality->name) && ($sp->speciality->name))
                                        @php($comSpecialt[] = $sp->speciality->name)
                                        @endif
                                        @endforeach
                                        @endif
                                       
                                        <td>{{($comSpecialt)?implode(',',$comSpecialt):'NA'}}</td>

                                        {{-- <td>5</td>
                                        <td>10</td>
                                        <td>
                                            <a class="btn btn-primary btn-sm btn-rounded waves-effect waves-light"
                                                href="{{ route('analytics.companydata', ['company_id' => $company->id  ] ) }}">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </td> --}}
                                    </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
                            @endif

                        </div>
                    </div>
                </div>
                <div class="mt-2">
                    {{$companies->links()}}
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
