<?php
require_once 'classes/response.class.php';
require_once 'classes/user.class.php';

 header("Access-Control-Allow-Origin: *");
 header("Access-Control-Allow-Headers: Content-Type, Authorization");

$_response = new Response();
$_user = new User();

if ($_SERVER['REQUEST_METHOD'] == "GET") {

    if (isset($_GET["id"]) && !empty($_GET["id"])) {
        $user_id = $_GET["id"];
        $userData = $_user->getUser($user_id);
        echo json_encode($userData);
    } else if (isset($_GET["email"])) {
        $email = $_GET["email"];
        $userData = $_user->getUserByEmail($email);
        echo json_encode($userData);
    } else {
        $result = $_user->listUsers();
        echo json_encode($result);
    }

} else if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $body = file_get_contents("php://input");
    $data = $_user->postUser($body);
    header('Content-Type: application/json');
    if (isset($data["result"]["error_id"])) {

        $response = $data["result"]["error_id"];
        http_response_code($response);
    } else {
        http_response_code(200);
    }
    echo json_encode($data);


} else if ($_SERVER['REQUEST_METHOD'] == "PUT") {
    $body = file_get_contents("php://input");
    $data = $_user->putUser($body);

    header('Content-Type: application/json');
    if (isset($data["result"]["error_id"])) {
        var_dump($data);
        $response = $data["result"]["error_id"];
        http_response_code($response);
    } else {
        http_response_code(200);
    }
    echo json_encode($data);

} else if ($_SERVER['REQUEST_METHOD'] == "DELETE") {
    $body = file_get_contents("php://input");
    $data = $_user->deleteUser($body);

    header('Content-Type: application/json');
    if (isset($data["result"]["error_id"])) {
        var_dump($data);
        $response = $data["result"]["error_id"];
        http_response_code($response);
    } else {
        http_response_code(200);
    }
    echo json_encode($data);
} else {
    header('Content-Type: application/json');
    $arrayData = $_response->error_405();
    echo json_encode($arrayData);
}
?>