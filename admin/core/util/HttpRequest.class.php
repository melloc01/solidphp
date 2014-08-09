<?php 
class HttpRequest
{
    /**
     * default controller class
     */
    const CONTROLLER_CLASSNAME = 'default';

    /**
     * default ACTION
     */
    const ACTION_NAME = 'home';

    /**
     * position of controller
     */
    protected $controllerkey = 0;

    /**
     * position of controller
     */
    protected $actionkey = 1;


    /**
     * position of controller
     */
    protected $actionvaluekey = 2;

    /**
     * site base url
     */
    protected $baseUrl;

    /**
     * current controller class name
     */
    protected $controllerClassName;

    /**
     * current action  name
     */
    protected $actionName;

    /**
     * current action  value
     */
    protected $actionValue;

    /**
     * list of all parameters $_GET and $_POST
     */
    protected $parameters;

    public function __construct()
    {
        // set defaults
        $this->controllerClassName = self::CONTROLLER_CLASSNAME;
        $this->actionName = self::ACTION_NAME;
    }

    public function setBaseUrl($url)
    {
        $this->baseUrl = $url;
        return $this;
    }

    public function setParameters($params)
    {
        $this->parameters = $params;
        return $this;
    }

    public function getParameters()
    {
        if ($this->parameters == null) {
            $this->parameters = array();
        }
        return $this->parameters;
    }

    public function getControllerClassName()
    {
        return $this->controllerClassName;
    }

    public function getActionName()
    {
        return $this->actionName;
    }

    public function getActionValue()
    {
        return $this->actionValue;
    }

    /**
     * get value of $_GET or $_POST. $_POST override the same parameter in $_GET
     * 
     * @param type $name
     * @param type $default
     * @param type $filter
     * @return type 
     */
    public function getParam($name, $default = null)
    {
        if (isset($this->parameters[$name])) {
            return $this->parameters[$name];
        }
        return $default;
    }

    public function getRequestUri()
    {
        if (!isset($_SERVER['REQUEST_URI'])) {
            return '';
        }

        $uri = $_SERVER['REQUEST_URI'];
        $uri = trim(str_replace($this->baseUrl, '', $uri), '/');

        return $uri;
    }

    public function createRequest()
    {
        $uri = $this->getRequestUri();

        // Uri parts
        $uriParts = explode('/', $uri);


        // if we are in index page
        if (empty($uriParts) || $uriParts[$this->controllerkey] == '') {
            return $this;
        }

        // format the controller class name
        $this->controllerClassName = $this->formatControllerName($uriParts[$this->controllerkey]);

        if (!isset($uriParts[$this->actionkey])) {
            return $this;
        }
        $this->actionName = $this->formatControllerName($uriParts[$this->actionkey]);
        
        if (!isset($uriParts[$this->actionvaluekey])) {
            return $this;
        }

        $this->actionValue = $this->formatControllerName($uriParts[$this->actionvaluekey]);
        

        // remove controller/action/actionvalue name from uri
        unset($uriParts[$this->controllerkey]);
        unset($uriParts[$this->actionkey]);
        unset($uriParts[$this->actionvaluekey]);

        // if there are no parameters left
        if (empty($uriParts)) {
            return $this;
        }

        // find and setup parameters starting from $_GET to $_POST
        $i = 0;
        $keyName = '';
        foreach ($uriParts as $key => $value) {
            if ($i == 0) {
                $this->parameters[$value] = '';
                $keyName = $value;
                $i = 1;
            } else {
                $this->parameters[$keyName] = $value;
                $i = 0;
            }
        }

        // now add $_POST data
        if ($_POST) {
            foreach ($_POST as $postKey => $postData) {
                $this->parameters[$postKey] = $postData;
            }
        }

        return $this;
    }

    public function setActionName($value)
    {
        $this->actionName = $value;
    }

    /**
     * word seperator is '-'
     * convert the string from dash seperator to camel case
     * 
     * @param type $unformatted
     * @return type 
     */
    protected function formatControllerName($unformatted)
    {
        return $unformatted;
    }

    /**
     * word seperator is '-'
     * convert the string from dash seperator to camel case
     * 
     * @param type $unformatted
     * @return type 
     */
    protected function formatActionName($unformatted)
    {
        return $unformatted;
    }
    /**
     * word seperator is '-'
     * convert the string from dash seperator to camel case
     * 
     * @param type $unformatted
     * @return type 
     */
    protected function formatActionValueName($unformatted)
    {
    	return $unformatted;
    }
}