(function () {
    var w, d, b, l, url, tags, frwdCallBack;
    
    w = window, 
    d = document, 
    b = d.body, 
    l = d.location,
    url = "localhost/linksharing/",
    tags = {};
    
    w.__linkCallBack = function ( data ){
    	// This is the callback sendend for jsonp;
    	if( typeof frwdCallBack === 'function' ){
    		frwdCallBack(data);
    	}
    };
    
    w._linkManager || (w._linkManager = {}), 
    w._linkManager.scope = {
    	// This would be url for the resource
    	base: '',
    	
    	post: function (n){
    		// Here is the function based on the post function
    	},
    	
    	ajaxRequest: function( request ){
		var r = new XMLHttpRequest(); 
			r.open("POST", "api/links/new/", true);
			r.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			r.setRequestHeader("Content-length", request.length);
			r.setRequestHeader("Connection", "close");
			r.onreadystatechange = function () {
				if (r.readyState != 4 || r.status != 200) return; 
    			console.log(r.responseText);
    		};
    		r.send( request );
    	},
    	
    	xss_ajax: function( url ){
            var id = "randomid_" + (Math.random() / +new Date()).toString(36).replace(/[^a-z]+/g, ''),
            	script = d.createElement('script'),
            	elem;
            
            script.setAttribute('type', 'text/javascript');
            script.setAttribute('src', url);
            script.setAttribute('id', id);
     
            elem = document.getElementById(id);
            
            if(elem){
                d.getElementsByTagName('head')[0].removeChild(elem);
            }
     
            // Insert <script> into DOM
            d.getElementsByTagName('head')[0].appendChild(script);
    	},
    	
    	serialize: function(form){if(!form||form.nodeName!=="FORM"){return }var i,j,q=[];for(i=form.elements.length-1;i>=0;i=i-1){if(form.elements[i].name===""){continue}switch(form.elements[i].nodeName){case"INPUT":switch(form.elements[i].type){case"text":case"hidden":case"password":case"button":case"reset":case"submit":q.push(form.elements[i].name+"="+encodeURIComponent(form.elements[i].value));break;case"checkbox":case"radio":if(form.elements[i].checked){q.push(form.elements[i].name+"="+encodeURIComponent(form.elements[i].value))}break;case"file":break}break;case"TEXTAREA":q.push(form.elements[i].name+"="+encodeURIComponent(form.elements[i].value));break;case"SELECT":switch(form.elements[i].type){case"select-one":q.push(form.elements[i].name+"="+encodeURIComponent(form.elements[i].value));break;case"select-multiple":for(j=form.elements[i].options.length-1;j>=0;j=j-1){if(form.elements[i].options[j].selected){q.push(form.elements[i].name+"="+encodeURIComponent(form.elements[i].options[j].value))}}break}break;case"BUTTON":switch(form.elements[i].type){case"reset":case"submit":case"button":q.push(form.elements[i].name+"="+encodeURIComponent(form.elements[i].value));break}break}}return q.join("&")},
    	
    	// this function is called inside the jsonp
    	// so in the php I would print something like:
    	// callback({"firstname":"John","lastname":"Smith","email":"john.smith@johnsmith.com"});
    	// _linkManager.scope.callback({"firstname":"John","lastname":"Smith","email":"john.smith@johnsmith.com"});
		callback : function (data) {
			// alert("Something!");
			var txt = '';
			for(var key in data) {
				txt += key + " = " + data[key];
				txt += "\n";
			}
		},
	
		renderTags : function(data){
			var html = '';
				for( var k in data ){
					html += '<li>'+ k +" - "+data[k]+"</li>";
				}
			return html;
		},
		
		done: function(status){
			// if(status){
				// alert("Done");
// 				
			// }else{
				// alert("Fail");
// 				
			// }
			var elem = d.getElementById("_linkSharingForm");
			elem.parentNode.removeChild(elem);
		},
    	
    	boot: function(){
    		// When is clicked in the bookmark this function would be executed
    		// The steps description is
    		// 1) CLIENT - Ask to the server with sending the url if the page is there ( The check needs to be without eventually additional hash tag )
    		// 1) SERVER - if the url isset Return te actual configuration, title, url, and tags (used and avaiables)
    		// Preview for the JSON { id: 0, title: "This is the title of the web page", description: "Amazing website", tags{ "avaiabled": [], "used": []}}
    		
    		
    		// Here is the execution call so what appen when the javascript is injected
    		var box = '', script, description = '', metas;
    		metas = d.getElementsByTagName("meta");
    		
    		for( var k in metas ){
    			if( typeof metas[k].name != "undefined" && metas[k].name.toLowerCase() == 'description' ){
    				description = metas[k].content;
    			}
    		}
    		
    		box += '<form action="javascript: return false;" method="post" id="_linkSharingForm"><div id="__scope" style="width:400px!important;top:50%!important;left:50%!important;position:fixed!important;z-index:1000000!important;background:#000!important; text-align:center!important;opacity:0.8!important; margin:-125px 0 0 -200px!important; padding:0!important;-moz-border-radius:10px!important;-webkit-border-radius:10px!important;border-radius:10px!important;-moz-box-shadow:0px 0px 40px #000!important; -webkit-box-shadow:0px 0px 40px #000!important; box-shadow:0px 0px 40px #000!importan; color: #FFF; font-family: Helvetica, Arial, \'san-serif\'; ">';
	    		box += 'Title: <br/><input type="text" style="width: 90%;" name="title" value="' + d.title + '"/><br/>';
	    		box += 'Description: <br/><textarea style="width: 90%; height: 100px;" name="description">' + description + '</textarea><br/>';
	    		box += '<input type="hidden" style="width: 90%;" value="' + l + '" name="url"/><br/>';
	    		box += '<button id="_linkSharingSend">Salva</button><br/>';
    		box += '</div></form>';
    		
    		window.open('http://localhost/linksharing/?m=new&title='+d.title+'&description='+description+'&url='+l,'miaFinestra','width=500,height=500,toolbar=no,location=no,status=yes,menubar=yes,scrollbars=no,resizable=no');
    		// Additional javascript
    		// script = d.createElement("script"), script.setAttribute("src", "//" + this.base + "/"+url+"api/links/?callback=_linkManager.scope.callback&_v=" + (new Date).getTime()),
    		// b.appendChild(script);
    		
    		// Append the box
    		return false;
    		// return b.innerHTML += box,
    		
    		// d.getElementById("_linkSharingSend").onclick = function(){
    			// var serialize = w._linkManager.scope.serialize( d.getElementById("_linkSharingForm") );
    			// // w._linkManager.scope.ajaxRequest(serialize);
    			// w._linkManager.scope.xss_ajax("http://"+url+"api/links/new/?"+serialize+"&callback=_linkManager.scope.done");
    			// return false;
    		// };
    		
    		
    	}
    },
    w._linkManager.scope.boot();
    
// Call this function
}).call(this);

// Hi have still some dudes for the usage for .call(this) and void(0);