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

$username = $requestData["username"];
$password = $requestData["password"];

if(empty($username) || empty($password)) {
    $error = ["message" => "Bad Request (empty values)"];
    sendJSON($error, 400);
}   

$JSONFileOfUsers = "data.json";

if(file_exists($JSONFileOfUsers)) {
    $json = file_get_contents($JSONFileOfUsers);
    $users = json_decode($json, true);
} 

if(!empty($users)) {
    foreach($users as $user) {
        if($user["username"] == $username) {
            $error = ["message" => "Conflict (the username is already taken)"];
            sendJSON($error, 409);
        } 
    }
}

$newUser = ["username" => $username, "password" => $password, "points" => 0];
$users[] = $newUser;

$json = json_encode($users, JSON_PRETTY_PRINT);
file_put_contents($JSONFileOfUsers, $json);

sendJSON($newUser, 201);
?>