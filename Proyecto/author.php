<?php
require_once 'classes/response.class.php';
require_once 'classes/author.class.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

$_response = new Response();
$_author = new Author();

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    if (isset($_GET["id"]) && !empty($_GET["id"])) {
        $author_id = $_GET["id"];
        $authorData = $_author->getAuthor($author_id);
        echo json_encode($authorData);
    } else {
        $result = $_author->listAuthors();
        echo json_encode($result);
    }
}
?>