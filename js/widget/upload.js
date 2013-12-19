require(['jquery', 'vendor/jquery.ui.widget', 'async!js/plugins/jquery/jquery.iframe-transport.js', 'async!js/plugins/jquery/jquery.fileupload.js'], function($) {
	//This function will be called when all the dependencies
	//listed above in deps are loaded. Note that this
	//function could be called before the page is loaded.
	//This callback is optional.
	// var $ = require('jquery');
	// require('plugins/jquery/jquery.login');
// 	
	//This function will be called when all the dependencies
	//listed above in deps are loaded. Note that this
	//function could be called before the page is loaded.
	//This callback is optional.
	$(document).ready(function(){
	    //This function is called once the DOM is ready.
	    //It will be safe to query the DOM and manipulate
	    //DOM nodes in this function.
    	'use strict';
    	
    	$(".e-upload-modal").on("click", function(){
    			$("#uploadModal").modal("show");
				return false;
    	});
    	
		// Change this to the location of your server-side upload handler:
		var url = (window.location.hostname === 'blueimp.github.com' || window.location.hostname === 'blueimp.github.io') ? '//jquery-file-upload.appspot.com/' : 'api/upload/';
		$('#fileupload').fileupload({
			url : url,
			dataType : 'json',
			done : function(e, data) {
				$.each(data.result.files, function(index, file) {
					$('<p/>').text(file.name).appendTo('#files');
					window.location = window.location.origin+"/asciimator/?id="+file.short; 
				});
			},
			progressall : function(e, data) {
				var progress = parseInt(data.loaded / data.total * 100, 10);
				$('#progress .bar').css('width', progress + '%');
			}
		});
	  });
});