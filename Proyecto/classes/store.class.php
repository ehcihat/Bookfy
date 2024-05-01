<?php
require_once "connection/conn.php";
require_once "response.class.php";

class Store extends Conn
{
    private $table = "tienda";

    private $nom_tie = "";
    private $cod_tie = "";
    private $horA = "";
    private $horB = "";
    private $dir_tie= "";

    private $_response;
    public function __construct()
    {
        parent::__construct();
        $this->_response = new Response();
    }

    /**
     * Obtiene la lista de tiendas.
     *
     * @return array La lista de tiendas.
     */
    public function listStores()
    {
        $query = "SELECT nom_tie, cod_tie FROM " . $this->table;
        $data = parent::getData(($query));
        return ($data);
    }

    /**
     * Obtiene los detalles de una tienda por su codigo.
     *
     * @param string $id El codigo de la tienda.
     * @return array Los detalles de la tienda.
     */
    public function getStore($id)
    {
        $query = "SELECT nom_tie, dir_tie, horA, horB FROM " . $this->table . " WHERE cod_tie = $id";
        return parent::getData($query);
    }

    
}
?>