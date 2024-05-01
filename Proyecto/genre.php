<?php
require_once 'classes/response.class.php';
require_once 'classes/genre.class.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

$_response = new Response();
$_genre = new Genre();

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    if (isset($_GET["id"]) && !empty($_GET["id"])) {
        $genre_id = $_GET["id"];
        $genreData = $_genre->getGenre($genre_id);
        echo json_encode($genreData);
    } else {
        $result = $_genre->listGenres();
        echo json_encode($result);
    }
}
?>