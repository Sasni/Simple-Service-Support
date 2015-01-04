jQuery(document).ready(function() {
	jQuery( "#delivery_date" ).datepicker({
		defaultDate: "+1w",
		changeMonth: true,
	
		dateFormat: "yy-mm-dd",
		numberOfMonths: 2,
		onClose: function( selectedDate ) {
			jQuery( "#data_wydania" ).datepicker( "option", "minDate", selectedDate );
		}
	});
	jQuery( "#data_wydania" ).datepicker({
		defaultDate: "+1w",
		changeMonth: true,
		dateFormat: "yy-mm-dd",
		numberOfMonths: 2,
		onClose: function( selectedDate ) {
			jQuery( "#delivery_date" ).datepicker( "option", "maxDate", selectedDate );
		}
	});
});
