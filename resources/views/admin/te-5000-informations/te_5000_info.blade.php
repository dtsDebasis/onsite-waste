<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-centered table-nowrap mb-0" id="">
                <thead class="thead-light">
                    <tr>
                        @php($last_information = json_decode($data->te_5000_info))
                        @foreach($last_information as $lkey => $lval)
                            <th>{{ucwords(str_replace('_',' ',$lkey), " \t\r\n\f\v'")}}</th>
                        @endforeach
                        
                    </tr>
                </thead>
                <tbody>
                    @if($data)
                    @php($last_information = json_decode($data->te_5000_info))
                    <tr class="">
                        @foreach($last_information as $lkey => $lval)
                            <td>{{$lval}}</td>
                        @endforeach                        
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