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
	},

	moodifyURLHash : function(value) {
		location.hash = "#"+value;
	}
};



Client.init();





