<?php
require_once "connection/conn.php";
require_once "response.class.php";


//Clase libro que extiende de nuestra otra clase Conn en la que realizamos funciones para el trato de consultas SQL.
class Book extends Conn
{
    private $table = "libro";
    private $id_lib = "";
    private $tit_lib = "";
    private $num_pag = "";
    private $des_lib = "";
    private $date_pub = "";
    private $precio = "";
    private $lan_lib = "";
    private $img_lib = "";
    private $id_edi = "";
    private $id_gen = "";
    private $cod_tie = "";
    private $isbn = "";


    /**
     * Devuelve un listado de todos los libros.
     *
     * @return array Un array que contiene los datos de todos los libros, incluyendo el título, precio e imagen.
     */
    public function listBooks()
    {
        $query = "SELECT tit_lib,precio,img_lib FROM " . $this->table;
        $data = parent::getData(($query));
        return ($data);
    }

    /**
     * Obtiene los detalles de un libro específico según su id concatenando los datos de las tablas intermedias de autores y categorias.
     *
     * @param int $id El id del libro que se desea obtener.
     * @return array Un array que contiene los detalles del libro, incluyendo su título, autores, categorías y otros datos.
     */
    public function getBook($id)
    {
        $query = "SELECT 
        libro.*,
        GROUP_CONCAT(DISTINCT autor.id_aut) AS id_autores,
        GROUP_CONCAT(DISTINCT autor.nom_aut) AS autores,
        GROUP_CONCAT(DISTINCT categoria.id_cat) AS id_categorias,
        GROUP_CONCAT(DISTINCT categoria.nom_cat) AS categorias
    FROM 
        $this->table
    LEFT JOIN 
        AUTOR_LIBRO ON libro.id_lib = AUTOR_LIBRO.id_lib
    LEFT JOIN 
        autor ON AUTOR_LIBRO.id_aut = autor.id_aut
    LEFT JOIN 
        CATEGORIA_LIBROS ON libro.id_lib = CATEGORIA_LIBROS.id_lib
    LEFT JOIN 
        categoria ON CATEGORIA_LIBROS.id_cat = categoria.id_cat
    WHERE 
        libro.id_lib = $id
    GROUP BY 
        libro.id_lib";

        $data = parent::getData($query);
        return ($data);
    }


    /**
     * Agrega un nuevo libro a la base de datos comprobando que no existan uno con el mismo ISBN.
     *
     * @param string $dataJson Los datos del libro en formato JSON.
     * @return array|array[] Un array que contiene el resultado de la operación, incluyendo el id del libro agregado si tuvo éxito, o un mensaje de error si falla.
     */
    public function postBook($dataJson)
    {

        $_response = new Response();
        $data = json_decode($dataJson, true);

        $requiredFields = ['tit_lib', 'num_pag', 'date_pub', 'precio', 'lan_lib', 'img_lib', 'id_edi', 'id_gen', 'cod_tie', 'isbn', 'id_aut', 'id_cat'];
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                return $_response->error_406("The field '$field' is required.");
            }
        }

        if (!isset($data['tit_lib']) || !isset($data['num_pag']) || !isset($data['date_pub']) || !isset($data['precio']) || !isset($data['lan_lib']) || !isset($data['img_lib']) || !isset($data['id_edi']) || !isset($data['id_gen']) || !isset($data['cod_tie']) || !isset($data['isbn']) || !isset($data['id_aut']) || !isset($data['id_cat'])) {
            return $_response->error_400();
        }

        $existingBook = $this->checkExistingBook($data['isbn']);
        if ($existingBook) {
            return $_response->error_416("Book with ISBN: " . $data['isbn'] . " already exists.");
        }

        $this->tit_lib = $data['tit_lib'];
        $this->num_pag = $data['num_pag'];
        $this->des_lib = $data['des_lib'];
        $this->date_pub = $data['date_pub'];
        $this->precio = $data['precio'];
        $this->lan_lib = $data['lan_lib'];
        $this->img_lib = $data['img_lib'];
        $this->id_edi = $data['id_edi'];
        $this->id_gen = $data['id_gen'];
        $this->cod_tie = $data['cod_tie'];
        $this->isbn = $data['isbn'];



        $expectedFields = ['tit_lib', 'num_pag', 'des_lib', 'date_pub', 'precio', 'lan_lib', 'img_lib', 'id_edi', 'id_gen', 'cod_tie', 'isbn', 'id_aut', 'id_cat'];
        $extraKeys = array_diff(array_keys($data), $expectedFields);

        // Devuelve un error 400 si hay claves adicionales
        if (!empty($extraKeys)) {

            return $_response->error_406("Unknown fields found: " . implode(", ", $extraKeys));
        }
        $bookId = $this->addBook();
        if ($bookId) {
            // Llamada a las funciones para agregar autor y categoría, pasando el id del libro recién creado.
            $this->addAuthorsToBook($bookId, $data['id_aut']);
            $this->addCategoriesToBook($bookId, $data['id_cat']);

            $resp = $_response->response;
            $resp["result"] = array(
                "id_lib" => $bookId
            );

            return $resp;
        } else {

            return $_response->error_500();
        }
    }
    /**
     * Función que realiza la consulta de inserción del libro.
     *
     * @return int El id del libro recién insertado si la operación tiene éxito, de lo contrario, retorna 0.
     */
    private function addBook()
    {

        $query = "INSERT INTO " . $this->table . " (tit_lib, num_pag, des_lib, date_pub, precio, lan_lib, img_lib, id_edi, id_gen, cod_tie, isbn) VALUES ('" .
            $this->tit_lib . "','" .
            $this->num_pag . "','" .
            $this->des_lib . "','" .
            $this->date_pub . "','" .
            $this->precio . "','" .
            $this->lan_lib . "','" .
            $this->img_lib . "','" .
            $this->id_edi . "','" .
            $this->id_gen . "','" .
            $this->cod_tie . "','" .
            $this->isbn . "');";

        $response = parent::execQuery($query);

        if ($response) {
            $lastInsertedId = parent::getLastInsertedId();
            return $lastInsertedId;

        } else {
            return 0;
        }
    }

    /**
     * Actualiza los datos de un libro existente.
     *
     * @param string $dataJson Los datos del libro en formato JSON.
     * @return array|array[] Un array que contiene el resultado de la operación, incluyendo el id del libro actualizado si tuvo éxito, o un mensaje de error si falla.
     */
    public function putBook($dataJson)
    {

        $_response = new Response();
        $data = json_decode($dataJson, true);

        if (!isset($data['id_lib'])) {
            return $_response->error_400();
        } else {
            $this->id_lib = $data['id_lib'];
            if (isset($data['tit_lib'])) {
                $this->tit_lib = $data['tit_lib'];
            }
            if (isset($data['num_pag'])) {
                $this->num_pag = $data['num_pag'];
            }
            if (isset($data['date_pub'])) {
                $this->date_pub = $data['date_pub'];
            }
            if (isset($data['precio'])) {
                $this->precio = $data['precio'];
            }
            if (isset($data['des_lib'])) {
                $this->des_lib = $data['des_lib'];
            }
            if (isset($data['lan_lib'])) {
                $this->lan_lib = $data['lan_lib'];
            }
            if (isset($data['img_lib'])) {
                $this->img_lib = $data['img_lib'];
            }
            if (isset($data['id_edi'])) {
                $this->id_edi = $data['id_edi'];
            }
            if (isset($data['id_gen'])) {
                $this->id_gen = $data['id_gen'];
            }
            if (isset($data['cod_tie'])) {
                $this->cod_tie = $data['cod_tie'];
            }
            if (isset($data['isbn'])) {
                $this->isbn = $data['isbn'];
            }
            if (isset($data['id_aut'])) {
                $this->updateAuthorsOfBook($data['id_aut']);
            }
            if (isset($data['id_cat'])) {
                $this->updateCategoriesOfBook($data['id_cat']);
            }
            $existingBook = $this->checkOtherExistingBook($data['id_lib'], $data['isbn']);
            if ($existingBook) {
                return $_response->error_416("Book with ISBN: " . $data['isbn'] . " already exists.");
            }
            $response = $this->editBook();

            if (isset($response)) {
                $resp = $_response->response;
                $resp["result"] = array(
                    "id_lib" => $this->id_lib
                );
                return $resp;
            } else {
                return $_response->error_500();
            }
        }
    }

    /**
     * Realiza la consulta a base de datos para actualizar los detalles de un libro.
     *
     * @return int El número de filas afectadas por la operación de actualización si tiene éxito, de lo contrario, retorna 0.
     */
    private function editBook()
    {

        $query = "UPDATE " . $this->table . " SET 
                    tit_lib = '" . $this->tit_lib . "',
                    num_pag = '" . $this->num_pag . "',
                    date_pub = '" . $this->date_pub . "',
                    precio = '" . $this->precio . "',
                    des_lib = '" . $this->des_lib . "',
                    lan_lib = '" . $this->lan_lib . "',
                    img_lib = '" . $this->img_lib . "',
                    id_edi = '" . $this->id_edi . "',
                    id_gen = '" . $this->id_gen . "',
                    cod_tie = '" . $this->cod_tie . "',
                    isbn = '" . $this->isbn . "' 
                WHERE id_lib = '" . $this->id_lib . "'";

        $response = parent::updateQuery($query);

        if ($response >= 1) {
            return $response;
        } else {
            return 0;
        }
    }

    /**
     * Elimina un libro de la base de datos.
     *
     * @param string $dataJson Los datos del libro en formato JSON que incluye el id del libro a eliminar.
     * @return array|array[] Un array que contiene el resultado de la operación, incluyendo el id del libro eliminado si tuvo éxito, o un mensaje de error si falla.
     */


    public function deleteBook($dataJson)
    {

        $_response = new Response();
        $data = json_decode($dataJson, true);

        if (!isset($data['id_lib'])) {
            return $_response->error_400();
        } else {
            $this->id_lib = $data['id_lib'];

            $response = $this->deleteBookQuery();

            $expectedField = ['id_lib'];
            $extraKeys = array_diff(array_keys($data), $expectedField);

            if (!empty($extraKeys)) {
                return $_response->error_406("Unknown fields found: " . implode(", ", $extraKeys));
            }

            if (isset($response)) {
                $resp = $_response->response;
                $resp["result"] = array(
                    "id_lib" => $this->id_lib
                );
                return $resp;
            } else {
                return $_response->error_500();
            }
        }
    }

    /**
     * Ejecuta las consultas para eliminar un libro de la base de datos, eliminando previamente el libro de las tablas intermedias de autores y categorías .
     *
     * @return int El número de filas afectadas por la operación de eliminación si tiene éxito, de lo contrario, retorna 0.
     */

    private function deleteBookQuery()
    {

        $deleteAuthorsQuery = "DELETE FROM autor_libro WHERE id_lib = '" . $this->id_lib . "'";
        parent::execQuery($deleteAuthorsQuery);

        $deleteCategoriesQuery = "DELETE FROM categoria_libros WHERE id_lib = '" . $this->id_lib . "'";
        parent::execQuery($deleteCategoriesQuery);

        $query = "DELETE FROM " . $this->table . " WHERE id_lib= '" . $this->id_lib . "'";
        $response = parent::execQuery($query);
        if ($response >= 1) {
            return $response;
        } else {
            return 0;
        }
    }

    /**
     * Obtiene una lista de libros destacados.
     *
     * @return array Un array que contiene los detalles de los libros destacados, incluyendo el título, la imagen y el nombre del autor.
     */


    public function getFeaturedBooks()
    {
        $query = "SELECT DISTINCT libro.id_lib, libro.tit_lib, libro.img_lib, autor.nom_aut
        FROM " . $this->table . "
        INNER JOIN review ON " . $this->table . ".id_lib = review.id_lib
        INNER JOIN AUTOR_LIBRO ON " . $this->table . ".id_lib = AUTOR_LIBRO.id_lib
        INNER JOIN autor ON AUTOR_LIBRO.id_aut = autor.id_aut
        WHERE review.rate = 5";

        $data = parent::getData($query);
        return $data;
    }


    /**
     * Obtiene los detalles de los libros junto con los nombres de sus autores.
     *
     * @return array Un array que contiene los detalles de los libros, incluyendo el título, precio, imagen y los nombres de los autores.
     */
    public function getBookAuthor()
    {
        $query = "SELECT libro.id_lib, libro.tit_lib, libro.precio, libro.img_lib, GROUP_CONCAT(autor.nom_aut SEPARATOR ', ') AS autores
        FROM " . $this->table . "
        INNER JOIN AUTOR_LIBRO ON " . $this->table . ".id_lib = AUTOR_LIBRO.id_lib
        INNER JOIN autor ON AUTOR_LIBRO.id_aut = autor.id_aut
        GROUP BY libro.id_lib";



        $data = parent::getData($query);
        return $data;
    }

    /**
     * Verifica si ya existe un libro con un ISBN dado en la base de datos.
     *
     * @param string $isbn El ISBN del libro que se desea verificar.
     * @return bool Devuelve true si ya existe un libro con el ISBN dado, de lo contrario, devuelve false.
     */
    private function checkExistingBook($isbn)
    {
        $query = "SELECT COUNT(*) AS count FROM " . $this->table . " WHERE isbn = '" . $isbn . "'";
        $result = $this->getData($query);
        $count = $result[0]['count'] ?? 0;
        return $count > 0;
    }


    /**
     * Verifica si existe otro libro con el mismo ISBN pero con un id de libro diferente en la base de datos. Esto se ha utilizado para la parte de actualizar libro.
     *
     * @param int $bookId El id del libro que se desea excluir de la verificación.
     * @param string $isbn El ISBN del libro a verificar.
     * @return bool Devuelve true si existe otro libro con el mismo ISBN pero con un id de libro diferente, de lo contrario, devuelve false.
     */
    private function checkOtherExistingBook($bookId, $isbn)
    {
        $query = "SELECT COUNT(*) AS count FROM " . $this->table . " WHERE isbn = '" . $isbn . "' AND id_lib != '" . $bookId . "'";
        $result = $this->getData($query);
        $count = $result[0]['count'] ?? 0;
        return $count > 0;
    }

    /**
     * Realiza la consulta que añade en la tabla intermedia de autor_libro.
     *
     * @param int $bookId El id del libro al que se asociará el autor.
     * @param int $authorId El Iid del autor que se asociará al libro.
     * @return int El número de filas afectadas por la operación de inserción.
     */
    private function addAuthorBook($bookId, $authorId)
    {
        $query = "INSERT INTO AUTOR_LIBRO (id_aut, id_lib) VALUES ($authorId, $bookId)";
        $response = $this->execQuery($query);
        return $response;
    }

    /**
     * Realiza la consulta que añade en la tabla intermedia de categoria_libros.
     *
     * @param int $bookId El id del libro al que se asociará la categoría.
     * @param int $categoryId El id de la categoría que se asociará al libro.
     * @return int El número de filas afectadas por la operación de inserción.
     */
    private function addCategoryBook($bookId, $categoryId)
    {
        $query = "INSERT INTO CATEGORIA_LIBROS (id_cat, id_lib) VALUES ($categoryId, $bookId)";
        $response = $this->execQuery($query);
        return $response;
    }

    /**
     * Asocia múltiples autores a un libro en la base de datos.
     *
     * @param int $bookId El id del libro al que se asociarán los autores.
     * @param array $authorIds Un array de ids de autores que se asociarán al libro.
     */
    private function addAuthorsToBook($bookId, $authorIds)
    {
        foreach ($authorIds as $authorId) {
            $this->addAuthorBook($bookId, $authorId);
        }
    }

    /**
     * Asocia múltiples categorías a un libro en la base de datos.
     *
     * @param int $bookId El id del libro al que se asociarán las categorías.
     * @param array $categoryIds Un array de id de categorías que se asociarán al libro.
     */
    private function addCategoriesToBook($bookId, $categoryIds)
    {
        foreach ($categoryIds as $categoryId) {
            $this->addCategoryBook($bookId, $categoryId);
        }
    }
    /**
     * Actualiza los autores asociados a un libro en la base de datos.
     *
     * @param array $authorIds Un array de ids de autores que se asociarán al libro.
     */
    private function updateAuthorsOfBook($authorIds)
    {
        //Eliminar todas las asociaciones de autores para este libro.
        $deleteQuery = "DELETE FROM autor_libro WHERE id_lib = '" . $this->id_lib . "'";
        parent::updateQuery($deleteQuery);

        //Insertar las nuevas asociaciones de autores.
        foreach ($authorIds as $authorId) {
            $insertQuery = "INSERT INTO autor_libro (id_aut, id_lib) VALUES ('$authorId', '" . $this->id_lib . "')";
            parent::updateQuery($insertQuery);
        }
    }
    /**
     * Actualiza las categorías asociadas a un libro en la base de datos.
     *
     * @param array $categoryIds Un array de ids de categorías que se asociarán al libro.
     */
    private function updateCategoriesOfBook($categoryIds)
    {
        //Eliminar todas las asociaciones de categorías para este libro.
        $deleteQuery = "DELETE FROM categoria_libros WHERE id_lib = '" . $this->id_lib . "'";
        parent::updateQuery($deleteQuery);

        //Insertar las nuevas asociaciones de categorías.
        foreach ($categoryIds as $categoryId) {
            $insertQuery = "INSERT INTO categoria_libros (id_cat, id_lib) VALUES ('$categoryId', '" . $this->id_lib . "')";
            parent::updateQuery($insertQuery);
        }
    }
    //Filtros

    /**
     * Obtiene una lista de libros ordenada por precio ascendente o descendente.
     *
     * @param string $value El valor que determina el orden de la lista, puede ser 'ASC' para ascendente o 'DESC' para descendente.
     * @return array Un array que contiene los detalles de los libros ordenados por precio.
     */

    public function getBookByPrice($value)
    {
        $query = "SELECT libro.id_lib, libro.tit_lib, libro.precio, libro.img_lib, GROUP_CONCAT(autor.nom_aut) AS autores
              FROM " . $this->table . "
              INNER JOIN AUTOR_LIBRO ON " . $this->table . ".id_lib = AUTOR_LIBRO.id_lib
              INNER JOIN autor ON AUTOR_LIBRO.id_aut = autor.id_aut
              GROUP BY libro.id_lib
              ORDER BY libro.precio $value";
        return parent::getData($query);
    }

    /**
     * Obtiene una lista de libros filtrados por el nombre.
     *
     * @param string $name El nombre o parte del nombre del libro a buscar.
     * @return array Un array que contiene los detalles de los libros que coinciden con el nombre proporcionado.
     */
    public function getBookByName($name)
    {
        $query = "SELECT libro.id_lib, libro.tit_lib, libro.precio, libro.img_lib, GROUP_CONCAT(autor.nom_aut) AS autores
              FROM " . $this->table . "
              INNER JOIN AUTOR_LIBRO ON " . $this->table . ".id_lib = AUTOR_LIBRO.id_lib
              INNER JOIN autor ON AUTOR_LIBRO.id_aut = autor.id_aut
              WHERE libro.tit_lib LIKE '%" . $name . "%'
              GROUP BY libro.id_lib";
        return parent::getData($query);
    }

    /**
     * Obtiene una lista de libros filtrados por el id del género.
     *
     * @param int $id_gen El id del género por el cual filtrar los libros.
     * @return array Un array que contiene los detalles de los libros que pertenecen al género especificado.
     */
    public function getBookByGenre($id_gen)
    {
        $query = "SELECT libro.id_lib, libro.tit_lib, libro.precio, libro.img_lib, GROUP_CONCAT(autor.nom_aut) AS autores
              FROM " . $this->table . "
              INNER JOIN AUTOR_LIBRO ON " . $this->table . ".id_lib = AUTOR_LIBRO.id_lib
              INNER JOIN autor ON AUTOR_LIBRO.id_aut = autor.id_aut
              WHERE libro.id_gen =  $id_gen
              GROUP BY libro.id_lib";
        return parent::getData($query);
    }
    /**
     * Obtiene una lista de libros filtrados por el ISBN.
     *
     * @param string $isbn El ISBN del libro a buscar.
     * @return array Un array que contiene los detalles de los libros que coinciden con el ISBN proporcionado.
     */
    public function getBookByISBN($isbn)
    {
        $query = "SELECT libro.id_lib, libro.tit_lib, libro.precio, libro.img_lib, GROUP_CONCAT(autor.nom_aut) AS autores
              FROM " . $this->table . "
              INNER JOIN AUTOR_LIBRO ON " . $this->table . ".id_lib = AUTOR_LIBRO.id_lib
              INNER JOIN autor ON AUTOR_LIBRO.id_aut = autor.id_aut
              WHERE libro.isbn LIKE '%" . $isbn . "%'
              GROUP BY libro.id_lib";
        return parent::getData($query);
    }

    /**
     * Obtiene una lista de libros filtrados por el ID del autor.
     *
     * @param int $id_aut El ID del autor por el cual filtrar los libros.
     * @return array Un array que contiene los detalles de los libros escritos por el autor especificado.
     */

    public function getBookByAuthor($id_aut)
    {
        $query = "SELECT libro.id_lib, libro.tit_lib, libro.precio, libro.img_lib, GROUP_CONCAT(autor.nom_aut) AS autores
              FROM " . $this->table . "
              INNER JOIN AUTOR_LIBRO ON " . $this->table . ".id_lib = AUTOR_LIBRO.id_lib
              INNER JOIN autor ON AUTOR_LIBRO.id_aut = autor.id_aut
              WHERE autor.id_aut = $id_aut
              GROUP BY libro.id_lib";
        return parent::getData($query);
    }

    /**
     * Obtiene una lista de libros filtrados por el id de la categoría.
     *
     * @param int $id_cat El id de la categoría por la cual filtrar los libros.
     * @return array Un array que contiene los detalles de los libros que pertenecen a la categoría especificada.
     */
    public function getBookByCategory($id_cat)
    {
        $query = "SELECT libro.id_lib, libro.tit_lib, libro.precio, libro.img_lib, GROUP_CONCAT(autor.nom_aut) AS autores
              FROM " . $this->table . "
              INNER JOIN AUTOR_LIBRO ON " . $this->table . ".id_lib = AUTOR_LIBRO.id_lib
              INNER JOIN autor ON AUTOR_LIBRO.id_aut = autor.id_aut
              INNER JOIN categoria_libros ON " . $this->table . ".id_lib = categoria_libros.id_lib
              WHERE categoria_libros.id_cat = " . $id_cat . "
              GROUP BY libro.id_lib";
        return parent::getData($query);
    }
}


?>