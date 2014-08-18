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



		public 
			$httpRequest ;

		private 
			$site_title ;


		function __construct($tool="")
		{	

			$this->init();
			$this->submit();
			$this->breakpoint($tool);

		}

		/**
		 * 		function setSiteTitle
		 * 			Sets the browser title 
		 * 
		 */
		public function setSiteTitle($_title = null)
		{
			$_class = $this->getClassName();
			if ($_title == null) 
				if ($_class == 'default')
						if(ON_ADMIN)  $_title =  "Solid - Control Panel ";
					else $_title = "Solid";
				else 
					$_title = "Solid - ".ucfirst($_class);
			$this->site_title = $_title;
		}

		public function getSiteTitle()
		{
			$this->site_title;
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
						$_SESSION['message_time'] = 1500;
						$this->movePermanently('../');
					}
				} 
			}
			return true;
		}

		/**
		 * function system_requires 
		 *			requires a basic set of system classes and instantiate Util
		 *		
		 *			called by : init()
		 *			
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
			$this->setNotifications();
			$this->setSiteTitle();
		}

		/**
		 * 		function setNotifications()
		 *			Set default system notifications ( user feedbacks )
		 *			
		 */
		public function setNotifications($value='')
		{
			$this->success_notification = "Registro criado com succeso.";	
			$this->info_notification    = "Registro editado com succeso.";	
			$this->warning_notification = "Atenção! Esta ação é importante.";	
			$this->danger_notification  = "Houve um erro ao executar a ação.";	

			$this->deleted_notification  = "Registro deletado com sucesso.";	
			$this->published_notification  = "Visualização alterada com sucesso.";	
		}



		/**
		 * 		function setPageTitle()
		 *			@param String $new_title
		 *			
		 */
		public function setPageTitle($new_title){
			$this->titulo = $new_title;
		}


		/**
		 * 		function include_footer()
		 *		requires the current header partial
		 */
		public function include_head()
		{
			if ($this->hasHeader) {
				include_once(CURRENT_BASE."partials/template_head.php");
			} else{
				include_once(CURRENT_BASE."partials/HTML_head_includes.php");
			}
		}
		

		/**
		 * 		function include_footer()
		 *		requires the current footer partial
		 */
		public function include_footer()
		{
			if ($this->hasFooter) {
				include_once(CURRENT_BASE."partials/template_footer.php");	
			} else{
				include_once(CURRENT_BASE."partials/footer_includes.php");	
			}
		}

		/**
		 * 		function checkCryptPassword()
		 *
		 *		@param $password
		 *		@param $crypted_password
		 *		@return bool true if match
		 *
		 */
		public function checkCryptPassword($password, $crypted_password)
		{
			return (crypt($password, $crypted_password) == $crypted_password);
		}

		/**
		 * 		function route()
		 *		routes the application
		 * 
		 */
		public function route()
		{
			$action = $this->httpRequest->getActionName();
			if (method_exists($this, $action)){
				$this->httpRequest = $this->httpRequest->createRequest(); //flush request || default_control behavior
				$this->$action();
			}
			else{
				$this->renderPure(CURRENT_BASE.'core/view/404.html',get_defined_vars());
			}	
		}

		/**
		 * 	
		 * 		function render()		 	
		 * 			@param String $file_location : 	path of the .php file
		 * 			@param Object $defined_vars : 	vars of the context it was called
		 * 			@param bool $renderPartials :  	If false the function will only render it's output, nothing else
		 * 											- Useful for APIs
		 */

		public function render($file_location, $defined_vars = null, $renderPartials = true)
		{
			$_defined_vars = $defined_vars;
			if ($renderPartials) {
				if (is_array($defined_vars)) 
					foreach ( $defined_vars as $name => $value)
						$$name = $value;

				$this->include_head();
			}

			if (is_array($_defined_vars)) 
				foreach ( $_defined_vars as $name => $value)
					$$name = $value;

			require $file_location;

			if ($renderPartials) {
				if (is_array($_defined_vars)) 
					foreach ( $_defined_vars as $name => $value)
						$$name = $value;

				$this->include_footer();
			}
		}
		
		/**
		*  	function renderPure ()
		*		calls runder w/o context application **PARTIALS**
		*
		*		IT DOES INCLUDES ALL HTML DOCTYPE/METAS AND OTHER STUFF RELATED TO HTML SYNTAX AND FUNCTIONALITY 
		*
		*/
		public function renderPure($file_location, $defined_vars)
		{
			$this->hasHeader = false;
			$this->hasFooter = false;
			$this->render($file_location,$defined_vars);
		}


		public function dispatchErrors()
		{
			if ( !$this->debug && isset($_SESSION['mysql_error'])){
				unset($_SESSION['mysql_error']);
				$_SESSION['system_danger'] = $this->danger_notification;
			} 
			else {
				$_SESSION['system_danger'] = $this->danger_notification;
			}
		}

		/**
		 * 	
		 * 		function submit()
		 * 			This function takes care of all the CRUD doing automatically on /admin, be careful on changes if needed.
		 * 	
		 */
		public function submit()
		{
			if (isset($_POST["ins"])){
				if ($this->Model->submit_insert()) 
					$_SESSION['system_success'] = $this->success_notification;
				else
					$this->dispatchErrors();
				$this->movePermanently('./');
			}
			
			if (isset($_POST["upd"])){
				if ($this->Model->submit_update()) 
					$_SESSION['system_info'] = $this->info_notification;
				else
					$this->dispatchErrors();
				$this->movePermanently('../');
			}
			
			if (isset($_POST["del"])){
				if ($this->Model->submit_remove()) 
					$_SESSION['system_info'] = $this->deleted_notification;
				else 
					$this->dispatchErrors();
				$this->movePermanently('./');			
			}

			if (isset($_POST["pub"])){
				if ($this->Model->submit_publicado()) 
					$_SESSION['system_info'] = $this->published_notification;
				else 
					$this->dispatchErrors();
				$this->movePermanently('./');
			}
		}

		/**
		 * 
		 * 		function getClassName
		 * 			@example calledby : post_control
		 * 			@return  post
		 * 
		 */
		public function getClassName()
		{
			$aux = explode("_control", get_class($this));
			return $aux[0];
		}


		/**
		 * 		function hasAccess($tool)
		 * 			@param String $tool : check the $_SESSION object for the table  ACCESS  value.
		 * 
		 */
		public function hasAccess($tool)
		{
			if ($tool = '') return true;

			if (isset($_SESSION['admin']['access'][$tool])) {
				if ($_SESSION['admin']['access'][$tool])
					return true;
			}
			return false;
		}

		/**
		 * 		function movePermanently()
		 *			@param String $link
		 *			301 redirect
		 *
		 */
		public function movePermanently($link)
		{
			header("Location: $link");
			exit;
		}


		/**
		 *	 	function setAccessError()
		 *			Sets the message user will receive when he attempt to access some restricted-area controller.
		 *
		 *
		 */
		public function setAccessError()
		{
			$_SESSION['system_warning'] = "Voc&ecirc; n&atilde;o tem permiss&atilde;o para acessar esta ferramenta.";
		}

		/**
		 * 		function printMessage
		 *			print error/feedback messages
		 *
		 */
		public function printMessage()
		{
			require ADMIN.'core/view/system_messages.php';
		}

		/**
		 * 		Getters
		 * 
		 */
		public function getActionValue()
		{
			return $this->httpRequest->getActionValue();
		}
		public function getControllerName()
		{
			return $this->httpRequest->getControllerClassName();
		}
		public function getActionName()
		{
			return $this->httpRequest->getActionName();
		}

		/**
		 * 		function injectCSS
		 * 			Injects the controllers css on the current scope
		 * 			TO DO : ON_PRODUCTION constant to get styles.min.css - minified and concat'd
		 * 
		 */
		public function injectCSS()
		{
			$_module = $this->getControllerName();
			$_location =  ON_ADMIN ? '/admin/' : '';
			if (file_exists("{$_module}/css/{$_module}.css")) {
				echo "<link rel='stylesheet' href='$_location/$_module/css/$_module.css'>";
			}
		}

		/**
		 * 		function injectJS
		 * 			Injects the controllers js on the current scope
		 * 			TO DO : ON_PRODUCTION constant to get scripts.min.js - minified and concat'd
		 * 
		 */
		public function injectJS()
		{
			$_module = $this->getControllerName();
			$_location =  ON_ADMIN ? '/admin/' : '';
			if (file_exists("{$_module}/js/{$_module}.js")) {
				echo "<script src='$_location/$_module/js/$_module.js' type='text/javascript' charset='utf-8' async defer></script>";
			}
		}

	}
