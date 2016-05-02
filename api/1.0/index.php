<?php
header('Content-type: application/json');

require_once 'Bootstrap.php';

$apiRes;

try {
    $requestController = new RequestController();
    //
    
    $requestController->loadRequestData();      
    
    $method = $requestController->getMethod();
    $resource = $requestController->getResource();
    $httpMethod = $requestController->getHttpMethod();
    $apiKey = $requestController->getApiKey();
    $params = $requestController->getParams();
    //
    $apiRes = $requestController->dispatch();
    
    $apiJsonRes = APICommon::jsonEncode($apiRes, true, true);

} catch (Exception $exc) {
    error_log("Exception caught: " . $exc->getMessage());
    $apiRes = $exc->getMessage();
    $apiJsonRes = APICommon::jsonEncode($apiRes, false, false);
}

echo $apiJsonRes;