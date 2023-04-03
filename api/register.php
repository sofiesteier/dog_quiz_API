<?php 
ini_set("display_errors", 1);

require_once("functions.php");

$requestMethod = $_SERVER["REQUEST_METHOD"];

$contentType = $_SERVER["CONTENT_TYPE"];

$userFileJSON = "data.json";

if($contentType != "application/json") {
    $error = ["error" => "Invalid Content Type. Only JSON is allowed."];
    sendJSON($error, 405);
}

$requestJSON = file_get_contents("php://input");
$requestData = json_decode($requestJSON, true);

$users = [];

if(file_exists($userFileJSON)) {
    $json = file_get_contents($userFileJSON);
    $users = json_decode($json, true);
} 

if($requestMethod == "POST") {
    if(empty($requestData["username"]) || empty($requestData["password"])) {
        $error = ["message" => "Bad Request (empty values)"];
        sendJSON($error, 400);
    }

    $username = $requestData["username"];
    $password = $requestData["password"];   
    
    $newUser = ["username" => $username, "password" => $password, "points" => 0];
    $users[] = $newUser;

    $json = json_encode($users, JSON_PRETTY_PRINT);

    file_put_contents($userFileJSON, $json);
    sendJSON($newUser);
}





?>