
var body = document.body,
    html = document.documentElement;

var doc_height = Math.max( body.scrollHeight, body.offsetHeight, 
                       html.clientHeight, html.scrollHeight, html.offsetHeight );

$(".sidebar").css('height',doc_height);

$(document).ready(function() {	
	getNewsAjax('http://feeds.folha.uol.com.br/emcimadahora/rss091.xml','folhaNews');
	getNewsAjax('http://estadao.feedsportal.com/c/33043/f/534105/index.rss','estadaoNews');
	getNewsAjax('http://revistagalileu.globo.com/Revista/Common/Rss/0,,DMI0-17579,00-MATERIAS.xml','galileuNews',10);
});


function remove_this (element) {
	$(element).remove();
}

function confirmDelete (idForm) {
	if (confirm("Deseja realmente excluir esse item ?")){
		$("#rem_"+idForm).submit();
	};
}

function togglePublicado (idForm, publicado) {
	$("#pub_"+idForm).submit();
}

function mostra_input_file_form (element,table,field) {
	$('#div_'+table+'_'+field).show(); 
	$(element).parent().hide();
}

function var_dump(obj) {
	var out = '';
	for (var i in obj) {
		out += i + ": " + obj[i] + "\n";
	}
	// alert(out);
    // or, if you wanted to avoid alerts...
    var pre = document.createElement('pre');
    pre.innerHTML = out;
    document.body.appendChild(pre)
}

	/**	
		getNews (  url_xml ,  id_elemento , numero_de_noticias );
	
		numero_de_noticias : default = 5
		**/
		function testeJSONP () {
			var cont = $("body");
			$.ajax({
				type: 'GET',
				url: 'http://www.mustacheweb.com.br/admin/core/ajax/AJAX_getNews.php',
				async: false,
				contentType: "application/json",
				dataType: 'jsonp',
				success: function(output) {
					console.log(output);
					cont.append(output);
				},
				error: function(e) {
					// console.log(e);
					// console.log("Erro!");
				},
				done: function(data){
					console.log(data);
				}
			});
			
		}

		function getNewsObject (rss_url,elementID,numero_noticias) {
			// alert(elementID);
			$.ajax({
				url: 'core/ajax/AJAX_getNews.php?element='+elementID+'&qtd='+numero_noticias,
				dataType:'json',
				data: {rss_url: rss_url},
			})
			.done(function(data) {
				printNews(data,$('#'+elementID),numero_noticias);	
			})
			.fail(function(data) {
				console.log("error");
				console.log(data);
			});
		}


		
		function getNewsAjax (rss_url, elementID, numero_noticias) {
			numero_noticias==undefined ? numero_noticias = 5: true ;
			numero_noticias++;
			var element = $("#"+elementID);
			if ( ( element  instanceof jQuery ) && (element.size()>0)  ) {	
				getNewsObject(rss_url,elementID,numero_noticias,function(data){
				});		
			}
		}

		function printNews (data,element,qtd) {
			var array_noticias = data;
			element.animate({"opacity": 0}, 0);
			var tamanho = array_noticias.length-1;
			for (var i = 1;  i <=  tamanho; i++) {
				var obj = array_noticias[i];
				var html_noticia = '<div class="row">';
				html_noticia = html_noticia.concat('<div class="col-md-12 noticia_rss"><div>&rarr; <span>');
				html_noticia = html_noticia.concat(obj['title']);
				html_noticia = html_noticia.concat('</span><span><a href="'+obj['link']+'" target="__blank"> Leia mais..</a></span></div></div> </div>');
				if (i < qtd) {
					element.prepend(html_noticia);
				};
			};
			element.animate({"opacity": 1}, 500);	
		}

		var Util = {
			sources: new Array(),
			color: "red",

			getDateFromTimestamp: function (timestamp) {
				data.setDate(data.getDate()+1);
		/* 
			Methods from Date	
			var data = new Date(timestamp);
			setDate( day ), getDate(0-31), getMonth(1-12), getHours(0-23), getFullYear() 
			*/
			return data;
		},

		addSource: function ( element ) {
			this.sources.push(element);
		}
	}


