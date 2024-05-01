<?php
require_once "connection/conn.php";
require_once "response.class.php";

class Image extends Conn
{
    private $dir = "./front-end/img/";
    private $_response;

    public function __construct()
    {
        parent::__construct();
        $this->_response = new Response();
    }
    /**
     * Obtiene una imagen del servidor y la muestra.
     *
     * @param string $imgName El nombre del archivo de imagen que se desea obtener y mostrar.
     */
    public function getImage($imgName)
    {
        $imgPath = $this->dir . $imgName;

        //Obtener la extension del archivo.
        $imgExtension = strtolower(pathinfo($imgPath, PATHINFO_EXTENSION));

        //Establecer el tipo de contenido segun la extension del archivo.
        switch ($imgExtension) {
            case 'jpg':
            case 'jpeg':
                header('Content-Type: image/jpeg');
                break;
            case 'png':
                header('Content-Type: image/png');
                break;
            default:
                //Enviar un error si la extension no es compatible.
                http_response_code(400);
                echo json_encode(array('error' => 'Unsupported image format'));
                return;
        }

        // Verificar si el archivo existe.
        if (file_exists($imgPath)) {
            //Leer y mostrar el archivo de imagen.
            readfile($imgPath);
        } else {
            //Si el archivo no existe, enviar una imagen predeterminada o un error.
            readfile('cosmere.jpg'); //Cambiar por la imagen predeterminada.
        }
    }
    /**
     * Sube una imagen al servidor.
     *
     * @param array $file El arreglo que contiene la informacion del archivo de imagen a subir.
     * @return string|string[] El resultado de la operación de carga de la imagen, codificado en JSON.
     */
    public function uploadImage($file)
    {
        //Verificar si se recibio un archivo.
        if (!isset($file['img_file']) || empty($file['img_file']['tmp_name'])) {
            return $this->_response->error_400();
        }

        // Obtener información del archivo
        $img_tmp = $file['img_file']['tmp_name'];
        $img_name = $file['img_file']['name'];

        //Mover el archivo a la ubicacion.
        $img_path = $this->dir . $img_name;
        if (move_uploaded_file($img_tmp, $img_path)) {
            //Preparar la respuesta exitosa.
            $response = array(
                "success" => true,
                "message" => "Image uploaded successfully",
                "data" => array(
                    "img_file" => $img_name
                )
            );

            //Establecer los encabezados de la respuesta como JSON.
            header('Content-Type: application/json');

            //Devolver la respuesta como JSON.
            return json_encode($response);
        } else {
            //Preparar la respuesta de error.
            $response = array(
                "success" => false,
                "message" => "Failed to upload image"
            );

            //Establecer los encabezados de la respuesta como JSON.
            header('Content-Type: application/json');

            //Devolver la respuesta como JSON.
            return json_encode($response);
        }
    }
}

?>