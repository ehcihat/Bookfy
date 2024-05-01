<?php
require_once 'connection/conn.php';
require_once 'response.class.php';

//Clase para manejar la autenticación de usuarios.
class Auth extends Conn
{
    private $_response;
    /**
     * Constructor de la clase en la que instanciamos la clase respuestas.
     */
    public function __construct()
    {
        parent::__construct();
        $this->_response = new Response();
    }
    /**
     * Método para realizar el login.
     *
     * @param string $json Los datos de inicio de sesion en formato JSON.
     * @return array|array[] El resultado del inicio de sesion.
     */

    public function login($json)
    {

        $data = json_decode($json, true);

        if ($data === null) {
            return $this->_response->error_400();
        }
        $expectedField = ['nom_usu', 'email_usu', 'pass_usu'];
        $extraKeys = array_diff(array_keys($data), $expectedField);

        if (!empty($extraKeys)) {
            //Devolver un error 400 si hay claves adicionales.
            return $this->_response->error_406("Unknown fields found: " . implode(", ", $extraKeys));
        }

        if (!isset($data['email_usu']) || !isset($data['pass_usu'])) {

            $expectedField = ['nom_usu', 'email_usu', 'pass_usu'];
            $extraKeys = array_diff(array_keys($data), $expectedField);

            if (!empty($extraKeys)) {
                //Devolver un error 400 si hay claves adicionales.
                return $this->_response->error_406("Unknown fields found: " . implode(", ", $extraKeys));
            }

            return $this->_response->error_400();

        } else {

            $user = $data['email_usu'];
            $pass = $data['pass_usu'];
            $pass = parent::encrypt($pass);
            $data = $this->getDataUser($user);

            if ($data) {
                if ($pass == $data[0]['pass_usu']) {



                    $token = $this->insertToken($data[0]["id_usu"]);


                    if ($token) {
                        $expirationDate = $this->getExpirationDate($data[0]["id_usu"]);
                        $result = $this->_response->response;
                        $result["result"] = array(
                            "token" => $token,
                            "expiration_date" => $expirationDate
                        );
                        return $result;

                    } else {
                        return $this->_response->error_500();
                    }

                } else {

                    return $this->_response->error_409("Contraseña incorrecta.");
                }

            } else {

                return $this->_response->error_408("Este email no está registrado: $user.");
            }
        }
    }
    /**
     * Obtiene los datos del usuario desde la base de datos.
     *
     * @param string $email_usu El email del usuario.
     * @return array|array[] Los datos del usuario.
     */
    private function getDataUser($email_usu)
    {
        $query = "SELECT nom_usu,id_usu,pass_usu FROM usuario WHERE email_usu ='$email_usu'";
        $data = parent::getData($query);

        if (isset($data[0]["id_usu"])) {

            return $data;
        } else {

            return array();
        }
    }

    /**
     * Inserta un nuevo token en la base de datos o devuelve el token existente si aún es válido.
     *
     * @param int $user_id El id del usuario.
     * @return string|boolean El token generado o false si falla.
     */
    private function insertToken($user_id)
    {
        $existingToken = $this->getExistingToken($user_id);
        $exp_date = $this->getExpirationDate($user_id);

        if ($existingToken && $this->isTokenDateValid($exp_date)) {

            return $existingToken;
        } else {

            $token = bin2hex(openssl_random_pseudo_bytes(16));
            $cur_date = date("Y-m-d");
            $exp_date = date("Y-m-d", strtotime($cur_date . " +3 days"));

            $query = "INSERT INTO TOKEN (value_tok, date_creation, date_exp, id_usu) VALUES (?, ?, ?, ?)";

            if ($stmt = $this->conn->prepare($query)) {
                $stmt->bind_param("sssi", $token, $cur_date, $exp_date, $user_id);
                if ($stmt->execute()) {
                    return $token;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
    }
    /**
     * Obtiene el ultimo token insertado en la base de datos para un usuario especifico.
     *
     * @param int $user_id El id del usuario.
     * @return string|boolean El token existente o false si no se encuentra.
     */
    private function getExistingToken($user_id)
    {
        $existingToken = null;
        $query = "SELECT value_tok FROM TOKEN WHERE id_usu = ? ORDER BY date_creation DESC LIMIT 1";

        if ($stmt = $this->conn->prepare($query)) {
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $stmt->bind_result($existingToken);
                $stmt->fetch();

                return $existingToken; //Retorna el token existente si hay uno.
            } else {
                return false; //Retorna false si no hay ningun token existente.
            }
        } else {
            return false; //Retorna false si hay algún error en la preparacion de la consulta.
        }
    }
    /**
     * Obtiene la fecha de expiracion del ultimo token insertado en la base de datos para un usuario especifico.
     *
     * @param int $user_id El ID del usuario.
     * @return string|string[] La fecha de expiracion del token.
     */
    private function getExpirationDate($user_id)
    {
        $date_exp = null;

        $query = "SELECT date_exp FROM TOKEN WHERE id_usu = ? ORDER BY date_creation DESC LIMIT 1";

        if ($stmt = $this->conn->prepare($query)) {
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $stmt->bind_result($date_exp);
                $stmt->fetch();
                return $date_exp; //Retorna la fecha de expiracion si existe un token para el usuario.
            } else {
                return $this->_response->error_406("There is no existing token for this user." . $user_id); // No hay ningún token para el usuario, por lo que no hay fecha de expiracion.
            }
        } else {
            return $this->_response->error_500();
        }
    }
    /**
     * Verifica si la fecha de expiracion del token es valida.
     *
     * @param string $expirationDate La fecha de expiración del token.
     * @return boolean true si el token es válido, false si ha expirado.
     */
    private function isTokenDateValid($expirationDate)
    {

        $currentDate = date("Y-m-d");

        //Si la fecha actual es anterior a la fecha de expiracion del token, el token es valido.
        return ($currentDate < $expirationDate);
    }
    /**
     * Obtiene un token a partir de su valor.
     *
     * @param string $token El valor del token.
     * @return array|array[] El token y su fecha de expiración, o un mensaje de error.
     */
    public function getToken($token)
    {
        $foundToken = null;
        $expirationDate = null;
        $query = "SELECT value_tok, date_exp FROM TOKEN WHERE value_tok = ? LIMIT 1";

        if ($stmt = $this->conn->prepare($query)) {
            $stmt->bind_param("s", $token);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                //Si se encontro el token en la base de datos, obtener los datos.
                $stmt->bind_result($foundToken, $expirationDate);
                $stmt->fetch();

                //Devolver un array con el token y la fecha de expiracion.
                return array("token" => $foundToken, "expiration_date" => $expirationDate);
            } else {
                //Si no se encontró el token en la base de datos, devolver false.
                return $this->_response->error_406("Token not found");
            }
        } else {
            //Si hay algun error en la preparacion de la consulta, devolver un error 500.
            return $this->_response->error_500();
        }
    }
}

?>