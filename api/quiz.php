<?php

ini_set("display_errors", 1);
/*
$requestMethod = $_SERVER["REQUEST_METHOD"];

$allowedMethods = ["GET"];

if($requestMethod != $allowedMethods) {
    $error = ["error" => "Invalid HTTP method."];
    sendJSON($error, 405);
}

$userFileJSON = "data.json";

$users = [];

if(file_exists($userFileJSON)) {
    $json = file_get_contents($userFileJSON);
    $users = json_decode($json, true);
} 
*/


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



echo "<pre>";
var_dump($dogs);
echo "<pre/>";


shuffle($array_of_images);
$four_dogs = array_splice($array_of_images, 2, 4);
$random_index = rand(0, count($four_dogs));

$correct_dog = $four_dogs[$random_index];

echo "<pre>";
var_dump($correct_dog);
echo "<pre/>";









/*
header("Content-Type: application/json");
echo '{"image":"images\/German_Shepherd.jpg","alternatives":[{"correct":false,"name":"Shetland Sheepdog"},{"correct":false,"name":"Jack Russell Terrier"},{"correct":true,"name":"German Shepherd"},{"correct":false,"name":"Labrador Retriever"}]}'
*/
?>