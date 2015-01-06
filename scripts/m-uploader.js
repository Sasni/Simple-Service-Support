
/*jQuery(document).ready(function() {
jQuery('.upload_button').click(function() {
    uploadID = jQuery(this).prev('input'); // grabs the correct field
    spanID = jQuery(this).parent().find('span'); // grabs the correct span
    formfield = jQuery('.frp_upload').attr('name');
    tb_show('', 'media-upload.php?type=image&TB_iframe=true');
    return false;
});

window.send_to_editor = function(html) {
    imgurl = jQuery('img', html).attr('src'); // grabs the image URL from the IMG tag
    jQuery('.frp_upload').val(imgurl); // wyciąga adres obrazka i wrzuca do pola tekstowego
    uploadID.val(imgurl); // sends the image URL to the hidden input field
    spanID.html(html); // sends the IMG tag to the preview span
    tb_remove();
}

});
*/



/* jQuery(document).ready(function() {
	var formfield;
	jQuery('.upload_image_button').click(function() {
		jQuery('html').addClass('Image');
		formfield = jQuery(this).prev().attr('name');
		tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
	return false;
});

window.original_send_to_editor = window.send_to_editor;
window.send_to_editor = function(html){
	if (formfield) {
		fileurl = jQuery('img',html).attr('src');
		jQuery('#'+formfield).val(fileurl);
		tb_remove();
		jQuery('html').removeClass('Image');
	} else {
		window.original_send_to_editor(html);
	}
};

});*/

jQuery(document).ready(function($){
  
    var custom_uploader;
    var formfield;
  
    $('.upload_image_button').click(function(e) {
 
        e.preventDefault();
        formfield = jQuery(this).prev().attr('name');
 
        //If the uploader object has already been created, reopen the dialog
        if (custom_uploader) {
            custom_uploader.open();
            return;
        }
 
       //Extend the wp.media object
        custom_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose Image',
            button: {
                text: 'Choose Image'
            },
            multiple: false
        });
 
        //When a file is selected, grab the URL and set it as the text field's value
        custom_uploader.on('select', function() {
            attachment = custom_uploader.state().get('selection').first().toJSON();
           
		    $('#'+formfield).val(attachment.url);
            /*$('#image_1').val(attachment.url);*/
        });
 
        //Open the uploader dialog
        custom_uploader.open();
 
    });
 
 
});