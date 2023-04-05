<?php
ini_set("display_errors", 1);

require_once("functions.php");

$requestMethod = $_SERVER["REQUEST_METHOD"];

$allowedMethods = ["POST"];

if(!in_array($requestMethod, $allowedMethods)) {
    $error = ["error" => "Invalid HTTP method."];
    sendJSON($error, 405);
}

$contentType = $_SERVER["CONTENT_TYPE"];

if($contentType != "application/json") {
    $error = ["error" => "Invalid Content Type. Only JSON is allowed."];
    sendJSON($error, 400);
}

$requestJSON = file_get_contents("php://input");
$requestData = json_decode($requestJSON, true);

$userFileJSON = "data.json";

$users = [];

if(file_exists($userFileJSON)) {
    $json = file_get_contents($userFileJSON);
    $users = json_decode($json, true);
} else {
    $error = ["error" => "JSON file does not exists."]; 
    sendJSON($error);
}

$username = $requestData["username"];
$password = $requestData["password"];


foreach($users as $user) {

    if($user["username"] == $username and $user["password"] == $password) {
        sendJSON($user);
    } 
}

$error = ["message" => "Not Found"];
sendJSON($error, 404);

?>