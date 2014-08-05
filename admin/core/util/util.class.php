<?php  
	/**
	*	Class Util :: 
	* 	 Functions that are often used.
	*
	*	Methods Index
	*
	*		Date Methods:
	*			dateFormat()
	*			getCurrentDate()
	*	
	*		File Methods:
	*			getFileExtension()
	*		
	*		String Methods:
	*			montaURL()
	*			tiraAcentos()
	*			formatExternalLink()
	*
	*		Number methods:
	*			moneyMask()
	*
	*		Database Methods:
	*		
	*	
	*		Interfaces:
	*			GoogleAPI_getDistance();
	*			embedMedia();
	*	
	*		
	*	
	*/
	class Util
	{
		public static $array_img_extensions = array('jpg','png','jpeg','bmp','ico','gif');
		public $highliter_required = false;
		

		function __construct(){
		}

		public function getFileExtension($filename)
		{
			$extensao = explode('.', $filename) ;
			return ($extensao[1]);
		}

		public function getParsedCode($code,$language)
		{
			require_once ROOT.'plugins/lib/geshi/geshi.php';
			$geshi = new GeSHi( $code, $language );
			return  $geshi->parse_code(); 
		}

		public function montaURL($url)
		{
			$aux =  str_replace(' ', '-', $url);
			$aux =  str_replace('.', '-', $aux);

			return strtolower( urlencode( $this->tiraAcentos($aux) ) ) ;
		}

		public function tiraAcentos($str)
		{
			$patterns = array();
			$replacements = array();

			$patterns[0] = "/\ç/";
			$patterns[1] = "/\~/";
			$patterns[2] = "/\`/";
			$patterns[3] = "/\´/";
			$patterns[4] = "/\^/";

			$replacements[0] = "";

			$str = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
			return  preg_replace($patterns, $replacements, $str);
		}


		public function parse_code($code,$language)
		{
			$path = "admin/core/util/syntax_highlighter/";

			$brushName = "";
			switch ($language) {
				case 'js':
				$brushName="JScript";
				break;
				case 'php':
				$brushName="Php";
				break;
				case 'css':
				$brushName="Css";
				break;
				case 'html':
				$brushName="Php";
				break;

				default:
				break;
			}

			if (!$this->highliter_required) {
				echo "<script type='text/javascript' src='{$path}scripts/shCore.js'></script>";
				echo "<script type='text/javascript' src='{$path}scripts/shBrush$brushName.js'></script>";
				echo "	<link href='{$path}styles/shCore.css' rel='stylesheet' type='text/css' />
				<link href='{$path}styles/shThemeEclipse.css' rel='stylesheet' type='text/css' />
				<script type='text/javascript'>SyntaxHighlighter.all()</script>";
			}

			$this->highliter_required = true;

			echo "<pre class='brush: $language' >$code</pre>";
		}

			public function isFileInput($nomeColuna)
			{
				$aux = explode('_', $nomeColuna);
			// var_dump($aux);
				if ( in_array('file', $aux) || in_array('arquivo', $aux) || in_array('img', $aux) ){
					return true;
				} else {
					return false;
				}
			}

		public function getFkTable($nome_coluna)
		{
			$aux = explode('fk', $nome_coluna);
			if ($aux[0]=="") {
				return strtolower($aux[1]);
			}
			return false;
		}

		public function getColumnsFromTable($table)
		{
			$sql = "SELECT DISTINCT(COLUMN_NAME) as Field, DATA_TYPE as Type, IS_NULLABLE, COLUMN_DEFAULT
			FROM INFORMATION_SCHEMA.COLUMNS
			WHERE table_name =  '$table'";
			$dbStatment = Conexao::getInstance()->prepare($sql);
			if($dbStatment->execute() && $dbStatment->rowCount() != 0){
				return  $dbStatment->fetchAll();
			}
			return null;
		}

		public function hasColumn($table,$column)
		{

			$sql = "SELECT DISTINCT(COLUMN_NAME) as Field, DATA_TYPE as Type, IS_NULLABLE, COLUMN_DEFAULT
			FROM INFORMATION_SCHEMA.COLUMNS
			WHERE table_name =  '$table' and COLUMN_NAME = '$column' ";
		// echo "$sql";

			$dbStatment = Conexao::getInstance()->prepare($sql);
			if($dbStatment->execute() && $dbStatment->rowCount() != 0){
				return  true;
			}
			return null;
		// var_dump($this->colunas);
		}




		/**
		* 
		*	function getCurrentData();
		*		@param String format  		- formato de saida
		*
		*/
		public function getCurrentDate($format ="d/m/Y")
		{
			$datetime = new DateTime(date("Y-m-d H:i"));
			return ($datetime->format($format));
		}		

		/**
		* 
		*	function getData();
		* 		- Quando a função é chamada sem parâmetros $this->getData() ela retorna a data de Hoje em dd/mm/yyyy 
		*		- Datas específicas : 
		*			- Especifique um formato e passe como segundo parâmetro 
		*			uma TIMESTAMP no formato que vem do Banco de dados MySQL. yyyy-mm-dd hh:mm:ss
		*
		*		@param String format  		- formato de saida
		*		@param String timestamp  	- Data
		*		@param String inputFormat  	- formato de entrada  ( default: DATABASE DEFAULT)
		*
		*
		*/

		public function getDate($format ="d/m/Y", $timestamp, $inputFormat='Y-m-d H:i:s')
		{
			try {
				if ($timestamp==null) {
					throw new Exception("Data inválida ( null ) ", 1);
				}

				$date = DateTime::createFromFormat($inputFormat,$timestamp);
				$date = $date->format($format);

				if ($format == DateTime::ISO8601) {
					$aux = explode('+', $date);
					$date = $aux[0];
				}
				return $date;
			} catch (Exception $e) {
				return $e; 
			}

		}


		/**
		* 
		*	function getDateAdded();
		* 		- Quando a função é chamada sem parâmetros $this->getData() ela retorna a data de Hoje em dd/mm/yyyy 
		*		- Datas específicas : 
		*			- Especifique um formato e passe como segundo parâmetro 
		*			uma TIMESTAMP no formato que vem do Banco de dados MySQL. yyyy-mm-dd hh:mm:ss
		*
		*		@param String format  		- formato de saida
		*		@param String interval  	- Interval ex.: 1Y || 15D || 3M
		*		@param String timestamp  	- Data
		*		@param String inputFormat  	- formato de entrada  ( default: DATABASE DEFAULT)
		*
		*
		*/
		public function getDateAdded($timestamp, $interval, $format = 'd/m/Y', $inputFormat='Y-m-d H:i:s'  )
		{
			try {
				if ($timestamp==null) {
					return null;
				}

				$date = DateTime::createFromFormat($inputFormat,$timestamp);
				$date->add(new DateInterval("P{$interval}"));
				$date = $date->format($format);

				if ($format == DateTime::ISO8601) {
					$aux = explode('+', $date);
					$date = $aux[0];
				}
				return $date;
			} catch (Exception $e) {
				return $e;
			}
		}
		

		/**
		 *  	function GoogleAPI_getDistance()
		 *
		 *		@param formatted String $origem  	- Endereço. ex.: São Carlos Rua São Sebastião 2913 Centro 
		 *		@param formatted String $destino  	- Endereço. ex.: São Carlos Rua São Lucas 953 Jd. Tangará 
		 *
		 */
		public function GoogleAPI_getDistance($origem,$destino)
		{
			$origem = urlencode($origem);
			$destino = urlencode($destino);
			//request the directions
			$routes=json_decode(file_get_contents("http://maps.googleapis.com/maps/api/directions/json?origin={$origem}&destination={$destino}&alternatives=true&sensor=false"))->routes;
			//sort the routes based on the distance
			usort($routes,create_function('$a,$b','return intval($a->legs[0]->distance->value) - intval($b->legs[0]->distance->value);'));
			//print the shortest distance
			return  $routes[0]->legs[0]->distance->text;//returns x.y km
		}


		/** 
		*
		*	function embedVideo();
		*		- Passe como parâmetro a URL do vídeo 
		*			- Do Youtube 	'http://www.youtube.com/watch?v=gOIUGWrAKPQ'
		*			- Ou Vimeo  	'http://vimeo.com/10118515'
		*		- Width e Height:
		*			- Podem ser definidas com porcentagem. ex. 100%
		*
		*/
		public function embedMedia($url, $width=420, $height=315)
		{
			$youtube 	= 	strpos($url, "youtube");
			$vimeo 		= 	strpos($url, "vimeo");
			
			if ($youtube) {
				$aux = explode("v=", $url);
				$idVideo = $aux[1];
				echo "<iframe  style='width:$width; height:$height;  ' src='//www.youtube.com/embed/$idVideo' frameborder='0' allowfullscreen></iframe>";
			}

			if ($vimeo) {
				$aux = explode("vimeo.com/", $url);
				$idVideo = $aux[1];
				echo "<iframe src='//player.vimeo.com/video/$idVideo?title=0&amp;byline=0&amp;portrait=0&amp;badge=0' width='$width' height='$height' frameborder='0' webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>";
			}
		}



		/**
		 *  	function getYoutubeVideoID()
		 *
		 *		@param url - link do youtube 
		 *
		 */
		public function getYoutubeVideoID($url)
		{
			$aux = explode("=", $url);
			$aux1 = explode("&", $aux[1]);
			return $aux1[0];
		}

		
		/**
		 *  	function getYoutubeVideoID()
		 *
		 *		@param url - link do youtube 
		 *
		 */
		public function getYoutubeVideoThumbSRC($url, $thumbNumber=0)
		{
			$videoID = $this->getYoutubeVideoID($url);
			return  "http://img.youtube.com/vi/$videoID/$thumbNumber.jpg";
		}




		
		public function gera_codigo ($tamanho, $maiuscula, $minuscula, $numeros, $codigos)
		{
			$maius = "ABCDEFGHIJKLMNOPQRSTUWXYZ";
			$minus = "abcdefghijklmnopqrstuwxyz";
			$numer = "0123456789";
			$codig = '!@*()-+^><';

			$base = '';
			$base .= ($maiuscula) ? $maius : '';
			$base .= ($minuscula) ? $minus : '';
			$base .= ($numeros) ? $numer : '';
			$base .= ($codigos) ? $codig : '';

			srand((float) microtime() * 10000000);
			$senha = '';
			for ($i = 0; $i < $tamanho; $i++) {
				$senha .= substr($base, rand(0, strlen($base)-1), 1);
			}
			return $senha;
		}


		public function CLIENT_is_mobile()
		{
			$user_agent = $_SERVER['HTTP_USER_AGENT'];

			$android = strpos(strtolower($user_agent),"android");
			$ipad = strpos(strtolower($user_agent),"ipad");
			$iphone = strpos(strtolower($user_agent),"iphone");
			$ipod = strpos(strtolower($user_agent),"ipod");

			return (  !  ($android === false) && ($ipad === false) && ($iphone === false) && ($ipod === false)    );
		}


		public function formatExternalLink($link)
		{
			$aux = $link;
			if (substr($link, 0,4) == "http") {
				return $aux;
			} else {
				return "http://".$link;
			}
		}

		public function moneyMask($float_value)
		{
			return $nombre_format_francais = number_format($float_value, 2, ',', '.');
		}


		/**
		 *  function cors() :   It will allow any GET, POST, or OPTIONS requests from any origin.
		 *
		 *
		 *	- Info About Cors :
		 *  - https://developer.mozilla.org/en/HTTP_access_control
		 *  - http://www.w3.org/TR/cors/
		 *
		 */

		function cors($url_origin) {

		    // Allow from any origin
			if (isset($_SERVER['HTTP_ORIGIN'])) {
				header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
				header('Access-Control-Allow-Credentials: true');
		        header('Access-Control-Max-Age: 86400');    // cache for 1 day
		    }

		    // Access-Control headers are received during OPTIONS requests
		    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

		    	if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
		    		header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         

		    	if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
		    		// header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
		    		header("Access-Control-Allow-Headers: {$origin_access}");

		    	exit(0);
		    }

		    echo "You have CORS!";
		}


		

		public function getTableName($this_context)
		{
			$aux = explode("_", get_class($this_context));
			$classname ="";
			$i=2;
			foreach ($aux as $key => $parte_explode) {
				if ($parte_explode != end($aux)) {
					$classname .= $parte_explode;
					if (count($aux)> $i ) {
						$classname .="_";
					}
					$i++;
				}
			}
			return $classname;
		}

		function JSON_prettyPrint( $json )
		{
			$result = '';
			$level = 0;
			$in_quotes = false;
			$in_escape = false;
			$ends_line_level = NULL;
			$json_length = strlen( $json );

			for( $i = 0; $i < $json_length; $i++ ) {
				$char = $json[$i];
				$new_line_level = NULL;
				$post = "";
				if( $ends_line_level !== NULL ) {
					$new_line_level = $ends_line_level;
					$ends_line_level = NULL;
				}
				if ( $in_escape ) {
					$in_escape = false;
				} else if( $char === '"' ) {
					$in_quotes = !$in_quotes;
				} else if( ! $in_quotes ) {
					switch( $char ) {
						case '}': case ']':
						$level--;
						$ends_line_level = NULL;
						$new_line_level = $level;
						break;

						case '{': case '[':
						$level++;
						case ',':
						$ends_line_level = $level;
						break;

						case ':':
						$post = " ";
						break;

						case " ": case "\t": case "\n": case "\r":
						$char = "";
						$ends_line_level = $new_line_level;
						$new_line_level = NULL;
						break;
					}
				} else if ( $char === '\\' ) {
					$in_escape = true;
				}
				if( $new_line_level !== NULL ) {
					$result .= "\n".str_repeat( "\t", $new_line_level );
				}
				$result .= $char.$post;
			}

			return $result;
		}

		
		/**
		 * 		function TEMPLATE_ENGINE_mustache_like - usado para CRUD com relação NxN
		 *
		 *		@param String 				$html 			- nome da tabela no banco de dados
		 *		@param String 				$registro		- coluna no banco de dados, servida como texto do Chk.
		 *	
		 * 
		*/
		public function TEMPLATE_ENGINE_mustache_like ($html,$registro)
		{

			$aux =  explode('{{', $html);
			
			$parsedHtml=$aux[0];

			if (isset($aux[1])) {
				foreach ($aux as $key => $html_piece) {
					$aux_1 = explode('}}', $html_piece);
					if (isset($aux_1[1])) {
						if (isset($registro[$aux_1[0]])) {
							$aux_1[0] = $registro[$aux_1[0]];
						} else {
							throw new Exception("Error Processing Request, TEMPLATE_ENGINE didn't found column '{$aux[0]}'.", 1);
						}
						$parsedHtml .= $aux_1[0].$aux_1[1];
					}
				}
			}
			return $parsedHtml;
		}

	}
	?>