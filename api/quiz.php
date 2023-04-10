<?php
ini_set("display_errors", 1);

require_once("functions.php");

$requestMethod = $_SERVER["REQUEST_METHOD"];

$allowedMethod = ["GET"];

if(!in_array($requestMethod, $allowedMethod)) {
    $error = ["message" => "Method Not Allowed (only GET is allowed)"];
    sendJSON($error, 405);
}

$JSONFileOfUsers = "data.json";

if(file_exists($JSONFileOfUsers)) {
    $json = file_get_contents($JSONFileOfUsers);
    $users = json_decode($json, true);
} else {
    $error = ["message" => "JSON file does not exists."]; 
    sendJSON($error);
}

$images = scandir("../images", $sorting_order = SCANDIR_SORT_ASCENDING);
$dots = [".", ".."];
$arrayOfImages = array_diff($images, $dots);

$dogs = [];

foreach($arrayOfImages as $dog) {
    $nameOfDog = str_replace(["_", ".jpg"], " ", $dog);

    $currentDog = [
        "name" => trim($nameOfDog),
        "url" => $dog,
    ];

    $dogs[] = $currentDog;
}

$dogsJSON = "dogs.json";

if(!file_exists($dogsJSON)) {
    $json = json_encode($dogs, JSON_PRETTY_PRINT);
    file_put_contents($dogsJSON, $json);
} 

shuffle($dogs);
$fourDogs = array_splice($dogs, 0, 4);
$randomIndex = rand(0, 3);
$correctDog = $fourDogs[$randomIndex];

foreach($fourDogs as $dog) {
    $quizAnswer[] = [
        "correct" => ($dog == $correctDog ? true : false),
        "name" => $dog["name"],
    ];
}

$quizInfo = [
    "image" => "images/" . $correctDog["url"],
    "alternatives" => $quizAnswer,
];

header("Content-Type: application/json");
http_response_code(200);
echo json_encode($quizInfo);
exit();
?>