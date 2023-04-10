<?php
ini_set("display_errors", 1);

require_once("functions.php");

$requestMethod = $_SERVER["REQUEST_METHOD"];

$allowedMethods = ["POST", "GET"];

if(!in_array($requestMethod, $allowedMethods)) {
    $error = ["message" => "Invalid HTTP method."];
    sendJSON($error, 405);
}

if($requestMethod == "POST") {
    $contentType = $_SERVER["CONTENT_TYPE"];

    if($contentType != "application/json") {
    $error = ["message" => "Invalid Content Type. Only JSON is allowed."];
    sendJSON($error, 400);
    }

    $requestJSON = file_get_contents("php://input");
    $requestData = json_decode($requestJSON, true);

    $username = $requestData["username"];
    $password = $requestData["password"];
    $UserPoints = $requestData["points"];

    $JSONFileOfUsers = "data.json";

    if(file_exists($JSONFileOfUsers)) {
        $json = file_get_contents($JSONFileOfUsers);
        $users = json_decode($json, true);
    } else {
        $error = ["message" => "JSON file does not exists."]; 
        sendJSON($error, 404);
    }

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
    file_put_contents($JSONFileOfUsers, $json);

    foreach($users as $user) {
        if($user["username"] == $username) {
            $newPoints = ["points" => $user["points"] + $UserPoints];
        }
    }
    sendJSON($newPoints);
}

if($requestMethod == "GET") {
    $JSONFileOfUsers = "data.json";

    if(file_exists($JSONFileOfUsers)) {
        $json = file_get_contents($JSONFileOfUsers);
        $users = json_decode($json, true);
    } else {
        $error = ["error" => "JSON file does not exists."]; 
        sendJSON($error, 404);
    }
    
    usort($users, function ($user1, $user2) {
        if ($user1["points"] == $user2["points"]) {
            return 0;
        }
        return ($user1["points"] < $user2["points"]) ? 1 : -1;
    });
    
    $firstFiveUsers = array_splice($users, 0, 5);
    $topFiveHighscore = [];

    foreach($firstFiveUsers as $user) {
        $userTopFive = $user["username"];
        $pointsTopFive = $user["points"];
        $userHighscore = [
            "username" => $userTopFive,
            "points" => $pointsTopFive,
        ];
        $topFiveHighscore[] = $userHighscore;
    }
    sendJSON($topFiveHighscore);
}
?>