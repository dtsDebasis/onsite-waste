@php($frequency_types = ['1'=>'Days','2'=>'Weeks','3'=>'Months','4'=>'Years'])
@php($yes_no_arr = ['0' => 'No','1' => 'Yes'])
@php($service_types = ['TE-5000' => 'TE-5000','Pick-up' => 'Pick-up', 'Hybrid' => 'Hybrid'])
<div class="card">
    <div class="card-body mb-4">
        <div class="table-responsive">
            <table class="table table-centered table-nowrap mb-0">
                <thead class="thead-light">
                    <tr>
                        <th>Package Name</th>
                        <th>Service Type</th>
                        <th>Monthly Rate</th>
                        <th>Compliance</th>
                        <th>Frequency</th>
                        <th>Number Of Boxes Included</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($packages as $pval )
                    <tr>
                        <td>{{ $pval->name }} </td>
                        <td>{{ isset($service_types[$pval->service_type]) ? $service_types[$pval->service_type] : 'NA' }}
                        <td><input type="number" name="package_price" class="form-control col-md-5"
                                id="package_price_{{$pval->id}}" value="{{ $pval->monthly_rate }}"></td>
                        <td>{{ (array_key_exists($pval->compliance,$yes_no_arr)) ?$yes_no_arr[$pval->compliance]: '' }}
                        </td>
                        <td>{{ ($pval->frequency_number)?$pval->frequency_number.'/'.$frequency_types[$pval->frequency_type]:'' }}
                        </td>
                        <td>{{ $pval->boxes_included }}</td>
                        <td>
                            <button type="button"
                                class="btn btn-outline-primary waves-effect waves-light clone-package {{(!empty($input['branch_id']) && ($pval->branch_id == $input['branch_id']))?'branch-package-assigned':''}}"
                                data-purpose="{{$input['purpose']}}" packageid="{{ $pval->id }}"
                                data-name="{{$pval->name}}" data-monthly_rate="{{$pval->monthly_rate}}"
                                data-frquency_number="{{($pval->frequency_number)?$pval->frequency_number.'/'.$frequency_types[$pval->frequency_type]:'' }}"
                                data-box_included="{{ $pval->boxes_included }}">{!! (!empty($input['branch_id']) &&
                                ($pval->service_type == $branchPackage->service_type))?'<i class="fa fa-check"></i>
                                Assigned':'Assign' !!}</button>
                            @if(!empty($input['branch_id']) && $pval->branch_id == $input['branch_id']))
                            <a href="javascript:;" data-id="{{$pval->id}}" data-branch_id="{{$input['branch_id']}}"
                                data-company_id="{{$input['company_id']}}"
                                class="btn btn-primary btn-sm btn-rounded waves-effect waves-light edit-branch-package"><i
                                    class="bx bx-edit-alt"></i></a>
                            <a href="javascript:;" data-id="{{$pval->id}}"
                                class="btn btn-danger btn-sm btn-rounded waves-effect waves-light delete-branch-package"><i
                                    class="bx bx-trash-alt"></i></a>

                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer text-right">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        @if($input['purpose'] =="form")
        <a href="javascript:;" class="btn btn-success" data-purpose="{{$input['purpose']}}"
            data-branch_id="{{$input['branch_id']}}" data-company_id="{{$input['company_id']}}"
            id="cloned_submit">Submit</a>
        @endif
    </div>
</div>
