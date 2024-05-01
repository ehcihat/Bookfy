<?php
require_once "connection/conn.php";
require_once "response.class.php";

class Category extends Conn
{
    private $table = "categoria";

    private $nom_cat = "";
    private $id_cat = "";


    private $_response;
    public function __construct()
    {
        parent::__construct();
        $this->_response = new Response();
    }
    /**
     * Obtiene un listado de todas las categorias.
     *
     * @return array Un arreglo asociativo con los nombres y IDs de las categorías.
     */
    public function listCategories()
    {
        $query = "SELECT nom_cat, id_cat FROM " . $this->table;
        $data = parent::getData(($query));
        return ($data);
    }
    /**
     * Obtiene la informacion de una categoria específica por su id.
     *
     * @param int $id El id de la categoria que se desea obtener.
     * @return array Un arreglo asociativo con el nombre de la categoria si se encuentra.
     */
    public function getCategory($id)
    {
        $query = "SELECT nom_cat FROM " . $this->table . " WHERE id_cat = $id";
        return parent::getData($query);
    }


}
?>