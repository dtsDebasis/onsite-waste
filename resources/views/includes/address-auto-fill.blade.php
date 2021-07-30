
	<div class="col-md-12">
		<h3 class="custom-heading"> {{isset($shipping)?'Shipping ':''}}Address </h3>
	</div>

	<div class="col-md-6">
		<div class="form-group">
			<label>Address Line 1 *:</label>
			<!--  Avoid the word "address" in id, name, or label text to avoid browser autofill from conflicting with Place Autocomplete. Star or comment bug https://crbug.com/587466 to request Chromium to honor autocomplete="off" attribute.  -->
			<input
				class="form-control"
				id="addressline1"
				name="addressline1"
				required
				autocomplete="off"
				value="{{ $addressdata->addressline1 ?? '' }}"
			  />
		</div>
	</div>

	<div class="col-md-6"  >
		<div class="form-group">
			<label>Address Line 2</label>
			<input  class="form-control" id="address1" name="address1"  value="{{ $addressdata->address1  ?? '' }}" />
		</div>
	</div>

	<div class="col-md-6" style="display:none;" >
		<div class="form-group">
			<span class="form-label">Street/ locality</span>
			<input  class="form-control" id="address2" name="address2" value="{{ $addressdata->address2 ?? '' }}" />
		</div>
	</div>

	<div class="col-md-6" style="display:none;" >
		<div class="form-group">
			<span class="form-label">City*</span>
			<input class="form-control" id="locality" name="locality" value="{{ $addressdata->locality ?? '' }}"/>
		</div>
	</div>

	<div class="col-md-6" style="display:none;" >
		<div class="form-group">
			<span class="form-label">State/Province*</span>
			<input class="form-control" id="state" name="state" value="{{ $addressdata->state ?? '' }}"/>
		</div>
	</div>

	<div class="col-md-6" style="display:none;" >
		<div class="form-group">
			<span class="form-label">Postal code*</span>
			<input class="form-control" id="postcode" name="postcode" value="{{ $addressdata->postcode ?? '' }}"/>
		</div>
	</div>

	<div class="col-md-6" style="display:none;" >
		<div class="form-group">
			<span class="form-label">Country/Region*</span>
			<input class="form-control" id="country" name="country" value="{{ $addressdata->country ?? '' }}"  />
		</div>
	</div>

	<div class="col-md-6" style="display:none;" >
		<div class="form-group">
			<span class="form-label">Latitude*</span>
			<input class="form-control" id="latitude" name="latitude" value="{{ $addressdata->latitude ?? '' }}"/>
		</div>
	</div>

	<div class="col-md-6" style="display:none;" >
		<div class="form-group">
			<span class="form-label">Longitude*</span>
			<input class="form-control" id="longitude" name="longitude" value="{{ $addressdata->longitude ?? '' }}"/>
		</div>
	</div>





