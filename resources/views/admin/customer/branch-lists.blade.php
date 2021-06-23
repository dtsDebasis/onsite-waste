
    @if($id)
    <div class="card-body card">
        <div class=" row"> 
                <div class="col-md-3">
                    <Strong> Company ID </strong>: @if(isset($company) && isset($company->company_number) && $company->company_number) {{$company->company_number}}  @else NA @endif
                </div>
                <div class="col-md-4">
                    <strong> Company Name </strong>:@if(isset($company) && isset($company->company_name) && $company->company_name) {{$company->company_name}} @else NA @endif
                </div>   
                <div class="col-md-3">
                    <strong> Phone </strong>:@if(isset($company) && isset($company->phone) && $company->phone) {{$company->phone}} @else NA @endif
                </div>             
                
                @if(isset($company) && isset($company->id) && $company->id)
                <div class="col-md-2">
                    <a class="btn btn-primary btn-sm btn-rounded waves-effect waves-light" href="{{ route('customers.create', ['id' => $company->id  ] ) }}" >Edit Company</a>
                </div>
                @endif
        </div>
    </div>
    @endif    
    
    <div class="card card-body">
        <div class="table-responsive">
            <table class="table table-centered table-nowrap mb-0">
                <thead class="thead-light">
                    <tr>
                        <th>Location Code</th>
                        <th>Name</th>
                        <th>Start Date</th>
                        <th>OnSite Partner</th>
                        <th>Phone No.</th>
                        <th>Address</th>
                        <th>Specialty</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($companybranch_list))
                        @foreach($companybranch_list as $companybranch )
                            <tr>
                                <td>{{ $companybranch->uniq_id }}</td>
                                <td><a href="{{route('customers.branche-info-details',[$id,$companybranch->id])}}">{{ $companybranch->name }}</a></td>
                                <td>{{($companybranch->created_at)?\App\Helpers\Helper::showdate($companybranch->created_at):'NA'}}</td>
                               
                                @php($onsitePartners = \App\Helpers\Helper::getOnsitePartners($companybranch->company_id,$companybranch->id))
                                <td> {{($onsitePartners)?implode(',',$onsitePartners):'NA'}}</td>
                                <td>{{($companybranch->phone)?$companybranch->phone:'NA'}}</td>
                                <td>{{ ($companybranch->addressdata)?$companybranch->addressdata->addressline1:'NA' }}</td>
                                
                                @php($comSpecialt = [])
                                @if(isset($companybranch->branchspecialty) && count($companybranch->branchspecialty))
                                    @foreach($companybranch->branchspecialty as $sp)
                                        @if(isset($sp->speciality_details->name) && ($sp->speciality_details->name))
                                            @php($comSpecialt[] = $sp->speciality_details->name)
                                        @endif
                                    @endforeach
                                @endif
                                <td>{{($comSpecialt)?implode(',',$comSpecialt):'NA'}}</td>
                                <td><a href="{{route('customers.branche-info-details',[$id,$companybranch->id])}}" data-toggle="tooltip" data-placement="top" title="" data-original-title="Details"><i class="fa fa-eye"></i></td>
                            </tr>
                        @endforeach
                    @else
                    <tr>
                        <td colspan="25">No record found</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>    

@include('admin.customer.create.tab4.modal-content')
<input type="hidden" id="page_type" value="view_details">
@push('pagejs')
    @include('admin.customer.create.tab4.modal-script')
@endpush