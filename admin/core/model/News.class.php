<?php   
	/**
	* 
	*How does it work ? 
	* 	This class retrieves the news from the rss url. The array_n
	* 
	*How To :
	*
	* 	1st step :  $your_var =  new News ('rss.your_url.com');
	*
	* 	2nd step :  call getNews function with the number of news you want to get 
	* 		tip¹ :	to print the news without controlling the array just uncomment and style as you wish  on RSSParser.class.php -> endElement function
	*
	* 	3rd step :	with a foreach/for/while you can fetch the array : $array_news of this class to get your news.
	*		
	*				example:		
	*							<?php 
	*						$folha_news = new News("http://feeds.folha.uol.com.br/emcimadahora/rss091.xml"); 
	*						$folha_news->getNews();
	*						$numb_news=1;
	*						foreach ($folha_news->array_news as $key => $new) {?>
	*							<div class="noticia_rss">
	*								<div><span><?php echo $new['title']?></span><span><a href="<?php echo $new['link']?>" target="__blank"> Leia mais..</a></span></div>
	*								<div class='description'><?php echo $new['description']?></div>
	*							</div>
	*
	*
	*						<?
	*							$numb_news++;
	*							if ($numb_news >4) {
	*								break;
	*							}
	*						}
	*						//var_dump($folha_news->array_news);
	*					?>
	*		tip¹ : 'description' may be null in some rss news. 
	* 
	*/

	class News
	{

		var $rss_url;
		var $rss_parser;
		var $array_news;

		function __construct($url)
		{
			$this->rss_url = $url;
			$this->getNews();
		}

		public function getNews()
		{
			$this->array_news = array();
			$feed_url = $this->rss_url;
			$xml_parser = xml_parser_create();
		    $this->rss_parser = new RSSParser();
		    xml_set_object($xml_parser,$this->rss_parser);
		    xml_set_element_handler($xml_parser, "startElement", "endElement");
		    xml_set_character_data_handler($xml_parser, "characterData");
		    $fp = fopen("$feed_url","r")
		       or die("Error reading RSS data.");

		    while ($data = fread($fp, 4096)){ // header+1 new =  approx. 850 bytes, 1 new = 230 bytes
		    	//echo "linha = ".xml_get_current_line_number($xml_parser)." <br>";
		    	//if (xml_get_current_line_number($xml_parser) < 2) {		    	
		    		xml_parse($xml_parser, $data, feof($fp))
		    		or die(sprintf("XML error: %s at line %d",  
		    			xml_error_string(xml_get_error_code($xml_parser)),  
		    			xml_get_current_line_number($xml_parser)));
		    	//}
		    }
		    fclose($fp); 
		    xml_parser_free($xml_parser);
			$this->array_news = $this->rss_parser->getNewsArray();
		}

	}
?>