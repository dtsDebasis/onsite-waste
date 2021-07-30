@php ($headerOption = [
    'title' => 'Welcome to Dashboard',
    'noCardView' => true
])
@extends('admin.layouts.layout', $headerOption)

@section('content')
@if (can('View Dashboard'))
    <div class="row">
    <div class="col-sm-3">
        <div class="card">
            <div class="card-body bg-soft-success">
                <div class="d-flex align-items-center mb-2">
                    <div class="avatar-xs mr-3">
                        <span class="avatar-title rounded-circle bg-soft-primary text-primary font-size-20">
                            <i class="bx bx-bookmark"></i>
                        </span>
                    </div>
                    <a href="{{route('customers.index')}}"><h5 class="font-size-16 text-info mb-0">Customers Count</h5></a>
                </div>
                <div class="text-muted mt-2">
                    <h4>{{$customers}}</h4>
                    <div class="d-flex">
                        <span class="text-truncate">Total Number of Customers</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="card">
            <div class="card-body bg-soft-primary">
                <div class="d-flex align-items-center mb-2">
                    <div class="avatar-xs mr-3">
                        <span class="avatar-title rounded-circle bg-soft-primary text-primary font-size-20">
                            <i class="bx bx-bookmark"></i>
                        </span>
                    </div>
                    <a href="{{route('customers.index')}}"><h5 class="font-size-16 text-info mb-0">Locations Count</h5></a>
                </div>
                <div class="text-muted mt-2">
                    <h4>{{ $locations }}</h4>
                    <div class="d-flex">
                        <span class="text-truncate">Total Number of Locations</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="card">
            <div class="card-body bg-soft-success">
                <div class="d-flex align-items-center mb-2">
                    <div class="avatar-xs mr-3">
                        <span class="avatar-title rounded-circle bg-soft-primary text-primary font-size-20">
                            <i class="bx bx-bookmark"></i>
                        </span>
                    </div>
                    <a href="{{route('customers.index')}}"><h5 class="font-size-16 text-info mb-0">Active Locations Count</h5></a>
                </div>
                <div class="text-muted mt-2">
                    <h4>{{ $active_locations }}</h4>
                    <div class="d-flex">
                        <span class="text-truncate">Total Number of Active Locations</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="card">
            <div class="card-body bg-soft-primary">
                <div class="d-flex align-items-center mb-2">
                    <div class="avatar-xs mr-3">
                        <span class="avatar-title rounded-circle bg-soft-primary text-primary font-size-20">
                            <i class="bx bx-bookmark"></i>
                        </span>
                    </div>
                    <a href="{{route('customers.index')}}"><h5 class="font-size-16 text-info mb-0">Booked Locations Count</h5></a>
                </div>
                <div class="text-muted mt-2">
                    <h4>{{ $booked_locations }}</h4>
                    <div class="d-flex">
                        <span class="text-truncate">Total Number of Booked Locations</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- <div class="col-sm-3">
        <div class="card">
            <div class="card-body bg-soft-primary">
                <div class="d-flex align-items-center mb-2">
                    <div class="avatar-xs mr-3">
                        <span class="avatar-title rounded-circle bg-soft-primary text-primary font-size-20">
                            <i class="bx bx-bookmark"></i>
                        </span>
                    </div>
                    <a href=""><h5 class="font-size-16 text-info mb-0">TE-5000 - Installed Count</h5></a>
                </div>
                <div class="text-muted mt-2">
                    <h4>1,452</h4>
                    <div class="d-flex">
                        <span class="text-truncate">Total Number of TE-5000 - Installed</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="card">
            <div class="card-body bg-soft-success">
                <div class="d-flex align-items-center mb-2">
                    <div class="avatar-xs mr-3">
                        <span class="avatar-title rounded-circle bg-soft-primary text-primary font-size-20">
                            <i class="bx bx-bookmark"></i>
                        </span>
                    </div>
                    <a href=""><h5 class="font-size-16 text-info mb-0">TE-5000 - Booked Count</h5></a>
                </div>
                <div class="text-muted mt-2">
                    <h4>1,452</h4>
                    <div class="d-flex">
                        <span class="text-truncate">Total Number of TE-5000 - Booked</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="card">
            <div class="card-body bg-soft-primary">
                <div class="d-flex align-items-center mb-2">
                    <div class="avatar-xs mr-3">
                        <span class="avatar-title rounded-circle bg-soft-primary text-primary font-size-20">
                            <i class="bx bx-bookmark"></i>
                        </span>
                    </div>
                    <a href=""><h5 class="font-size-16 text-info mb-0">Pick up Only - Installed Count</h5></a>
                </div>
                <div class="text-muted mt-2">
                    <h4>1,452</h4>
                    <div class="d-flex">
                        <span class="text-truncate">Total Number of Pick up Only - Installed</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="card">
            <div class="card-body bg-soft-success">
                <div class="d-flex align-items-center mb-2">
                    <div class="avatar-xs mr-3">
                        <span class="avatar-title rounded-circle bg-soft-primary text-primary font-size-20">
                            <i class="bx bx-bookmark"></i>
                        </span>
                    </div>
                    <a href=""><h5 class="font-size-16 text-info mb-0">Pick up Only - Booked Count</h5></a>
                </div>
                <div class="text-muted mt-2">
                    <h4>1,452</h4>
                    <div class="d-flex">
                        <span class="text-truncate">Total Number of Pick up Only - Booked</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="card">
            <div class="card-body bg-soft-primary">
                <div class="d-flex align-items-center mb-2">
                    <div class="avatar-xs mr-3">
                        <span class="avatar-title rounded-circle bg-soft-primary text-primary font-size-20">
                            <i class="bx bx-bookmark"></i>
                        </span>
                    </div>
                    <a href=""><h5 class="font-size-16 text-info mb-0">Hybrid - Installed Count</h5></a>
                </div>
                <div class="text-muted mt-2">
                    <h4>1,452</h4>
                    <div class="d-flex">
                        <span class="text-truncate">Total Number of Hybrid - Installed</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="card">
            <div class="card-body bg-soft-success">
                <div class="d-flex align-items-center mb-2">
                    <div class="avatar-xs mr-3">
                        <span class="avatar-title rounded-circle bg-soft-primary text-primary font-size-20">
                            <i class="bx bx-bookmark"></i>
                        </span>
                    </div>
                    <a href=""><h5 class="font-size-16 text-info mb-0">Hybrid - Booked Count</h5></a>
                </div>
                <div class="text-muted mt-2">
                    <h4>1,452</h4>
                    <div class="d-flex">
                        <span class="text-truncate">Total Number of Hybrid - Booked</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="card">
            <div class="card-body bg-soft-primary">
                <div class="d-flex align-items-center mb-2">
                    <div class="avatar-xs mr-3">
                        <span class="avatar-title rounded-circle bg-soft-primary text-primary font-size-20">
                            <i class="bx bx-bookmark"></i>
                        </span>
                    </div>
                    <a href=""><h5 class="font-size-16 text-info mb-0">Average Locations</h5></a>
                </div>
                <div class="text-muted mt-2">
                    <h4>1,452</h4>
                    <div class="d-flex">
                        <span class="text-truncate">Average Locations per Customer</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="card">
            <div class="card-body bg-soft-success">
                <div class="d-flex align-items-center mb-2">
                    <div class="avatar-xs mr-3">
                        <span class="avatar-title rounded-circle bg-soft-primary text-primary font-size-20">
                            <i class="bx bx-bookmark"></i>
                        </span>
                    </div>
                    <a href=""><h5 class="font-size-16 text-info mb-0">Average Months</h5></a>
                </div>
                <div class="text-muted mt-2">
                    <h4>1,452</h4>
                    <div class="d-flex">
                        <span class="text-truncate">Average Months Since Start </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="card">
            <div class="card-body bg-soft-primary">
                <div class="d-flex align-items-center mb-2">
                    <div class="avatar-xs mr-3">
                        <span class="avatar-title rounded-circle bg-soft-primary text-primary font-size-20">
                            <i class="bx bx-bookmark"></i>
                        </span>
                    </div>
                    <a href=""><h5 class="font-size-16 text-info mb-0">Average TE-5000 Rate</h5></a>
                </div>
                <div class="text-muted mt-2">
                    <h4>1,452</h4>
                    <div class="d-flex">
                        <span class="text-truncate">Average TE-5000 Rate for TE-5000 Only Customers</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="card">
            <div class="card-body bg-soft-success">
                <div class="d-flex align-items-center mb-2">
                    <div class="avatar-xs mr-3">
                        <span class="avatar-title rounded-circle bg-soft-primary text-primary font-size-20">
                            <i class="bx bx-bookmark"></i>
                        </span>
                    </div>
                    <a href=""><h5 class="font-size-16 text-info mb-0">Average Monthly Rate</h5></a>
                </div>
                <div class="text-muted mt-2">
                    <h4>1,452</h4>
                    <div class="d-flex">
                        <span class="text-truncate">Average Monthly Rate for Hybrid Customers</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="card">
            <div class="card-body bg-soft-primary">
                <div class="d-flex align-items-center mb-2">
                    <div class="avatar-xs mr-3">
                        <span class="avatar-title rounded-circle bg-soft-primary text-primary font-size-20">
                            <i class="bx bx-bookmark"></i>
                        </span>
                    </div>
                    <a href=""><h5 class="font-size-16 text-info mb-0">Average Contracted Revenue</h5></a>
                </div>
                <div class="text-muted mt-2">
                    <h4>1,452</h4>
                    <div class="d-flex">
                        <span class="text-truncate">Average Contracted Monthly Revenue</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="card">
            <div class="card-body bg-soft-success">
                <div class="d-flex align-items-center mb-2">
                    <div class="avatar-xs mr-3">
                        <span class="avatar-title rounded-circle bg-soft-primary text-primary font-size-20">
                            <i class="bx bx-bookmark"></i>
                        </span>
                    </div>
                    <a href=""><h5 class="font-size-16 text-info mb-0">Average Term Length</h5></a>
                </div>
                <div class="text-muted mt-2">
                    <h4>1,452</h4>
                    <div class="d-flex">
                        <span class="text-truncate">Average number of months of locations' term</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="card">
            <div class="card-body bg-soft-primary">
                <div class="d-flex align-items-center mb-2">
                    <div class="avatar-xs mr-3">
                        <span class="avatar-title rounded-circle bg-soft-primary text-primary font-size-20">
                            <i class="bx bx-bookmark"></i>
                        </span>
                    </div>
                    <a href=""><h5 class="font-size-16 text-info mb-0">Number of Industries</h5></a>
                </div>
                <div class="text-muted mt-2">
                    <h4>1,452</h4>
                    <div class="d-flex">
                        <span class="text-truncate">Count of industries serviced</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="card">
            <div class="card-body bg-soft-success">
                <div class="d-flex align-items-center mb-2">
                    <div class="avatar-xs mr-3">
                        <span class="avatar-title rounded-circle bg-soft-primary text-primary font-size-20">
                            <i class="bx bx-bookmark"></i>
                        </span>
                    </div>
                    <a href=""><h5 class="font-size-16 text-info mb-0">Number of States</h5></a>
                </div>
                <div class="text-muted mt-2">
                    <h4>1,452</h4>
                    <div class="d-flex">
                        <span class="text-truncate">Total count of states serviced</span>
                    </div>
                </div>
            </div>
        </div>
    </div> -->


    <!-- <div class="col-xl-4">
        <div class="card bg-soft-primary">
            <div>
                <div class="row">
                    <div class="col-7">
                        <div class="text-white p-3">
                            <h5 class="text-white">Welcome Back !</h5>
                            <p>Skote Saas Dashboard</p>

                            <ul class="pl-3 mb-0">
                                <li class="py-1">7 + Layouts</li>
                                <li class="py-1">Multiple apps</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-5 align-self-end">
                        <img src="assets/images/profile-img.png" alt="" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </div> -->

        <!-- end row -->
    </div>
</div>

@else
<div class="row">
<div class="col-sm-12">
<div class="alert alert-warning" role="alert">
  Sorry! you don't have permission to view dashboard
</div>
</div>

</div>
@endif



@endsection
