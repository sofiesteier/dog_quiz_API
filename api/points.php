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

if(file_exists($userFileJSON)) {
    $json = file_get_contents($userFileJSON);
    $users = json_decode($json, true);
} else {
    $error = ["error" => "JSON file does not exists."]; 
    sendJSON($error, 404);
}

$username = $requestData["username"];
$password = $requestData["password"];
$UserPoints = $requestData["points"];

$updatedUsers = [];

foreach($users as $user) {
    
    $userInfo = [
        "username" => $user["username"],
        "password" => $user["password"],
        "points" => ($user["username"] == $username ? $user["points"] + $UserPoints : $user["points"]),
    ];

    $updatedUsers[] = $userInfo;

}

$json = json_encode($updatedUsers, JSON_PRETTY_PRINT);
file_put_contents($userFileJSON, $json);

foreach($users as $user) {
    if($user["username"] == $username) {
        $newPoints = ["points" => $user["points"] + $UserPoints];
    }
}

header("Content-Type: application/json");
http_response_code(200);
echo json_encode($newPoints);
exit();

?>