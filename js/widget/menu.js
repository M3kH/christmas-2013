require([
	'jquery', 
	'plugins/codemirror/codemirror', 'plugins/codemirror/mode/xml/xml', 'plugins/codemirror/mode/javascript/javascript', 'plugins/codemirror/addon/comments/comment-wrapper',
	'plugins/codemirror/mode/css/css', 'plugins/codemirror/mode/htmlmixed/htmlmixed', 'plugins/codemirror/addon/hint/html-hint',
	'plugins/bootstrap/bootstrap-affix', 'plugins/bootstrap/bootstrap-tab', 'plugins/jquery/jquery.nanoscroller.min'
	], function($) {
	
	
	var _render = function(json, dir){
		var html = '';
		if(typeof json.length == 'undefined' ){
			if(typeof dir != 'undefined'){
				html += '<ul>';
			}
			
			
			for( var k in json ){
				if(typeof json[k] === 'string'){
					// This is file
					html += '<li><a href="#" data-type="file" data-file="'+k+'"><i class="icon icon-file"></i>'+k+'</a></li>';
				}else{
					// this is directory;
					html += '<li><a href="#" data-type="dir"  data-file="'+k+'"><i class="icon icon-folder-close-alt"></i>'+k+'</a>'+_render(json[k], 'true')+'</li>';
					
				}
			}
			if(typeof dir != 'undefined'){
				html += '</ul>';
			}
		}
		
		return html;
	};
	
			
			
	var _hierarchy = function(elem, file){
		
		if(typeof file === 'undefined'){
			var file = elem.data("file");
		}else{
			file = elem.data("file")+'/'+file;
		}
		
		if(  elem.data("file") ){
			var dir = elem.parents("li").eq(1).find("a:first"),
				directory = dir.data("file");
			if(directory){
				file = _hierarchy(dir, file);
			}
		}
		return file;
	};
	
	$(document).ready(function(){
			// $('.tabs').affix(100);
			$.ajax({
				url: '/editor/api/ide/dir',
				dataType: 'json',
				type: 'GET',
				beforeSend: function ( ) {
					// Start Loading
				},
				success: function ( msg ){
					// console.log(msg);
					var html = '<ul class="nav nav-list">';
					html += _render(msg);
					html+= '</ul>';
					$('#file-browser').append(html);
					$('#file-browser ul').hide();
					$('.nav-list').show();
					$(".nano").nanoScroller().height($(document).height() - 35);
					// <ul class="nav nav-list">
					  // <li class="nav-header">List header</li>
					  // <li class="active"><a href="#">Home</a></li>
					  // <li><a href="#">Library</a></li>
					  // ...
					// </ul>
					
	            	$('#file-browser').find("li > a").on("click", function(){
	            		var _file = _hierarchy($(this)),
	            			type = $(this).data("type");
	            		
	            		$(this).closest('li').find(' > ul').toggle();
						$(".nano").nanoScroller();
	            		if(type == 'file'){
	            			$("#file-browser").trigger("openfile", [_file]);
	            		}
						
	            		return false;
	            	});
				},
				error: function( msg ){
				}
			});
	  });
});