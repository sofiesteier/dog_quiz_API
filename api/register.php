<?php 
ini_set("display_errors", 1);

require_once("functions.php");

$requestMethod = $_SERVER["REQUEST_METHOD"];

$contentType = $_SERVER["CONTENT_TYPE"];

if($contentType != "application/json") {
    $error = ["error" => "Invalid Content Type. Only JSON is allowed."];
    sendJSON($error, 405);
}

$requestJSON = file_get_contents("php://input");
$requestData = json_decode($requestJSON, true);



$user = [];

if($requestMethod == "POST") {
    if(!isset($requestData["username"], $requestData["password"])) {
        $error = ["error" => "Bad Request"];
        sendJSON($error, 400);
    }

    $username = $requestData["username"];
    $password = $requestData["password"];   
    
    
}



?>