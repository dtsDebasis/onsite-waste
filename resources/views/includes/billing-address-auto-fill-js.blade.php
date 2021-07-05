
<script>
let autocomplete_b;
let branchAddressField_b;
let address1Field_b;
let address2Field_b;
let postalField_b;

function initAutocomplete2() {
	branchAddressField_b = document.querySelector("#addressline1_b");
	address1Field_b = document.querySelector("#address1_b");
	address2Field_b = document.querySelector("#address2_b");
	postalField_b = document.querySelector("#postcode_b");
	// Create the autocomplete object, restricting the search predictions to
	// addresses in the US and Canada.
	autocomplete_b = new google.maps.places.Autocomplete(branchAddressField_b, {
		//componentRestrictions: { country: ["us", "ca"] },
		fields: ["address_components", "geometry"],
		types: ["address"],
	});
	branchAddressField_b.focus();
	// When the user selects an address from the drop-down, populate the
	// address fields in the form.
	autocomplete_b.addListener("place_changed", fillInAddress_b);
}

function fillInAddress_b() {
	// Get the place details from the autocomplete object.
	const place = autocomplete_b.getPlace();
	let address1_b = "";
	let postcode_b = "";
	// console.log(place.geometry.location.lat());
	document.querySelector("#latitude_b").value = place.geometry.location.lat()
	document.querySelector("#longitude_b").value = place.geometry.location.lng()

	// Get each component of the address from the place details,
	// and then fill-in the corresponding field on the form.
	// place.address_components are google.maps.GeocoderAddressComponent objects
	// which are documented at http://goo.gle/3l5i5Mr
	for (const component of place.address_components) {
		console.log(component);
		const componentType = component.types[0];

		switch (componentType) {
			case "street_number": {
				if (address1_b == "") {
					address1_b = `${component.long_name} ${address1_b}`;
				} else {
					address1_b += ", " + component.long_name;
				}

				break;
			}

			case "premise": {
				if (address1_b == "") {
					address1_b = `${component.long_name} ${address1_b}`;
				} else {
					address1_b += ", " + component.long_name;
				}
				break;
			}

			case "route": {
				if (address1_b == "") {
					address1_b = `${component.long_name} ${address1_b}`;
				} else {
					address1_b += ", " + component.long_name;
				}
				break;
			}

			case "sublocality_level_3": {
				if (address1_b == "") {
					address1_b = `${component.long_name} ${address1_b}`;
				} else {
					address1_b += ", " + component.long_name;
				}
				break;
			}

			case "sublocality_level_2": {
				if (address1_b == "") {
					address1_b = `${component.long_name} ${address1_b}`;
				} else {
					address1_b += ", " + component.long_name;
				}
				break;
			}

			case "sublocality_level_1": {
				if (address1_b == "") {
					address1_b = `${component.long_name} ${address1_b}`;
				} else {
					address1_b += ", " + component.long_name;
				}
				break;
			}

			case "postal_code": {
				postcode_b = `${component.long_name}${postcode_b}`;
				break;
			}

			case "postal_code_suffix": {
				postcode_b = `${postcod_b}-${component.long_name}`;
				break;
			}
			case "locality":
				document.querySelector("#locality_b").value = component.long_name;
				break;

			case "administrative_area_level_1": {
				document.querySelector("#state_b").value = component.short_name;
				break;
			}
			case "country":
				document.querySelector("#country_b").value = component.long_name;
				break;
		}
	}
	// console.log(address1)
	address2Field_b.value = address1_b;
	postalField_b.value = postcode_b;
	// After filling the form with address components from the Autocomplete
	// prediction, set cursor focus on the second address line to encourage
	// entry of subpremise information such as apartment, unit, or floor number.
	address1Field_b.focus();
}

function copyAddress(){
	document.querySelector("#addressline1_b").value = document.querySelector("#addressline1").value;
	document.querySelector("#address1_b").value = document.querySelector("#address1").value;	
	document.querySelector("#address2_b").value = document.querySelector("#address2").value;
	document.querySelector("#locality_b").value = document.querySelector("#locality").value ;
	document.querySelector("#state_b").value = document.querySelector("#state").value;
	document.querySelector("#postcode_b").value = document.querySelector("#postcode").value;
	document.querySelector("#country_b").value = document.querySelector("#country").value;
	document.querySelector("#latitude_b").value = document.querySelector("#latitude").value;
	document.querySelector("#longitude_b").value = document.querySelector("#longitude").value;
}

</script>