
@extends('admin.layouts.layout')
@push('pageplugincss')

@endpush

@section('content')
<div class="card-body card mb-4">
                        <form>
                            <div class="row">
                         <div class="col-md-4">
                             <div class="form-group">
                                 <label for="formrow-email-input">Select Category</label>
                                 <select class="form-control" name="eqp_ownership_id" id="eqp_ownership_id">
                                    <option value="">Select</option>        
                                    <option value="1" data-attr="COMPANY_OWNED" class="eqp_ownership_id">Category1</option> 
                                    <option value="2" data-attr="LEASED" class="eqp_ownership_id">Category2</option> 
                                    <option value="3" data-attr="OWNER_OPERATED" class="eqp_ownership_id">Category3</option> 
                                </select>
                             </div>
                         </div>
                         <div class="col-md-4">
                             <div class="form-group">
                                 <label for="formrow-email-input">Select State</label>
                                 <select class="form-control" name="eqp_ownership_id" id="eqp_ownership_id">
                                    <option value="">Select</option>        
                                    <option value="1" data-attr="COMPANY_OWNED" class="eqp_ownership_id">Alabama</option> 
                                    <option value="2" data-attr="LEASED" class="eqp_ownership_id">Alaska</option> 
                                    <option value="3" data-attr="OWNER_OPERATED" class="eqp_ownership_id">Arizona</option> 
                                    <option value="3" data-attr="OWNER_OPERATED" class="eqp_ownership_id">California</option> 
                                </select>
                             </div>
                         </div>
                         <div class="col-md-4">
                             <div class="form-group">
                                 <label for="formrow-email-input">Title</label>
                                 <input type="email" class="form-control" id="formrow-email-input">
                             </div>
                         </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="formrow-email-input">Description </label>
                                <textarea name="" style="max-height: 80px;" id="" class="form-control" cols="10" rows="10"></textarea>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <h5 class="font-size-13">Upload  File</h5>
                            <div class="input-group mb-3">
                                <div class="custom-file">
                                <input type="file" class="custom-file-input" id="inputGroupFile03" aria-describedby="inputGroupFileAddon03">
                                <label class="custom-file-label" for="inputGroupFile03">Choose file</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <h5 class="font-size-13">Upload  Video</h5>
                            <div class="input-group mb-3">
                                <div class="custom-file">
                                <input type="file" class="custom-file-input" id="inputGroupFile03" aria-describedby="inputGroupFileAddon03">
                                <label class="custom-file-label" for="inputGroupFile03">Choose file</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="formrow-email-input">Content URL </label>
                                <input type="email" class="form-control" id="formrow-email-input">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="d-block mb-3">Featured :</label>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="customRadioInline1" name="outer-group[0][customRadioInline1]" class="custom-control-input">
                                    <label class="custom-control-label" for="customRadioInline1">Featured 1</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="customRadioInline2" name="outer-group[0][customRadioInline1]" class="custom-control-input">
                                    <label class="custom-control-label" for="customRadioInline2">Featured 2</label>
                                </div>
                            </div>
                        </div>
                         </div>
                        </form>
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary w-md">Submit</button>
                        </div>
                     </div>

@endsection

@push('pagejs')

@endpush

