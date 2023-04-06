<?php

ini_set("display_errors", 1);

require_once("functions.php");

$requestMethod = $_SERVER["REQUEST_METHOD"];

$allowedMethods = ["GET"];

if(!in_array($requestMethod, $allowedMethods)) {
    $error = ["message" => "Invalid HTTP method."];
    sendJSON($error, 405);
}

$userFileJSON = "data.json";

$users = [];

if(file_exists($userFileJSON)) {
    $json = file_get_contents($userFileJSON);
    $users = json_decode($json, true);
} else {
    $error = ["message" => "JSON file does not exists."]; 
    sendJSON($error);
}

$images = scandir("../images", $sorting_order = SCANDIR_SORT_ASCENDING);

$dots = [".", ".."];

$array_of_images = array_diff($images, $dots);

$dogs = [];

foreach($array_of_images as $dog) {
    $name_of_dog = str_replace(["_", ".jpg"], " ", $dog);

    $current_dog = [
        "name" => trim($name_of_dog),
        "url" => $dog,
    ];

    $dogs[] = $current_dog;
}

$dogsJSON = "dogs.json";

if(!file_exists($dogsJSON)) {
    $json = json_encode($dogs, JSON_PRETTY_PRINT);
    file_put_contents($dogsJSON, $json);
} 

shuffle($dogs);

$four_dogs = array_splice($dogs, 0, 4);

$random_index = rand(0, 3);

$correct_dog = $four_dogs[$random_index];

foreach($four_dogs as $dog) {
    
    $quizAnswer[] = [
        "correct" => ($dog == $correct_dog ? true : false),
        "name" => $dog["name"],
    ];

}

$quizInfo = [
    "image" => "images/" . $dog["url"],
    "alternatives" => $quizAnswer,
];

header("Content-Type: application/json");
http_response_code(200);
echo json_encode($quizInfo);
exit();

/*
header("Content-Type: application/json");
echo '{"image":"images\/German_Shepherd.jpg","alternatives":[{"correct":false,"name":"Shetland Sheepdog"},{"correct":false,"name":"Jack Russell Terrier"},{"correct":true,"name":"German Shepherd"},{"correct":false,"name":"Labrador Retriever"}]}'
exit();
*/
?>