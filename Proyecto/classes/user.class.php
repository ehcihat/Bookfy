<?php
require_once "connection/conn.php";
require_once "response.class.php";

class User extends Conn
{
    private $table = "usuario";

    private $id_usu = "";
    private $nom_usu = "";
    private $email_usu = "";
    private $pass_usu = "";
    private $img_usu = "";

    private $_response;
    public function __construct()
    {
        parent::__construct();
        $this->_response = new Response();
    }

    /**
     * Obtiene la lista de usuarios.
     *
     * @return array La lista de usuarios.
     */
    public function listUsers()
    {
        $query = "SELECT id_usu,nom_usu,email_usu FROM " . $this->table;
        $data = parent::getData(($query));
        return ($data);
    }
  /**
     * Obtiene los detalles de un usuario por su id.
     *
     * @param string $id El id del usuario.
     * @return array Los detalles del usuario.
     */
    public function getUser($id)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE id_usu = $id";
        return parent::getData($query);
    }
    /**
     * Crea un nuevo usuario.
     *
     * @param string $dataJson Los datos del usuario en formato JSON.
     * @return array El resultado de la operacion.
     */
    public function postUser($dataJson)
    {   
      
        $data = json_decode($dataJson, true);
        if ($data === null) {
            return $this->_response->error_400();
        }
       
        if (!isset($data['nom_usu']) || !isset($data['email_usu']) || !isset($data['pass_usu'])) {

            return $this->_response->error_400();
        } else {

            $email = $data['email_usu'];
        
            //Verificar si el email ya esta registrado.
            $existingUser = $this->getUserByEmail($email);
            if (!empty($existingUser)) {
                //El email ya esta registrado, entonces devuelve error.
                return $this->_response->error_406("El correo electrónico ya está registrado.");
            }
    

            $this->nom_usu = $data['nom_usu'];
            $this->email_usu = $data['email_usu'];
            $this->pass_usu = parent::encrypt($data['pass_usu']);
            $expectedField = ['nom_usu', 'email_usu', 'pass_usu'];
            $extraKeys = array_diff(array_keys($data), $expectedField);

            if (!empty($extraKeys)) {
                //Devolver un error 406 si hay claves adicionales.
                return $this->_response->error_406("Unknown fields found: " . implode(", ", $extraKeys));
            }

            $response = $this->addUser();
            if ($response) {
                $resp = $this->_response->response;
                $resp["result"] = array(
                    "id_usu" => $response
                );

                return $resp;
            } else {

                return $this->_response->error_500();
            }
        }

    }
/**
 * Realiza la consulta para agregar un nuevo usuario a la base de datos.
 *
 * @return int|string El ID del usuario agregado si la insercion fue exitosa, de lo contrario, devuelve 0.
 */
    private function addUser()
    {
        $query = "INSERT INTO " . $this->table . " (nom_usu, email_usu, pass_usu, img_usu) VALUES (
                '" . $this->nom_usu . "','"
            . $this->email_usu . "','"
            . $this->pass_usu . "','"
            . $this->img_usu . "');";

        $response = parent::execQuery($query);
        if ($response) {
            
            return $response;
        } else {
            return 0;
        }
    }
/**
 * Actualiza los datos de un usuario existente en la base de datos.
 *
 * @param string $dataJson Los datos del usuario en formato JSON.
 * @return array|string El resultado de la operación de actualización, codificado en JSON.
 */
    public function putUser($dataJson)
    {
       
        $data = json_decode($dataJson, true);

        if (!isset($data['id_usu'])) {

            return $this->_response->error_400();
        } else {
            $this->id_usu = $data['id_usu'];
            if (isset($data['nom_usu'])) {
                $this->nom_usu = $data['nom_usu'];
            }
            if (isset($data['email_usu'])) {
                $this->email_usu = $data['email_usu'];
            }
            if (isset($data['pass_usu'])) {
                $this->pass_usu = $data['pass_usu'];
            }
            if (isset($data['img_usu'])) {
                $this->img_usu = $data['img_usu'];
            }
            $response = $this->editUser();

         
            $expectedField = ['id_usu','nom_usu', 'email_usu', 'pass_usu', 'img_usu'];
            $extraKeys = array_diff(array_keys($data), $expectedField);

            if (!empty($extraKeys)) {
               
                return $this->_response->error_406("Unknown fields found: " . implode(", ", $extraKeys));
            }
            if (isset($response)) {
  
                $resp = $this->_response->response;
                $resp["result"] = array(
                    "id_usu" => $this->id_usu
                );

                return $resp;
            } else {

                return $this->_response->error_500();

            }

        }
    }
    /**
 * Realiza la consulta para actualizar los datos del usuario en la base de datos.
 *
 * @return int|string El numero de filas afectadas por la consulta de actualizacion o 0 si no se realizaron cambios.
 */
    private function editUser()
    {
        $query = "UPDATE " . $this->table . " SET nom_usu = '" . $this->nom_usu . "',email_usu = '" . $this->email_usu . "',pass_usu  = '" . $this->pass_usu . "',img_usu = '" . $this->img_usu .
            "' WHERE id_usu = '" . $this->id_usu . "'";
        $response = parent::updateQuery($query);
  
        if ($response >= 1) {
            return $response;
        } else {
            return 0;
        }
    }
/**
 * Elimina un usuario de la base de datos.
 *
 * @param string $dataJson Los datos JSON que contienen la informacion del usuario a eliminar.
 * @return array|array[] El resultado de la operación  codificado en JSON o un mensaje de error si falla la eliminacion.
 */
    public function deleteUser($dataJson){

      
        $data = json_decode($dataJson, true);

        if (!isset($data['id_usu'])) {

            return $this->_response->error_400();
        } else {

            $this->id_usu = $data['id_usu'];
            $response = $this->deleteUserQuery();

         
            $expectedField = ['id_usu'];
            $extraKeys = array_diff(array_keys($data), $expectedField);

            if (!empty($extraKeys)) {
               
                return $this->_response->error_406("Unknown fields found: " . implode(", ", $extraKeys));
            }
            if (isset($response)) {
  
                $resp = $this->_response->response;
                $resp["result"] = array(
                    "id_usu" => $this->id_usu
                );

                return $resp;
            } else {

                return $this->_response->error_500();

            }

        }
    }
/**
 * Elimina un usuario de la base de datos segun su id.
 *
 * @return int|string El numero de filas afectadas por la eliminacion o 0 si la eliminacion falla.
 */
    private function deleteUserQuery(){
        $query = "DELETE FROM " . $this->table . " WHERE id_usu= '" . $this->id_usu . "'";
    
        $response = parent::execQuery($query);
        if($response >= 1){
            return $response;
        }else{
            return 0;
        }
    }
/**
 * Obtiene un usuario de la base de datos según su dirección de correo electrónico.
 *
 * @param string $email La dirección de correo electrónico del usuario a buscar.
 * @return array|array[]  Los datos del usuario encontrados en la base de datos o un mensaje de error si la búsqueda falla.
 */
    public function getUserByEmail($email)
{
    $query = "SELECT * FROM " . $this->table . " WHERE email_usu = '$email'";
    return parent::getData($query);
}
}
?>