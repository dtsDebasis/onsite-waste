@extends('admin.customer.create.createlayout')


@section('create-customer-content')
<!-- tab7 -->
<div class="tab-pane active" id="document-1" role="tabpanel">
    <div class="card">
        <div class="card-body mb-4">
            <form>
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="custom-heading">Document </h3>
                    </div>
                    <div class="col-sm-4">
                        <h5 class="font-size-14">Upload Documents</h5>
                        <div class="input-group mb-3">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="inputGroupFile03" aria-describedby="inputGroupFileAddon03">
                                <label class="custom-file-label" for="inputGroupFile03">Choose file</label>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="mt-2">
                <h4 class="card-title">List of documents</h4>
                <div class="row">
                    <div class="col-xl-3 col-sm-6">
                        <div class="card shadow-none border">
                            <div class="card-body p-3">
                                <div class="">
                                    <div class="float-right ml-2">
                                        <div class="dropdown mb-2">
                                            <a class="font-size-16 text-muted dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="mdi mdi-dots-horizontal"></i>
                                            </a>

                                            <div class="dropdown-menu dropdown-menu-right" style="">
                                                <a class="dropdown-item" href="#">Open/Download</a>
                                                <!-- <a class="dropdown-item" href="#">Edit</a> -->
                                                <a class="dropdown-item" href="#">Rename</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item text-danger" href="#">Remove</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="avatar-xs avatar-bx mr-3 mb-3">
                                        <img src="assets/images/dd-1.jpg" alt="">
                                    </div>
                                    <div class="d-flex">
                                        <div class="overflow-hidden mr-auto">
                                            <h5 class="font-size-14 text-truncate mb-1">
                                                <a href="javascript: void(0);" class="text-body">Files Name</a>
                                            </h5>
                                        </div>
                                        <div class="align-self-end ml-2">
                                            <p class="text-muted mb-0">6GB</p>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6">
                        <div class="card shadow-none border">
                            <div class="card-body p-3">
                                <div class="">
                                    <div class="float-right ml-2">
                                        <div class="dropdown mb-2">
                                            <a class="font-size-16 text-muted dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="mdi mdi-dots-horizontal"></i>
                                            </a>

                                            <div class="dropdown-menu dropdown-menu-right" style="">
                                                <a class="dropdown-item" href="#">Open/Download</a>
                                                <!-- <a class="dropdown-item" href="#">Edit</a> -->
                                                <a class="dropdown-item" href="#">Rename</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item text-danger" href="#">Remove</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="avatar-xs avatar-bx mr-3 mb-3">
                                        <img src="assets/images/dd-1.jpg" alt="">
                                    </div>
                                    <div class="d-flex">
                                        <div class="overflow-hidden mr-auto">
                                            <h5 class="font-size-14 text-truncate mb-1">
                                                <a href="javascript: void(0);" class="text-body">Files Name</a>
                                            </h5>
                                        </div>
                                        <div class="align-self-end ml-2">
                                            <p class="text-muted mb-0">6GB</p>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6">
                        <div class="card shadow-none border">
                            <div class="card-body p-3">
                                <div class="">
                                    <div class="float-right ml-2">
                                        <div class="dropdown mb-2">
                                            <a class="font-size-16 text-muted dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="mdi mdi-dots-horizontal"></i>
                                            </a>

                                            <div class="dropdown-menu dropdown-menu-right" style="">
                                                <a class="dropdown-item" href="#">Open/Download</a>
                                                <!-- <a class="dropdown-item" href="#">Edit</a> -->
                                                <a class="dropdown-item" href="#">Rename</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item text-danger" href="#">Remove</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="avatar-xs avatar-bx mr-3 mb-3">
                                        <img src="assets/images/dd-1.jpg" alt="">
                                    </div>
                                    <div class="d-flex">
                                        <div class="overflow-hidden mr-auto">
                                            <h5 class="font-size-14 text-truncate mb-1">
                                                <a href="javascript: void(0);" class="text-body">Files Name</a>
                                            </h5>
                                        </div>
                                        <div class="align-self-end ml-2">
                                            <p class="text-muted mb-0">6GB</p>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6">
                        <div class="card shadow-none border">
                            <div class="card-body p-3">
                                <div class="">
                                    <div class="float-right ml-2">
                                        <div class="dropdown mb-2">
                                            <a class="font-size-16 text-muted dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="mdi mdi-dots-horizontal"></i>
                                            </a>

                                            <div class="dropdown-menu dropdown-menu-right" style="">
                                                <a class="dropdown-item" href="#">Open/Download</a>
                                                <!-- <a class="dropdown-item" href="#">Edit</a> -->
                                                <a class="dropdown-item" href="#">Rename</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item text-danger" href="#">Remove</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="avatar-xs avatar-bx mr-3 mb-3">
                                        <img src="assets/images/dd-1.jpg" alt="">
                                    </div>
                                    <div class="d-flex">
                                        <div class="overflow-hidden mr-auto">
                                            <h5 class="font-size-14 text-truncate mb-1">
                                                <a href="javascript: void(0);" class="text-body">Files Name</a>
                                            </h5>
                                        </div>
                                        <div class="align-self-end ml-2">
                                            <p class="text-muted mb-0">6GB</p>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="text-right">
                <button type="submit" class="btn btn-primary w-md">Next</button>
            </div>
        </div>
    </div>
</div>

@endsection('create-customer-content')


@section('create-customer-content-js')
<script>
$(document).ready(function () {

    let loc = window.location.href;
    console.log(loc);
    $('.nav-link.active').removeClass('active');
    if(/create\/document/.test(loc)) {
        $('.nav-link.document').addClass('active');
    }

});
</script>
@endsection('create-customer-content')