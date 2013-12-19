require(['jquery', 'plugins/jquery/selectize'], function($) {
	//This function will be called when all the dependencies
	//listed above in deps are loaded. Note that this
	//function could be called before the page is loaded.
	//This callback is optional.
	// var $ = require('jquery');
	// require('plugins/jquery/jquery.login');
// 	
	$(document).on("submit", "#_linkSharingForm", function(){
		var data = $(this).serialize();
		$.ajax({
			url : 'api/links/new/',
			type : 'POST',
			data : data,
			success : function(msg){
				// alert(msg);
				window.close();
			},
			error : function(msg){
				
			}
		});
		return false;
	});

	$(function() {
		var tags = [];
		$.ajax({
			url : 'api/tags/',
			type : 'GET',
			dataType : 'json',
			async : 'false',
			success : function(msg){
				for( var k in msg ){
					if( typeof msg[k] != 'undefined' && typeof msg[k].name != 'undefined' ){
						tags[tags.length] = {value: msg[k].name, text: msg[k].name};
					}
				}
				
				$('#tags').selectize({
				    delimiter: ',',
				    persist: false,
				    options: tags,
				    create: function(input) {
				        return {
				            value: input,
				            text: input
				        }
				    }
				});
				
				console.log(tags);
				
			},
			error : function(msg){
				
			}
		});
		
	});
});