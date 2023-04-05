<?php

ini_set("display_errors", 1);

$requestMethod = $_SERVER["REQUEST_METHOD"];

$allowedMethods = ["GET"];

if($requestMethod != $allowedMethods) {

}

$userFileJSON = "data.json";

$users = [];

if(file_exists($userFileJSON)) {
    $json = file_get_contents($userFileJSON);
    $users = json_decode($json, true);
} 



?>