require(['jquery'], function($) {
	//This function will be called when all the dependencies
	//listed above in deps are loaded. Note that this
	//function could be called before the page is loaded.
	//This callback is optional.
	// var $ = require('jquery');
	// require('plugins/jquery/jquery.login');
	JSON.stringify = JSON.stringify || function (obj) {
	    var t = typeof (obj);
	    if (t != "object" || obj === null) {
	        // simple data type
	        if (t == "string") obj = '"'+obj+'"';
	        return String(obj);
	    }
	    else {
	        // recurse array or object
	        var n, v, json = [], arr = (obj && obj.constructor == Array);
	        for (n in obj) {
	            v = obj[n]; t = typeof(v);
	            if (t == "string") v = '"'+v+'"';
	            else if (t == "object" && v !== null) v = JSON.stringify(v);
	            json.push((arr ? "" : '"' + n + '":') + String(v));
	        }
	        return (arr ? "[" : "{") + String(json) + (arr ? "]" : "}");
	    }
	};
//
	var Comments = {

		templates: {
			detail: '<li class="list-group-item" data-qt="{qt}" data-des="{desc}"> <a class="e-delete-detail" href="#">x</a> <span class="badge i-total">{qt}</span> <span class="i-desc">{desc}</span> </li>'
		},

		render: function(template, data) {
		  return template.replace(/\{([\w\.]*)\}/g, function(str, key) {
		    var keys = key.split("."), v = data[keys.shift()];
		    for (var i = 0, l = keys.length; i < l; i++) v = v[keys[i]];
		    return (typeof v !== "undefined" && v !== null) ? v : "";
		  });
		},

		addDetail : function( qt, desc, dest){
			qt = parseInt(qt) || 0;
			console.log(typeof qt, typeof desc, dest);
			if( typeof qt != "number" ){ return false; }
			if( typeof desc != "string" && desc != "" ){ return false; }

			dest.append(Comments.render(Comments.templates.detail, {'qt': qt, 'desc': desc}));

		},

		serialize: function(){
			var res = {};
			res.brings = {};
			res.msg = $(".i-message").val();
			// Here I process each box
			$(".form-container").each(function(){
				// This is a box
				var list = $(this).find(".list-group"),
					type = $(this).data("type");

					res.brings[type] = [];

				list.find("li").each(function(){
					var r = res.brings[type],
						rLength = r.length,
						qt = $(this).data("qt"),
						desc = $(this).data("desc");
					res.brings[type][rLength] = {'qt': qt, 'desc': 'desc'};
				});
			});

			var val = res.brings;
			res.brings = JSON.stringify(val);

			return res;

		}

	};

	// On add element in a list
	$(document).on("click", ".e-addElem", function(){
		var form_group = $(this).closest(".form-group"),
			input_group = form_group.find(".g-element"),
			list = form_group.find(".list-group"),
			qt = input_group.find(".i-qt"),
			desc = input_group.find(".i-desc");

			Comments.addDetail(qt.val(), desc.val(), list);

			qt.val("");
			desc.val("");

			return false;
	});

	// On add element in a list
	$(document).on("click", ".e-delete-detail", function(){
		$(this).closest("li").remove();
		return false;
	});

	// On add element in a list
	$(document).on("click", ".e-submit", function(){
		$.ajax({
			url: "api/comments/new/",
			data: Comments.serialize(),
			dataType: 'json',
			type: 'POST',
			success: function ( msg ){
				location.reload();
			},
			error: function( msg ){
				location.reload();
			}
		});
	});

});