<?php
require_once "classes/auth.class.php";
require_once "classes/response.class.php";

$_auth = new Auth();
$_response = new Response();
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $body = file_get_contents("php://input");
    $arrayData = $_auth->login($body);

    header('content-type: application/json');

    // Verificamos si hay un error_id en la estructura recibida
    if (isset($arrayData["result"]["error_id"])) {
        $errorId = $arrayData["result"]["error_id"];
    } elseif (isset($arrayData["result"]["token"]["result"]["error_id"])) {
        $errorId = $arrayData["result"]["token"]["result"]["error_id"];
    } else {
       
        http_response_code(200);
        echo json_encode($arrayData);
        exit;
    }

    // Establecemos el código de respuesta HTTP según el error_id
    switch ($errorId) {
        case '400':
            http_response_code(400);
            break;
        case '406':
            http_response_code(406);
            break;
        case '408':
            http_response_code(408);
            break;
        case '409':
            http_response_code(409);
            break;

        default:
            http_response_code(500); // Cualquier otro error, establecer código 500 Internal Server Error
            break;
    }

    // Enviamos la respuesta al cliente
    echo json_encode($arrayData);
} elseif ($_SERVER['REQUEST_METHOD'] == "GET") {
    // Si la solicitud es GET, se espera que el cliente envíe el token en el query string
    $token = isset($_GET['token']) ? $_GET['token'] : null;

    if ($token) {
        // Obtener el token de la base de datos
        $tokenData = $_auth->getToken($token);

        // Verificar si se encontró el token en la base de datos
        if ($tokenData !== false) {
            // Si se encontró el token, devolverlo como respuesta
            http_response_code(200);
            echo json_encode($tokenData);
        } else {
            // Si no se encontró el token, devolver un error 406 Not Acceptable
            http_response_code(406);
            echo json_encode(array("error" => "Token not found"));
        }
    } else {
        // Si no se proporcionó un token en el query string, devolver un error 400 Bad Request
        http_response_code(400);
        echo json_encode(array("error" => "Token not provided"));
    }
} else {
    // Si la solicitud no es POST ni GET, devolver un error 405 Method Not Allowed
    http_response_code(405);
}
?>