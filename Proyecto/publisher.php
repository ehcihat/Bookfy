<?php
require_once 'classes/response.class.php';
require_once 'classes/publisher.class.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

$_response = new Response();
$_publisher = new Publisher();

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    if (isset($_GET["id"]) && !empty($_GET["id"])) {
        $publisher_id = $_GET["id"];
        $publisherData = $_publisher->getPublisher($publisher_id);
        echo json_encode($publisherData);
    } else {
        $result = $_publisher->listPublishers();
        echo json_encode($result);
    }
}
?>