@if(isset($data) && $data)
@section('card-footer')
<div class="card-footer">
	<div class="row">
		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 m-b-15 font-bold">Total records: {{ $data->count() }}</div>
		<div class="col-lg-8 col-md-8 col-sm-6 col-xs-12 text-right align-right m-b-15">{{ $data->appends(request()->input())->links() }}</div>
		<div class="clearfix"></div> 
	</div>
</div>
@endsection
@endif