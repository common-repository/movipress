(function($){
	
	var options = {
		
	    defaultColor: false,
	    // A callback to fire whenever the color changes to a valid color
	    change: function(event, ui){
		    
		    var color = ui.color.toString();
		    
		    switch(event.target.id) {
			    
			    case 'bg_color':
			        $('.mpio-container').css('background-color', color);
			        break;
			    case 'item_background_color':
			        $('.mpio-card-main').css('background-color', color);
			        break;
			    case 'toolbar_color':
			        $('.mpio-toolbar').css('background-color', color);
			        break;
			    case 'toolbar_icons_color':
					$('.mpio-toolbar-title').css('color', color);
					$('.svg-colorize').css({ fill: color });
			        break;
			    case 'item_title_text_color':
			    	setTextColor($('.mpio-card-title'), color);
			        break;
			    case 'item_date_text_color':
				    setTextColor($('.mpio-card-author-date'), color);
			        break;
			    case 'item_excerpt_text_color':
				    setTextColor($('.mpio-card-excerpt'), color);
			        break;		      		          
			    default:
			        break;		        
			}
    },
    clear: function() {},
    hide: true,
    palettes: true
	};
	
	$('#mpio-phone-accordion').accordion({
		heightStyle: "content",
	});

    $(function() {
        $( '.mpio-color-picker' ).wpColorPicker(options);
    });
    
    // Aproach to convert sp to px for the phone preview
    function sp2pxAproach(sp){
    	return (sp*1)+'px';
   	}

	    
    function getImgHeight(){
	    var spinner = $( '#item_img_height' ).spinner();
		return spinner.spinner('value')+'px';
	}
    
    
	function setTextAlign(elem, value){
		elem.css('text-align', value);
	}
	
	function setTextColor(elem, value){
		elem.css('color', value);
	}
	
 	function setTextSize(elem, value){
		elem.css('font-size', sp2pxAproach(value.spinner('value')));
	}
	   
 	function handleStatusbar(elem) {
		if(elem.prop('checked')==true){
			$('.statusbar_color').css('visibility', 'visible');
        }else{
			$('.statusbar_color').css('visibility', 'collapse');
        }
	}    
    
    function cancelImgBackground() {
	   $('.mpio-card-main').css('display', 'block');  
	   $('.mpio-card-main').css('align-items', 'stretch');
	     
	   $('.mpio-card-media').css('display', 'block');
	   $('.mpio-card-main').css('background-image', 'none');
	   $('.mpio-card-main').css('height', 'auto');
	   $('.mpio-card-main').css('min-height', '0px');
	   	
	   $('.item_img_height').css('visibility', 'visible');		
	   $('.item_img_border').css('visibility', 'visible');
	   $('.item_vertical_align').css('visibility', 'collapse');
	}
    
    function setToolbarTitle(elem){
	    var value = elem.val();
	    //Content title
	    if(value == 0){
			var content_title = $('.this-content-title').data('content');
			$('.mpio-toolbar-title').text(content_title);
			$('.toolbar_custom_title').css('visibility', 'collapse');   
	    //Custom title
	    }else if(value == 1){
		    $('.mpio-toolbar-title').text($('#toolbar_title').val());
		    $('.toolbar_custom_title').css('visibility', 'visible');   
	    }
	}

	
    function setupItem(elem){
	    if(elem.prop('checked')==true){
			$('.item_border').css('visibility', 'visible');			
        }else{
			$('.item_border').css('visibility', 'collapse');
        }
	}
    
    
    function setupImg(elem){
	    var itemTemplate = elem;

	    if(itemTemplate!='item_img_background'){
		    cancelImgBackground();
	    }
	    
	    $('.mpio-card-media').css('margin-right', '0px');
	    $('.mpio-card-media').css('margin-left', '0px');
	    $('.mpio-card-main').css('height', 'auto');	    
	    switch(itemTemplate){

		    case 'item_img_top':
		        $('.mpio-card-media').each(function() {
			        $(this).prependTo($(this).closest($('.mpio-card-main')));
    			});
		        $('.mpio-card-media').css('width', '100%');				
		        $('.mpio-card-media').css('float', 'none');
				$('.mpio-card-media').css('height', getImgHeight());	        
		        break;
		        
		    case 'item_img_middle':
		    
		        $('.mpio-card-media').each(function() {
			        $(this).appendTo($(this).parent().children('.mpio-card-group').children('.mpio-card-author-date'));
    			});
		        
		        $('.mpio-card-media').css('width', '100%');				
		        $('.mpio-card-media').css('float', 'none');
				$('.mpio-card-media').css('height', getImgHeight());	        
		        break;
		        
		    case 'item_img_left':
			    $('.mpio-card-media').each(function() {
			        $(this).appendTo($(this).closest($('.mpio-card-main')).children('.mpio-card-group').children('.mpio-card-title'));
    			});
				$('.mpio-card-media').css('float', 'left');
				$('.mpio-card-media').css('width', '40%');				
				$('.mpio-card-media').css('height', '100px');
				$('.mpio-card-media').css('margin-right', '10px');
	            $('.item_img_height').css('visibility', 'collapse');				
		        break;
		        
			case 'item_img_left_top':
			    $('.mpio-card-media').each(function() {
				    $(this).prependTo($(this).closest($('.mpio-card-main')));
    			});
				$('.mpio-card-media').css('float', 'left');				
				$('.mpio-card-media').css('width', '40%');				
				$('.mpio-card-media').css('height', '100px');
				$('.mpio-card-media').css('margin-right', '10px');
				$('.mpio-card-main').css('min-height', '100px');
	            $('.item_img_height').css('visibility', 'collapse');				
		        break;
		        
		    case 'item_img_right':
				$('.mpio-card-media').each(function() {
			        $(this).appendTo($(this).closest($('.mpio-card-main')).children('.mpio-card-group').children('.mpio-card-title'));
    			});
				$('.mpio-card-media').css('float', 'right');
				$('.mpio-card-media').css('width', '40%');				
				$('.mpio-card-media').css('height', '100px');
				$('.mpio-card-media').css('margin-left', '10px');
	            $('.item_img_height').css('visibility', 'collapse');				
		        break;

		    case 'item_img_right_top':
				$('.mpio-card-media').each(function() {
			        $(this).prependTo($(this).closest($('.mpio-card-main')));
    			});
				$('.mpio-card-media').css('float', 'right');
				$('.mpio-card-media').css('width', '40%');				
				$('.mpio-card-media').css('height', '100px');
				$('.mpio-card-main').css('min-height', '100px');
				$('.mpio-card-media').css('margin-left', '10px');
	            $('.item_img_height').css('visibility', 'collapse');
		        break;
		        		        
		    case 'item_img_bottom':
		        $('.mpio-card-media').each(function() {
			        $(this).appendTo($(this).closest($('.mpio-card-main')));
    			});
		        $('.mpio-card-media').css('width', '100%');				
		        $('.mpio-card-media').css('float', 'none');
				$('.mpio-card-media').css('height', getImgHeight());	        
		        break;

		    case 'item_img_background':
		    	$('.img-url').each(function() {
					var img = $(this).data('img');
					$(this).parent().css('background-image', 'url('+img+')');
					 
    			});
				setupDataVerticalAlign($('#item_data_vertical_align').val());
    			$('.mpio-card-media').css('display', 'none');
    			$('.mpio-card-main').css('min-height', '130px');
				$('.mpio-card-main').css('height', getImgHeight());
				$('.item_img_height').css('visibility', 'visible');		
				$('.item_img_border').css('visibility', 'collapse');
				$('.item_vertical_align').css('visibility', 'visible');
		        break;
		        
		    case 'item_no_img':
	            $('.mpio-card-media').css('display', 'none');
            	$('.item_img_border').css('visibility', 'collapse');
				$('.item_img_height').css('visibility', 'collapse');		
		        break;		        
		    default:
		        break;		        
		}  
		
    }
    
    function setupImgHeight(elem) {
	    if($('#item_template').val() == 'item_img_background'){
			$('.mpio-card-main').css('height', elem.spinner('value')+'px');
		}else{
			$('.mpio-card-media').css('height', elem.spinner('value')+'px');			
		}
	}
	
	function setupDataVerticalAlign(elem) {
		if($('#item_template').val()=='item_img_background'){
		    $('.mpio-card-main').css('display', 'flex');    
	    	if(elem == 'top'){
			    $('.mpio-card-main').css('align-items', 'flex-start'); 
		    }else if(elem == 'middle'){
				$('.mpio-card-main').css('align-items', 'center'); 
		    }else if(elem == 'bottom'){
				$('.mpio-card-main').css('align-items', 'flex-end'); 
			}
		}
	}
	
	
	function setupTitle(elem) {
		if(elem.prop('checked')==true){
			$('.mpio-card-title').css('display', 'block');
			$('.item_title_text_font').css('visibility', 'visible');
			$('.item_title_text_align').css('visibility', 'visible');
			$('.item_title_text_size').css('visibility', 'visible');
            $('.item_title_text_color').css('visibility', 'visible');
        }else{
			$('.mpio-card-title').css('display', 'none');
			$('.item_title_text_font').css('visibility', 'collapse');
			$('.item_title_text_align').css('visibility', 'collapse');
			$('.item_title_text_size').css('visibility', 'collapse');
            $('.item_title_text_color').css('visibility', 'collapse');
        }
	}    
	
	
	function setupAuthorDate(author,date){
		var author = author.prop('checked');
		var date = date.prop('checked');
		if(!author&&!date){
			$('.mpio-span-author').css('display', 'none');
			$('.mpio-span-date').css('display', 'none');
			$('.mpio-span-separator').css('display', 'none');
			
			$('.item_date_text_font').css('visibility', 'collapse');
			$('.item_date_text_align').css('visibility', 'collapse');
			$('.item_date_text_size').css('visibility', 'collapse');
			$('.item_date_text_color').css('visibility', 'collapse');
		}else{
			$('.item_date_text_font').css('visibility', 'visible');
			$('.item_date_text_align').css('visibility', 'visible');
			$('.item_date_text_size').css('visibility', 'visible');
			$('.item_date_text_color').css('visibility', 'visible');
			if(author&&date){
				$('.mpio-span-author').css('display', 'inline');
				$('.mpio-span-separator').css('display', 'inline');
				$('.mpio-span-date').css('display', 'inline');	
			}else{
				if(author){
					$('.mpio-span-author').css('display', 'inline');
					$('.mpio-span-date').css('display', 'none');
				}else{
					$('.mpio-span-author').css('display', 'none');
					$('.mpio-span-date').css('display', 'inline');	
				}
				$('.mpio-span-separator').css('display', 'none');
			}
		}
	}
	
	
	function setupExcerpt(elem){
		if (elem.prop('checked') == true) {
			$('.mpio-card-excerpt').css('display', 'block');
			$('.item_excerpt_text_font').css('visibility', 'visible');
			$('.item_excerpt_text_align').css('visibility', 'visible');
			$('.item_excerpt_text_size').css('visibility', 'visible');
			$('.item_excerpt_text_color').css('visibility', 'visible');	
        } else {
			$('.mpio-card-excerpt').css('display', 'none');
			$('.item_excerpt_text_font').css('visibility', 'collapse');
			$('.item_excerpt_text_align').css('visibility', 'collapse');
			$('.item_excerpt_text_size').css('visibility', 'collapse');
			$('.item_excerpt_text_color').css('visibility', 'collapse');	
        }
	}
	
	
    // General
    $('#show_statusbar').live('click', function () {
		handleStatusbar($(this));  
    });

    $('#list_border').on('spinstop', function(){
	    $('.mpio-container').css('padding', '0px '+sp2pxAproach($('#list_border').spinner('value')));
	} );
    
    // Toolbar
    $('#toolbar_title_type').change(function() {
	    setToolbarTitle($(this));
	});
    $('#toolbar_title').bind('input', function() {
	    $('.mpio-toolbar-title').text($(this).val());
	});
    $('#toolbar_text_align').change(function() {
	   setTextAlign($('.mpio-toolbar-title'),$(this).val());
	});
    $('#toolbar_text_size').on('spinstop', function(){
	    setTextSize($('.mpio-toolbar-title'),$(this));
	});
	
	// Item layout
    $('#item_template').change(function(){
	    setupImg($(this).val());
	});
	
	$('#item_custom_border').live('click', function () {
		setupItem($(this));  
    });
	
	// Featured Img
	$('#item_img_height').on('spinstop', function(){
		setupImgHeight($(this));
	});
	$('#item_img_border').on('spinstop', function(){
		$('.mpio-card-media').css('padding', $(this).spinner('value')+'px');
	});
    $('#item_data_vertical_align').change(function() {
	    setupDataVerticalAlign($(this).val());
	});
	
	
	// Title
	$('#item_show_title').live('click', function () {
		setupTitle($(this));  
    });
    $('#item_title_text_align').change(function() {
	   setTextAlign($('.mpio-card-title'), $(this).val());
	});
    $('#item_title_text_size').on('spinstop', function(){
	    setTextSize($('.mpio-card-title'),$(this));
	});



	// Author and date
	$('#item_show_author').live('click', function () {
		setupAuthorDate($('#item_show_author'),$('#item_show_date'));
	});
	$('#item_show_date').live('click', function () {
		setupAuthorDate($('#item_show_author'),$('#item_show_date'));
	});
    $('#item_date_text_align').change(function() {
	   setTextAlign($('.mpio-card-author-date'), $(this).val());
	});
    $('#item_date_text_size').on('spinstop', function(){
	    setTextSize($('.mpio-card-author-date'),$(this));
	} );
	
	
	
	// Excerpt
	$('#item_show_excerpt').live('click', function () {
        setupExcerpt($(this));
    });
    $('#item_excerpt_text_align').change(function() {
	   setTextAlign($('.mpio-card-excerpt'), $(this).val());
	});
    $('#item_excerpt_text_size').on('spinstop', function(){
	    setTextSize($('.mpio-card-excerpt'),$(this));
	});	


    // General
    function initGeneral(){
		handleStatusbar($('#show_statusbar'));	    
	    $('#list_border').spinner({min: 0, max: 14});	   
	    $('.mpio-container').css('padding', '0px '+sp2pxAproach($('#list_border').spinner('value')));
	    $('.mpio-container').css('background-color', $('#bg_color').val());
	    $('#list_template_name').attr('size', '20');
	}
	
	// Item
    function initItem(){
	    setupItem($('#item_custom_border'));
	    $('#item_border').spinner({min: 0, max: 14});	   
	    $('.mpio-card-main').css('background-color', $('#item_background_color').val());
    }
    // Toolbar
    function initToolbar(elem){
	    $('#toolbar_text_size').spinner({min: 8, max: 24});	   
	    $('#toolbar_title').attr('size', '18');
	    $('.mpio-toolbar').css('background-color', $('#toolbar_color').val());
	    $('.svg-colorize').css({ fill: $('#toolbar_icons_color').val() });

	    setToolbarTitle($('#toolbar_title_type'));
	    setTextColor(elem,$('#toolbar_icons_color').val());
		setTextAlign(elem, $('#toolbar_text_align').val());
	    setTextSize(elem, $('#toolbar_text_size'));
	}
    
    // Featured Img
    function initFeaturedImg(){
		$('#item_img_height').spinner({min: 0, max: 400});	    
		$('#item_img_border').spinner({min: 0, max: 20});	   
		$('.mpio-card-media').css('padding', $('#item_img_border').spinner('value')+'px'); 
	    setupImgHeight($('#item_img_height'));
	    setupImg($('#item_template').val());   
    }
    
    
	// Title
    function initTitle(elem){
	    $('#item_title_text_size').spinner({min: 8, max: 30});	  
		setupTitle($('#item_show_title'));
		setTextColor(elem, $('#item_title_text_color').val());
		setTextAlign(elem, $('#item_title_text_align').val());
		setTextSize(elem, $('#item_title_text_size'));
	}	
	
	
	// Author and date
	function initAuthorDate(elem){
		$( '#item_date_text_size' ).spinner({min: 8, max: 18});	  
		setupAuthorDate($('#item_show_author'),$('#item_show_date'));
		setTextColor(elem,$('#item_date_text_color').val());
		setTextAlign(elem, $('#item_date_text_align').val());
		setTextSize(elem,$('#item_date_text_size'));
	}


	// Excerpt
	function initExcerpt(elem){
		$('#item_excerpt_text_size').spinner({min: 8, max: 28});	  
		setupExcerpt($('#item_show_excerpt'));
		setTextColor(elem,$('#item_excerpt_text_color').val());
		setTextAlign(elem, $('#item_excerpt_text_align').val());
		setTextSize(elem,$('#item_excerpt_text_size'));
	}


    function initPhoneStyle(){
	    initGeneral();
	    initItem();
	    initToolbar($('.mpio-toolbar-title'));
	    initFeaturedImg();
	    initTitle($('.mpio-card-title'));
	    initAuthorDate($('.mpio-card-author-date'));
	    initExcerpt($('.mpio-card-excerpt'));
	    $('.mpio-phone-wrapper').css('display', 'block');
	}initPhoneStyle();
    
    
    
    
	// On click open accordion option     
	$('.mpio-container').click(function(){
		$('.mpio-accordion-general').click();
	});    
	$('.mpio-toolbar').click(function(){
		$('.mpio-accordion-toolbar').click();
	});
	$('.mpio-card-media').click(function(){
		$('.mpio-accordion-image').click();
	});
	$('.mpio-card-title').click(function(){
		$('.mpio-accordion-title').click();
	});
	$('.mpio-card-author-date').click(function(){
		$('.mpio-accordion-date').click();
	});
	$('.mpio-card-excerpt').click(function(){
		$('.mpio-accordion-content').click();
	});

    
})(jQuery);