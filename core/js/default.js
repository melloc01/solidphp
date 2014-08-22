var Client = {
	
	header : $('header'),

	WINDOW_fullwidth 	: window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth,
	WINDOW_fullheight 	: window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight,
	
	init : function(){
		this.eventBinds();
		// this.setFullHeight();
	},

	setFullHeight : function(){
		this.resizeRefresh();
		this.header.css('height', this.WINDOW_fullheight);
	},

	resizeRefresh : function(){
		this.WINDOW_fullwidth 	= window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
		this.WINDOW_fullheight 	= window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;

	},

	eventBinds : function(){
		$( window ).resize(function() {
			// Client.setFullHeight();
		});
		

		$("[data-event='ajax-request-loader']").click(function(event) {
			event.preventDefault();
			Client.ajaxLoadContent(this);
		});
	},

	moodifyURLHash : function(value) {
		location.hash = value;
	},

	processAjaxData : function(response, urlPath){
		$("body").html(response.html); //html to be injected inside the CONTENT ( not reloading partials )
		History.pushState({"html":response.html,"pageTitle":response.pageTitle},"", urlPath); //window.history - History.js plugin for cross-browser compatibility
		document.title = response.pageTitle; //browser title
 	},

 	ajaxLoadContent: function (element) {
 		var loaderElement = document.getElementById(element.getAttribute('data-loader')); //expects #id
 		if (loaderElement == undefined) { alert('loaderElement not defined');};
	 		Client.asyncUriRequest(element,function(data) {
	 			loaderElement.innerHTML = data;
	 		});
 	},


 	ajaxRequest : function(element){
 		Client.asyncUriRequest(element,function(data) {
 			console.log(data);
 		})
 	},

 	asyncUriRequest : function(element,callback){
 		var uri = element.getAttribute('data-href');
 		$.ajax({
 			url: uri,
 			dataType: 'html'
 		})
 		.done(function(data) {
 			callback(data);
 		})
 		.fail(function(data) {
 			console.log('error');
 			callback(data);
 		})
 		.always(function(data) {
 			callback(data);
 		});
 		
 	}

};



Client.init();	





