 $(document).ready(function(){
 	$.web_url = 'http://www.devxekera.com/';
 	$.resources = 'resources/';
 	$.extn = '.php';
	 var alltypesactive = $('#select-all-types').attr('active');
	 if(alltypesactive == '1'){
		$('.selectalltypes').css({
			"background": "#02a69c",
			"color": "#fff",
			"cursor": "pointer"
		});
	 }
	 var allstagesactive = $('#select-stage-all').attr('active');
	 if(allstagesactive == '1'){
		$('#select-stage-all').css({
			"background": "#02a69c",
			"color": "#fff",
			"cursor": "pointer"
		});
	 } 

 	$(document).on('keyup', '#resource-search-input', function() {
       //Assigning search box value to javascript variable named as "name".
       var name = $('#resource-search-input').val();
       var rtypes = $(this).attr("resourcetypes");
	   var lifestage = $(this).attr("lifestage");
	   var sortid = $(this).attr("sort");
	   var tags = $(this).attr("tags");
       $('.pagination-box-n').html('');
       $('.pagination-box').html('');
       var page = $(this).attr('page');
       //Validating, if "name" is empty.
       if (name == "") {
           //Assigning empty value to "display" div in "search.php" file.
           $("#display").html("");
       }
		$('.content-inner-page').addClass('hidden').fadeIn(2000);
		$('.search-nxt-click').removeAttr('resourcetypes').attr("resourcetypes", rtypes);
	//	$('.next-btn').removeAttr('type').attr("type", rtypes);
	//	$('.pg-btn').removeAttr('typevalue').attr("typevalue", rtypes);
		$('.siderbar-small-category').removeAttr('search').attr("search", name);
		$('#select-stage-all').removeAttr('search').attr("search", name);
		$('.lifestagec').removeAttr('search').attr("search", name);
		$('.sort-form-select').removeAttr('search').attr('search', name);
		$('#select-all-types').removeAttr('search').attr('search', name);
		$('.next-btn').removeAttr('search').attr('search', name);
		$('.pg-btn').removeAttr('search').attr('search', name);
		$('.pvr-btn').removeAttr('search').attr('search', name);
		$('.tag-click').removeAttr('search').attr('search', name);
		$('.autocomplete-tag-input').removeAttr('search').attr('search', name);
		$('.autocomplete-tag-list').removeAttr('search').attr('search', name);


		
	    $('.resource-column-new').removeClass('hidden').fadeIn(5000).html('<div style="margin-left: 42%; margin-top: 17%; padding-bottom: 30%;"><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> loading search results</div>');
	    if (history.pushState) {
			window.history.pushState("object or string", "BALANCE Financial Fitness Program  | Resources", $.web_url+$.resources+'?query='+name+'&pager=1');
		} else {
			document.location.href = $.web_url+$.resources+'?query='+name+'&pager=1';
		}
	    var postData=JSON.stringify({"dvalue": name, "resourcetypes": rtypes, "lifestage": lifestage,"sort":sortid, "tags": tags});
		//If name is not empty.
		var sync1 = $.ajax({  
			type: "POST",  
			url: $.web_url+"includes/core/search_query"+$.extn,
			dataType: 'JSON', //this is what we expect our returned data as  
			data: {data:postData},
			cache: false,  
			success: function(new_data)
			{
				var len = new_data.length;
				$(".resource-column-new").html('');
				$("#cd-"+name+ " span .fake-label").removeClass('fake-label').addClass('fake-label-active');
			    for(var i=0; i < len; i++){
			    var title = new_data[i].title;
			    var catv = new_data[i].name;
			    var url = new_data[i].slug;
			    var tpage = new_data[i].pages;
			    var message = new_data[i].message;
			    if(catv == 'article'){
					var seo_dvalue = 'articles/';
				}else if(catv == 'calculator'){
					var seo_dvalue = 'calculators/';
				}else if(catv == 'video'){
					var seo_dvalue = 'videos/';
				}else if(catv == 'newsletter'){
					var seo_dvalue = 'newsletters/';
				}else if(catv == 'podcast'){
					var seo_dvalue = 'podcasts/';
				}else if(catv == 'toolkit'){
					var seo_dvalue = 'toolkits/';
				}else if(catv == 'booklet'){
					var seo_dvalue = 'booklets/';
				}else{}
				var tr_str = message;
			    $(".resource-column-new").append(tr_str);
			}
			      
		}
	});
	var postDatan=JSON.stringify({"dvalue": name, "page": page, "resourcetypes": rtypes, "lifestage": lifestage,"sort":sortid, "tags": tags});
	var sync2 = $.ajax({  
	type: "POST",  
	url: $.web_url+"includes/core/search_query_pagination"+$.extn,
	dataType: 'JSON', //this is what we expect our returned data as  
	data: {data:postDatan},
	cache: false,  
	success: function(new_data)
	{
		$("#pagination-box-n").removeClass('hidden').html(new_data.message);
		$("#pagination-box").addClass('hidden').html('');
	}
});
	$.when(sync1, sync2).done(function(result2, result1) {
		console.log('both call finished');
	});
});

$(document).on('click', '.search-nxt-click', function()
	{
		var type = $(this).attr("query");
		var pager = $(this).attr("pager");
		var resourcetypes = $(this).attr("resourcetypes");
		var lifestage = $(this).attr("lifestage");
		var sortid  = $(this).attr("sort");
		var tags = $(this).attr("tags");
		$(".content-inner-page").addClass('hidden').html('').fadeIn(5000);
		$(".resource-column-new").html('').html('<div style="margin-left: 42%; margin-top: 17%; padding-bottom: 30%;"><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> loading</div>');
		var url = (window.location.href);
    mnurl = url.split('&pager')[0];
    murl = (mnurl);
    if (history.pushState) {
		window.history.pushState("object or string", "BALANCE Financial Fitness Program  |  Resources", murl+'&pager='+pager);
	} else {
		document.location.href = murl+'&pager='+pager;
	}
	var postData=JSON.stringify({"query": type, "pager": pager, "resourcetypes": resourcetypes, "lifestage": lifestage,"sort":sortid, "tags": tags});
	var sync1 = $.ajax({  
		type: "POST",  
		url: $.web_url+"includes/core/resource_search_pagination_content"+$.extn,
		dataType: 'JSON', //this is what we expect our returned data as  
		data: {data:postData},
		cache: false,  
		success: function(new_data){
			var len = new_data.length;
			$(".resource-column-new").html('');
			$(".content-inner-page").addClass('hidden');
			$(".resource-column-new").removeClass('hidden');
			$("#cd-"+name+ " span .fake-label").removeClass('fake-label').addClass('fake-label-active');
			for(var i=0; i < len; i++){
			    var message = new_data[i].message;
			    var tr_str = message;
			    var pagern = parseInt(pager) + 1;
			    $(".resource-column-new").append(tr_str);
			}
		}
	});
	var postDatan=JSON.stringify({"dvalue": type, "page": pager, "resourcetypes": resourcetypes, "lifestage": lifestage, "tags": tags});
	var sync2 = $.ajax({  
		type: "POST",  
		url: $.web_url+"includes/core/search_query_pagination"+$.extn,
		dataType: 'JSON', //this is what we expect our returned data as  
		data: {data:postDatan},
		cache: false,  
		success: function(new_data){
			$("#pagination-box-n").removeClass('hidden').html(new_data.message);
			$("#pagination-box").addClass('hidden').html('');
		}
	});
	$.when(sync1, sync2).done(function(result2, result1) {
    	console.log('both call finished');
	});
			
});
$(document).on('click', '.pg-btn-search', function()
	{
		var type = $(this).attr("queryvalue");
		var pager = $(this).attr("pagerv");
		var resourcetypes = $(this).attr("resourcetypes");
		var lifestage = $(this).attr("lifestage");
		var sortid = $(this).attr("sort");
		var tags = $(this).attr("tags");
		$(".content-inner-page").addClass('hidden').html('').fadeIn(5000);
		$(".resource-column-new").html('').html('<div style="margin-left: 42%; margin-top: 17%; padding-bottom: 30%;"><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> loading</div>');
		var url = (window.location.href);
     	mnurl = url.split('&pager')[0];
      	murl = (mnurl);
      	if (history.pushState) {
			window.history.pushState("object or string", "BALANCE Financial Fitness Program  |  Resources", murl+'&pager='+pager);
		} else {
			document.location.href = murl+'&pager='+pager;
		}
		var postData=JSON.stringify({"query": type, "pager": pager, "resourcetypes": resourcetypes, "lifestage": lifestage,"sort":sortid, "tags": tags});
		var sync1 = $.ajax({  
			type: "POST",  
			url: $.web_url+"includes/core/resource_search_pagination_content"+$.extn,
			dataType: 'JSON', //this is what we expect our returned data as  
			data: {data:postData},
			cache: false,  
			success: function(new_data){
				var len = new_data.length;
				$(".resource-column-new").html('');
				$(".content-inner-page").addClass('hidden');
				$(".resource-column-new").removeClass('hidden');
				$("#cd-"+name+ " span .fake-label").removeClass('fake-label').addClass('fake-label-active');
			    for(var i=0; i < len; i++){
			        var message = new_data[i].message;
			        var tr_str = message;
			        var pagern = parseInt(pager) + 1;
			        $(".resource-column-new").append(tr_str);
			    }
			}
		});
		var postDatan=JSON.stringify({"dvalue": type, "page": pager, "resourcetypes": resourcetypes, "lifestage": lifestage, "tags": tags});
		var sync2 = $.ajax({  
			type: "POST",  
			url: $.web_url+"includes/core/search_query_pagination"+$.extn,
			dataType: 'JSON', //this is what we expect our returned data as  
			data: {data:postDatan},
			cache: false,  
			success: function(new_data){
				$("#pagination-box-n").removeClass('hidden').html(new_data.message);
				$("#pagination-box").addClass('hidden').html('');
			}
		});
		$.when(sync1, sync2).done(function(result2, result1) {
    		console.log('both call finished');
		});		
});

$(document).on('click', '.search-prv-click', function(){
	var type = $(this).attr("query");
	var pager = $(this).attr("pager");
	var resourcetypes = $(this).attr("resourcetypes");
	var lifestage = $(this).attr("lifestage");
	var sortid = $(this).attr("sort");
	var tags = $(this).attr("tags");
	$(".content-inner-page").addClass('hidden').html('').fadeIn(5000);
	$(".resource-column-new").html('').html('<div style="margin-left: 42%; margin-top: 17%; padding-bottom: 30%;"><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> loading</div>');
	var url = (window.location.href);
    mnurl = url.split('&pager')[0];
    murl = (mnurl);
    if (history.pushState) {
		window.history.pushState("object or string", "BALANCE Financial Fitness Program  |  Resources", murl+'&pager='+pager);
	} else {
		document.location.href = murl+'&pager='+pager;
	}
	var postData=JSON.stringify({"query": type, "pager": pager, "resourcetypes": resourcetypes, "lifestage": lifestage, "sort":sortid, "tags": tags});
	var sync1 = $.ajax({  
		type: "POST",  
		url: $.web_url+"includes/core/resource_search_pagination_content"+$.extn,
		dataType: 'JSON', //this is what we expect our returned data as  
		data: {data:postData},
		cache: false,  
		success: function(new_data){
			var len = new_data.length;
			$(".resource-column-new").html('');
			$(".content-inner-page").addClass('hidden');
			$(".resource-column-new").removeClass('hidden');
			$("#cd-"+name+ " span .fake-label").removeClass('fake-label').addClass('fake-label-active');
			for(var i=0; i < len; i++){
			    var message = new_data[i].message;
			    var tr_str = message;
			    var pagern = parseInt(pager) + 1;
			    $(".resource-column-new").append(tr_str);
			}
		}
	});
	var postDatan=JSON.stringify({"dvalue": type, "page": pager, "resourcetypes": resourcetypes, "lifestage": lifestage, "tags": tags});
	var sync2 = $.ajax({  
		type: "POST",  
		url: $.web_url+"includes/core/search_query_pagination"+$.extn,
		dataType: 'JSON', //this is what we expect our returned data as  
		data: {data:postDatan},
		cache: false,  
		success: function(new_data){
			$("#pagination-box-n").removeClass('hidden').html(new_data.message);
			$("#pagination-box").addClass('hidden').html('');
		}
	});
	$.when(sync1, sync2).done(function(result2, result1) {
    	console.log('both call finished');
	});	
});

$(document).on('click', '.next-btn', function()
{	
	var type = $(this).attr("type"); // Resource Type
	var rtype = $(this).attr("resourcetypes");
	//alert(type);
	var pager = $(this).attr("pager"); // Page number
	var lifestage = $(this).attr("lifestage");  // Life Stage 
	var search = $(this).attr("search"); // Search Keywords
	var sortid = $(this).attr("sort"); // Search Keywords
	var tags = $(this).attr("tags");
	//var s1 = $('#resource-search-input').val();
	//if(s1)
	//alert(s1);
	$(".content-inner-page").addClass('hidden').html('').fadeIn(5000);
	$(".resource-column-new").html('').html('<div style="margin-left: 42%; margin-top: 17%; padding-bottom: 30%;"><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> loading</div>');
	var url = (window.location.href);
    mnurl = url.split('&pager')[0];
    murl = (mnurl);
    if (history.pushState) {
		window.history.pushState("object or string", "BALANCE Financial Fitness Program  |  Resources", murl+'&pager='+pager);
	} else {
		document.location.href = murl+'&pager='+pager;
	}
	var postData=JSON.stringify({"type": type, "pager": pager, "lifestage": lifestage, "search": search,"rtype":rtype,"sort":sortid, "tags": tags});
	var sync1 = $.ajax({  
		type: "POST",  
		url: $.web_url+"includes/core/resource_pagination_content"+$.extn,
		dataType: 'JSON', //this is what we expect our returned data as  
		data: {data:postData},
		cache: false,  
		success: function(new_data){
			var len = new_data.length;
			$(".resource-column-new").html('');
			$(".content-inner-page").addClass('hidden');
			$(".resource-column-new").removeClass('hidden');
			$("#cd-"+name+ " span .fake-label").removeClass('fake-label').addClass('fake-label-active');
			for(var i=0; i < len; i++){
			    var message = new_data[i].message;
			    var tr_str = message;
			    var pagern = parseInt(pager) + 1;
			    $(".resource-column-new").append(tr_str);
			    $(".pg-btn").removeAttr("typevalue").attr('typevalue', type);
			    $(".next-btn").removeAttr("search").attr('search', search).removeAttr("type").attr('type', type).removeAttr("pager").attr('pager', pagern);
			}
		}
	});
	var postDatan=JSON.stringify({"dvalue": type, "pager": pager, "lifestage": lifestage, "search": search,"sort":sortid, "tags": tags});
	var sync2 = $.ajax({  
		type: "POST",  
		url: $.web_url+"includes/core/pagination_new_check"+$.extn,
		dataType: 'JSON', //this is what we expect our returned data as  
		data: {data:postDatan},
		cache: false,  
		success: function(new_data){
			$("#pagination-box-n").removeClass('hidden').html(new_data.message);
			$("#pagination-box").addClass('hidden').html('');
		}
	});
	$.when(sync1, sync2).done(function(result2, result1) {
    	console.log('both call finished');
	});
			
});

$(document).on('click', '.prv-btn', function(){
	var type = $(this).attr("type");
	var pager = $(this).attr("pager");
	var lifestage = $(this).attr("lifestage");
	var search = $(this).attr("search");
	var sortid = $(this).attr("sort");
	var tags = $(this).attr("tags");
	$(".content-inner-page").addClass('hidden').html('').fadeIn(5000);
	$(".resource-column-new").html('').html('<div style="margin-left: 42%; margin-top: 17%; padding-bottom: 30%;"><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> loading</div>');
	var url = (window.location.href);
    mnurl = url.split('&pager')[0];
    murl = (mnurl);
    if (history.pushState) {
		window.history.pushState("object or string", "BALANCE Financial Fitness Program  |  Resources", murl+'&pager='+pager);
	} else {
		document.location.href = murl+'&pager='+pager;
	}
	var postData=JSON.stringify({"type": type, "pager": pager, "lifestage": lifestage, "search": search,"sort":sortid, "tags": tags});
	var sync1 = $.ajax({  
		type: "POST",  
		url: $.web_url+"includes/core/resource_pagination_content"+$.extn,
		dataType: 'JSON', //this is what we expect our returned data as  
		data: {data:postData},
		cache: false,  
		success: function(new_data){
			var len = new_data.length;
			$(".resource-column-new").html('');
			$(".content-inner-page").addClass('hidden');
			$(".resource-column-new").removeClass('hidden');
			$("#cd-"+name+ " span .fake-label").removeClass('fake-label').addClass('fake-label-active');
			for(var i=0; i < len; i++){
				var message = new_data[i].message;
				var tr_str = message;
				var pagern = parseInt(pager) - 1;
				$(".resource-column-new").append(tr_str);
				$(".pg-btn").removeAttr("typevalue").attr('typevalue', type);
				$(".next-btn").removeAttr("type").attr('type', type).removeAttr("pager").attr('pager', pagern);
			}
		}
	});
	var postDatan=JSON.stringify({"dvalue": type, "pager": pager, "lifestage": lifestage, "search": search,"sort":sortid, "tags": tags});
	var sync2 = $.ajax({  
		type: "POST",  
		url: $.web_url+"includes/core/pagination_new_check"+$.extn,
		dataType: 'JSON', //this is what we expect our returned data as  
		data: {data:postDatan},
		cache: false,  
		success: function(new_data)
		{
			$("#pagination-box-n").removeClass('hidden').html(new_data.message);
			$("#pagination-box").addClass('hidden').html('');
		}
	});
	$.when(sync1, sync2).done(function(result2, result1) {
    	console.log('both call finished');
	});
			
});
$(document).on('keyup', '.autocomplete-tag-input', function()
{
	$('.tag-search-keyword').removeClass('hidden');
	var rtypes = $('.tagsbox').attr("resourcetypes");
	var lifestage = $('.tagsbox').attr("lifestage");
	var tags = $(this).attr("tags");
	var sortid =  $(this).attr("sort");
	var searchv = $(this).val();
	var postData=JSON.stringify({"keyword": searchv, "rtypes": rtypes, "lifestage": lifestage, "tags": tags,"sort":sortid});
	$('.tag-search-keyword').css({
		"background-color": "#f6f6f6",
		"margin-top": "-20px",
		"z-index": "9999",
		"padding-top": "10px"
	}).html('').html('<div style="clear: both;padding: 5px 15px 15px;">Loading..</div>').fadeIn(2000);
	$.ajax({  
		type: "POST",  
		url: $.web_url+"includes/core/auto_search_query"+$.extn,
		dataType: 'JSON', //this is what we expect our returned data as  
		data: {data:postData},
		cache: false,  
		success: function(new_data){
			var message = new_data.message;
			var tr_str = message;
			$('.tag-search-keyword').css({
				"background-color": "#f6f6f6",
				"margin-top": "-20px",
				"z-index": "9999",
				"padding-top": "10px"
			}).html(tr_str).fadeIn(2000);
		}
	});
});

// var allstagesactive = $('#select-stage-all').attr('active');
// if(allstagesactive == '1'){
// 	$('#select-stage-all').css({
// 		"background": "#02a69c",
// 		"color": "#fff",
// 		"cursor": "pointer"
// 	});
// }
 $(document).on('click', '#select-all-types', function()
 {
	//$('#select-stage-all').attr('style', '');
	$('.resourcetype').attr('style', '');
	$('.tagicon').attr('style', '');
	$('.selectalltypes').css({
		"background": "#02a69c",
		"color": "#fff",
		"cursor": "pointer"
	});
	var lifestage = $(this).attr("lifestage");
	//alert(lifestage);
	var dvalue = $(this).attr("dvalue");
	var rtypes = $(this).attr("resourcetypes");
	//alert(dvalue);
	var pager = $(this).attr("pager");
	var tags = $(this).attr("tags");
	var search = $(this).attr("search");
	var sort = $(this).attr("sort");
	$('.lifestagec').removeAttr('resourcetypes').attr('resourcetypes', dvalue);
	$('#select-stage-all').removeAttr('resourcetypes').attr('resourcetypes', dvalue);
	$('#resource-search-input').removeAttr('resourcetypes').attr('resourcetypes', dvalue);
	$('.tag-click').removeAttr('resourcetypes').attr('resourcetypes', dvalue);
	$('.autocomplete-tag-input').removeAttr('resourcetypes').attr('resourcetypes', dvalue);
	$('.autocomplete-tag-list').removeAttr('resourcetypes').attr('resourcetypes', dvalue);
	

	$('.next-btn').removeAttr('search').attr('search', search);
	$('.pg-btn').removeAttr('search').attr('search', search);
	$('.pvr-btn').removeAttr('search').attr('search', search);
	$(".content-inner-page").addClass('hidden').html('').fadeIn(5000);
	$(".resource-column-new").html('').html('<div style="margin-left: 42%; margin-top: 17%; padding-bottom: 30%;"><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> loading</div>');
	var postData=JSON.stringify({"dvalue": dvalue, "lifestage": lifestage, "type": rtypes, "page": pager, "tags": tags, "search": search, "sort": sort, "tags": tags});
	var sync1 = $.ajax({  
		type: "POST",  
		url: $.web_url+"includes/core/resource_all_page"+$.extn,
		dataType: 'JSON', //this is what we expect our returned data as  
		data: {data:postData},
		cache: false,  
		success: function(new_data)
		{
			var len = new_data.length;
			$(".resource-column-new").html('');
			$(".content-inner-page").addClass('hidden');
			$(".resource-column-new").removeClass('hidden');
			$("#cd-"+name+ " span .fake-label").removeClass('fake-label').addClass('fake-label-active');
			for(var i=0; i < len; i++){
				var title = new_data[i].title;
				var catv = new_data[i].posttype;
				var url = new_data[i].postname;
				var message = new_data[i].message;
				if(catv == 'article'){
					var seo_dvalue = 'articles/';
				}else if(catv == 'calculator'){
					var seo_dvalue = 'calculators/';
				}else if(catv == 'video'){
					var seo_dvalue = 'videos/';
				}else if(catv == 'newsletter'){
					var seo_dvalue = 'newsletters/';
				}else if(catv == 'podcast'){
					var seo_dvalue = 'podcasts/';
				}else if(catv == 'toolkit'){
					var seo_dvalue = 'toolkits/';
				}else if(catv == 'booklet'){
					var seo_dvalue = 'booklets/';
				}else{}
				var tr_str = message;
				$(".resource-column-new").append(tr_str);
			}
		}
	});
	var postDatan=JSON.stringify({"dvalue": dvalue, "lifestage": lifestage, "type": rtypes, "page": pager, "tags": tags, "search": search, "sort": sort, "tags": tags});
	var sync2 = $.ajax({  
		type: "POST",  
		url: $.web_url+"includes/core/pagination_check"+$.extn,
		dataType: 'JSON', //this is what we expect our returned data as  
		data: {data:postDatan},
		cache: false,  
		success: function(new_data)
		{
			$("#pagination-box-n").removeClass('hidden').html(new_data.message);
			$("#pagination-box").addClass('hidden').html('');
		}
	});
	$.when(sync1, sync2).done(function(result2, result1) {
		console.log('both call finished');
	});
 });
 $(document).on('click', '#select-stage-all', function()
 {
	var dvalue = $(this).attr("dvalue");
	$('.lfstyle').attr('style', '');
	$('.tagicon').attr('style', '');
	$('#select-stage-all').css({
		"background": "#02a69c",
		"color": "#fff",
		"cursor": "pointer"
	});
	
	var lifestage = $(this).attr("lifestage");
	//alert(lifestage);
	var pager = $(this).attr("pager");
	var tags = $(this).attr("tags");
	var search = $(this).attr("search");
	var sort = $(this).attr("sort");
	//var rtypes = $('.tagsbox').attr("resourcetypes");
	var rtypes = $(this).attr("resourcetypes");
	//alert(rtypes);
	$('.siderbar-small-category').removeAttr('lifestage').attr('lifestage', dvalue);
	$('.next-btn').removeAttr('type').attr('type', rtypes);
	$('.pg-btn').removeAttr('typevalue').attr('typevalue', rtypes);
	$('.prv-btn').removeAttr('typevalue').attr('type', rtypes);
	$('#select-all-types').removeAttr('lifestage').attr('lifestage', dvalue);
	$('#resource-search-input').removeAttr('lifestage').attr('lifestage', dvalue);
	$('.tag-click').removeAttr('lifestage').attr('lifestage', dvalue);
	$('.autocomplete-tag-input').removeAttr('lifestage').attr('lifestage', dvalue);
	$('.autocomplete-tag-list').removeAttr('lifestage').attr('lifestage', dvalue);
    //$('.selectalltypes').attr('style', '');

	
	$(".content-inner-page").addClass('hidden').html('').fadeIn(5000);
	$(".resource-column-new").html('').html('<div style="margin-left: 42%; margin-top: 17%; padding-bottom: 30%;"><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> loading</div>');
	var postData=JSON.stringify({"dvalue": dvalue,"tags":tags,"search":search,"sort":sort,"rtype":rtypes, "tags": tags});
	var sync1 = $.ajax({  
		type: "POST",  
		url: $.web_url+"includes/core/resource_all_page"+$.extn,
		dataType: 'JSON', //this is what we expect our returned data as  
		data: {data:postData},
		cache: false,  
		success: function(new_data)
		{
			var len = new_data.length;
			$(".resource-column-new").html('');
			$(".content-inner-page").addClass('hidden');
			$(".resource-column-new").removeClass('hidden');
			$("#cd-"+name+ " span .fake-label").removeClass('fake-label').addClass('fake-label-active');
			for(var i=0; i < len; i++){
				var title = new_data[i].title;
				var catv = new_data[i].posttype;
				var url = new_data[i].postname;
				var message = new_data[i].message;
				if(catv == 'article'){
					var seo_dvalue = 'articles/';
				}else if(catv == 'calculator'){
					var seo_dvalue = 'calculators/';
				}else if(catv == 'video'){
					var seo_dvalue = 'videos/';
				}else if(catv == 'newsletter'){
					var seo_dvalue = 'newsletters/';
				}else if(catv == 'podcast'){
					var seo_dvalue = 'podcasts/';
				}else if(catv == 'toolkit'){
					var seo_dvalue = 'toolkits/';
				}else if(catv == 'booklet'){
					var seo_dvalue = 'booklets/';
				}else{}
				var tr_str = message;
				$(".resource-column-new").append(tr_str);
	        }	
		}
	});
	// "dvalue": dvalue, "lifestage": lifestage, "type": rtypes, "page": pager, "tags": tags, "search": search, "sort": sort
	var postDatan=JSON.stringify({"dvalue": rtypes, "lifestage": lifestage, "pager": pager, "search": search, "sort": sort, "tags": tags});
	var sync2 = $.ajax({  
		type: "POST",  
		url: $.web_url+"includes/core/pagination_new_check"+$.extn,
		dataType: 'JSON', //this is what we expect our returned data as  
		data: {data:postDatan},
		cache: false,  
		success: function(new_data)
		{
			$("#pagination-box-n").removeClass('hidden').html(new_data.message);
			$("#pagination-box").addClass('hidden').html('');
		}
	});
	$.when(sync1, sync2).done(function(result2, result1) {
    	console.log('both call finished');
	});
 });
 function GetURLParameter(sParam)
{
    var sPageURL = window.location.search.substring(1);
    var sURLVariables = sPageURL.split('&');
    for (var i = 0; i < sURLVariables.length; i++) 
    {
        var sParameterName = sURLVariables[i].split('=');
        if (sParameterName[0] == sParam) 
        {
            return sParameterName[1];
        }
    }
}
 function setQueryStringParameter(name, value, append=false) {
    const url = new URL(window.document.URL);
    if (append) url.searchParams.append(name, value);
    else url.searchParams.set(name, value);
    window.history.replaceState(null, "", url.toString());
}
/** sort order list starts **/
$(".sort-form-select").change(function(){
	var sortid = $(this).val();
	var lifestage = $(this).attr("lifestage");
	//alert(lifestage);
	var dvalue = $(this).attr("dvalue");
	var pager = $(this).attr("pager");
	var tags = $(this).attr("tags");
	var search = $(this).attr("search");
	var sort = $(this).attr("sort");
	var rtype = $(this).attr("resourcetypes");
	if(sortid == 'nothing'){


	}else{
		$('.siderbar-small-category').removeAttr('sort').attr('sort', sortid);
		$('#select-all-types').removeAttr('sort').attr('sort', sortid);
		$('#resource-search-input').removeAttr('sort').attr('sort', sortid);
		$('#select-stage-all').removeAttr('sort').attr('sort', sortid);
		$('.lifestagec').removeAttr('sort').attr('sort', sortid);
		$('.next-btn').removeAttr('sort').attr('sort', sortid);
		$('.pg-btn').removeAttr('sort').attr('sort', sortid);
		$('.prv-btn').removeAttr('sort').attr('sort', sortid);
		$('.search-prv-click').removeAttr('sort').attr('sort', sortid);
		$('.search-nxt-click').removeAttr('sort').attr('sort', sortid);
		$('.pg-btn-search').removeAttr('sort').attr('sort', sortid);
		$('.tag-click').removeAttr('sort').attr('sort', sortid);

		$('.autocomplete-tag-input').removeAttr('sort').attr('sort', sortid);

		$('.autocomplete-tag-list').removeAttr('sort').attr('sort', sortid);
	

		
		//console.log(lifestage);
		var postData=JSON.stringify({"sortid": sortid,"lifestage":lifestage, "search":search,"rtype": rtype, "tags": tags});
		$.ajax({  
			type: "POST",  
			url: $.web_url+"includes/core/resource_sort_search"+$.extn,
			dataType: 'JSON', //this is what we expect our returned data as  
			data: {data:postData},
			cache: false,  
			success: function(new_data)
			{
				var len = new_data.length;
				//$('.resource-column-new').html('');
				$('.resource-column-new').html('');
				$(".content-inner-page").addClass('hidden');
				//$(".resource-column-new").removeClass('hidden');
				$("#cd-"+name+ " span .fake-label").removeClass('fake-label').addClass('fake-label-active');
				for(var i=0; i < len; i++){
					var title = new_data[i].title;
					var catv = new_data[i].posttype;
					var url = new_data[i].postname;
					var message = new_data[i].message;
					if(catv == 'article'){
						var seo_dvalue = 'articles/';
					}else if(catv == 'calculator'){
						var seo_dvalue = 'calculators/';
					}else if(catv == 'video'){
						var seo_dvalue = 'videos/';
					}else if(catv == 'newsletter'){
						var seo_dvalue = 'newsletters/';
					}else if(catv == 'podcast'){
						var seo_dvalue = 'podcasts/';
					}else if(catv == 'toolkit'){
						var seo_dvalue = 'toolkits/';
					}else if(catv == 'booklet'){
						var seo_dvalue = 'booklets/';
					}else{}
					var tr_str = message;
					$(".resource-column-new").removeClass('hidden').append(tr_str);
				}
			}
		});
    }
        
		// 	 var postDatan=JSON.stringify({"dvalue": sortid});
		// 	 var sync2 = $.ajax({  
		// 								type: "POST",  
		// 								url: $.web_url+"includes/core/pagination_tags_check"+$.extn,
		// 								dataType: 'JSON', //this is what we expect our returned data as  
		// 								data: {data:postDatan},
		// 								cache: false,  
		// 								success: function(new_data)
		// 									{
		// 										$("#pagination-box-n").removeClass('hidden').html(new_data.message);
		// 										$("#pagination-box").addClass('hidden').html('');
		// 									}
		// 							});
		// $.when(sync1, sync2).done(function(result2, result1) {
  //   	console.log('both call finished');
		// });
    });

function spliceValueParam(list, value) {
  return list.replace(new RegExp(",?" + value + ",?"), function(match) {
      var begin_comma = match.charAt(0) === ',',
          last_comma;

      if (begin_comma &&
          (last_comma = match.charAt(match.length - 1) === ',')) {
        return ',';
      }
      return '';
    });
};

/** sort order list ends **/
 $(document).on('click', '.closetag', function()
	{
		$('.autocomplete-tag-list').removeAttr('style');
		//alert('close tag clicked');
		var tagname = $(this).attr("tagname");
		var tagid = $(this).attr("tagid");
		var tags = $(this).attr("tags");
		var rtypes = $(this).attr("resourcetypes");
		var sortid = $(this).attr("sort");
		var lifestage  = $(this).attr("lifestage");
		var search  = $(this).attr("search");
		//get current val
		var val = $(".siderbar-small-category").attr('tags');
		var newtag = spliceValueParam(val, tagid);
		if(newtag == ''){
			newtag0 = '0';
		}else{
			var newtag0 = newtag;
			$(".siderbar-small-category").removeAttr('tags').attr('tags', newtag0);
			$(".next-btn-tags").removeAttr('tagid').attr('tagid', newtag0);
			$(".pg-btn-tags").removeAttr('tagid').attr('tagid', newtag0);
		}
			
		//console.log(newstring);
		//var url = $(this).attr("url");
		// $(".resource-column-new").html('');
		// $(".autocomplete-tag-list").html('');
		$(".content-inner-page").removeClass('hidden').html('');
		$(".tgbtn"+tagid).html('').addClass('hidden');
		$(".tag-search-keyword").html('').addClass('hidden');
		//setQueryStringParameter('tag', tagid);
		var tag = GetURLParameter('tag');
		$('.resource-column-new').html('').fadeIn(5000).html('<div style="margin-left: 42%; margin-top: 17%; padding-bottom: 30%;"><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> loading</div>');
	  	if(newtag == ''){
	  		var postData=JSON.stringify({"dvalue": newtag0});
			var sync1 = $.ajax({  
				type: "POST",  
				url: $.web_url+"includes/core/resource_all_page"+$.extn,
				dataType: 'JSON', //this is what we expect our returned data as  
				data: {data:postData},
				cache: false,  
				success: function(new_data)
					{
						var len = new_data.length;
						$(".resource-column-new").html('');
						$("#cd-"+newtag0+ " span .fake-label").removeClass('fake-label').addClass('fake-label-active');
			            for(var i=0; i < len; i++){
			                var message = new_data[i].message;
			                var tr_str = message;
			                $(".resource-column-new").append(tr_str);
			            }
					}
			});
			 var postDatan=JSON.stringify({"dvalue": newtag0, "lifestage": lifestage, "rtypes": rtypes, "tags": tags,"sort":sortid, "search": search});
			 var sync2 = $.ajax({  
				type: "POST",  
				url: $.web_url+"includes/core/pagination_check"+$.extn,
				dataType: 'JSON', //this is what we expect our returned data as  
				data: {data:postDatan},
				cache: false,  
				success: function(new_data)
				{
					$("#pagination-box-n").removeClass('hidden').html(new_data.message);
					$("#pagination-box").addClass('hidden').html('');
				}
			});
	  		}else{
	  			var postData=JSON.stringify({"dvalue": newtag0, "lifestage": lifestage, "rtypes": rtypes, "tags": tags,"sort":sortid, "search": search});
				var sync1 = $.ajax({  
					type: "POST",  
					url: $.web_url+"includes/core/resource_tag_search"+$.extn,
					dataType: 'JSON', //this is what we expect our returned data as  
					data: {data:postData},
					cache: false,  
					success: function(new_data)
						{
							var len = new_data.length;
							//$('.resource-column-new').html('');
							$('.resource-column-new').html('');
							$(".content-inner-page").addClass('hidden');
							//$(".resource-column-new").removeClass('hidden');
							$("#cd-"+name+ " span .fake-label").removeClass('fake-label').addClass('fake-label-active');
							for(var i=0; i < len; i++){
								var message = new_data[i].message;
								var tr_str = message;
								$(".resource-column-new").removeClass('hidden').append(tr_str);
							}
					}
			});
			 var postDatan=JSON.stringify({"dvalue":"tags","page":"1","tagid": newtag0, "lifestage": lifestage, "rtypes": rtypes, "tags": tags,"sort":sortid, "search": search});
			 var sync2 = $.ajax({  
				type: "POST",  
				url: $.web_url+"includes/core/pagination_tags_check"+$.extn,
				dataType: 'JSON', //this is what we expect our returned data as  
				data: {data:postDatan},
				cache: false,  
				success: function(new_data)
				{
					$("#pagination-box-n").removeClass('hidden').html(new_data.message);
					$("#pagination-box").addClass('hidden').html('');
				}
			});
	  		}
		$.when(sync1, sync2).done(function(result2, result1) {
    	console.log('both call finished');
		});
 });
  function parseURL(url) {
    const [domain, rest] = url.split("?");
    const args = {};
    for(const [k, v] of rest.split("&").map(pair => pair.split("=")))
      args[k] = v;
    return { domain, args };
 }
 //disable enter on input tag starts
 $('#resource-search-input').keypress(function(event) {
    if (event.which == 13) {
        event.preventDefault();
    }
});
 var input = $('.autocomplete-tag-input');
  input.on('keydown', function() {
    var key = event.keyCode || event.charCode;

    if( key == 8 || key == 46 ){
        $('.tag-search-keyword').html('').removeAttr('style');
    }
  });
 //disable enter on input tag ends 
 $(document).on('click', '.tag-click', function()
 	{
		var lifestage = $('.tagsbox').attr("lifestage");
		//$('.autocomplete-tag-list');
		var tagidn = $(this).attr("tagid");
		$('.tag-search-keyword').html('').removeAttr('style');
		var typestag = $('.siderbar-small-category').attr("tags");
		// var valuen = tagidn+',';
		 alert(tagidn);
		if(typestag == '0'){
			//var typestag = $('.siderbar-small-category').attr("tags");
			var valuen = tagidn;
			//alert(valuen);
			$('.siderbar-small-category').removeAttr("tags").attr("tags", valuen);
			$('.lifestagec').removeAttr("tags").attr("tags", valuen);
			$('#resource-search-input').removeAttr("tags").attr("tags", valuen);
			$('.sort-form-select').removeAttr("tags").attr("tags", valuen);
		}else{
			var vn = $('.siderbar-small-category').attr("tags");
			var valuen = tagidn+','+vn;
			//alert(valuen);
			$('.siderbar-small-category').removeAttr("tags").attr("tags", valuen);
			$('.lifestagec').removeAttr("tags").attr("tags", valuen);
			$('#resource-search-input').removeAttr("tags").attr("tags", valuen);
			$('.sort-form-select').removeAttr("tags").attr("tags", valuen);
			$('.sort-form-select').removeAttr("tags").attr("tags", valuen);
		}
		$(".next-btn-tags").removeAttr('tagid').attr('tagid', valuen);
		$(".pg-btn-tags").removeAttr('tagid').attr('tagid', valuen);
		//var dvalue = $(this).attr("dvalue");
		var tagname = $(this).attr("tagname");
		var tags = $(this).attr("tags");
		var rtype = $(this).attr("resourcetypes");
		//var url = $(this).attr("url");
		var dvalue = $(this).attr("dvalue");
		var pager = $(this).attr("pager");
		var type = $(this).attr("type");
		var url = (window.location.href);
		var new_val = $(this).attr("dvalue");
		var cur_val = GetURLParameter('tag');
		var rtypes = $(this).attr("resourcetypes");
		var sortid = $(this).attr("sort");
		var lifestage  = $(this).attr("lifestage");
		var search  = $(this).attr("search");

		var url2 = (window.location.href);
		mnurl = url2.split('&pager')[0];
		murl = (mnurl);
		$(".autocomplete-tag-list").append('<span class="tag ng-scope tgbtn'+dvalue+'"><span tagid="'+dvalue+'" tagname="'+tagname+'" class="tagbtn">'+tagname+'</span><div class="close closetag" tagid="'+dvalue+'" url="'+url+'" tagname="'+tagname+'"></div></span>');
		$('.tagsbox').val('').removeAttr('checked').removeAttr('selected');
		var postData=JSON.stringify({"dvalue": valuen, "lifestage": lifestage, "rtypes": rtypes, "tags": tags,"sort":sortid,"search": search});
		var sync1 = $.ajax({  
			type: "POST",  
			url: $.web_url+"includes/core/resource_tag_search"+$.extn,
			dataType: 'JSON', //this is what we expect our returned data as  
			data: {data:postData},
			cache: false,  
			success: function(new_data)
			{
				var len = new_data.length;
				$(".resource-column-new").html('');
				$(".content-inner-page").addClass('hidden');
				$(".resource-column-new").removeClass('hidden');
				$("#cd-"+name+ " span .fake-label").removeClass('fake-label').addClass('fake-label-active');
			    for(var i=0; i < len; i++){
			        var message = new_data[i].message;
			        var tr_str = message;
			        $(".resource-column-new").append(tr_str);
			    }
			}
			});
			 var postDatan=JSON.stringify({"dvalue": 'tags', "page": pager, "tagid": valuen, "tags": tags,  "lifestage": lifestage, "rtypes": rtypes, "sort":sortid, "search": search});
			 var sync2 = $.ajax({  
				type: "POST",  
				url: $.web_url+"includes/core/pagination_tags_n_check"+$.extn,
				dataType: 'JSON', //this is what we expect our returned data as  
				data: {data:postDatan},
				cache: false,  
				success: function(new_data)
				{
					var messagenow = new_data.message;
					$("#pagination-box-n").html('').removeClass('hidden').html(messagenow);
					$("#pagination-box").addClass('hidden').html('');
				}
			});
		$.when(sync1, sync2).done(function(result2, result1) {
    	console.log('both call finished');
		});
	});
	$(document).on('click', '.life-stage-siderbar', function()
		{
		  	var dvalue = $(this).attr("dvalue");
		  	var pager = $(this).attr("pager");
		 	var inform = $(this).attr("inform");
			var tags = $(this).attr("tags");
			//alert(pager);
			//var postData=JSON.stringify({"dvalue": dvalue});
			$('.content-inner-page').addClass('hidden').fadeIn(2000);
			$('.resource-column-new').removeClass('hidden').fadeIn(5000).html('<div style="margin-left: 42%; margin-top: 17%; padding-bottom: 30%;"><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> loading</div>');
			if (history.pushState) {
				window.history.pushState("object or string", "BALANCE Financial Fitness Program  |  Resources", $.web_url+$.resources+'?type='+dvalue+'&pager=1');
			} else {
				document.location.href = $.web_url+$.resources+'?type='+dvalue+'&pager=1';
			}
	      var postData=JSON.stringify({"dvalue": dvalue, "action": inform, "tags": tags});
			var sync1 = $.ajax({  
				type: "POST",  
				url: $.web_url+"includes/core/resource_search"+$.extn,
				dataType: 'JSON', //this is what we expect our returned data as  
				data: {data:postData},
				cache: false,  
				success: function(new_data)
					{
						var len = new_data.length;
						$(".resource-column-new").html('');
						$("#cd-"+dvalue+ " span .fake-label").removeClass('fake-label').addClass('fake-label-active');
			            for(var i=0; i < len; i++){
			                var title = new_data[i].title;
			                var catv = new_data[i].dvalue;
			                var url = new_data[i].slug;
			                var tpage = new_data[i].pages;
			                var message = new_data[i].message;
			                if(catv == 'article'){
								var seo_dvalue = 'articles/';
							}else if(catv == 'calculator'){
								var seo_dvalue = 'calculators/';
							}else if(catv == 'video'){
								var seo_dvalue = 'videos/';
							}else if(catv == 'newsletter'){
								var seo_dvalue = 'newsletters/';
							}else if(catv == 'podcast'){
								var seo_dvalue = 'podcasts/';
							}else if(catv == 'toolkit'){
								var seo_dvalue = 'toolkits/';
							}else if(catv == 'booklet'){
								var seo_dvalue = 'booklets/';
							}else{}
			                var tr_str = message;
			                $(".resource-column-new").append(tr_str);
			            }
					}
			});
			 var postDatan=JSON.stringify({"dvalue": dvalue, "page": pager, "tags": tags});
			 var sync2 = $.ajax({  
				type: "POST",  
				url: $.web_url+"includes/core/pagination_new_check"+$.extn,
				dataType: 'JSON', //this is what we expect our returned data as  
				data: {data:postDatan},
				cache: false,  
				success: function(new_data)
				{
					$("#pagination-box-n").removeClass('hidden').html(new_data.message);
					$("#pagination-box").addClass('hidden').html('');
				}
			});
		$.when(sync1, sync2).done(function(result2, result1) {
    	console.log('both call finished');
		});
	});		
	$(document).on('click', '.siderbar-small-category', function()
		{
			//alert('this clicked');
		  var dvalue = $(this).attr("dvalue");
		  var pager = $(this).attr("pager");
		  var inform = $(this).attr("inform");
		  var active = $(this).attr("active");
		  var lifestage = $(this).attr("lifestage");
		  var search = $(this).attr("search");
		  var sort = $(this).attr("sort");
		  var tags = $(this).attr("tags");
		  //alert(pager);
		  $('#select-stage-all').removeAttr('resourcetypes').attr('resourcetypes', dvalue);
		  $('.siderbar-small-category').removeAttr('active').attr('active', '0');
		  $('.sort-form-select').removeAttr('resourcetypes').attr('resourcetypes', dvalue);
		  //$('.fake-label').attr('style', '');
	        $('.selectalltypes').attr('style', '');
	        $('.resourcetype').attr('style', '');
	        //$('#select-stage-all').attr('style', '');
	        $('.tagicon').attr('style', '');
		  //alert(pager);
		  //var postData=JSON.stringify({"dvalue": dvalue});
	      $('.lifestagec').removeAttr('resourcetypes').attr('resourcetypes', dvalue);
		  $('.tag-click').removeAttr('resourcetypes').attr('resourcetypes', dvalue);
		  $('.autocomplete-tag-input').removeAttr('resourcetypes').attr('resourcetypes', dvalue);
		  $('.autocomplete-tag-list').removeAttr('resourcetypes').attr('resourcetypes', dvalue);
		 
	      $('.tagsbox').removeAttr('resourcetypes').attr('resourcetypes', dvalue);
	      $('.next-btn').removeAttr('lifestage').attr('lifestage', dvalue);
		  $('.next-btn').removeAttr('type').attr('type', dvalue);
		 // alert(dvalue);
		  $('.pg-btn').removeAttr('typevalue').attr('typevalue', dvalue);
	      $('prv-btn').removeAttr('lifestage').attr('lifestage', dvalue);
	      $('#resource-search-input').removeAttr('resourcetypes').attr('resourcetypes', dvalue);
	      $('.content-inner-page').addClass('hidden').fadeIn(2000);
	      if(active > 0){
	      	$('#cb-'+dvalue).removeAttr('active').attr('active', '0');
	      	$('.fake-label').attr('style', '');
	        $('.tagicon').attr('style', '');
	        $('.lifestagec').removeAttr('resourcetypes').attr('resourcetypes', '0');
	        $('.selectalltypes').css({
			    "background": "#02a69c",
    			"color": "#fff",
    			"cursor": "pointer"
			});
	        var dvaluenb = '0';
	        var postData=JSON.stringify({"dvalue": dvaluenb,"lifestage": lifestage, "pager": pager, "search": search, "sort": sort, "tags": tags});
			var sync1 = $.ajax({  
				type: "POST",  
				url: $.web_url+"includes/core/resource_all_page"+$.extn,
				dataType: 'JSON', //this is what we expect our returned data as  
				data: {data:postData},
				cache: false,  
				success: function(new_data)
					{
						var len = new_data.length;
						$(".resource-column-new").html('');
						$("#cd-"+dvalue+ " span .fake-label").removeClass('fake-label').addClass('fake-label-active');
			            for(var i=0; i < len; i++){
			                var title = new_data[i].title;
			                var catv = new_data[i].dvalue;
			                var url = new_data[i].slug;
			                var tpage = new_data[i].pages;
			                var message = new_data[i].message;
			                if(catv == 'article'){
								var seo_dvalue = 'articles/';
							}else if(catv == 'calculator'){
								var seo_dvalue = 'calculators/';
							}else if(catv == 'video'){
								var seo_dvalue = 'videos/';
							}else if(catv == 'newsletter'){
								var seo_dvalue = 'newsletters/';
							}else if(catv == 'podcast'){
								var seo_dvalue = 'podcasts/';
							}else if(catv == 'toolkit'){
								var seo_dvalue = 'toolkits/';
							}else if(catv == 'booklet'){
								var seo_dvalue = 'booklets/';
							}else{}
			                var tr_str = message;
			                $(".resource-column-new").append(tr_str);
			            }
					}
			});
			 var postDatan=JSON.stringify({"dvalue": dvaluenb,"lifestage": lifestage, "pager": pager, "search": search, "sort": sort, "tags": tags});
			 var sync2 = $.ajax({  
				type: "POST",  
				url: $.web_url+"includes/core/pagination_check"+$.extn,
				dataType: 'JSON', //this is what we expect our returned data as  
				data: {data:postDatan},
				cache: false,  
				success: function(new_data)
				{
					$("#pagination-box-n").removeClass('hidden').html(new_data.message);
					$("#pagination-box").addClass('hidden').html('');
				}
			});
	      }else{
	      	//alert('this clicked reached line 985');
	      	$('#cb-'+dvalue).removeAttr('active').attr('active', '0');
	      	$('.active-'+dvalue).css({
	      	    "height": "28px",
			    "background": "#02a69c",
    			"color": "#fff",
    			"cursor": "pointer"
			});
	     	$('.icon-'+dvalue).css({
			    "background": "#02a69c",
    			"color": "#fff",
    			"cursor": "pointer"
			});
			var postData=JSON.stringify({"dvalue": dvalue, "action": inform, "lifestage": lifestage, "search": search, "sort": sort, "tags": tags});
			var postcheck = $.ajax({  
				type: "POST",  
				url: $.web_url+"includes/core/resource_search"+$.extn,
				dataType: 'JSON', //this is what we expect our returned data as  
				data: {data:postData},
				cache: false,  
				success: function(postlist)
					{
						var len = postlist.length;
						$(".resource-column-new").html('');
						$("#cd-"+dvalue+ " span .fake-label").removeClass('fake-label').addClass('fake-label-active');
			            for(var i=0; i < len; i++){
			                var message = postlist[i].message;
			                var tr_str = message;
			                $(".resource-column-new").append(tr_str);
			            }
					}
			});
			 var postDatan=JSON.stringify({"dvalue": dvalue, "pager": pager, "lifestage": lifestage, "search": search, "sort": sort, "tags": tags});
			 var paginationcheck = $.ajax({  
				type: "POST",  
				url: $.web_url+"includes/core/pagination_new_check"+$.extn,
				dataType: 'JSON', //this is what we expect our returned data as  
				data: {data:postDatan},
				cache: false,  
				success: function(pagination)
				{
					console.log('click at line 1033');
					$("#pagination-box-n").removeClass('hidden').html(pagination.message);
					$("#pagination-box").addClass('hidden').html('');
				}
			});
	      }
	      $('.resource-column-new').removeClass('hidden').fadeIn(5000).html('<div style="margin-left: 42%; margin-top: 17%; padding-bottom: 30%;"><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> loading</div>');
	      if (history.pushState) {
			window.history.pushState("object or string", "BALANCE Financial Fitness Program  |  Resources", $.web_url+$.resources+'?type='+dvalue+'&pager=1');
		} else {
			document.location.href = $.web_url+$.resources+'?type='+dvalue+'&pager=1';
		}
		$.when(postcheck, paginationcheck).done(function(postlist, pagination) {
    	console.log('both post check and pagination check finishd');
		});
	});
	function getUrlVars() {
	    var vars = {};
	    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi,    
	    function(m,key,value) {
	      vars[key] = value;
	    });
	    return vars;
	  }
	$(document).on('click', '.pg-btn', function()
		{
			var type = $(this).attr("typevalue");
			var pager = $(this).attr("pagerv");
			var lifestage = $(this).attr("lifestage");
			var search = $(this).attr("search");
			var resourcetypes = $(this).attr("resourcetypes");
			var sortid = $(this).attr("sort");
			var tags = $(this).attr("tags");
			$(".content-inner-page").addClass('hidden').html('').fadeIn(5000);
			$(".resource-column-new").html('').html('<div style="margin-left: 42%; margin-top: 17%; padding-bottom: 30%;"><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> loading</div>');
			var url = (window.location.href);
      		mnurl = url.split('&pager')[0];
      		murl = (mnurl);
      if (history.pushState) {
				window.history.pushState("object or string", "BALANCE Financial Fitness Program  |  Resources", murl+'&pager='+pager);
			} else {
				document.location.href = murl+'&pager='+pager;
			}
			var postData=JSON.stringify({"type": type, "pager": pager, "lifestage": lifestage, "search": search,"rtype":resourcetypes,"sort":sortid, "tags": tags});
			var sync1 = $.ajax({  
				type: "POST",  
				url: $.web_url+"includes/core/resource_pagination_content"+$.extn,
				dataType: 'JSON', //this is what we expect our returned data as  
				data: {data:postData},
				cache: false,  
				success: function(new_data){
						var len = new_data.length;
						$(".resource-column-new").html('');
						$(".content-inner-page").addClass('hidden');
						$(".resource-column-new").removeClass('hidden');
						$("#cd-"+name+ " span .fake-label").removeClass('fake-label').addClass('fake-label-active');
			            for(var i=0; i < len; i++){
			                var title = new_data[i].title;
			                var catv = new_data[i].posttype;
			                var url = new_data[i].postname;
			                var message = new_data[i].message;
			                if(catv == 'article'){
								var seo_dvalue = 'articles/';
							}else if(catv == 'calculator'){
								var seo_dvalue = 'calculators/';
							}else if(catv == 'video'){
								var seo_dvalue = 'videos/';
							}else if(catv == 'newsletter'){
								var seo_dvalue = 'newsletters/';
							}else if(catv == 'podcast'){
								var seo_dvalue = 'podcasts/';
							}else if(catv == 'toolkit'){
								var seo_dvalue = 'toolkits/';
							}else if(catv == 'booklet'){
								var seo_dvalue = 'booklets/';
							}else{}
			                var tr_str = message;
			                var pagern = parseInt(pager) + 1;
			                $(".resource-column-new").append(tr_str);
			                $(".pg-btn").removeAttr("typevalue").attr('typevalue', type);
			                $(".next-btn").removeAttr("type").attr('type', type).removeAttr("pager").attr('pager', pagern);
			            }
					}
			});
			var postDatan=JSON.stringify({"dvalue": type, "pager": pager, "lifestage": lifestage, "search": search,"sort":sortid, "tags": tags});
			 var sync2 = $.ajax({  
				type: "POST",  
				url: $.web_url+"includes/core/pagination_new_check"+$.extn,
				dataType: 'JSON', //this is what we expect our returned data as  
				data: {data:postDatan},
				cache: false,  
				success: function(new_data)
				{
					$("#pagination-box-n").removeClass('hidden').html(new_data.message);
					$("#pagination-box").addClass('hidden').html('');
				}
			});
		$.when(sync1, sync2).done(function(result2, result1) {
    		console.log('both pagination call finished');
		});
		 //  var dvalue = $(this).attr("typevalue");
		 //  var pagerv = $(this).attr("pagerv");
		 //  var lifestage = $(this).attr("lifestage");
		 //  $('.pagination-box').addClass('hidden').fadeOut(2000);
	  //     $('.pagination-box-n').removeClass('hidden');
	  //     //alert(dvalue+'---'+pagerv);
   //    	  //var fType = getUrlVars()["type"];
   //    	  //var fpager = getUrlVars()["pager"];
   //    	  //var totalurl = parseInt(fpager) + 1;
   //         //alert(fType+'--type----pager--'+fpager);
	  //     if (history.pushState) {
			// window.history.pushState("object or string", "BALANCE Financial Fitness Program  |  Resources", $.web_url+$.resources+'?type='+dvalue+'&pager='+pagerv);
		 //  } else {
			// document.location.href = $.web_url+$.resources+'?type='+dvalue+'&pager='+pagerv;
		 //  }
	  //     var postData=JSON.stringify({"type": dvalue, "page": pagerv});
			// var sync1 = $.ajax({  
			// 	type: "POST",  
			// 	url: $.web_url+"includes/core/pgn_btn_resource"+$.extn,
			// 	dataType: 'JSON', //this is what we expect our returned data as  
			// 	data: {data:postData},
			// 	cache: false,  
			// 	success: function(new_data)
			// 		{
			// 			var len = new_data.length;
			// 			var tpage = new_data.totalpagess;
			// 			$(".content-inner-page").html('').addClass('hidden');
			// 			$(".resource-column-new").removeClass('hidden').html('');
			// 			$("#cd-"+dvalue+ " span .fake-label").removeClass('fake-label').addClass('fake-label-active');
			// 			//alert(len+'the length');
			//             for(var i=0; i < len; i++){
			//                 var message = new_data[i].message;
			//                 var tr_str = message;
			//                 $(".resource-column-new").append(tr_str);
			//                  //$(".content-inner-page").addClass('hidden');
			//                   $(".pg-btn").removeAttr("typevalue").attr('typevalue', dvalue);
			//             }
			// 		}
			// });
			// var postDatan=JSON.stringify({"dvalue": dvalue, "page": pagerv, "lifestage": "0"});
			//  var sync2 = $.ajax({  
			// 							type: "POST",  
			// 							url: $.web_url+"includes/core/pagination_new_check"+$.extn,
			// 							dataType: 'JSON', //this is what we expect our returned data as  
			// 							data: {data:postDatan},
			// 							cache: false,  
			// 							success: function(new_data)
			// 								{
			// 									var messagenow = new_data.message;
			// 									$("#pagination-box-n").html('').removeClass('hidden').html(messagenow);
			// 									$("#pagination-box").addClass('hidden').html('');
			// 								}
			// 						});
	});
	$(document).on('click', '.pg-btn-tags', function()
		{
		  var dvalue = $(this).attr("typevalue");
		  var tagid = $(this).attr("tagid");
		  var tags = $(this).attr("tags");
		  var pagerv = $(this).attr("pagerv");
		  $('.pagination-box').addClass('hidden').fadeOut(2000);
	      $('.pagination-box-n').removeClass('hidden').fadeIn(5000);
	      //alert(dvalue+'---'+pagerv);
      	  //var fType = getUrlVars()["type"];
      	  //var fpager = getUrlVars()["pager"];
      	  //var totalurl = parseInt(fpager) + 1;
           //alert(fType+'--type----pager--'+fpager);
	      if (history.pushState) {
			window.history.pushState("object or string", "BALANCE Financial Fitness Program  |  Resources", $.web_url+$.resources+'?type='+dvalue+'&pager='+pagerv);
		  } else {
			document.location.href = $.web_url+$.resources+'?type='+dvalue+'&pager='+pagerv;
		  }
	      var postData=JSON.stringify({"type": dvalue, "page": pagerv, "tagid": tagid, "tags": tags});
     var postData=JSON.stringify({"type": dvalue, "page": pagerv, "tagid": tagid, "tags": tags});
			var sync1 = $.ajax({  
				type: "POST",  
				url: $.web_url+"includes/core/pg_btn_tags"+$.extn,
				dataType: 'JSON', //this is what we expect our returned data as  
				data: {data:postData},
				cache: false,  
				success: function(new_data)
					{
						var len = new_data.length;
						var tpage = new_data.totalpagess;
						$(".content-inner-page").html('').addClass('hidden');
						$(".resource-column-new").removeClass('hidden').html('');
						$("#cd-"+dvalue+ " span .fake-label").removeClass('fake-label').addClass('fake-label-active');
						//alert(len+'the length');
			            for(var i=0; i < len; i++){
			                var title = new_data[i].title;
			                var catv = new_data[i].dvalue;
			                var url = new_data[i].slug;
			                var tpage = new_data[i].pages;
			                var message = new_data[i].message;
			                if(catv == 'article'){
							var seo_dvalue = 'articles/';
							}else if(catv == 'calculator'){
								var seo_dvalue = 'calculators/';
							}else if(catv == 'video'){
								var seo_dvalue = 'videos/';
							}else if(catv == 'newsletter'){
								var seo_dvalue = 'newsletters/';
							}else if(catv == 'podcast'){
								var seo_dvalue = 'podcasts/';
							}else if(catv == 'toolkit'){
								var seo_dvalue = 'toolkits/';
							}else if(catv == 'booklet'){
								var seo_dvalue = 'booklets/';
							}else{}
			                var tr_str = message;
			                $(".resource-column-new").append(tr_str);
			                 //$(".content-inner-page").addClass('hidden');
			                  $(".pg-btn").removeAttr("typevalue").attr('typevalue', dvalue);
			            }
					}
				});
			var postDatan=JSON.stringify({"dvalue": dvalue, "page": pagerv, "tagid": tagid, "tags": tags});
			var sync2 = $.ajax({  
				type: "POST",  
				url: $.web_url+"includes/core/pagination_tags_n_check"+$.extn,
				dataType: 'JSON', //this is what we expect our returned data as  
				data: {data:postDatan},
				cache: false,  
				success: function(new_data)
				{
					var messagenow = new_data.message;
					$("#pagination-box-n").html('').removeClass('hidden').html(messagenow);
					$("#pagination-box").addClass('hidden').html('');
				}
			});
	});
	$(document).on('click', '.next-btn-tags', function()
		{
			var type = $(this).attr("type");
			var pager = $(this).attr("pager");
			var tagid = $(this).attr("tagid");
			var tags = $(this).attr("tags");
			$(".content-inner-page").addClass('hidden').html('').fadeIn(5000);
			$(".resource-column-new").html('').html('<div style="margin-left: 42%; margin-top: 17%; padding-bottom: 30%;"><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> loading</div>');
			var url = (window.location.href);
      mnurl = url.split('&pager')[0];
      murl = (mnurl);
      if (history.pushState) {
				window.history.pushState("object or string", "BALANCE Financial Fitness Program  |  Resources", murl+'&pager='+pager);
			} else {
				document.location.href = murl+'&pager='+pager;
			}
			var postData=JSON.stringify({"type": type, "page": pager, "tagid": tagid, "tags": tags});
			var sync1 = $.ajax({  
				type: "POST",  
				url: $.web_url+"includes/core/pg_btn_tags"+$.extn,
				dataType: 'JSON', //this is what we expect our returned data as  
				data: {data:postData},
				cache: false,  
				success: function(new_data)
					{
						var len = new_data.length;
						$(".resource-column-new").html('');
						$(".content-inner-page").addClass('hidden');
						$(".resource-column-new").removeClass('hidden');
						$("#cd-"+name+ " span .fake-label").removeClass('fake-label').addClass('fake-label-active');
			            for(var i=0; i < len; i++){
			                var title = new_data[i].title;
			                var catv = new_data[i].posttype;
			                var url = new_data[i].postname;
			                var message = new_data[i].message;
			                if(catv == 'article'){
								var seo_dvalue = 'articles/';
							}else if(catv == 'calculator'){
								var seo_dvalue = 'calculators/';
							}else if(catv == 'video'){
								var seo_dvalue = 'videos/';
							}else if(catv == 'newsletter'){
								var seo_dvalue = 'newsletters/';
							}else if(catv == 'podcast'){
								var seo_dvalue = 'podcasts/';
							}else if(catv == 'toolkit'){
								var seo_dvalue = 'toolkits/';
							}else if(catv == 'booklet'){
								var seo_dvalue = 'booklets/';
							}else{}
			                var tr_str = message;
			                var pagern = parseInt(pager) + 1;
			                $(".resource-column-new").append(tr_str);
			                $(".pg-btn-tags").removeAttr("tagid").attr('tagid', tagid).removeAttr("typevalue").attr('typevalue', type);
			                $(".next-btn-tags").removeAttr("tagid").attr('tagid', tagid).removeAttr("type").attr('type', type).removeAttr("pager").attr('pager', pagern);
			            }
					}
				});
			var postDatan=JSON.stringify({"dvalue": type, "tagid": tagid, "page": pager, "tags": tags});
			 var sync2 = $.ajax({  
				type: "POST",  
				url: $.web_url+"includes/core/pagination_tags_n_check"+$.extn,
				dataType: 'JSON', //this is what we expect our returned data as  
				data: {data:postDatan},
				cache: false,  
				success: function(new_data)
				{
					var messagenow = new_data.message;
					$("#pagination-box-n").html('').removeClass('hidden').html(messagenow);
					$("#pagination-box").addClass('hidden').html('');
				}
			});
		$.when(sync1, sync2).done(function(result2, result1) {
    	console.log('both call finished');
		});
	});
	$(document).on('click', '.next-btn-lifestage', function()
		{
			var type = $(this).attr("type");
			var pager = $(this).attr("pager");
			var tagid = $(this).attr("tagid");
			var rtype = $(this).attr("rtype");
			var search = $(this).attr("search");
			var tags = $(this).attr("tags");
			var sortid =  $(this).attr("sort");
			$(".content-inner-page").addClass('hidden').html('').fadeIn(5000);
			$(".resource-column-new").html('').html('<div style="margin-left: 42%; margin-top: 17%; padding-bottom: 30%;"><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> loading</div>');
			var url = (window.location.href);
			mnurl = url.split('&pager')[0];
			murl = (mnurl);
		//    if (history.pushState) {
			// 	window.history.pushState("object or string", "BALANCE Financial Fitness Program  |  Resources", murl+'&pager='+pager);
			// } else {
			// 	document.location.href = murl+'&pager='+pager;
			// }
			var postData=JSON.stringify({"type": tagid, "page": pager, "rtype": rtype, "searchquery": search,"tags":tags,"sort":sortid});
			var sync1 = $.ajax({  
				type: "POST",  
				url: $.web_url+"includes/core/pg_btn_lifestage"+$.extn,
				dataType: 'JSON', //this is what we expect our returned data as  
				data: {data:postData},
				cache: false,  
				success: function(new_data)
					{
						var len = new_data.length;
						$(".resource-column-new").html('');
						$(".content-inner-page").addClass('hidden');
						$(".resource-column-new").removeClass('hidden');
						$("#cd-"+name+ " span .fake-label").removeClass('fake-label').addClass('fake-label-active');
			            for(var i=0; i < len; i++){
			                var title = new_data[i].title;
			                var catv = new_data[i].posttype;
			                var url = new_data[i].postname;
			                var message = new_data[i].message;
			                if(catv == 'article'){
								var seo_dvalue = 'articles/';
							}else if(catv == 'calculator'){
								var seo_dvalue = 'calculators/';
							}else if(catv == 'video'){
								var seo_dvalue = 'videos/';
							}else if(catv == 'newsletter'){
								var seo_dvalue = 'newsletters/';
							}else if(catv == 'podcast'){
								var seo_dvalue = 'podcasts/';
							}else if(catv == 'toolkit'){
								var seo_dvalue = 'toolkits/';
							}else if(catv == 'booklet'){
								var seo_dvalue = 'booklets/';
							}else{}
			                var tr_str = message;
			                var pagern = parseInt(pager) + 1;
			                $(".resource-column-new").append(tr_str);
			                $(".pg-btn-tags").removeAttr("tagid").attr('tagid', tagid).removeAttr("typevalue").attr('typevalue', type);
			                $(".next-btn-tags").removeAttr("tagid").attr('tagid', tagid).removeAttr("type").attr('type', type).removeAttr("pager").attr('pager', pagern);
			            }
					}
				});
			var postDatan=JSON.stringify({"tagid": tagid, "page": pager, "rtypes": rtype, "searchquery": search,"tags":tags});
			var sync2 = $.ajax({  
				type: "POST",  
				url: $.web_url+"includes/core/pagination_life_stage_check"+$.extn,
				dataType: 'JSON', //this is what we expect our returned data as  
				data: {data:postDatan},
				cache: false,  
				success: function(new_data)
				{
					var messagenow = new_data.message;
					$("#pagination-box-n").html('').removeClass('hidden').html(messagenow);
					$("#pagination-box").addClass('hidden').html('');
				}
			});
		$.when(sync1, sync2).done(function(result2, result1) {
    	console.log('both call finished');
		});
			
	});
	$(document).on('click', '.lifestagec', function()
		{
			console.log('lifestage clicked');
			var dvalue = $(this).attr("dvalue");
         	var pager = $(this).attr("pager");
         	var resourcetypes = $(this).attr("resourcetypes");
            var active = $(this).attr("active");
            var lifestage = $(this).attr("lifestage");
            var search = $(this).attr("search");
			var tags = $(this).attr("tags");
			var sortid =  $(this).attr("sort");
            $('.lifestagec').removeAttr('active').attr('active', '0');
			$('.sort-form-select').removeAttr('lifestage').attr('lifestage', dvalue);
            $('#resource-search-input').removeAttr('lifestage').attr('lifestage', dvalue);
            $('.lfstyle').removeAttr('style');
            $('.checkattr'+dvalue).removeAttr('style').removeAttr('active').attr('active', '1');
				$('.lifestage'+dvalue).css({
					"background": "#02a69c",
					"color": "#fff",
					"cursor": "pointer"
				});
			$('#select-stage-all').attr('style', '');
			$('#select-all-types').removeAttr('lifestage').attr('lifestage', dvalue);
            $(".siderbar-small-category").removeAttr('lifestage').attr('lifestage', dvalue);
			$('.tag-click').removeAttr('lifestage').attr('lifestage', dvalue);
			$('.autocomplete-tag-input').removeAttr('lifestage').attr('lifestage', dvalue);
			$('.autocomplete-tag-list').removeAttr('lifestage').attr('lifestage', dvalue);
		  
            //console.log({"dvalue": dvalue, "page": pager, "active": active, "lifestage": lifestage, "search": search, "rtype": resourcetypes});
            var postData=JSON.stringify({"type": dvalue, "page": pager, "rtype": resourcetypes, "searchquery": search,"tags":tags,"sort":sortid});
            /** action starts here **/
            var sync1 = $.ajax({  
				type: "POST",  
				url: $.web_url+"includes/core/pg_btn_lifestage"+$.extn,
				dataType: 'JSON', //this is what we expect our returned data as  
				data: {data:postData},
				cache: false,  
				success: function(new_data)
					{
						var len = new_data.length;
						$(".resource-column-new").html('');
						$(".content-inner-page").addClass('hidden');
						$(".resource-column-new").removeClass('hidden');
						$("#cd-"+name+ " span .fake-label").removeClass('fake-label').addClass('fake-label-active');
			            for(var i=0; i < len; i++){
			            	console.log('im reaching at line 1441');
			                var message = new_data[i].message;
			                var tr_str = message;
			                //var pagern = parseInt(pager) + 1;
			                $(".resource-column-new").append(tr_str);
			            }
					}
			});
			console.log('im reaching at line 1449');
			var postDatan=JSON.stringify({"tagid": dvalue, "page": pager, "rtypes": resourcetypes, "searchquery": search,"tags":tags,"sort":sortid});
			console.log('im reaching at line 1451');
			var sync2 = $.ajax({  
				type: "POST",  
				url: $.web_url+"includes/core/pagination_life_stage_check"+$.extn,
				dataType: 'JSON', //this is what we expect our returned data as  
				data: {data:postDatan},
				cache: false,  
				success: function(new_data)
				{
					var messagenow = new_data.message;
					console.log('im reaching at line 1459');
					$("#pagination-box-n").html('').removeClass('hidden').html(messagenow);
					$("#pagination-box").addClass('hidden').html('');
				}
			});
		$.when(sync2, sync1).done(function(result2, result1) {
    	console.log('lifestage both result and pagination clicked' + dvalue);
		});
            /** action ends here **/
	});
	$(document).on('click', '.prv-btn-lifestage', function()
		{
			var type = $(this).attr("type");
			var pager = $(this).attr("pager");
			var tagid = $(this).attr("tagid");
			var rtype = $(this).attr("rtype");
			var search = $(this).attr("search");
			var tags = $(this).attr("tags");
			var sortid =  $(this).attr("sort");
			$(".content-inner-page").addClass('hidden').html('').fadeIn(5000);
			$(".resource-column-new").html('').html('<div style="margin-left: 42%; margin-top: 17%; padding-bottom: 30%;"><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> loading</div>');
			var url = (window.location.href);
			mnurl = url.split('&pager')[0];
			murl = (mnurl);
   //    if (history.pushState) {
			// 	window.history.pushState("object or string", "BALANCE Financial Fitness Program  |  Resources", murl+'&pager='+pager);
			// } else {
			// 	document.location.href = murl+'&pager='+pager;
			// }
			var postData=JSON.stringify({"type": tagid, "page": pager, "rtype": rtype, "searchquery": search,"tags":tags,"sort":sortid});
			var sync1 = $.ajax({  
				type: "POST",  
				url: $.web_url+"includes/core/pg_btn_lifestage"+$.extn,
				dataType: 'JSON', //this is what we expect our returned data as  
				data: {data:postData},
				cache: false,  
				success: function(new_data)
					{
						var len = new_data.length;
						$(".resource-column-new").html('');
						$(".content-inner-page").addClass('hidden');
						$(".resource-column-new").removeClass('hidden');
						$("#cd-"+name+ " span .fake-label").removeClass('fake-label').addClass('fake-label-active');
			            for(var i=0; i < len; i++){
			                var title = new_data[i].title;
			                var catv = new_data[i].posttype;
			                var url = new_data[i].postname;
			                var message = new_data[i].message;
			                if(catv == 'article'){
								var seo_dvalue = 'articles/';
							}else if(catv == 'calculator'){
								var seo_dvalue = 'calculators/';
							}else if(catv == 'video'){
								var seo_dvalue = 'videos/';
							}else if(catv == 'newsletter'){
								var seo_dvalue = 'newsletters/';
							}else if(catv == 'podcast'){
								var seo_dvalue = 'podcasts/';
							}else if(catv == 'toolkit'){
								var seo_dvalue = 'toolkits/';
							}else if(catv == 'booklet'){
								var seo_dvalue = 'booklets/';
							}else{}
			                var tr_str = message;
			                var pagern = parseInt(pager) + 1;
			                $(".resource-column-new").append(tr_str);
			                $(".pg-btn-tags").removeAttr("tagid").attr('tagid', tagid).removeAttr("typevalue").attr('typevalue', type);
			                $(".next-btn-tags").removeAttr("tagid").attr('tagid', tagid).removeAttr("type").attr('type', type).removeAttr("pager").attr('pager', pagern);
			            }
					}
			});
			var postDatan=JSON.stringify({"tagid": tagid, "page": pager, "rtypes": rtype, "searchquery": search,"tags":tags,"sort":sortid});
			var sync2 = $.ajax({  
				type: "POST",  
				url: $.web_url+"includes/core/pagination_life_stage_check"+$.extn,
				dataType: 'JSON', //this is what we expect our returned data as  
				data: {data:postDatan},
				cache: false,  
				success: function(new_data)
				{
					var messagenow = new_data.message;
					$("#pagination-box-n").html('').removeClass('hidden').html(messagenow);
					$("#pagination-box").addClass('hidden').html('');
				}
			});
		$.when(sync1, sync2).done(function(result2, result1) {
    	console.log('both call finished');
		});
	});	
	$(document).on('click', '.pg-btn-lifestage', function()
		{
			//alert('im here');
			var type = $(this).attr("type");
			var pager = $(this).attr("pager");
			var tagid = $(this).attr("tagid");
			var rtype = $(this).attr("rtype");
			var search = $(this).attr("search");
			var tags = $(this).attr("tags");
			var sortid =  $(this).attr("sort");
			$(".content-inner-page").addClass('hidden').html('').fadeIn(5000);
			$(".resource-column-new").html('').html('<div style="margin-left: 42%; margin-top: 17%; padding-bottom: 30%;"><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> loading</div>');
			var url = (window.location.href);
			mnurl = url.split('&pager')[0];
			murl = (mnurl);
   //    if (history.pushState) {
			// 	window.history.pushState("object or string", "BALANCE Financial Fitness Program  |  Resources", murl+'&pager='+pager);
			// } else {
			// 	document.location.href = murl+'&pager='+pager;
			// }
			var postData=JSON.stringify({"type": tagid, "page": pager, "rtype": rtype, "searchquery": search,"tags":tags,"sort":sortid});
			var sync1 = $.ajax({  
				type: "POST",  
				url: $.web_url+"includes/core/pg_btn_lifestage"+$.extn,
				dataType: 'JSON', //this is what we expect our returned data as  
				data: {data:postData},
				cache: false,  
				success: function(new_data)
					{
						var len = new_data.length;
						$(".resource-column-new").html('');
						$(".content-inner-page").addClass('hidden');
						$(".resource-column-new").removeClass('hidden');
						$("#cd-"+name+ " span .fake-label").removeClass('fake-label').addClass('fake-label-active');
			            for(var i=0; i < len; i++){
			                var title = new_data[i].title;
			                var catv = new_data[i].posttype;
			                var url = new_data[i].postname;
			                var message = new_data[i].message;
			                if(catv == 'article'){
								var seo_dvalue = 'articles/';
							}else if(catv == 'calculator'){
								var seo_dvalue = 'calculators/';
							}else if(catv == 'video'){
								var seo_dvalue = 'videos/';
							}else if(catv == 'newsletter'){
								var seo_dvalue = 'newsletters/';
							}else if(catv == 'podcast'){
								var seo_dvalue = 'podcasts/';
							}else if(catv == 'toolkit'){
								var seo_dvalue = 'toolkits/';
							}else if(catv == 'booklet'){
								var seo_dvalue = 'booklets/';
							}else{}
			                var tr_str = message;
			                var pagern = parseInt(pager) + 1;
			                $(".resource-column-new").append(tr_str);
			                $(".pg-btn-tags").removeAttr("tagid").attr('tagid', tagid).removeAttr("typevalue").attr('typevalue', type);
			                $(".next-btn-tags").removeAttr("tagid").attr('tagid', tagid).removeAttr("type").attr('type', type).removeAttr("pager").attr('pager', pagern);
			            }
					}
			});
			var postDatan=JSON.stringify({"tagid": tagid, "page": pager, "rtypes": rtype, "searchquery": search,"tags":tags,"sort":sortid, "tags": tags});
			 var sync2 = $.ajax({  
				type: "POST",  
				url: $.web_url+"includes/core/pagination_life_stage_check"+$.extn,
				dataType: 'JSON', //this is what we expect our returned data as  
				data: {data:postDatan},
				cache: false,  
				success: function(new_data)
				{
					var messagenow = new_data.message;
					$("#pagination-box-n").html('').removeClass('hidden').html(messagenow);
					$("#pagination-box").addClass('hidden').html('');
				}
			});
		$.when(sync1, sync2).done(function(result2, result1) {
    	console.log('both call finished');
		});
	});	
	$(document).on('click', '.prv-btn-tags', function()
		{
			var type = $(this).attr("type");
			var pager = $(this).attr("pager");
			var tagid = $(this).attr("tagid");
			var tags = $(this).attr("tags");
			$(".content-inner-page").addClass('hidden').html('').fadeIn(5000);
			$(".resource-column-new").html('').html('<div style="margin-left: 42%; margin-top: 17%; padding-bottom: 30%;"><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> loading</div>');
			var url = (window.location.href);
			mnurl = url.split('&pager')[0];
			murl = (mnurl);
			if (history.pushState) {
				window.history.pushState("object or string", "BALANCE Financial Fitness Program  |  Resources", murl+'&pager='+pager);
			} else {
				document.location.href = murl+'&pager='+pager;
			}
			var postData=JSON.stringify({"type": type, "page": pager, "tagid": tagid, "tags": tags});
			var sync1 = $.ajax({  
				type: "POST",  
				url: $.web_url+"includes/core/pg_btn_tags"+$.extn,
				dataType: 'JSON', //this is what we expect our returned data as  
				data: {data:postData},
				cache: false,  
				success: function(new_data)
					{
						var len = new_data.length;
						$(".resource-column-new").html('');
						$(".content-inner-page").addClass('hidden');
						$(".resource-column-new").removeClass('hidden');
						$("#cd-"+name+ " span .fake-label").removeClass('fake-label').addClass('fake-label-active');
			            for(var i=0; i < len; i++){
			                var title = new_data[i].title;
			                var catv = new_data[i].posttype;
			                var url = new_data[i].postname;
			                var message = new_data[i].message;
			                if(catv == 'article'){
								var seo_dvalue = 'articles/';
							}else if(catv == 'calculator'){
								var seo_dvalue = 'calculators/';
							}else if(catv == 'video'){
								var seo_dvalue = 'videos/';
							}else if(catv == 'newsletter'){
								var seo_dvalue = 'newsletters/';
							}else if(catv == 'podcast'){
								var seo_dvalue = 'podcasts/';
							}else if(catv == 'toolkit'){
								var seo_dvalue = 'toolkits/';
							}else if(catv == 'booklet'){
								var seo_dvalue = 'booklets/';
							}else{}
			                var tr_str = message;
			                var pagern = parseInt(pager) + 1;
			                $(".resource-column-new").append(tr_str);
			                $(".pg-btn").removeAttr("typevalue").attr('typevalue', type);
			                $(".next-btn").removeAttr("type").attr('type', type).removeAttr("pager").attr('pager', pagern);
			            }
					}
			});
			var postDatan=JSON.stringify({"dvalue": type, "tagid": tagid, "page": pager, "tags": tags});
			var sync2 = $.ajax({  
				type: "POST",  
				url: $.web_url+"includes/core/pagination_tags_n_check"+$.extn,
				dataType: 'JSON', //this is what we expect our returned data as  
				data: {data:postDatan},
				cache: false,  
				success: function(new_data)
				{
					var messagenow = new_data.message;
					$("#pagination-box-n").html('').removeClass('hidden').html(messagenow);
					$("#pagination-box").addClass('hidden').html('');
				}
			});
		$.when(sync1, sync2).done(function(result2, result1) {
    	console.log('both call finished');
		});
			
	});	
});// JavaScript Document