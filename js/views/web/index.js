require([
	'jquery',
	'plugins/bootstrap/modal', 'plugins/bootstrap/affix', 'plugins/bootstrap/alert',
	'plugins/bootstrap/collapse', 'plugins/bootstrap/transition', 'plugins/bootstrap/dropdown', 
	'plugins/jquery/jquery.slimscroll.min', 'plugins/jquery/jquery.fullPage', "vendor/jquery.ui.core_effects.min", 'plugins/jquery/selectize'
	   ], function($) {
	   	// $.fn.fullpage({
	   		// autoScrolling: true
	   	// });
	   	$('.filters-affix').affix({
		    offset: {
		      top: 30
		    , bottom: function () {
		        return (this.bottom = $('.bs-footer').outerHeight(true))
		      }
		    }
		  })
	   	
		var tags = [];
		$.ajax({
			url : 'api/tags/',
			type : 'GET',
			dataType : 'json',
			async : 'false',
			success : function(msg){
				tags= msg;
				
				$('#tags').selectize({
				    delimiter: ',',
				    persist: false,
				    maxItems: null,
				    valueField: 'id',
				    labelField: 'name',
				    searchField: ['name', 'id'],
				    options: tags,
				    onChange: function(value) {
				    	
				    }
				});
				
				console.log(tags);
				
			},
			error : function(msg){
				
			}
		});
		
	   	$('.section:first').addClass("active");
	   	$('.section').each(function(){
	   		
	   		// var li = $(this).find("li"),
	   			// liLength = li.length;
	   		// if(liLength > 0){
	   			// li.addClass("slide");	
	   		// }
	   		
	   	});
	    // var test = $.fn.fullpage({
				// slidesColor: ['#1bbc9b', '#4BBFC3', '#7BAABE', 'whitesmoke', '#ccddff']
	    // });
	    // $.fn.fullpage.moveToSlide(3);
	    // console.log(test);
	   	// $(document).ready(function() {
		// });
}); 