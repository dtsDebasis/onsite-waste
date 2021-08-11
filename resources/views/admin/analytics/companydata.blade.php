@extends('admin.layouts.layout')


@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="tab-content mt-3 text-muted">
                    <div class="tab-pane active" id="home1" role="tabpanel">
                    <div class="table-responsive">
            <table class="table table-centered table-condensed table-striped table-nowrap mb-4">
                <thead class="thead-light">
                    <tr>
                        <th>Customer </th>
                        <th>Group Name </th>
                        <th>Location Count </th>
                        <th>Color Code </th>
                        <th>Normalization Type </th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Onsite Waste Management</td>
                        <td>test</td>
                        <td>10</td>
                        <td><span style="height:10px;padding: 6px;display:block;width:50px;background:#92ee8c"></span></td>
                        <td>Normalization 1</td>
                        <td><span class="badge badge-pill badge-soft-success font-size-12">Enabled</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="table-responsive">
            <table class="table table-centered table-condensed table-striped table-nowrap mb-4">
                <thead class="thead-light">
                    <tr>
                        <th>Customer </th>
                        <th>Group Name </th>
                        <th>Location Count </th>
                        <th>Color Code </th>
                        <th>Normalization Type </th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Onsite Waste Management</td>
                        <td>test</td>
                        <td>10</td>
                        <td><span style="height:10px;padding: 6px;display:block;width:50px;background:#92ee8c"></span></td>
                        <td>Normalization 1</td>
                        <td><span class="badge badge-pill badge-soft-success font-size-12">Enabled</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@push('pagejs')
<script>

</script>
@endpush
