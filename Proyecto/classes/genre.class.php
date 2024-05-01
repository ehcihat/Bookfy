<?php
require_once "connection/conn.php";
require_once "response.class.php";

class Genre extends Conn
{
    private $table = "genero";

    private $nom_gen = "";
    private $id_gen = "";

    private $_response;
    public function __construct()
    {
        parent::__construct();
        $this->_response = new Response();
    }
    /**
     * Obtiene un listado de todos los generos.
     *
     * @return array Un arreglo asociativo con los nombres y ids de los generos.
     */
    public function listGenres()
    {
        $query = "SELECT nom_gen, id_gen FROM " . $this->table;
        $data = parent::getData(($query));
        return ($data);
    }
    /**
     * Obtiene la informacion de un genero especifico por su id.
     *
     * @param int $id El id del genero que se desea obtener.
     * @return array Un arreglo asociativo con el nombre del genero si se encuentra, o un arreglo vacio si no se encuentra.
     */
    public function getGenre($id)
    {
        $query = "SELECT nom_gen FROM " . $this->table . " WHERE id_gen = $id";
        return parent::getData($query);
    }


}
?>