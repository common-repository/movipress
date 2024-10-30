jQuery(document).ready(function($){
	
	// Metabox tabs
    $("#mpio-metabox-tabs .hidden").removeClass('hidden');
    $("#mpio-metabox-tabs").tabs();
    
    // Show metabox or not
    if($('#mpio-show').prop("checked")) {
    	$('#mpio-metabox-tabs').show();
  	}else{
    	$('#mpio-metabox-tabs').hide();
  	}
	    
	$('#mpio-show').click(function() {
	    $("#mpio-metabox-tabs").toggle(this.checked);
	});
	
    // Show content editor or not
    if($('#mpio-alt-content').prop("checked")) {
    	$('#mpio-wp-editor').show();
  	}else{
    	$('#mpio-wp-editor').hide();
  	}
	    
	$('#mpio-alt-content').click(function() {
	    $("#mpio-wp-editor").toggle(this.checked);
	});
});
      