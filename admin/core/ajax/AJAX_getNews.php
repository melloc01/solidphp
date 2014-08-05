<?
	// include_once '../model/RSSParser.class.php';
	// include_once '../model/News.class.php';

	// $folha_news = new News($_GET['rss_url']); 

	// $json[] = array();

	// foreach ($folha_news->array_news as $key => $noticia) {
	// 	$json[] = json_encode($noticia);  //encoda cada elemento
	// }
	// echo (json_encode($json));  //encoda o vetor inteiro
?>
<?
    header('Access-Control-Allow-Origin: *');
	header('content-type: application/json; charset=utf-8');

	include_once '../model/RSSParser.class.php';
	include_once '../model/News.class.php';
	include_once '../util/util.class.php';

	$util = new Util();

	$folha_news = new News($_GET['rss_url']); 
	
	
	/**
	 *  func is a Javascript function defined in default.js 
	 *
	 *		$_GET['element'] 	is the id of the element on the DOM ( will be filled with the news array ), 
	 *							if you pass w/o quotes you pass the global variable ( that represents the HTML Object ) 
	 *							to the javascript otherwise you pass it's ID
	 */
	echo json_encode($folha_news->array_news);
?>