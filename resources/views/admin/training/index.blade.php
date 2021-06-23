
@extends('admin.layouts.layout')
@push('pageplugincss')

@endpush

@section('content')
<div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex flex-column flex-md-row justify-content-between">
                                        <div class="input-group mw-30">
                                            <input type="text" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="button-addon2">
                                            <button class="btn btn-primary" type="button" id="button-addon2">Search</button>
                                          </div>
                                        <a href="{{route('training.create')}}" type="submit" class="btn btn-primary w-md">Add Training & Compliance</a>
                                    </div>
                                    <div class="tab-content mt-3 text-muted">
                                        <div class="tab-pane active" id="home1" role="tabpanel">
                                            <div class="table-responsive">
                                                <table class="table table-centered table-nowrap mb-0">
                                                    <thead class="thead-light">
                                                        <tr>
                                                            <th>Category</th>
                                                            <th>Subject Title</th>
                                                            <th>State</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr class="">
                                                            <td>Category Name</td>
                                                            <td>Title 1</td>
                                                            <td>California </td>
                                                            <td>
                                                                <a href="#" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit" type="button" class="btn btn-primary btn-sm btn-rounded waves-effect waves-light">
                                                                    <i class="bx bx-edit-alt"></i>
                                                                </a>
                                                                <a href="#" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete" type="button" class="btn btn-danger btn-sm btn-rounded waves-effect waves-light">
                                                                    <i class="bx bx-trash-alt"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr class="">
                                                            <td>Category Name</td>
                                                            <td>Title 2</td>
                                                            <td>Alaska </td>
                                                            <td>
                                                                <a href="#" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit" type="button" class="btn btn-primary btn-sm btn-rounded waves-effect waves-light">
                                                                    <i class="bx bx-edit-alt"></i>
                                                                </a>
                                                                <a href="#" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete" type="button" class="btn btn-danger btn-sm btn-rounded waves-effect waves-light">
                                                                    <i class="bx bx-trash-alt"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr class="">
                                                            <td>Category Name</td>
                                                            <td>Title 3</td>
                                                            <td>Washington </td>
                                                            <td>
                                                                <a href="#" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit" type="button" class="btn btn-primary btn-sm btn-rounded waves-effect waves-light">
                                                                    <i class="bx bx-edit-alt"></i>
                                                                </a>
                                                                <a href="#" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete" type="button" class="btn btn-danger btn-sm btn-rounded waves-effect waves-light">
                                                                    <i class="bx bx-trash-alt"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr class="">
                                                            <td>Category Name</td>
                                                            <td>Title 4</td>
                                                            <td>California </td>
                                                            <td>
                                                                <a href="#" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit" type="button" class="btn btn-primary btn-sm btn-rounded waves-effect waves-light">
                                                                    <i class="bx bx-edit-alt"></i>
                                                                </a>
                                                                <a href="#" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete" type="button" class="btn btn-danger btn-sm btn-rounded waves-effect waves-light">
                                                                    <i class="bx bx-trash-alt"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr class="">
                                                            <td>Category Name</td>
                                                            <td>Title 5</td>
                                                            <td>Alaska  </td>
                                                            <td>
                                                                <a href="#" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit" type="button" class="btn btn-primary btn-sm btn-rounded waves-effect waves-light">
                                                                    <i class="bx bx-edit-alt"></i>
                                                                </a>
                                                                <a href="#" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete" type="button" class="btn btn-danger btn-sm btn-rounded waves-effect waves-light">
                                                                    <i class="bx bx-trash-alt"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr class="">
                                                            <td>Category Name</td>
                                                            <td>Title 6</td>
                                                            <td>Washington </td>
                                                            <td>
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


@endsection

@push('pagejs')

@endpush

