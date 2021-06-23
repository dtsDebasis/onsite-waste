<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-centered table-nowrap mb-0" id="">
                <thead class="thead-light">
                    <tr>
                        @php($last_information = json_decode($data->last_run_information))
                        @foreach($last_information as $lkey => $lval)
                            <th>{{ucwords(str_replace('_',' ',$lkey), " \t\r\n\f\v'")}}</th>
                        @endforeach
                       
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if($data)
                    @php($last_information = json_decode($data->last_run_information))
                    <tr class="">
                        @foreach($last_information as $lkey => $lval)
                            <td>{{$lval}}</td>
                        @endforeach
                        <td>                                
                            <a href="javascript:;" data-toggle="tooltip" data-info_id="{{$data->id}}" data-branch_id="{{$data->branch_id}}" data-placement="top" title="" data-original-title="View" type="button" class="btn btn-primary btn-sm btn-rounded waves-effect waves-light cycling_details_status_change">
                                Ping
                            </a>
                        </td>
                    </tr>
                    @else
                        <tr class="">
                            <td colspan="25">Record not found</td>
                        </tr>
                    @endif
                    
                </tbody>
            </table>
        </div>
        <div id="ping_data_body">
        </div>
    </div>
</div>