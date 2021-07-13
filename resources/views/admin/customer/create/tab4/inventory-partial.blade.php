<div class="card">
                    <div class="card-body mb-4">
                        <div class="row mb-4">

                            <div class="col-sm-12">
                                <div class="d-flex flex-column flex-md-row justify-content-between custom-heading-wrapper align-items-center">
                                <h3>TE 5000</h3>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="table-responsive">
                                    <table class="table table-centered table-nowrap mb-0">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>IMEI</th>
                                                <th>Serial Number</th>
                                                <th>Firmware</th>
                                                <th>Status</th> 
                                                <th>Last run</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(count($te5000))
                                                @foreach($te5000['results'] as $key => $val) 
                                                    <tr>
                                                        <td>{{isset($val['imei']) ? $val['imei'] : 'NA'}}</td>
                                                        <td>{{isset($val['serialNumber']) ? $val['serialNumber'] : 'NA'}}</td>
                                                        <td>{{isset($val['currentFirmwareVersion']) ? $val['currentFirmwareVersion'] : 'NA'}}</td>
                                                        <td>{{isset($val['status']) ? $val['status'] : 'NA'}}</td>
                                                        <td>{{isset($val['lastAnnounceDateTime']) ? \App\Helpers\Helper::showdate($val['lastAnnounceDateTime'],true,'m-d-Y h:i A') : 'NA'}}</td>
                                                        
                                                        <!-- <td>
                                                            <button type="button" class="btn btn-sm btn-outline-primary waves-effect waves-light last_run_info" data-location_id="{{isset($val['locationId']) ? $val['locationId'] : ''}}" data-imie_no="{{isset($val['imei']) ? $val['imei'] : ''}}">Last Run Info</button>
                                                        </td> -->
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
                        </div>
                        <div class="row mb-4 d-none" id="last_run_info_row">
                            <div class="col-sm-12">
                                <div class="d-flex flex-column flex-md-row justify-content-between custom-heading-wrapper align-items-center">
                                    <div class="col-sm-6">
                                        <h3>Last Run-info</h3>
                                    </div>
                                    <div class="col-sm-6 text-right">
                                        <a href="javascript:;" id="close_last_run_info" class="btn btn-sm btn-danger"><i class="fa fa-times"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="table-responsive">
                                    <table class="table table-centered table-nowrap mb-0">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Start DateTime</th>
                                                <th>End DateTime</th>
                                                <th>Low Temp</th>
                                                <th>High Temp</th>
                                                <th>Type</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr id="last_run_ifo_tr">
                                                <td>4542313</td>
                                                <td>E56SS6777677</td>
                                                <td>24/02/21</td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-outline-primary waves-effect waves-light machine-ping" data-location_id="{{'223453'}}" data-imie_no="{{'E56SS6777677'}}">Ping Machine</button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-4">

                            <div class="col-sm-12">
                                <div class="d-flex flex-column flex-md-row justify-content-between custom-heading-wrapper align-items-center">
                                    <h3>Container Inventory</h3>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="table-responsive">
                                    
                                    <table class="table table-centered table-nowrap mb-0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>SH Inventory</th>
                                            <th>SH ROP</th>
                                            <th>SH Container Type</th>
                                            <th>RB Inventory</th>
                                            <th>RB ROP</th>
                                            <th>RB Container Type</th>
                                        </tr>
                                    </thead>
                                    <tbody>                                               
                                            <tr class="">
                                                @php($inventory_details = (array_key_exists("locationId",$containerInventory))?$containerInventory: array())
                                                <td><input class="form-control" type="number" name="sh_inventory[]" id="sh_inventory_{{$uniq_id}}" value="{{isset($inventory_details['sharps']['containersAtHand'])?$inventory_details['sharps']['containersAtHand']:0}}"> </td>
                                                <td><input class="form-control" type="number" name="sh_rop[]" id="sh_rop_{{$uniq_id}}" value="{{isset($inventory_details['sharps']['reorderPoint'])?$inventory_details['sharps']['reorderPoint']:0}}"> </td>
                                                <!-- <td>{!! Form::select('sh_container_type',['Spinner'=>'Spinner','Rocker'=>'Rocker'],isset($inventory_details['sharps']['canisterType'])?$inventory_details['sharps']['canisterType']:null,['class'=>'form-control select2','id'=>'sh_container_type','placeholder'=>'Choose ...']) !!}</td> -->
                                                <!-- <td>{{isset($inventory_details[1]['canisterType'])?$inventory_details[1]['canisterType']:'NA'}} </td> -->
                                                <td>{{($location->sh_container_type)?$location->sh_container_type:'NA'}}</td>
                                                <td><input class="form-control" type="number" name="rb_inventory[]" id="rb_inventory_{{$uniq_id}}" value="{{isset($inventory_details['redbag']['containersAtHand'])?$inventory_details['redbag']['containersAtHand']:0}}"> </td>
                                                <td><input class="form-control" type="number" name="rb_rop[]" id="rb_rop_{{$uniq_id}}" value="{{isset($inventory_details['redbag']['reorderPoint'])?$inventory_details['redbag']['reorderPoint']:0}}"> </td>
                                                <td>{{($location->rb_container_type)?$location->rb_container_type:'NA'}}</td>
                                                <!-- <td>{{isset($inventory_details[0]['canisterType'])?$inventory_details[0]['canisterType']:'NA'}} </td> -->
                                                <!-- <td>{!! Form::select('rb_container_type',['Rocker'=>'Rocker','Open'=>'Open'],isset($inventory_details['redbag']['canisterType'])?$inventory_details['redbag']['canisterType']:null,['class'=>'form-control select2','id'=>'rb_container_type','placeholder'=>'Choose ...']) !!}</td> -->
                                            </tr>                                                
                                    </tbody>
                                </table>
                                </div>
                            </div>
                        </div>
                        
                    </div>

                </div>