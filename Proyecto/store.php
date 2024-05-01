<?php
require_once 'classes/response.class.php';
require_once 'classes/store.class.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

$_response = new Response();
$_store = new Store();

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    if (isset($_GET["id"]) && !empty($_GET["id"])) {
        $store_id = $_GET["id"];
        $storeData = $_store->getStore($store_id);
        echo json_encode($storeData);
    } else {
        $result = $_store->listStores();
        echo json_encode($result);
    }
}
?>