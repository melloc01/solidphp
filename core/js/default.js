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
		$("[data-event='request']").click(function(event) {
			event.preventDefault();
			Client.asyncUriRequest(this);
		});
	},

	moodifyURLHash : function(value) {
		location.hash = "#"+value;
	},

	processAjaxData : function(response, urlPath){
		document.getElementById("mainContent").innerHTML = response.html; //html to be injected inside the CONTENT ( not reloading partials )
		document.title = response.pageTitle; //browser title
		History.pushState({"html":response.html,"pageTitle":response.pageTitle},"", urlPath); //window.history - History.js plugin for cross-browser compatibility
 	},


 	asyncUriRequest : function(element){
 		var uri = element.getAttribute('data-href');
 		$.ajax({
 			url: uri,
 			dataType: 'html'
 		})
 		.done(function(data) {
 			var response = [];
 			response.pageTitle = element.getAttribute('data-title');
 			response.html = data;
 			Client.processAjaxData(response,uri);
 		})
 		.fail(function() {
 		})
 		.always(function() {
 		});
 		
 	}

};



Client.init();	





