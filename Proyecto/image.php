<?php
require_once 'classes/image.class.php';

// Instanciar la clase Image
$imageHandler = new Image();
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['imgName'])) {
        // Obtener el nombre de la imagen del parámetro de la solicitud
        $imgName = $_GET['imgName'];
        
        // Llamar al método para obtener la imagen
        $imageHandler->getImage($imgName);
    } else {
        // Si no se proporciona el nombre de la imagen, enviar un error 400 (Bad Request)
        http_response_code(400);
        echo json_encode(array('error' => 'Image name not provided'));
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['img_file'])) {
        // Llamar al método para cargar la imagen
        $result = $imageHandler->uploadImage($_FILES);
        // Enviar la respuesta como JSON
        header('Content-Type: application/json');
        echo json_encode($result);
    } else {
        http_response_code(400);
        echo json_encode(array('error' => 'No file uploaded'));
    }
} else {
    // Si el método de solicitud no es GET ni POST, devolver un error 405 (Method Not Allowed)
    http_response_code(405);
    echo json_encode(array('error' => 'Method Not Allowed'));
}
?>