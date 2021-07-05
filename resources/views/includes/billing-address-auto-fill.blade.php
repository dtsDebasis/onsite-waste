
	<div class="col-md-12">
		<h3 class="custom-heading">Billing Address <span style="float:right"><input type="checkbox" name="same" onclick="copyAddress()" class="copy-address"> Same as Shipping <span></h3>
	</div>

	<div class="col-md-6">
		<div class="form-group">
			<label>Address Line 1:</label>
			<!--  Avoid the word "address" in id, name, or label text to avoid browser autofill from conflicting with Place Autocomplete. Star or comment bug https://crbug.com/587466 to request Chromium to honor autocomplete="off" attribute.  -->
			<input
				class="form-control"
				id="addressline1_b"
				name="addressline1_b"
				autocomplete="off"
				value="{{ $billingaddress->addressline1 ?? '' }}"
			  />
		</div>
	</div>

	<div class="col-md-6"  >
		<div class="form-group">
			<label>Address Line 2</label>
			<input  class="form-control" id="address1_b" name="address1_b"  value="{{ $billingaddress->address1  ?? '' }}" />
		</div>
	</div>
	
	<div class="col-md-6" style="display:none;" >
		<div class="form-group">
			<span class="form-label">Street/ locality</span>
			<input  class="form-control" id="address2_b" name="address2_b" value="{{ $billingaddress->address2 ?? '' }}" />
		</div>
	</div>
	
	<div class="col-md-6" style="display:none;" >
		<div class="form-group">
			<span class="form-label">City*</span>
			<input class="form-control" id="locality_b" name="locality_b" value="{{ $billingaddress->locality ?? '' }}"/>
		</div>
	</div>
	
	<div class="col-md-6" style="display:none;" >
		<div class="form-group">
			<span class="form-label">State/Province*</span>
			<input class="form-control" id="state_b" name="state_b" value="{{ $billingaddress->state ?? '' }}"/>
		</div>
	</div>
	
	<div class="col-md-6" style="display:none;" >
		<div class="form-group">
			<span class="form-label">Postal code*</span>
			<input class="form-control" id="postcode_b" name="postcode_b" value="{{ $billingaddress->postcode ?? '' }}"/>
		</div>
	</div>
	
	<div class="col-md-6" style="display:none;" >
		<div class="form-group">
			<span class="form-label">Country/Region*</span>
			<input class="form-control" id="country_b" name="country_b" value="{{ $billingaddress->country ?? '' }}"  />
		</div>
	</div>

	<div class="col-md-6" style="display:none;" >
		<div class="form-group">
			<span class="form-label">Latitude*</span>
			<input class="form-control" id="latitude_b" name="latitude_b" value="{{ $billingaddress->latitude ?? '' }}"/>
		</div>
	</div>

	<div class="col-md-6" style="display:none;" >
		<div class="form-group">
			<span class="form-label">Longitude*</span>
			<input class="form-control" id="longitude_b" name="longitude_b" value="{{ $billingaddress->longitude ?? '' }}"/>
		</div>
	</div>
	




