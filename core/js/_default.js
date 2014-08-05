
/**
	getNews (  url_xml ,  id_elemento , numero_de_noticias );
	
	numero_de_noticias : default = 5

	**/
	function getNewsAjax (rss_url, elementID, numero_noticias) {
	// default values 
	numero_noticias==undefined ? numero_noticias = 5: true ;
	numero_noticias++;
	// end:: default values 
	var element = $("#"+elementID);
	if ( ( element  instanceof jQuery ) && (element.size()>0)  ) {	
		$.get('./admin/core/ajax/AJAX_getNews.php',{ rss_url : rss_url }, function(data) {
			var json_array = $.parseJSON(data);
			//alert(data);
			$("#"+elementID).animate({"opacity": 0}, 0);
			var tamanho = json_array.length-1;
			for (var i = 1;  i <=  json_array.length-1; i++) {
				var obj = $.parseJSON(json_array[i]);
				var html_noticia = '<div class="row">';
				html_noticia = html_noticia.concat('<div class="col-md-12 noticia_rss"><div><small><span class="glyphicon glyphicon-chevron-right"></span></small><span>');
				html_noticia = html_noticia.concat(obj['title']);
				html_noticia = html_noticia.concat('</span><span><a href="'+obj['link']+'" target="__blank"> Leia mais..</a></span></div></div> </div>');
				if (i < numero_noticias) {
					$("#"+elementID).prepend(html_noticia);
				};
			};
			$("#"+elementID).animate({"opacity": 1}, 500);
		});
	}
}

function getArrayNews (rss_url,nomeFonte,numero_noticias) {
	// default values 
	numero_noticias==undefined ? numero_noticias = 10: true ;
	numero_noticias++;
	// end:: default values 
	
	var this_news = new Array();
	this_news[0] = nomeFonte;
	$.get('./admin/core/ajax/AJAX_getNews.php',{ rss_url : rss_url }, function(data) {
		var json_array = $.parseJSON(data);
			//alert(data);
			var tamanho = json_array.length-1;
			for (var i = 1;  i <=  json_array.length-1; i++) {
				var obj = $.parseJSON(json_array[i]);
				if (i < numero_noticias) {
					//append no array
					this_news[i]= obj;
				};
			}
			News.addFonte(this_news);
		});
}


function var_dump(obj) {
	var out = '';
	for (var i in obj) {
		out += i + ": " + obj[i] + "\n";
	}

	alert(out);

    // or, if you wanted to avoid alerts...

    var pre = document.createElement('pre');
    pre.innerHTML = out;
    document.body.appendChild(pre)
}