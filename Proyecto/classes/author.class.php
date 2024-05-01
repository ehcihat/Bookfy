<?php
require_once "connection/conn.php";
require_once "response.class.php";

class Author extends Conn
{
    private $table = "autor";

    private $nom_aut = "";
    private $id_aut = "";
    private $nac_aut = "";
    private $email_aut = "";

    private $_response;
    public function __construct()
    {
        parent::__construct();
        $this->_response = new Response();
    }
      /**
     * Obtiene un listado de todos los autores.
     *
     * @return array Un arreglo asociativo con los nombres y ids de los autores.
     */
    public function listAuthors()
    {
        $query = "SELECT nom_aut, id_aut FROM " . $this->table;
        $data = parent::getData(($query));
        return ($data);
    }
    /**
     * Obtiene la informacion de un autor específico por su id.
     *
     * @param int $id El id del autor que se desea obtener.
     * @return array Un arreglo asociativo con la informacion del autor si se encuentra.
     */
    public function getAuthor($id)
    {
        $query = "SELECT nom_aut, email_aut, nac_aut FROM " . $this->table . " WHERE id_aut = $id";
        return parent::getData($query);
        
    }
}