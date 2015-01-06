 /*jQuery(document).ready(function() {
                    var fromDate = jQuery("#delivery_date, #data_wydania").datepicker({
                        defaultDate: "+1w",
                        numberOfMonths: 2,
                        dateFormat: "yy-mm-dd",
                        //minDate: new Date(),
                        onSelect: function(selectedDate){
                            if(this.id == 'delivery_date'){
                                //var dateMin = jQuery('#delivery_date').datepicker('getDate');
                                var rMin = new Date(dateMin.getFullYear(), dateMin.getMonth(),dateMin.getDate()); // Min Date = Selected + 1d
                                var rMax = new Date(dateMin.getFullYear(), dateMin.getMonth(),dateMin.getDate() + 31); // Max Date = Selected + 31d
                                jQuery('#delivery_date').datepicker("option","minDate", rMin);
                                jQuery('#data_wydania').datepicker("option","maxDate", rMax);
                                //jQuery('#data_wydania').val(jQuery.datepicker.formatDate('yy-mm-dd', new Date(rMax)));

                            }
                        }
                    });

                });

*/

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