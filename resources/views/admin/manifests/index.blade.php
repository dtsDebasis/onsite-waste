
@extends('admin.layouts.layout')

@section('content')
<div class="card-body card mb-4">
    <div class="d-flex flex-column flex-md-row justify-content-between">

        <div>
        <form class="form-inline checkediting" id="csv-preview" type="multipart/form-data" method="post" action="{{route('manifests.processcsv')}}">
            <input value="" name="start-date" type="date" class="form-control" placeholder="Start Date" aria-label="Search" aria-describedby="button-addon2">
            <input onchange="$(this).parent('form').submit()" style="opacity:0;width:1px" type="file" id="manifest-csv" name="manifestdata" accept=".csv">
            <a onclick="$('#manifest-csv').click()" class="btn btn-primary w-md"><i class="fa fa-upload"></i> Upload File</a>
            <button class="btn btn-warning ml-3" onclick="$('#csv-preview').trigger('reset');$('#csv-preview-table').html('')"><i class="fa fa-trash"></i></button>
        </form>
        </div>
    </div>
    <div class="tab-content mt-3 text-muted">
        <div class="tab-pane active" id="home1" role="tabpanel">
            <div class="table-responsive" id="csv-preview-table">

            </div>
        </div>
    </div>
</div>
@push('pagejs')
<script>
    $('#csv-preview').on('submit', function(event){
        event.preventDefault();
        if (!$('#manifest-csv').val()) {
            return;
        }
        $('#cover-spin').show(0);
        $.ajax({
        url:"{{route('manifests.processcsv')}}",
        method:"POST",
        data:new FormData(this),
        dataType:'json',
        contentType:false,
        cache:false,
        processData:false,
            success:function(result)
            {
                var data = result.data;
                var html = '<table class="table table-condensed table-striped table-bordered">';
                if (typeof data.column == 'undefined') {
                    html = '<div align="center"><div class="alert alert-danger alert-dismissable">'+result.message+'</div></div>';
                    $('#cover-spin').hide(0);
                    $('#csv-preview-table').html(html);
                    return;
                }
                if(data.column)
                {
                    html += '<tr>';
                    for(var count = 0; count < data.column.length; count++)
                    {
                        html += '<th>'+data.column[count]+'</th>';
                    }
                    html += '</tr>';
                }

                if(data.row_data)
                {
                    for(var count = 0; count < data.row_data.length; count++)
                    {
                        if (data.row_data[count].locationid) {
                            html += '<tr>';
                        } else {
                            html += '<tr style="background:#ff8a80">';
                        }

                        html += '<td class="locationid" contenteditable>'+data.row_data[count].locationid+'</td>';
                        html += '<td class="internalid" style="background:#eff2f7 !important;">'+data.row_data[count].internalid+'</td>';
                        html += '<td class="date" contenteditable>'+data.row_data[count].date+'</td>';
                        // html += '<td class="deliveryid" contenteditable>'+data.row_data[count].deliveryid+'</td>';
                        // html += '<td class="disposaldate" contenteditable>'+data.row_data[count].disposaldate+'</td>';
                        html += '<td class="driver" contenteditable>'+data.row_data[count].driver+'</td>';
                        html += '<td class="customer_company_id" contenteditable>'+data.row_data[count].customer_company_id+'</td>';
                        // html += '<td class="customer_internal_acc" contenteditable>'+data.row_data[count].customer_internal_acc+'</td>';
                        html += '<td class="customer_name" contenteditable>'+data.row_data[count].customer_name+'</td>';
                        html += '<td class="customer_address" contenteditable>'+data.row_data[count].customer_address+'</td>';
                        html += '<td class="manifest_id" contenteditable>'+data.row_data[count].manifest_id+'</td>';
                        html += '<td class="manifest_note" contenteditable>'+data.row_data[count].manifest_note+'</td>';
                        html += '<td class="schedule_freq" contenteditable>'+data.row_data[count].schedule_freq+'</td>';
                        html += '<td class="no_of_items" contenteditable>'+data.row_data[count].no_of_items+'</td>';
                        html += '<td class="weight_of_items" contenteditable>'+data.row_data[count].weight_of_items+'</td>';
                        html += '<td class="truck_pickup" contenteditable>'+data.row_data[count].truck_pickup+'</td>';
                        html += '<td class="waste_type" contenteditable>'+data.row_data[count].waste_type+'</td>';
                        html += '<td class="waste_subtype" contenteditable>'+data.row_data[count].waste_subtype+'</td>';
                        html += '</tr>';
                    }
                }
                html += '</table>';
                if (data.row_data.length)
                {
                    html += '<div align="center"><button type="button" id="import_data" class="btn btn-success mb-3">Import</button></div>';
                } else {
                    html += '<div align="center"><div class="alert alert-warning alert-dismissable">No data in selected date.</div></div>';
                }
                $('#cover-spin').hide(0);
                $('#csv-preview-table').html(html);
                // $('#upload_csv')[0].reset();
            }
        })
    })

    $(document).on('click', '#import_data', function(){
        var data_json = [];
        var locationid = [];
        var internalid = [];
        var date = [];
        // var deliveryid = [];
        // var disposaldate = [];
        var driver = [];
        var customer_company_id = [];
        // var customer_internal_acc = [];
        var customer_name = [];
        var customer_address = [];
        var manifest_id = [];
        var manifest_note = [];
        var schedule_freq = [];
        var no_of_items = [];
        var weight_of_items = [];
        var truck_pickup = [];
        var waste_type = [];
        var waste_subtype = [];

        $('#cover-spin').show(0);

        $('.locationid').each(function(){
            locationid.push($(this).text());
        });
        $('.internalid').each(function(){
            internalid.push($(this).text());
        });
        $('.date').each(function(){
            date.push($(this).text());
        });
        // $('.deliveryid').each(function(){
        //     deliveryid.push($(this).text());
        // });
        // $('.disposaldate').each(function(){
        //     disposaldate.push($(this).text());
        // });
        $('.driver').each(function(){
            driver.push($(this).text());
        });
        $('.customer_company_id').each(function(){
            customer_company_id.push($(this).text());
        });
        // $('.customer_internal_acc').each(function(){
        //     customer_internal_acc.push($(this).text());
        // });
        $('.customer_name').each(function(){
            customer_name.push($(this).text());
        });
        $('.customer_address').each(function(){
            customer_address.push($(this).text());
        });
        $('.manifest_id').each(function(){
            manifest_id.push($(this).text());
        });
        $('.manifest_note').each(function(){
            manifest_note.push($(this).text());
        });
        $('.schedule_freq').each(function(){
            schedule_freq.push($(this).text());
        });
        $('.no_of_items').each(function(){
            no_of_items.push($(this).text());
        });
        $('.weight_of_items').each(function(){
            weight_of_items.push($(this).text());
        });
        $('.truck_pickup').each(function(){
            truck_pickup.push($(this).text());
        });
        $('.waste_type').each(function(){
            waste_type.push($(this).text());
        });
        $('.waste_subtype').each(function(){
            waste_subtype.push($(this).text());
        });

        $('.locationid').each(function(index ){
            data_json.push(
                {
                    'locationid'  : locationid[index],
                    'internalid'  : internalid[index],
                    'date'  : date[index],
                    // 'deliveryid'  : deliveryid[index],
                    // 'disposaldate'  : disposaldate[index],
                    'driver'  :driver[index],
                    'customer_company_id'  : customer_company_id[index],
                    // 'customer_internal_acc'  : customer_internal_acc[index],
                    'customer_name'  : customer_name[index],
                    'customer_address'  : customer_address[index],
                    'manifest_id'  : manifest_id[index],
                    'manifest_note'  : manifest_note[index],
                    'schedule_freq'  : schedule_freq[index],
                    'no_of_items'  : no_of_items[index],
                    'weight_of_items'  : weight_of_items[index],
                    'truck_pickup'  : truck_pickup[index],
                    'waste_type'  : waste_type[index],
                    'waste_subtype'  : waste_subtype[index],
                }
            );
        });
        $.ajax({
            url:"{{route('manifests.savecsv')}}",
            method:"post",
            dataType:'json',
            data: JSON.stringify(data_json),
            processData:false,
            contentType:'application/json',
            success:function(data)
            {
                $('#cover-spin').hide(0);
                var htmldata = '<div class="alert alert-success">'+ data.data.rowcount + ' rows were added to queue for processing</div>';
                $('#csv-preview').trigger('reset');
                $('#csv-preview-table').html(htmldata);
            },
            error: function (textStatus, errorThrown) {
                $('#cover-spin').hide(0);
                var htmldata = '<div class="alert alert-danger">Something went wrong..Try again</div>';
                $('#csv-preview-table').html(htmldata);
            }
        })
    });
</script>
@endpush
@endsection


