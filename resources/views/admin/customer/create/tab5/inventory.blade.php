@extends('admin.customer.create.createlayout')


@section('create-customer-content')
<!-- tab5 -->
<div class="tab-pane active" id="messages-2" role="tabpanel">
    <div class="card">
        <div class="card-body mb-4">
            <form>
                <div class="row">

                    <div class="col-sm-12">
                        <div class="d-flex flex-column flex-md-row justify-content-between custom-heading-wrapper align-items-center">
                        <h3>Inventory</h3>
                        <button type="button" class="btn btn-primary w-md" data-toggle="modal" data-target=".bs-example-modal-lg">Add Package</button>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table class="table table-centered table-nowrap mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Location</th>
                                        <th>TE 5000</th>
                                        <th>IMIE No.</th>
                                        <th>Container</th>
                                        <th>Re-Order Point</th>
                                        <th>Spinner</th>
                                        <th>Rocker</th>
                                        <th>Sharps (Old)</th>
                                        <th>Red Bag (Old)</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Super Package</td>
                                        <td>20</td>
                                        <td>14564</td>
                                        <td>150</td>
                                        <td>10</td>
                                        <td>5</td>
                                        <td>10</td>
                                        <td>20</td>
                                        <td>15</td>
                                        <td>
                                            <button type="button" class="btn btn-outline-primary waves-effect waves-light">Assign</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Super Package</td>
                                        <td>20</td>
                                        <td>14564</td>
                                        <td>150</td>
                                        <td>10</td>
                                        <td>5</td>
                                        <td>10</td>
                                        <td>20</td>
                                        <td>15</td>
                                        <td>
                                            <button type="button" class="btn btn-outline-primary waves-effect waves-light">Assign</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Super Package</td>
                                        <td>20</td>
                                        <td>14564</td>
                                        <td>150</td>
                                        <td>10</td>
                                        <td>5</td>
                                        <td>10</td>
                                        <td>20</td>
                                        <td>15</td>
                                        <td>
                                            <button type="button" class="btn btn-outline-primary waves-effect waves-light">Assign</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Super Package</td>
                                        <td>20</td>
                                        <td>14564</td>
                                        <td>150</td>
                                        <td>10</td>
                                        <td>5</td>
                                        <td>10</td>
                                        <td>20</td>
                                        <td>15</td>
                                        <td>
                                            <button type="button" class="btn btn-outline-primary waves-effect waves-light">Assign</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </form>
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
    if(/create\/inventory/.test(loc)) {
        $('.nav-link.inventory').addClass('active');
    }

});
</script>
@endsection('create-customer-content')