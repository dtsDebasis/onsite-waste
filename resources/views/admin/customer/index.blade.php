@extends('admin.layouts.layout')


@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-column flex-md-row justify-content-between">
                    <div class="col-md-8">
                        @if (can('Customer Search'))
                                                    {!! Form::open(['method' => 'GET','route' => 'customers.index','id' => 'srch-form','class' =>
                        'form-controll']) !!}
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="company_number"
                                        value="{{Request::get('company_number')?Request::get('company_number'):null}}"
                                        placeholder="Search by ID" aria-label="Search">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="company_name"
                                        value="{{Request::get('company_name')?Request::get('company_name'):null}}"
                                        placeholder="Search by name" aria-label="Search">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="address"
                                        value="{{Request::get('address')?Request::get('address'):null}}"
                                        placeholder="Search by address" aria-label="Search">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <button class="btn btn-primary" type="submit" id="button-addon2">Search</button>
                                <a class="btn btn-danger" href="{{route('customers.index')}}">Reset</a>
                            </div>
                        </div>
                        {!! Form::close() !!}
                        @endif

                        <!-- <input type="text" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="button-addon2">
                        <button class="btn btn-primary" type="button" id="button-addon2">Search</button> -->
                    </div>
                    <div class="col-md-4">
                        @if (can('Customer Add'))
                                                    <a href="{{route('customers.create', ['id'=> 0 ] )}}" type="submit"
                            class="btn btn-primary w-md">Add Customer</a>
                        @endif
                        @if (can('Customer Import'))
                        <a href="javascript:;" class="btn btn-info w-md" id="import_customer">Import</a>

                        @endif
                    </div>
                </div>
                <div class="tab-content mt-3 text-muted">
                    <div class="tab-pane active" id="home1" role="tabpanel">
                        <div class="table-responsive">
                            @if (can('Customer List'))
                                <table class="table table-centered table-nowrap mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Company ID</th>
                                        <th>Company Name</th>
                                        <th>Start Date</th>
                                        <th>OnSite Partner</th>
                                        <th>Phone No.</th>
                                        <th>Address</th>
                                        <th>Specialty</th>
                                        <th>Service Type</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($companies) && count($companies)>0)
                                    @php($company_owners = \App\Helpers\Helper::getCompanyOwners())
                                    @foreach($companies as $company)
                                    <tr class="">
                                        <td> {{$company->company_number}} </td>
                                        <td> <a
                                                href="{{ route('customers.branches', ['id' => $company->id  ] ) }}">{{$company->company_name ?? '' }}</a>
                                        </td>
                                        <td>{{($company->created_at)?\App\Helpers\Helper::showdate($company->created_at):'NA'}}
                                        </td>
                                        @php($onsitePartners = \App\Helpers\Helper::getOnsitePartners($company->id))
                                        <td> {{($onsitePartners)?implode(',',$onsitePartners):'NA'}}</td>
                                        <td><a href="tel:{{$company->phone}}">{{$company->phone}}</a></td>
                                        <td> {{(isset($company->addressdata->addressline1) && $company->addressdata->addressline1)?$company->addressdata->addressline1:'NA'}}
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
                                        <td>NA</td>
                                        <td>
                                            @if (can('Customer Edit'))
                                                <a class="btn btn-primary btn-sm btn-rounded waves-effect waves-light"
                                                href="{{ route('customers.create', ['id' => $company->id  ] ) }}"
                                                data-toggle="tooltip" data-placement="top" title=""
                                                data-original-title="Edit"><i class="bx bx-edit-alt"></i></a>
                                            @endif

                                            @if (can('Customer Branch List'))
                                                <a class="btn btn-primary btn-sm btn-rounded waves-effect waves-light"
                                                href="{{ route('customers.branches', ['id' => $company->id  ] ) }}"
                                                data-toggle="tooltip" data-placement="top" title=""
                                                data-original-title="Branch Lists"><i class="fa fa-building"></i></a>
                                            @endif

                                            @if (can('Customer Delete'))
                                                <a class="btn btn-outline-danger waves-effect" data-toggle="tooltip"
                                                title=""
                                                data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?"
                                                data-confirm-yes="event.preventDefault();
                                                document.getElementById('delete-form-{{$company->id}}').submit();"
                                                data-original-title="Delete">{!! \Config::get('settings.icon_delete')
                                                !!}</a>
                                            @endif


                                            {!! Form::open([
                                            'method' => 'DELETE',
                                            'route' => [
                                            'customers.destroy',
                                            $company->id
                                            ],
                                            'style'=>'display:inline',
                                            'id' => 'delete-form-' . $company->id
                                            ]) !!}
                                            {!! Form::close() !!}
                                        </td>
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
<div class="modal fade import_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="importModalLabel">Customers Import</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {!! Form::open(['method' => 'POST','route' => ['customers.import-data'],'id' => 'import-form','enctype' =>
            'multipart/form-data']) !!}
            <div class="modal-body" id="line_item_modal_body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Upload CSV *:</label>
                            {!! Form::file('import_file',null,['class'=>'form-control','required' => 'required']) !!}
                        </div>
                    </div>
                </div>

                <p>Please check sample CSV file for format, any other format will not be recognized.</p>

                <a class="btn btn-info" href="{{ url('samples/Company Sample Format.csv') }}">Sample Format</a>

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Submit</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>

            </div>
            {!! Form::close() !!}
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

@endsection
@push('pagejs')
<script>
    $(document).ready(function () {
        $('body').on('click', '#import_customer', function () {
            $('.import_modal').modal('show');
        });

    });
</script>
@endpush
