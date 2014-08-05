<?

$staticPages = array
				(
				"fale_conosco",
				"quem_somos",
				"anuncie",
				);
// formulario customizado
$formularios = array
				(
					"busca_avancada" => "anunciante/view/form_busca_avancada.php",
					"categoria" => "anunciante/view/form_categoria.php",
				);
/*
$explode = explode("/",$_SERVER['REQUEST_URI']);
$_GET["l"] = $explode[2];
*/

/* if 'l'ocation is defined /domain.com/<LOCATION>/ */
if (isset($_GET["l"]) && $_GET["l"])
{
	/*
	$explodeAux = explode("-", $_GET["l"]);
	if ((count($explodeAux) == 2) && is_numeric($explodeAux[1]))
	{
		$_GET["l"] = $explodeAux[0]; $_GET["cod"] = $explodeAux[1];
	}
	*/
	
	if (in_array($_GET["l"], $staticPages))
	{
		include_once(ROOT."templateHead.php");
		include_once(ROOT."{$_GET["l"]}.php");
		include_once(ROOT."templateRodape.php");	
	}
	else
	{
		if (file_exists(ROOT.$_GET["l"]."/control/".$_GET["l"]."_control.php"))
		{
			include_once(ROOT.$_GET["l"]."/control/".$_GET["l"]."_control.php"); 
			$control = $_GET["l"]."_control";
			$pagina = new $control; // instancia o control de 'l'
		}
		else
		{
			//echo "<h1>sorry! bad request.<br>'".$_GET["l"]."' doesnt exists</h1>";
			//// CUSTOMIZACAO
			$pagina = new GamaControl();
		}
	}
}
else // if not, call default behavior 
{
	$pagina = new GamaControl();
}

class GamaControl
{
	var $tabela; // define a tabela a ser visualizada
	var $default_base; // p/ corrigir problemas de nao carregar css
	var $model = array(); // DECLARAR NA MAO TODOS OS MODELS UTILIZADOS NO PROJETO
	
	function __construct($ferramenta = "")
	{
		// Deixar comentado se o htaccess estiver
		// tratando URL Amigável
		//$this->urlFriendly();

		$this->tabela = get_class($this);
		
		// DECLARAR NA MAO TODOS OS MODELS UTILIZADOS NO PROJETO
		$this->model = array(
							"anunciante",
							"anunciante_premium",
							"categoria_anunciante",
							"cidade",
							"lancamento",
							"radar",
							);
		
		if (get_class($this) == "GamaControl")
		{
			$this->redirect(true); // true ~> default = true
		}
		else
		{
			$this->createModels(); // instancia o(s) model(s) utilizado(s) pelo controlador
			$this->execute();
		}	
	}
	protected function createModels()
	{
		if (is_array($this->model))
		{
			if (count($this->model) > 0)
				foreach($this->model as $model)
				{
					$this->model[$model] = $this->factory($model); // instancia o model e armazena no vetor models
				}					
		}
		else
			echo "erro de configuracao no controlador ".$this->tabela.": verificar atributo 'model'";
	}
	protected function execute()
	{		
		$tamanho = strlen($this->tabela);
		$this->tabela = substr(get_class($this),0,$tamanho-8); // Tabela_control ~> Tabela
					
		$this->tabela = strtolower(substr($this->tabela,0,1)) . substr($this->tabela,1);
		
		$this->functions(); // chama functions
		$this->redirect(); // chama redireciona as paginas -> view
	}
	/* redireciona a pagina com l (location) e sl (sublocation) */
	protected function redirect($default=false)
	{
		//$this->getDefaultBase();	
		
		// gambi
		$projeto = "/liston/";
			
		if (!preg_match("/admin/i", $_SERVER["REQUEST_URI"])) // so printa base se estiver no site
			echo "<base href='http://{$_SERVER["HTTP_HOST"]}{$projeto}'/>"; 
		// fim gambi
		
		include_once(ROOT."templateHead.php");
		
		////customizacao
		if ($default && !isset($_GET["l"]))
			$this->incluirPaginaDefault();
		else
			$this->go();				
		
		include_once(ROOT."templateRodape.php");			
	}
	// BASE
	public function getDefaultBase()
	{
		$explode = explode("/", $_SERVER["REQUEST_URI"]);
		$levelsUp = "";
		$levels = count($explode) - 3;
		
		for ($i = 0; $i < $levels; $i ++)
			$levelsUp .= "../";
		
		//echo "<base href='".$levelsUp."'>";
		//echo $levelsUp;
		//echo "<base href='http://www.liston.com.br/beta/'/>"; 
		echo "<base href='http://gamaserver:8080/liston/'/>"; 
	}
	public function setDefaultBase($src)
	{
		$this->default_base = $src;
	}	
	// URL AMIGAVEL
	protected static function urlFriendly()
	{
		
		$explode = explode("/",$_SERVER['REQUEST_URI']);
		$explodeAux = null;
			
		//1=dominio, 2=l, 3=sl
		for ($i = 3; $i < count($explode); $i ++)
		{
			$explodeAux = explode("-", $explode[$i]);
			if ((count($explodeAux) == 2) && (is_numeric($explodeAux[1])))
			{
				if ($explodeAux[0] == "p")
				{
					$_GET["p"] = $explodeAux[1];
				}
				else 
				{ 
					$_GET["sl"] = $explodeAux[0];
					$_GET["cod"] = $explodeAux[1];
				}
			} // fim if
			else { 
				if ($explode[$i] != "") 
					$_GET["sl"] = $explode[$i];
			}
		} // fim for
	} // fim metodo
	protected function go()
	{		
		if (isset($_GET["sl"]))
		{
			$sl = $_GET["sl"];
		
			if (method_exists($this, $sl))
				$this->$sl();
			else
				echo "Pagina nao encontrada";
		}
		/////////// customizacao
		else if (!$this->is_palavra_reservada($_GET["l"]))
			if ($this->url_anunciante_exists($_GET["l"]))
				$this->exibe_anunciante($_GET["l"]);
			else
				echo "Anunciante nao encontrado";
		/////////// customizacao
		else
			$this->home();
		
	}
	/* 
		Método Factory parametrizado
		Permite instanciar classes em tempo de execucao
	*/
	//factory para MODEL
	protected function factory($type)
	{
		$root = "";
		$aux_tabela = "";
		$tabela = explode("_", $this->tabela);
		
		// gambi
		for ($i = 0; $i < count($tabela) - 1; $i ++)	
		{
			$aux_tabela .= "{$tabela[$i]}";
			
			if ($i != count($tabela) - 2)
				$aux_tabela .= "_";
		}	
		$tabela = $aux_tabela;
		
		// se o model possuir pasta propria
		if (file_exists('admin/'.$type.'/model/' . $type . '.class.php'))
		{
			include_once('admin/'.$type.'/model/' . $type . '.class.php');
			$classname = $type . "_model"; // Nome da classe em 'model': <nome_da_tabela>_model
			return new $classname($type);
		} 
		// se o model estiver na pasta /model/ desta tabela
		else if (file_exists('admin/'.$tabela.'/model/' . $type . '.class.php')) 
		{
			include_once('admin/'.$tabela.'/model/' . $type . '.class.php');
			
			$classname = $type . "_model"; // Nome da classe em 'model': <nome_da_tabela>_model
			return new $classname($type);
		}
		// gambi
		else if ($tabela == "anunciante_premium")
		{			
			include_once('admin/anunciante/model/' . $type . '.class.php');
			
			$classname = $type . "_model"; // Nome da classe em 'model': <nome_da_tabela>_model
			return new $classname($type);
		}
		// gambi
		else
		{	
			throw new exception("Erro: model '{$type}.class.php' nao existe em admin/{$tabela}/model/ e admin/{$type}/model/ ");
		}
	}
	protected function functions()
	{
		if ($_SERVER["REQUEST_METHOD"] == "POST")
		{
			$dados_tabela = $this->factory($this->tabela);
			
			/* recupera os campos que foram usados no form de edit/cad */
			//$campos = $this->factory_view($this->tabela)->getOrder();
			//print_r($campos);
		}
		
		if(isset($_POST["cad"]))
		{
			if($dados_tabela->insertPost())
				$_SESSION["ok"] = "Cadastrado com sucesso!";
			else
				$_SESSION["mens"] = "Erro ao cadastrar.<br> Tente novamente";
		}
		
		if(isset($_POST["edit"]))
		{
			if ($dados_tabela->updatePost($_POST["edit"]))
				$_SESSION["ok"] = "Editado com sucesso!";
			else
				$_SESSION["mens"] = "Erro ao editar.<br> Tente novamente";
		}
		
		if(isset($_POST["rem"]))
		{
			if ($dados_tabela->remove($_POST["rem"]))
			{
				$_SESSION["ok"] = "Removido com sucesso!";
			}
			else
			{
				$_SESSION["mens"] = "Erro ao remover.<br> Tente novamente";
			}
		}
	}
	
	function incluirPaginaDefault(){
		include_once("admin/radar/model/radar.class.php");
		include_once("admin/cidade/model/cidade.class.php");
		$cidade_radar = $_SESSION["cidade"];
		// torna a cidade 1 (sao carlos) como padrao 
		if($_SESSION["cidade"] == "")
			$cidade_radar = "1";
		$radar_model = new radar_model();
		
		$data = date("Y-m-d");
		$radares = $radar_model->getListaArray("fkCidade='{$cidade_radar}' and exibirnosite = '1' and data = '{$data}'");
		
		// recuperando rigistro da cidade autal
		$cidade_model = new cidade_model();
		$cidade_atual = $cidade_model->getRegistro($cidade_radar);

		//Gama maps
		include_once("./GamaMaps/GamaMaps.php");
		$map = new GamaMaps(300,300);
		include_once(ROOT."default.php");
	
	}
	/////////// customizacao
	public function url_anunciante_exists($url_anunciante)
	{
		//gambi
		include_once("admin/anunciante/model/anunciante_premium.class.php");
		include_once("admin/anunciante/model/anunciante.class.php");
		$this->model["anunciante_premium"] = new anunciante_premium_model();
		$this->model["anunciante"] = new anunciante_model();
		//gambi
		
		// busca pelo 'url anunciante' somente se fkAnunciante > 0 (se > 0, indica que ele é PREMIUM)
		$aux_anunciante = $this->model["anunciante_premium"]->getListaArray("url_anunciante='{$url_anunciante}' and fkAnunciante > 0");
		$anunciante = $aux_anunciante[0];
		if ($anunciante["id"]){ // se for premium e possuir URL
			$aux = $this->model["anunciante"]->getRegistro($anunciante["id"]);
			if ($aux["exibirnosite"]) // se estiver p/ exibir no site
				return true;
		}
			
		return false;
	}
	public function exibe_anunciante($url_anunciante)
	{
		// exibe anunciante
		
		//gambi FORTE
		
		// mapa
		include_once("./GamaMaps/GamaMaps.php");
		$this->map = new GamaMaps(300,300);
		// fim mapa
		
		include_once("admin/anunciante/model/anunciante.class.php");
		include_once("admin/anunciante/model/anunciante_premium.class.php");
		include_once("admin/cidade/model/cidade.class.php");
		include_once("admin/anunciante/model/lancamento.class.php");
		$this->model["anunciante"] = new anunciante_model();
		$this->model["anunciante_premium"] = new anunciante_premium_model();
		$this->model["cidade"] = new cidade_model();
		$this->model["lancamento"] = new cidade_model();
		
		$aux = $this->model["anunciante_premium"]->getListaArray("url_anunciante='{$url_anunciante}'");
		$_GET["cod"] = $aux[0]["id"];
		
		$anunciantePremium = $this->model["anunciante"]->getAnunciantePremium($_GET['cod']);
		
		$lancamentos = $this->model["lancamento"]->getListaArray("fkAnunciante_premium={$anunciantePremium['id']}");
		$cidades_array = $this->model['cidade']->getListaArray();
			if($cidades_array)
				foreach($cidades_array as $cidade){
					$idCidade = $cidade["id"];
					$cidades[$idCidade]["nome"] = $cidade["nome"];
				}
		
		include_once("anunciante/view/ver_anunciante.php"); 
		//gambi
	}
	public function is_palavra_reservada($palavra)
	{
		$palavras_reservadas = array
								(
									"anunciante",
									"busca",
									"quem_somos",
									"fale_conosco",
									"anuncie"
								);
		return in_array($palavra, $palavras_reservadas);
	}
	
}
	
?>
