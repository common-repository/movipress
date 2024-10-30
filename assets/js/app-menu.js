jQuery(document).ready(function($){
	
	$('#menu-to-edit').sortable({
		connectWith: ".connectedSortable"
	}).disableSelection();
	
	$('#accordion').accordion({ heightStyle: "content"});
	
	$( function() {
    	$('#cat-tabs').tabs();
    	$('#tags-tabs').tabs();    	
		$('#posts-tabs').tabs();
		$('#pages-tabs').tabs();
		return false;
	});
		
	function getManuItem(id, title){
    	return '<li class="ui-state-default" id="'+id+'"><span class="mpio-menu-item">'+title+'</span><span class="mpio-menu-delete"><a href="#">Delete</a></span><div style="clear: both;"></div></li>';
   	}
	
	$('#submit-page').on("click",function() {
		$("input:checked").each(function() {
			var idPage = $(this).val();
			$("#menu-to-edit").append(getManuItem('p'+idPage, $(this).parent().text()));
			$(this).prop('checked', false);
			deleteActionPlugin();
		});
		return false;
  	}); 
  	
	$('#submit-post').on("click",function() {
		$("input:checked").each(function() {
			var idPost = $(this).val();
			$("#menu-to-edit").append(getManuItem('a'+idPost, $(this).parent().text()));
			$(this).prop('checked', false);
			deleteActionPlugin();
		});
		return false;
  	}); 
  	
  	$('#submit-taxonomy-tag').on("click",function() {
		$("input:checked").each(function() {
			var idTag = $(this).val();
			$("#menu-to-edit").append(getManuItem('t'+idTag, $(this).parent().text()));
			$(this).prop('checked', false);
			deleteActionPlugin();
		});
		return false;
  	});   	
  	
  	$('#submit-taxonomy-category').on("click",function() {
		$("input:checked").each(function() {
			var idCat = $(this).val();
			$("#menu-to-edit").append(getManuItem('c'+idCat, $(this).parent().text()));
			$(this).prop('checked', false);
			deleteActionPlugin();
		});
		return false;
  	}); 
  	  	
  	function deleteActionPlugin(){
	  	$('.mpio-menu-delete').click(function(){
	        $(this).parent().prev('li.ui-state-default').end().remove();
	        return false;
	    });
	}deleteActionPlugin();
	  	
  	$('#delete-all').on('click',function() {
        $('#menu-to-edit').empty();
        return false;
    });
		
	function saveMenu(){
		var navMenu = $( "#menu-to-edit" ).sortable( "toArray" );
        $('#navigation-menu').val(navMenu);
        return false;
	}	

	$('#save_menu_header').on('click',function() {
		saveMenu();
    });

	$('#save_menu_footer').on('click',function() {
		saveMenu();		
    });
});