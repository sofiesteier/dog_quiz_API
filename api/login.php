<?php
ini_set("display_errors", 1);

require_once("functions.php");

$requestMethod = $_SERVER["REQUEST_METHOD"];

$allowedMethod = ["POST"];

if(!in_array($requestMethod, $allowedMethod)) {
    $error = ["message" => "Method Not Allowed (only POST is allowed)"];
    sendJSON($error, 405);
}

$contentType = $_SERVER["CONTENT_TYPE"];

if($contentType != "application/json") {
    $error = ["message" => "Invalid Content Type (Only JSON is allowed)"];
    sendJSON($error, 400);
}

$requestJSON = file_get_contents("php://input");
$requestData = json_decode($requestJSON, true);

$JSONFileOfUsers = "data.json";

if(file_exists($JSONFileOfUsers)) {
    $json = file_get_contents($JSONFileOfUsers);
    $users = json_decode($json, true);
} else {
    $error = ["message" => "JSON file does not exists"]; 
    sendJSON($error, 500);
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