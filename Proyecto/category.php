<?php
require_once 'classes/response.class.php';
require_once 'classes/category.class.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

$_response = new Response();
$_category = new Category();

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    if (isset($_GET["id"]) && !empty($_GET["id"])) {
        $category_id = $_GET["id"];
        $categoryData = $_category->getCategory($category_id);
        echo json_encode($categoryData);
    } else {
        $result = $_category->listCategories();
        echo json_encode($result);
    }
}
?>