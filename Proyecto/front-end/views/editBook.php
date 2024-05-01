<!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <link rel="stylesheet" href="../css/bookfy.css">
        <script src="../js/editBook.js"></script>

        <title>Editar Libro</title>
    </head>

    <body>
        <?php include './header.php'; ?>
        <noscript>
            <META HTTP-EQUIV="Refresh" CONTENT="0;URL=noscript.php">
        </noscript>
        <h1>Editar Libro</h1>
        <div class="form-container">

                <form id="editBookForm" enctype="multipart/form-data">
                    <div id="successMessage" style="display: none;"></div>

                    <div id="errMessage" class="message-error" style="display: none;"></div>
                    <label for="tit_lib" id="tit_lib_lab">Título:</label><br>
                    <input type="text" id="tit_lib" name="tit_lib"><br>
                    <label for="id_aut">Autores:</label><br>
                    <span id="currentAuthors"></span><br>
                    <select id="id_aut" name="id_aut[]" multiple></select><br>
                    <label for="num_pag">Número de Páginas:</label><br>
                    <input type="number" id="num_pag" name="num_pag" min="0"><br>
                    <label for="des_lib">Sinopsis:</label><br>
                    <textarea id="des_lib" name="des_lib" rows="4" cols="50"></textarea><br>
                    <label for="date_pub">Fecha de Publicación:</label><br>
                    <input type="date" id="date_pub" name="date_pub"><br>
                    <label for="precio">Precio:</label><br>
                    <input type="number" id="precio" name="precio" min="0" step="any" /><br>
                    <label for="lan_lib">Idioma:</label><br>
                    <select id="lan_lib" name="lan_lib">
                        <option value="es">Español</option>
                        <option value="en">Inglés</option>
                        <option value="de">Alemán</option>
                        <option value="ru">Ruso</option>
                        <option value="ja">Japonés</option>
                        <option value="zh">Chino</option>
                    </select><br>
                    <label for="img_file">Imagen:</label><br>
                    <img id="bookImage" class="book-image" src="" alt="Imagen del libro">
                    <input type="file" id="img_file" name="img_file"><br>
                    <label for="id_edi">Editorial:</label><br>
                    <select id="id_edi" name="id_edi"></select><br>
                    <label for="id_gen">Género:</label><br>
                    <select id="id_gen" name="id_gen"></select><br>
                    <label for="cod_tie">Código de Tienda:</label><br>
                    <select id="cod_tie" name="cod_tie"></select><br>
                    <label for="id_cat">Categorías:</label><br>
                    <span id="currentCategories"></span><br>
                    <select id="id_cat" name="id_cat[]" multiple></select><br>
                    <label for="isbn">ISBN:</label><br>
                    <input type="text" id="isbn" name="isbn"><br><br>
                    <button type="submit">Editar Libro</button>
                </form>

        </div>


    </body>

    </html>