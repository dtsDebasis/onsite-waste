
@extends('admin.layouts.layout')
@push('pageplugincss')

@endpush

@section('content')
<div class="row">
                        <div class="col-lg-12">
                            <ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
                                <li class="nav-item waves-effect waves-light">
                                    <a class="nav-link active" data-toggle="tab" href="#home-1" role="tab" aria-selected="true">
                                        <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                        <span class="d-none d-sm-block">Active</span> 
                                    </a>
                                </li>
                                <li class="nav-item waves-effect waves-light">
                                    <a class="nav-link" data-toggle="tab" href="#profile-1" role="tab" aria-selected="false">
                                        <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                                        <span class="d-none d-sm-block">Completed</span> 
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content pt-3 text-muted">
                                <div class="tab-pane active" id="home-1" role="tabpanel">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="d-flex flex-column flex-md-row justify-content-between">
                                                <div class="input-group mw-30">
                                                    <input type="text" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="button-addon2">
                                                    <button class="btn btn-primary" type="button" id="button-addon2">Search</button>
                                                  </div>
                                                <a href="{{route('pickup.create', 0  )}}" type="submit" class="btn btn-primary w-md">Add Pick Up Schedule</a>
                                            </div>
                                            <div class="tab-content mt-3 text-muted">
                                                <div class="tab-pane active" id="home1" role="tabpanel">
                                                    <div class="table-responsive">
                                                        <table class="table table-centered table-nowrap mb-0">
                                                            <thead class="thead-light">
                                                                <tr>
                                                                    <th>Customer</th>
                                                                    <th>Branch</th>
                                                                    <th>Package</th>
                                                                    <th>Date</th>
                                                                    <th>Status</th>
                                                                    <th>Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr class="">
                                                                    <td>Vital Health and Hope.LLC</td>
                                                                    <td> <span class="color-b">City Branch</span>  <br> <i class="bx bx-map"></i> Mrs Smith 813 Howard Street Oswego NY 13126 </td>
                                                                    <td>1 Box</td>
                                                                    <td>15th Jan 2021</td>
                                                                    <td><span class="badge badge-pill badge-soft-success">Confirm</span></td>
                                                                    <td>
                                                                        <a href="#" data-toggle="tooltip" data-placement="top" title="" data-original-title="Add Manifest" type="button" class="btn btn-primary btn-sm btn-rounded waves-effect waves-light">
                                                                            <i  data-toggle="modal" data-target=".exampleModal" class="bx bx-plus-medical"></i>
                                                                        </a>
                                                                        <a href="#" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit" type="button" class="btn btn-primary btn-sm btn-rounded waves-effect waves-light">
                                                                            <i class="bx bx-edit-alt"></i>
                                                                        </a>
                                                                        <a href="#" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete" type="button" class="btn btn-danger btn-sm btn-rounded waves-effect waves-light">
                                                                            <i class="bx bx-trash-alt"></i>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                                <tr class="">
                                                                    <td>Leisurality.LLC</td>
                                                                    <td> <span class="color-b">Star South Branch</span>  <br> <i class="bx bx-map"></i> Mrs Smith 71 Cherry Court SOUTHAMPTON SO53 5PD </td>
                                                                    <td>2 Box</td>
                                                                    <td>16th Jan 2021</td>
                                                                    <td><span class="badge badge-pill badge-soft-warning">Not Confirm</span></td>
                                                                    <td>
                                                                        <a href="#" data-toggle="tooltip" data-placement="top" title="" data-original-title="Add Manifest" type="button" class="btn btn-primary btn-sm btn-rounded waves-effect waves-light">
                                                                            <i  data-toggle="modal" data-target=".exampleModal" class="bx bx-plus-medical"></i>
                                                                        </a>
                                                                        <a href="#" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit" type="button" class="btn btn-primary btn-sm btn-rounded waves-effect waves-light">
                                                                            <i class="bx bx-edit-alt"></i>
                                                                        </a>
                                                                        <a href="#" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete" type="button" class="btn btn-danger btn-sm btn-rounded waves-effect waves-light">
                                                                            <i class="bx bx-trash-alt"></i>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                                <tr class="">
                                                                    <td>Health and Hope.LLC</td>
                                                                    <td> <span class="color-b">North Branch</span>  <br> <i class="bx bx-map"></i> Mrs Smith 813 Howard Street Oswego NY 13126 </td>
                                                                    <td>3 Box</td>
                                                                    <td>17th Jan 2021</td>
                                                                    <td><span class="badge badge-pill badge-soft-primary">Pickup Done</span></td>
                                                                    <td>
                                                                        <a href="#" data-toggle="tooltip" data-placement="top" title="" data-original-title="Add Manifest" type="button" class="btn btn-primary btn-sm btn-rounded waves-effect waves-light">
                                                                            <i  data-toggle="modal" data-target=".exampleModal" class="bx bx-plus-medical"></i>
                                                                        </a>
                                                                        <a href="#" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit" type="button" class="btn btn-primary btn-sm btn-rounded waves-effect waves-light">
                                                                            <i class="bx bx-edit-alt"></i>
                                                                        </a>
                                                                        <a href="#" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete" type="button" class="btn btn-danger btn-sm btn-rounded waves-effect waves-light">
                                                                            <i class="bx bx-trash-alt"></i>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                                <tr class="">
                                                                    <td>Vital Health and Hope.LLC</td>
                                                                    <td> <span class="color-b">City Branch</span>  <br> <i class="bx bx-map"></i> Mrs Smith 813 Howard Street Oswego NY 13126 </td>
                                                                    <td>1 Box</td>
                                                                    <td>15th Jan 2021</td>
                                                                    <td><span class="badge badge-pill badge-soft-success">Confirm</span></td>
                                                                    <td>
                                                                        <a href="#" data-toggle="tooltip" data-placement="top" title="" data-original-title="Add Manifest" type="button" class="btn btn-primary btn-sm btn-rounded waves-effect waves-light">
                                                                            <i  data-toggle="modal" data-target=".exampleModal" class="bx bx-plus-medical"></i>
                                                                        </a>
                                                                        <a href="#" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit" type="button" class="btn btn-primary btn-sm btn-rounded waves-effect waves-light">
                                                                            <i class="bx bx-edit-alt"></i>
                                                                        </a>
                                                                        <a href="#" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete" type="button" class="btn btn-danger btn-sm btn-rounded waves-effect waves-light">
                                                                            <i class="bx bx-trash-alt"></i>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                                <tr class="">
                                                                    <td>Leisurality.LLC</td>
                                                                    <td> <span class="color-b">Star South Branch</span>  <br> <i class="bx bx-map"></i> Mrs Smith 71 Cherry Court SOUTHAMPTON SO53 5PD </td>
                                                                    <td>2 Box</td>
                                                                    <td>16th Jan 2021</td>
                                                                    <td><span class="badge badge-pill badge-soft-warning">Not Confirm</span></td>
                                                                    <td>
                                                                        <a href="#" data-toggle="tooltip" data-placement="top" title="" data-original-title="Add Manifest" type="button" class="btn btn-primary btn-sm btn-rounded waves-effect waves-light">
                                                                            <i  data-toggle="modal" data-target=".exampleModal" class="bx bx-plus-medical"></i>
                                                                        </a>
                                                                        <a href="#" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit" type="button" class="btn btn-primary btn-sm btn-rounded waves-effect waves-light">
                                                                            <i class="bx bx-edit-alt"></i>
                                                                        </a>
                                                                        <a href="#" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete" type="button" class="btn btn-danger btn-sm btn-rounded waves-effect waves-light">
                                                                            <i class="bx bx-trash-alt"></i>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                                <tr class="">
                                                                    <td>Health and Hope.LLC</td>
                                                                    <td> <span class="color-b">North Branch</span>  <br> <i class="bx bx-map"></i> Mrs Smith 813 Howard Street Oswego NY 13126 </td>
                                                                    <td>3 Box</td>
                                                                    <td>17th Jan 2021</td>
                                                                    <td><span class="badge badge-pill badge-soft-primary">Pickup Done</span></td>
                                                                    <td>
                                                                        <a href="#" data-toggle="tooltip" data-placement="top" title="" data-original-title="Add Manifest" type="button" class="btn btn-primary btn-sm btn-rounded waves-effect waves-light">
                                                                            <i  data-toggle="modal" data-target=".exampleModal" class="bx bx-plus-medical"></i>
                                                                        </a>
                                                                        <a href="#" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit" type="button" class="btn btn-primary btn-sm btn-rounded waves-effect waves-light">
                                                                            <i class="bx bx-edit-alt"></i>
                                                                        </a>
                                                                        <a href="#" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete" type="button" class="btn btn-danger btn-sm btn-rounded waves-effect waves-light">
                                                                            <i class="bx bx-trash-alt"></i>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <ul class="pagination pagination-rounded justify-content-center mt-4">
                                                        <li class="page-item disabled">
                                                            <a href="#" class="page-link"><i class="mdi mdi-chevron-left"></i></a>
                                                        </li>
                                                        <li class="page-item">
                                                            <a href="#" class="page-link">1</a>
                                                        </li>
                                                        <li class="page-item active">
                                                            <a href="#" class="page-link">2</a>
                                                        </li>
                                                        <li class="page-item">
                                        <a href="#" class="page-link">3</a>
                                    </li>
                                    <li class="page-item">
                                        <a href="#" class="page-link">4</a>
                                    </li>
                                    <li class="page-item">
                                        <a href="#" class="page-link">5</a>
                                    </li>
                                    <li class="page-item">
                                        <a href="#" class="page-link"><i class="mdi mdi-chevron-right"></i></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        
                        <!-- end table-responsive -->
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="profile-1" role="tabpanel">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-column flex-md-row justify-content-between">
                            <div class="input-group mw-30">
                                <input type="text" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="button-addon2">
                                <button class="btn btn-primary" type="button" id="button-addon2">Search</button>
                                </div>
                            <a href="pickup-add.html" type="submit" class="btn btn-primary w-md">Add Pick Up Schedule</a>
                        </div>
                        <div class="tab-content mt-3 text-muted">
                            <div class="tab-pane active" id="home1" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table table-centered table-nowrap mb-0">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Customer</th>
                                                <th>Branch</th>
                                                <th>Package</th>
                                                <th>Date</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="">
                                                <td>Vital Health and Hope.LLC</td>
                                                <td> <span class="color-b">City Branch</span>  <br> <i class="bx bx-map"></i> Mrs Smith 813 Howard Street Oswego NY 13126 </td>
                                                <td>1 Box</td>
                                                <td>15th Jan 2021</td>
                                                <td><span class="badge badge-pill badge-soft-success">Completed</span></td>
                                                <td>
                                                    <a href="#" data-toggle="tooltip" data-placement="top" title="" data-original-title="Add Manifest" type="button" class="btn btn-primary btn-sm btn-rounded waves-effect waves-light">
                                                        <i  data-toggle="modal" data-target=".exampleModal" class="bx bx-plus-medical"></i>
                                                    </a>
                                                    <a href="#" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit" type="button" class="btn btn-primary btn-sm btn-rounded waves-effect waves-light">
                                                        <i class="bx bx-edit-alt"></i>
                                                    </a>
                                                    <a href="#" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete" type="button" class="btn btn-danger btn-sm btn-rounded waves-effect waves-light">
                                                        <i class="bx bx-trash-alt"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr class="">
                                                <td>Leisurality.LLC</td>
                                                <td> <span class="color-b">Star South Branch</span>  <br> <i class="bx bx-map"></i> Mrs Smith 71 Cherry Court SOUTHAMPTON SO53 5PD </td>
                                                <td>2 Box</td>
                                                <td>16th Jan 2021</td>
                                                <td><span class="badge badge-pill badge-soft-success">Completed</span></td>
                                                <td>
                                                    <a href="#" data-toggle="tooltip" data-placement="top" title="" data-original-title="Add Manifest" type="button" class="btn btn-primary btn-sm btn-rounded waves-effect waves-light">
                                                        <i  data-toggle="modal" data-target=".exampleModal" class="bx bx-plus-medical"></i>
                                                    </a>
                                                    <a href="#" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit" type="button" class="btn btn-primary btn-sm btn-rounded waves-effect waves-light">
                                                        <i class="bx bx-edit-alt"></i>
                                                    </a>
                                                    <a href="#" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete" type="button" class="btn btn-danger btn-sm btn-rounded waves-effect waves-light">
                                                        <i class="bx bx-trash-alt"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr class="">
                                                <td>Health and Hope.LLC</td>
                                                <td> <span class="color-b">North Branch</span>  <br> <i class="bx bx-map"></i> Mrs Smith 813 Howard Street Oswego NY 13126 </td>
                                                <td>3 Box</td>
                                                <td>17th Jan 2021</td>
                                                <td><span class="badge badge-pill badge-soft-success">Completed</span></td>
                                                <td>
                                                    <a href="#" data-toggle="tooltip" data-placement="top" title="" data-original-title="Add Manifest" type="button" class="btn btn-primary btn-sm btn-rounded waves-effect waves-light">
                                                        <i  data-toggle="modal" data-target=".exampleModal" class="bx bx-plus-medical"></i>
                                                    </a>
                                                    <a href="#" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit" type="button" class="btn btn-primary btn-sm btn-rounded waves-effect waves-light">
                                                        <i class="bx bx-edit-alt"></i>
                                                    </a>
                                                    <a href="#" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete" type="button" class="btn btn-danger btn-sm btn-rounded waves-effect waves-light">
                                                        <i class="bx bx-trash-alt"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr class="">
                                                <td>Vital Health and Hope.LLC</td>
                                                <td> <span class="color-b">City Branch</span>  <br> <i class="bx bx-map"></i> Mrs Smith 813 Howard Street Oswego NY 13126 </td>
                                                <td>1 Box</td>
                                                <td>15th Jan 2021</td>
                                                <td><span class="badge badge-pill badge-soft-success">Completed</span></td>
                                                <td>
                                                    <a href="#" data-toggle="tooltip" data-placement="top" title="" data-original-title="Add Manifest" type="button" class="btn btn-primary btn-sm btn-rounded waves-effect waves-light">
                                                        <i  data-toggle="modal" data-target=".exampleModal" class="bx bx-plus-medical"></i>
                                                    </a>
                                                    <a href="#" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit" type="button" class="btn btn-primary btn-sm btn-rounded waves-effect waves-light">
                                                        <i class="bx bx-edit-alt"></i>
                                                    </a>
                                                    <a href="#" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete" type="button" class="btn btn-danger btn-sm btn-rounded waves-effect waves-light">
                                                        <i class="bx bx-trash-alt"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr class="">
                                                <td>Leisurality.LLC</td>
                                                <td> <span class="color-b">Star South Branch</span>  <br> <i class="bx bx-map"></i> Mrs Smith 71 Cherry Court SOUTHAMPTON SO53 5PD </td>
                                                <td>2 Box</td>
                                                <td>16th Jan 2021</td>
                                                <td><span class="badge badge-pill badge-soft-success">Completed</span></td>
                                                <td>
                                                    <a href="#" data-toggle="tooltip" data-placement="top" title="" data-original-title="Add Manifest" type="button" class="btn btn-primary btn-sm btn-rounded waves-effect waves-light">
                                                        <i  data-toggle="modal" data-target=".exampleModal" class="bx bx-plus-medical"></i>
                                                    </a>
                                                    <a href="#" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit" type="button" class="btn btn-primary btn-sm btn-rounded waves-effect waves-light">
                                                        <i class="bx bx-edit-alt"></i>
                                                    </a>
                                                    <a href="#" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete" type="button" class="btn btn-danger btn-sm btn-rounded waves-effect waves-light">
                                                        <i class="bx bx-trash-alt"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr class="">
                                                <td>Health and Hope.LLC</td>
                                                <td> <span class="color-b">North Branch</span>  <br> <i class="bx bx-map"></i> Mrs Smith 813 Howard Street Oswego NY 13126 </td>
                                                <td>3 Box</td>
                                                <td>17th Jan 2021</td>
                                                <td><span class="badge badge-pill badge-soft-success">Completed</span></td>
                                                <td>
                                                    <a href="#" data-toggle="tooltip" data-placement="top" title="" data-original-title="Add Manifest" type="button" class="btn btn-primary btn-sm btn-rounded waves-effect waves-light">
                                                        <i  data-toggle="modal" data-target=".exampleModal" class="bx bx-plus-medical"></i>
                                                    </a>
                                                    <a href="#" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit" type="button" class="btn btn-primary btn-sm btn-rounded waves-effect waves-light">
                                                        <i class="bx bx-edit-alt"></i>
                                                    </a>
                                                    <a href="#" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete" type="button" class="btn btn-danger btn-sm btn-rounded waves-effect waves-light">
                                                        <i class="bx bx-trash-alt"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <ul class="pagination pagination-rounded justify-content-center mt-4">
                                    <li class="page-item disabled">
                                        <a href="#" class="page-link"><i class="mdi mdi-chevron-left"></i></a>
                                    </li>
                                    <li class="page-item">
                                        <a href="#" class="page-link">1</a>
                                    </li>
                                    <li class="page-item active">
                                        <a href="#" class="page-link">2</a>
                                    </li>
                                    <li class="page-item">
                                        <a href="#" class="page-link">3</a>
                                    </li>
                                    <li class="page-item">
                                        <a href="#" class="page-link">4</a>
                                    </li>
                                    <li class="page-item">
                                        <a href="#" class="page-link">5</a>
                                    </li>
                                    <li class="page-item">
                                        <a href="#" class="page-link"><i class="mdi mdi-chevron-right"></i></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        
                        <!-- end table-responsive -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection

@push('pagejs')

@endpush

