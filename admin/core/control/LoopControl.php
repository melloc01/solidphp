<?php  
/**
  * Class 
  *
  *
  *	As classes filhas xxx_control.php sao responsaveis por tratemnto de submit e
  *	Essa classe e responsavel pelo gerenciamento das paginas, apenas de chama-las. 
  *	outras coisas mais especificas e perto do Model
  *
  *
  **/ 

class LoopControl
{
	/**
	 * The below variables can/should be overriden :
	 * 		$titulo and $models
	 * 
	*/

	public 
		/**
		* @param string : $titulo  Page Title ( not head Title -- this title is supposed to be printed on the content )
		*/
		$titulo, 		

		/**
		* @param string - html : $menu_left  -  Left Menu ( /admin ) 
		*/			
		$menu_left,	

		/**
		* @param multi-array : $historico  - initialize on init() to output on templatehead -- some registers 
		*/					
		$historico, 				
		
		/**
		* @param Class Object : $Util 
		*/				
		$Util, 						
		
		/**
		* @param Class Oject : $Model  - Model Class of the scope control 
		*/						
		
		$Model, 					
		
		/**
		* @param Class Oject : $Model  - Model Class of the scope control 
		*/						
		
		$Form, 	

		/**
		* @param string array : $models  - models used by the control, will have their class required by the control
		*/					
		$models = array(),

		/**
		* @param boolean : hasHeader  - false to don't include template_head (note : will still include the <head> tag with css's and metas )
		*/			
		$hasHeader = true,	

		/**
		* @param boolean : hasFooter  - false to don't include template_footer (note will still include .js's)
		*/			
		$hasFooter = true,

		/**
		* @param boolean : debug  - if true it won't redirect remaining post variables 
		*/			
		$debug = false,

		/**
		* @param string : site_title  - title of the site ( inside <head> </head> on templatehead) 
		*/			
		$site_title = "Site",

		/**
		* @param formatted string array $list_title
		*				Prefixing the array element with # the control won't try to  fetch the attribute value of the register  
		* 				it will just output what's after the #, as you know in PHP multi-array arrived from database can be accessed
		* 				by number or name of the column.
		*		the way it's defined  here it gets the second column value and prints nothing
		*/			
		$list_title = array("1","#"),
		

		$project_name = 'LitePHP',



		/**
		* 	@param url formatted string : $action_forms
		*
	    *			This variable is the URL that Forms will redirect. 
	    *			Change it on the last scope you'll use ( before calling a View ) 
	    */	
		$actionForms,


		/**
		* 	System Messagens
	    */	
		$success_notification,
		$info_notification,
		$warning_notification,
		$danger_notification,
		$published_notification,
		$deleted_notification,


		
		/**
		* 	@param FontAwesome class Icon : 
		*
	    */	
		$icon = 'fa-paperclip';


		function __construct($tool="")
		{	

			$this->init();
			$this->submit();
			$this->breakpoint($tool);

		}

		public function modifyURL($var, $value)
		{
			output_add_rewrite_var($var, $value);
		}


		/**
		 * function breakpoint :
		 *
		 * @param string $tool checks if the  $_SESSION has the access to this tool, if false
		 *			calls : trataPost() 
		 *
		 */
		public function breakpoint($tool)
		{
			if (ON_ADMIN) {
				if (isset($_SESSION['admin'])) {
					if (!$this->hasAccess($tool) && $tool != '') {
						$this->setAccessError();
						$this->movePermanently('./');
					}
				} 
			}
			return true;
		}

		/**
		 * function system_requires 
		 *			requires a basic set of system classes and instantiate Util
		 *			called by : init()
		 */
		public function system_requires()
		{
			require_once ADMIN.'core/util/util.class.php'		;
			require_once ADMIN.'core/model/Form.class.php'		;
			
			if ( ON_ADMIN )
				require_once ADMIN.'menul/model/menul.class.php';
  
			// require ADMIN.'./core/model/RSSParser.class.php';
			// require ADMIN.'./core/model/News.class.php'		;
			$this->Util = new Util();
		}

		/**
		 * function addModuleCSS - add Current CSS File at the Top
		 *
		 *	@param string array $models_array
		 *	
		 */
		public function getModuleCSS()
		{
			return $this->getClassName();			
		}


		/**
		 * 	function init 
		 *	
		 *		initializes the control variables
		 *	
		 */
		public function init()
		{
			$_class = $this->getClassName();
			if (file_exists(ADMIN."$_class/model/{$_class}.class.php")) {
				$model_name = $this->getClassName().'_model';
				$this->Model = new $model_name();
			}
			if (ON_ADMIN)
				$this->Form = new Form($this->getClassName());
			$this->system_requires();
			$this->redirectURL = $this->getFullurl();		
			$this->actionForms = $this->getActionForms();	
			$this->setNotifications();
		}

		/**
		 *  function getActionFormvValues()
		 *
		 *	This function returns the url that all action forms go ( it keeps _GET value of the URL )
		 *
		*/
		public function getFullurl()
		{	
			if (isset($_GET) && ($_GET != null)) {
				$_GET_keys = array_keys($_GET);
				$first_key = $_GET_keys[0];
				$url = "./?";
				foreach ($_GET as $key => $value) {
					$url .= ($key != $first_key)?"&$key=$value": "$key=$value";
				}
				return $url;
			} 
			return "";
		}

		public function setNotifications($value='')
		{
			$this->success_notification = "Registro criado com succeso.";	
			$this->info_notification    = "Registro editado com succeso.";	
			$this->warning_notification = "Atenção! Esta ação é importante.";	
			$this->danger_notification  = "Houve um erro ao executar a ação.";	

			$this->deleted_notification  = "Registro deletado com sucesso.";	
			$this->published_notification  = "Registro publicado com sucesso.";	
		}

		public function getActionForms()
		{	
			if (isset($_GET) && ($_GET != null)) {
				$url = "./?";
				foreach ($_GET as $key => $value) {
					if ($key != 'sl' && $key != 'id') {
						$url .= "$key=$value";
					}
				}
				return $url;
			} 
			return "";

			// return (isset($_GET['l']))? "./?l={$_GET['l']}" : "./";
		}

		public function requireModel($classe, $parent ="")
		{
			if ($parent=="") {
				require (ADMIN."$classe/model/$classe.class.php");
			} else {
				require (ADMIN."$parent/model/$classe.class.php");
			}
		}

		public function setPageTitle($new_title){
			$this->titulo = $new_title;
		}

		public function include_head()
		{
			if (RENDER_LAYOUT) {
				if ($this->hasHeader) {
					include_once(CURRENT_BASE."template_head.php");
				} else{
					include_once(CURRENT_BASE."HTML_head_includes.php");
				}
			}
		}

		public function getVars()
		{
			return get_defined_vars();
		}
		
		public function include_footer()
		{
			if (RENDER_LAYOUT) {
				if ($this->hasFooter) {
					include_once(CURRENT_BASE."template_footer.php");	
				} else{
					include_once(CURRENT_BASE."footer_includes.php");	
				}
			}
		}




		/**
		 * 		function checkCryptPassword()
		 *
		 *		@param $password
		 *		@param $crypted_password
		 *
		 */
		public function checkCryptPassword($password, $crypted_password)
		{
			return (crypt($password, $crypted_password) == $crypted_password);
		}

		public function route()
		{
			if (!defined("AJAX_CONTEXT") && ENABLE_ROUTE ) {
				if (isset($_GET["sl"])) {
					$sl = $_GET["sl"];
					if (method_exists($this, $sl)){
						$this->$sl();
					}
					else{
						$this->renderPure(CURRENT_BASE.'core/view/404.html',get_defined_vars());
					}
				} else {
					$this->home();
				}			
			}
		}

		public function render($file_location, $defined_vars = null)
		{
			$_defined_vars = $defined_vars;
			if (is_array($defined_vars)) 
				foreach ( $defined_vars as $name => $value)
					$$name = $value;

			$this->include_head();

			if (is_array($_defined_vars)) 
				foreach ( $_defined_vars as $name => $value)
					$$name = $value;

			require $file_location;

			if (is_array($_defined_vars)) 
				foreach ( $_defined_vars as $name => $value)
					$$name = $value;

			$this->include_footer();
		}

		
		public function renderPartial($file_location, $defined_vars)
		{
			if (is_array($defined_vars)) 
				foreach ( $defined_vars as $name => $value)
					$$name = $value;

			require $file_location;
		}
		
		/**
		 *  renderPure () - no vars
		*/
		public function renderPure($file_location, $defined_vars)
		{
			if (is_array($defined_vars)) 
				foreach ( $defined_vars as $name => $value)
					$$name = $value;

			$this->hasHeader = false;
			$this->hasFooter = false;
			$this->render($file_location,get_defined_vars());
		}


		public function dispatchErrors()
		{
			if ( $this->debug && isset($_SESSION['error'])){
				$_SESSION['mysql_error'] = $_SESSION['error'] ;
				unset($_SESSION['error']);
			} 
			else {
				$_SESSION['system_danger'] = $this->danger_notification;
			}
		}


		public function submit()
		{
			if (isset($_POST["ins"])){
				if ($this->Model->submit_insert()) 
					$_SESSION['system_success'] = $this->success_notification;
				else
					$this->dispatchErrors();
				$this->movePermanently($this->actionForms);
			}
			
			if (isset($_POST["upd"])){
				if ($this->Model->submit_update()) 
					$_SESSION['system_info'] = $this->info_notification;
				else
					$this->dispatchErrors();
				$this->movePermanently($this->actionForms);
			}
			
			if (isset($_POST["del"])){
				if ($this->Model->submit_remove()) 
					$_SESSION['system_info'] = $this->deleted_notification;
				else 
					$this->dispatchErrors();
				$this->movePermanently($this->actionForms);
			}

			if (isset($_POST["pub"])){
				if ($this->Model->submit_publicado()) 
					$_SESSION['system_info'] = $this->published_notification;
				else 
					$this->dispatchErrors();
				$this->movePermanently($this->actionForms);
			}
		}

		public function getClassName()
		{
			$aux = explode("_control", get_class($this));
			return $aux[0];
		}


		public function hasAccess($tool)
		{
			if (isset($_SESSION['admin']['access'][$tool])) {
				if ($_SESSION['admin']['access'][$tool])
					return true;
			}
			return false;
		}

		public function movePermanently($link)
		{
			header("Location: $link");
			exit;
		}

		public function setAccessError()
		{
			$_SESSION['system_warning'] = "Voc&ecirc; n&atilde;o tem permiss&atilde;o para acessar esta ferramenta.";
		}

		public function printMessage()
		{
			require ADMIN.'core/view/system_messages.php';
		}

	}
