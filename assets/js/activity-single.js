(function( $ ) {
	
	var options = {
		
	    defaultColor: false,
	    // A callback to fire whenever the color changes to a valid color
	    change: function(event, ui){
		    
		    var color = ui.color.toString();
		    
		    switch(event.target.id) {
			    case 'single_bg_color':
			        $('.mpio-container').css('background-color', color);
			        break;
			    case 'single_item_color':
			        $('.mpio-card-main').css('background-color', color);
			        break;
			    case 'single_toolbar_color':
			        $('.mpio-toolbar').css('background-color', color);
			        break;
			    case 'single_toolbar_icons_color':
					$('.mpio-toolbar-title').css('color', color);
					$('.svg-colorize').css({ fill: color });
			        break;
			    case 'single_title_text_color':
					$('.mpio-card-title').css('color', color);
			        break;
			    case 'single_date_text_color':
					$('.mpio-card-author-date').css('color', color);
			        break;
			    case 'single_excerpt_text_color':
					$('.mpio-card-excerpt').css('color', color);
			        break;
			    case 'single_content_text_color':
					$('.mpio-card-content').css('color', color);
			        break;
			    case 'single_categories_text_color':
					$('.mpio-card-categories').css('color', color);
			        break;
			    case 'single_categories_color':
					$('.mpio-card-categories a').css('color', color);
			        break;
			    case 'single_tags_text_color':
					$('.mpio-card-tags').css('color', color);
			        break;
			    case 'single_tags_color':
					$('.mpio-card-tags a').css('color', color);
			        break;
			    case 'single_authors_text_color':
					$('.mpio-card-authors').css('color', color);
			        break;
			    case 'single_authors_background':
					$('.mpio-card-authors').css('background-color', color);
			        break;
			    case 'single_comments_text_color':
					$('.mpio-card-comments').css('color', color);
			        break;	
			    case 'single_comments_background':
			        $('.mpio-card-comments').css('background-color', color);
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
   
	function setTextAlign(elem, value){
		elem.css('text-align', value);
	}
	function setBorder(elem, value){
		elem.css('padding','0px '+sp2pxAproach(value.spinner('value')));
	}
	function setTextColor(elem, value){
		elem.css('color', value);
	}
	
 	function setTextSize(elem, value){
		elem.css('font-size', sp2pxAproach(value.spinner('value')));
	}

	function handleCard(elem) {
		if($('#single_show_'+elem).prop('checked')==true){
			$('.mpio-card-'+elem).css('display', 'block');
			$('.single_'+elem+'_input').css('visibility', 'visible');
        }else{
			$('.mpio-card-'+elem).css('display','none');
			$('.single_'+elem+'_input').css('visibility', 'collapse');
        }
	} 

	function handleStatusbar(elem) {
		if(elem.prop('checked')==true){
			$('.statusbar_color').css('visibility', 'visible');
        }else{
			$('.statusbar_color').css('visibility', 'collapse');
        }
	} 
	
	function handleGeneral(elem){
		setBorder(elem, $('#single_border'));
	}

    function setToolbarTitle(elem){
	    var value = elem.val();
	    //Content title
	    if(value == 0){
			var content_title = $('.this-content-title').data('content');
			$('.mpio-toolbar-title').text(content_title);
			$('.single_toolbar_custom_title').css('visibility', 'collapse');   
		//Custom title
	    }else if(value == 1){
		    $('.mpio-toolbar-title').text($('#single_toolbar_title').val());
		    $('.single_toolbar_custom_title').css('visibility', 'visible');   
	    }
	}
	
	
	function handleToolbar(elem){
	    if (elem.prop('checked') == true) {
	        $('.mpio-toolbar').css('display', 'block');
            $('.mpio-container').css('height', '545px');
            $('.single_toolbar_input').css('visibility', 'visible');
        } else {
	        $('.mpio-toolbar').css('display', 'none');
            $('.mpio-container').css('height', '599px');
			$('.single_toolbar_input').css('visibility', 'collapse');
        }
	}
		
	// Featured Image
    function handleImgBorder(elem) {
		elem.css('padding', sp2pxAproach($('#single_img_border').spinner('value'))); 
	}
    function handleImgHeight(elem) {
		elem.css('height',sp2pxAproach($('#single_img_height').spinner('value')));			
	}

	function handleImg(elem){ 
		if(elem.prop('checked')==true){
			$('.mpio-card-media').css('display', 'block');
	    	$('.single_img_input').css('visibility', 'visible');
		}else{
			$('.mpio-card-media').css('display', 'none');
	    	$('.single_img_input').css('visibility', 'collapse');		
		}        
	}
    
    function handleShowTags(elem){ 
		if(elem.prop('checked')==true){
			$('.mpio-accordion-tags').css('display', 'none');
	    	$('.mpio-accordion-tags-area').css('visibility', 'collapse');	
	    	$('.mpio-card-tags').css('display', 'none');
		}else{
			$('.mpio-accordion-tags').css('display', 'block');
	    	$('.mpio-accordion-tags-area').css('visibility', 'visible');		
	    	$('.mpio-card-tags').css('display', 'block');
		}        
	}

	function handleAuthorDate(author,date){
		var author = author.prop('checked');
		var date = date.prop('checked');
		if(!author&&!date){
			$('.mpio-span-author').css('display', 'none');
			$('.mpio-span-date').css('display', 'none');
			$('.mpio-span-separator').css('display', 'none');
			$('.single_date_input').css('visibility', 'collapse');
		}else{
			$('.single_date_input').css('visibility', 'visible');
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


    // General
    $('#single_show_statusbar').live('click', function () {
		handleStatusbar($(this));  
    });
    $('#single_border').on('spinstop', function(){
	    setBorder($('.mpio-container'), ($(this)));
	} );
    // Toolbar
	$('#single_show_toolbar').live('click', function () {
		handleToolbar($(this));  
    });
    $('#single_toolbar_title_type').change(function(){
	    setToolbarTitle($(this));
	} );
    $('#single_toolbar_title').bind('input', function() {
	    $('.mpio-toolbar-title').text($(this).val());
	});
    $('#single_toolbar_text_align').change(function() {
	   setTextAlign($('.mpio-toolbar-title'),$(this).val());
	});
	$('#single_toolbar_text_size').on('spinstop', function(){
	    setTextSize($('.mpio-toolbar-title'),$(this));
	});

	//Featured Image
	$('#single_show_featured_img').live('click', function () {
        handleImg($(this));
    });	
    $('#single_img_height').on('spinstop', function(){
	    handleImgHeight($('.mpio-card-media'));
	});
	$('#single_img_border').on('spinstop', function(){
	    handleImgBorder($('.mpio-card-media'));
	});
	
	// Title
	$('#single_show_title').live('click', function () {
		handleCard('title');
    });
    $('#single_title_text_align').change(function() {
	   setTextAlign($('.mpio-card-title'), $(this).val());
	});
    $('#single_title_text_size').on('spinstop', function(){
	    setTextSize($('.mpio-card-title'),$(this));
	});

	// Author and date
	$('#single_show_author').live('click', function () {
		handleAuthorDate($('#single_show_author'),$('#single_show_date'));
	});
	$('#single_show_date').live('click', function () {
		handleAuthorDate($('#single_show_author'),$('#single_show_date'));
	});
    $('#single_date_text_align').change(function() {
	   setTextAlign($('.mpio-card-author-date'), $(this).val());
	});
    $('#single_date_text_size').on('spinstop', function(){
	    setTextSize($('.mpio-card-author-date'),$(this));
	});

	// Excerpt
	$('#single_show_excerpt').live('click',function(){
        handleCard('excerpt');
    });
    $('#single_excerpt_text_align').change(function() {
	   setTextAlign($('.mpio-card-excerpt'), $(this).val());
	});
    $('#single_excerpt_text_size').on('spinstop',function(){
	    setTextSize($('.mpio-card-excerpt'),$(this));
	});	
    
	// Content
	$('#single_show_content').live('click',function(){
        handleCard('content');
    });
    $('#single_content_text_align').change(function() {
	   setTextAlign($('.mpio-card-content'), $(this).val());
	});
    $('#single_content_text_size').on('spinstop', function(){
	    setTextSize($('.mpio-card-content, .mpio-card-content p'),$(this));
	});	
	
	
	// Categories
	$('#single_show_categories').live('click', function () {
        handleCard('categories');
    });	
	$('#single_include_tags').live('click', function () {
        handleShowTags($(this));
    });	    
    $('#single_categories_text_align').change(function() {
	   setTextAlign($('.mpio-card-categories'), $(this).val());
	});
    $('#single_categories_text_size').on('spinstop', function(){
	    setTextSize($('.mpio-card-categories'),$(this));
	} );	
	
	// Tags
	$('#single_show_tags').live('click',function(){
		handleCard('tags');
    });
    $('#single_author_text_align').change(function() {
	   setTextAlign($('.mpio-card-author'), $(this).val());
	});    	
    $('#single_tags_text_size').on('spinstop',function(){
	    setTextSize($('.mpio-card-tags'),$(this));
	});	
	
	
	// Author Card
	$('#single_show_authors').live('click',function(){
        handleCard('authors');
    });
    $('#single_authors_text_align').change(function() {
	   setTextAlign($('.mpio-card-authors'), $(this).val());
	});	
    $('#single_authors_text_size').on('spinstop',function(){
	    setTextSize($('.mpio-card-authors'),$(this));
	} );	
	
	// Comments
	$('#single_show_comments').live('click',function(){
		handleCard('comments');
    });
    $('#single_comments_text_align').change(function() {
	   setTextAlign($('.mpio-card-comments'), $(this).val());
	});	
    $('#single_comments_text_size').on('spinstop',function(){
	    setTextSize($('.mpio-card-comments'),$(this));
	});	
	
	// Comments
	$('#single_show_related').live('click',function(){
		handleCard('related');
    });    
    
    
    
    /***************************************************************
	*	Init elements
	***************************************************************/    
    
    // General toolbar_title
    function initGeneral(elem){
	    $('#single_border').spinner({min: 0, max: 14});	   
		setBorder(elem, $('#single_border'));
		handleStatusbar($('#single_show_statusbar'));
	    elem.css('background-color', $('#single_bg_color').val());
	    $('.mpio-card-main').css('background-color', $('#single_item_color').val());
	    $('#single_template_name').attr('size', '20');
	    $('#single_categories_title').attr('size', '18');
	    $('#single_categories_separator').attr('size', '5');	    
	    $('#single_categories_ending').attr('size', '5');	
	    $('#single_tags_title').attr('size', '18');
	    $('#single_tags_separator').attr('size', '5');	
	    $('#single_tags_ending').attr('size', '5');	    	    
	    $('#single_comments_text').attr('size', '18');
	    $('#single_to_comment_text').attr('size', '18');
	}

    // Toolbar
    function initToolbar(elem){
	    $('#single_toolbar_text_size').spinner({min: 8, max: 24});	   
	    $('#single_toolbar_title').attr('size', '18');
	    $('.mpio-toolbar').css('background-color', $('#single_toolbar_color').val());
	    $('.svg-colorize').css({ fill: $('#single_toolbar_icons_color').val() });
		handleToolbar($('#single_show_toolbar'));
	    setToolbarTitle($('#single_toolbar_title_type'));
	    setTextColor(elem,$('#single_toolbar_icons_color').val());
		setTextAlign(elem, $('#single_toolbar_text_align').val());
	    setTextSize(elem, $('#single_toolbar_text_size'));
	}

    // Featured Image
    function initFeaturedImg(elem){
		$('#single_img_height').spinner({min: 0, max: 400});	    
		$('#single_img_border').spinner({min: 0, max: 20});	   
		handleImg($('#single_show_featured_img'));   
		handleImgHeight(elem);
		handleImgBorder(elem);
    }

	// Author and date
	function initAuthorDate(elem){
		$( '#single_date_text_size' ).spinner({min: 8, max: 18});	  
		handleAuthorDate($('#single_show_author'),$('#single_show_date'));
		setTextColor(elem,$('#single_date_text_color').val());
		setTextAlign(elem,$('#single_date_text_align').val());
		setTextSize(elem,$('#single_date_text_size'));
	}

    // Comments Button
    function initCommentsButton(elem){
	    $('#single_comments_text_size').spinner({min: 8, max: 28});	   
	    elem.css('background-color', $('#single_comments_background').val());
	    setTextColor(elem,$('#single_comments_text_color').val());
	    setTextSize(elem, $('#single_comments_text_size'));
	}

	// Categories and tags
	function initTaxonomy(elem){
		$('#single_'+elem+'_text_size').spinner({min: 8, max: 28});	
		handleCard(elem);
		setTextAlign($('.mpio-card-'+elem), $('#single_'+elem+'_text_align').val());
		setTextColor($('.mpio-card-'+elem),$('#single_'+elem+'_text_color').val());
		setTextColor($('.mpio-card-'+elem+' a'),$('#single_'+elem+'_color').val());
		setTextSize(($('.mpio-card-'+elem)),$('#single_'+elem+'_text_size'));
	}
	
    // Authors
    function initAuthors(elem){
	    $('#single_authors_text_size').spinner({min: 8, max: 28});	   
	    elem.css('background-color', $('#single_authors_background').val());
	    setTextColor(elem,$('#single_authors_text_color').val());
	    setTextSize(elem, $('#single_authors_text_size'));
	}

	// Related articles
	function initRelatedArticles(elem){
		$('#single_related_number').spinner({min: 1, max: 6});	
		handleCard(elem);
	}
		
	function initElement(elem){
		$('#single_'+elem+'_text_size').spinner({min: 8, max: 28});	
		handleCard(elem);
		setTextAlign($('.mpio-card-'+elem), $('#single_'+elem+'_text_align').val());
		setTextColor($('.mpio-card-'+elem),$('#single_'+elem+'_text_color').val());
		setTextSize(($('.mpio-card-'+elem+', '+'.mpio-card-'+elem+' p')),$('#single_'+elem+'_text_size'));
	}

    function initPhoneStyle(){
		initGeneral($('.mpio-container'));
	    initToolbar($('.mpio-toolbar-title'));
	    initFeaturedImg($('.mpio-card-media'));
	    initElement('title');
	    initAuthorDate($('.mpio-card-author-date'));
	    initElement('excerpt');
	    initElement('content');
	    initTaxonomy('categories');
	    initTaxonomy('tags');
	    initCommentsButton($('.mpio-card-comments'));
	    initAuthors($('.mpio-card-authors'));
	    initRelatedArticles('related');
	    $('.mpio-phone-wrapper').css('display', 'block');
	}initPhoneStyle();
    


    
    // On click open accordion option    
	$('.mpio-toolbar').click(function(){
		$('.mpio-accordion-toolbar').click();
	});
	$('.mpio-card-media').click(function(){
		$('.mpio-accordion-featured-img').click();
	});
	$('.mpio-card-title').click(function(){
		$('.mpio-accordion-title').click();
	});
	$('.mpio-card-excerpt').click(function(){
		$('.mpio-accordion-excerpt').click();
	});
	$('.mpio-card-author-date').click(function(){
		$('.mpio-accordion-date').click();
	});
	$('.mpio-card-content').click(function(){
		$('.mpio-accordion-content').click();
	});
	$('.mpio-card-comments').click(function(){
		$('.mpio-accordion-comments').click();
	});
	$('.mpio-card-categories').click(function(){
		$('.mpio-accordion-categories').click();
	});
	$('.mpio-card-tags').click(function(){
		$('.mpio-accordion-tags').click();
	});
	$('.mpio-card-authors').click(function(){
		$('.mpio-accordion-author-details').click();
	});
	$('.mpio-card-related').click(function(){
		$('.mpio-accordion-related').click();
	});
    
    
    
})( jQuery );