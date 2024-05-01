<?php
//Clase que maneja la conexión a la base de datos.
class Conn
{

    private $server;
    private $user;
    private $pass;
    private $db;
    private $port;

    /**
     * Constructor de la clase. Establece la conexión con la base de datos.
     */
    function __construct()
    {
        $data = $this->connData();
        foreach ($data as $data => $value) {
            $this->server = $value['server'];
            $this->user = $value['user'];
            $this->pass = $value['pass'];
            $this->db = $value['db'];
            $this->port = $value['port'];
        }
        $this->conn = new mysqli($this->server, $this->user, $this->pass, $this->db, $this->port);

        if ($this->conn->connect_errno) {
            echo "Malfunction detected.";
            die();
        }
    }

    /**
     * Obtiene los datos de conexión desde el archivo de configuración 'config'.
     *
     * @return array Un array que contiene los datos de conexión.
     */

    private function connData()
    {
        $path = dirname(dirname(__DIR__));
        $json = file_get_contents($path . "/classes/config");
        return json_decode($json, true);
    }

    /**
     * Convierte los datos a UTF-8 para evitar problemas de codificación.
     *
     * @param array $array El array de datos.
     * @return array El array de datos convertido a UTF-8.
     */
    private function convertUTF8($array)
    {
        array_walk_recursive($array, function (&$item, $key) {
            if (!mb_detect_encoding($item, 'utf-8', true)) {
                $item = mb_convert_encoding($item, 'utf-8', 'auto');
            }
        });
        return $array;
    }

    /**
     * Ejecuta una consulta SQL y retorna los resultados.
     *
     * @param string $query La consulta SQL a ejecutar.
     * @return array Un array que contiene los resultados de la consulta.
     */
    public function getData($query)
    {
        $results = $this->conn->prepare($query);
        if ($results === false) {
            return array();
        }

        $results->execute();
        $resultArray = $results->get_result()->fetch_all(MYSQLI_ASSOC);

        if ($resultArray === null) {
            return array();
        }

        return $this->convertUTF8($resultArray);
    }

    /**
     * Encripta una cadena utilizando el algoritmo md5.
     *
     * @param string $string La cadena a encriptar.
     * @return string La cadena encriptada.
     */
    protected function encrypt($string)
    {
        return md5($string);
    }

    /**
     * Ejecuta una consulta SQL que no retorna resultados,usada para consultas como INSERT, UPDATE o DELETE.
     *
     * @param string $query La consulta SQL a ejecutar.
     * @return boolean true si la consulta se ejecutó con éxito, false en caso contrario.
     */
    public function execQuery($query)
    {
        $results = $this->conn->prepare($query);
        if ($results === false) {
            return false;
        }
        $results->execute();
        return $this->conn->affected_rows;
    }

    /**
     * Ejecuta una consulta SQL de actualización.
     *
     * @param string $query La consulta SQL a ejecutar.
     * @return int|boolean El número de filas afectadas por la consulta si se ejecutó con éxito, o false en caso contrario.
     */
    public function updateQuery($query)
    {
        $results = $this->conn->prepare($query);
        if ($results === false) {
            return false;
        }
        $results->execute();
        $rows = $this->conn->affected_rows;
        if ($rows >= 1) {
            return $rows;
        } else {
            return false;
        }
    }

    /**
     * Obtiene el último ID insertado en la base de datos.
     *
     * @return int El último ID insertado.
     */
    public function getLastInsertedId()
    {
        return $this->conn->insert_id;
    }
}