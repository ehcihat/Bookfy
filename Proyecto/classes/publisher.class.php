<?php
require_once "connection/conn.php";
require_once "response.class.php";

class Publisher extends Conn
{
    private $table = "editorial";

    private $id_edi = "";
    private $nom_edi = "";

    private $_response;
    public function __construct()
    {
        parent::__construct();
        $this->_response = new Response();
    }
    /**
     * Obtiene una lista de editoriales.
     *
     * @return array|string[] La lista de editoriales.
     */
    public function listPublishers()
    {
        $query = "SELECT nom_edi, id_edi FROM " . $this->table;
        $data = parent::getData(($query));
        return ($data);
    }
    /**
     * Obtiene una editorial por su id.
     *
     * @param int $id El id de la editorial.
     * @return array|string[] La editorial encontrada.
     */
    public function getPublisher($id)
    {
        $query = "SELECT nom_edi FROM " . $this->table . " WHERE id_edi = $id";
        return parent::getData($query);
    }


}
?>