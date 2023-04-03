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
} 

if(empty($requestData["username"]) || empty($requestData["password"])) {
    $error = ["message" => "Bad Request (empty values)"];
    sendJSON($error, 400);
}

$username = $requestData["username"];
$password = $requestData["password"];   

foreach($users as $user) {
    if($user["username"] == $username) {
        $error = ["message" => "Conflict (the username is already taken)"];
        sendJSON($error, 409);
    }
}

$newUser = ["username" => $username, "password" => $password, "points" => 0];
$users[] = $newUser;
$json = json_encode($users, JSON_PRETTY_PRINT);
file_put_contents($userFileJSON, $json);
sendJSON($newUser, 201);








?>