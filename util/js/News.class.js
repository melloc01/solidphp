
	var News = {
		sources: new Array(),
		color: "red",

		getNumberSources: function () {
			return this.sources.length;
		},

		addSource: function ( element ) {
			this.sources.push(element);
		},

		getSourceByName: function( nome ){
			for (var i = this.sources.length - 1; i >= 0; i--) {
				if (this.sources[i][0]==nome) {
					return i;	
				};
			};
			return false;
		},

		getRSSSource: function (checkbox, number_of_news) {
			if ($(checkbox).is(':checked')) {
				var rss_url = $(checkbox).val();
				var source_name = $(checkbox).parent().find('span').html();
				// default values 
				number_of_news==undefined ? number_of_news = 11: true ; //10 news actually
				// end:: default values 
				var this_news = new Array();
				this_news[0] = source_name;
				$.get('./admin/core/ajax/AJAX_getNews.php',{ rss_url : rss_url }, function(data) {
					// alert(data);
					var news_array = $.parseJSON(data);
					var tamanho = news_array.length-1;
					for (var i = 1;  i <=  news_array.length-1; i++) {
						var obj = news_array[i];
						if (i < number_of_news) {
							//append array
							this_news[i]= obj;
						};
					}
					console.log('adedou');
					News.addSource(this_news);
				});
			}
			else{
				
			}
		},

		refreshNews: function(checkbox,number_of_news){
			var rss_url = $(checkbox).val();

			var source_name = $(checkbox).parent().find('span').html();
			var array_pos = News.getSourceByName('Estadão');
			
			// default values 
			number_of_news==undefined ? number_of_news = 10: true ;
			number_of_news++;
			// end:: default values 
			var this_news = new Array();
			this_news[0] = source_name;
			$.get('./admin/core/ajax/AJAX_getNews.php',{ rss_url : rss_url }, function(data) {
				//alert(data);
				var json_array = $.parseJSON(data);
				var tamanho = json_array.length-1;
				for (var i = 1;  i <=  json_array.length-1; i++) {
					var obj = $.parseJSON(json_array[i]);
					if (i < number_of_news) {
						//append array
						this_news[i]= obj;
					};
				}
				//console.log('atualizou'+array_pos);
				News.sources[array_pos] = this_news;
			});

		}

	}


function carrega_xml() {
	news = getArrayNews('http://feeds.folha.uol.com.br/emcimadahora/rss091.xml','Folha');
	//	getNewsAjax('http://feeds.folha.uol.com.br/emcimadahora/rss091.xml','folhaNews');
	//	getNewsAjax('http://estadao.feedsportal.com/c/33043/f/534105/index.rss','estadaoNews');
	//	getNewsAjax('http://revistagalileu.globo.com/Revista/Common/Rss/0,,DMI0-17579,00-MATERIAS.xml','galileuNews',10);]

}
