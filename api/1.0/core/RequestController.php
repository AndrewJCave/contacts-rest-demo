<?php

/**
 * Request Controller class for REST API Demo
 * Handles the HTTP Request and marshalls to the appropriate resource handler
 * @author Andrew Cave andrew@wpdevhouse.com
 * @copyright (c) 2016, Andrew Cave
 */

class RequestController {
        
    private $_appRequest;

    private $_resource;

    private $_httpMethod;
    
    private $_method;
	
    private $_apiKey;

    private $_params;

    public function __construct() {
        error_log("RequestController->__construct()");
    }

    /**
     * Parse the request data and load into variables held in the RequestController
     * 
     * @throws Exception
     */
    public function loadRequestData() {
        //Get the http method
        $this->_httpMethod = $_SERVER['REQUEST_METHOD'];
        error_log("HTTP Method :: $this->_httpMethod");
        
        //Get the API Key
        if (isset($_REQUEST['apiKey'])) {
            $this->_apiKey = $_REQUEST['apiKey'];
            error_log("API key :: " . $this->_apiKey);
        } else {
            throw new Exception('Missing Api key!');
        }
        
        //Get the app request
        $appReq = null;
        if (isset($_REQUEST['request'])) {
            $appReq = $_REQUEST['request'];
	    error_log("Request type :: " . $appReq);
        } else {
            throw new Exception('Application Request Is Not Defined');
        }

        $this->_appRequest = explode('/', $appReq);
        $this->_resource = ucfirst($this->_appRequest[0]) . '_Model';
        $this->_method = isset($this->_appRequest[1]) ? $this->_appRequest[1] : 'index';
        $this->_params = isset($_REQUEST['params']) ? $_REQUEST['params'] : array();
					
        if (isset($this->_params['isArray'])) {
            unset($this->_params['isArray']);            
        }
            
        array_push($this->_params, $this->_apiKey);
		
        foreach ($this->_params as $key => $value) {
            error_log("RequestController.php Params:: $key -> " . $value);
        }	
    }
    
    public function getResource() {
        return $this->_resource;
    }
    
    public function getMethod() {
        return $this->_method;
    }
    
    public function getHttpMethod() {
        return $this->_httpMethod;
    }
    
    public function getApiKey() {
        return $this->_apiKey;
    }
    
    public function getParams() {
        return $this->_params;
    }
    
    public function dispatch() {
        error_log("RequestController.php :: dispatch()");
        if (!$this->validateCaller()) {
            return array('Authentication error');
        }
        
        /* @var $response type */
        $response = null;
        error_log("RequestController.php :: dispatch():: detect the http method");
        switch ($this->getHttpMethod()) {
           case "GET":
                // read operations
                switch ($this->getResource()) {
                    case "Contact_Model":
                        error_log("RequestController.php :: dispatch()");
                        $resource = new Contact_Model();
                        $id = null;
                        if ( isset($this->getParams()['id'])) {
                            $id = $this->getParams()['id'];
                        }                      
                        $response = $resource->get($id);
                        break;
                        
                    case "UserModel":
                        $resource = new User_Model();
                        break;
                        
                    default :
                        throw new InvalidArgumentException("Resource -> " . $resource . "doesn't exist");
               }
               break;
           
           case "POST":
               //Add operations
               switch ($this->getResource()) {
                    case "Contact_Model":
                        error_log("RequestController.php :: Handling POST method()");
                        $resource = new Contact_Model();
                        $data = $this->getHTTPBody();
                        $response = $resource->add($data);
                        break;
                        
                    case "UserModel":
                        $resource = new User_Model();
                        break;
                        
                    default :
                        throw new InvalidArgumentException("Resource -> " . $resource . "doesn't exist");
               }
               break;
           
           case "PUT":
               //Update operations
               switch ($this->getResource()) {
                    case "Contact_Model":
                        error_log("RequestController.php :: Handling PUT method()");
                        $resource = new Contact_Model();
                        $data = $this->getHTTPBody();
                        $response = $resource->add($data);
                        break;
                        
                    case "UserModel":
                        $resource = new User_Model();
                        break;
                        
                    default :
                        throw new InvalidArgumentException("Resource -> " . $resource . "doesn't exist");
               }
               break;
           
           case "DELETE":
               //Delete operations
                              
                switch ($this->getResource()) {
                    case "Contact_Model":
                        error_log("RequestController.php :: Handling DELETE method()");
                        $resource = new Contact_Model();
                        if ( isset($this->getParams()['id'])) {
                            $id = $this->getParams()['id'];
                        } else {
                             return array('Missing contact id param');
                        }
                        $response = $resource->delete($id);
                        break;
                        
                    case "UserModel":
                        $resource = new User_Model();
                        break;
                        
                    default :
                        throw new InvalidArgumentException("Resource -> " . $resource . "doesn't exist");
               }
               break;
           
           default :
               return array('Invalid method');
        }
                
        return $response;
        
    }
    
    /**
     * Valicates the caller is an autorised party
     * @return boolean
     */
    private function validateCaller() {
        $userModel = new User_Model();
        $rs = $userModel->getUser($this->getApiKey(),'access_key', $this->getApiKey());
        
        if ($rs === null) {
            return false;
        } else {
            return true;
        }        
    }
    
    
    private function getHTTPBody() {
         $entityBody = file_get_contents('php://input');
         error_log("\n" . $entityBody);
         $body_data = APICommon::jsonDecode($entityBody);
         //2 dimensional array. First level contains the contact record, 
         //2nd level contains contact attributes
         foreach ($body_data as $key => $value) {
            if (! is_array($value)) {
                error_log("RequestController.php Params:: $key -> $value");
            } else {
                error_log("RequestController.php Params:: $key");
                foreach ($body_data[$key] as $key2 => $value2) {
                    error_log("Key :: $key2 -> " . $value2);
                }
            }
             
         }
         
         return $body_data;
    }
}
