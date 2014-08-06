<?php 
class HttpRequest
{
    /**
     * default controller class
     */
    const CONTROLLER_CLASSNAME = 'default';

    /**
     * position of controller
     */
    protected $controllerkey = 0;

    /**
     * site base url
     */
    protected $baseUrl;

    /**
     * current controller class name
     */
    protected $controllerClassName;

    /**
     * list of all parameters $_GET and $_POST
     */
    protected $parameters;

    public function __construct()
    {
        // set defaults
        $this->controllerClassName = self::CONTROLLER_CLASSNAME;
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
        Kint::dump($_SERVER['REQUEST_URI']);

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
        if (!isset($uriParts[$this->controllerkey])) {
            return $this;
        }

        // format the controller class name
        $this->controllerClassName = $this->formatControllerName($uriParts[$this->controllerkey]);

        // remove controller name from uri
        unset($uriParts[$this->controllerkey]);

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
}