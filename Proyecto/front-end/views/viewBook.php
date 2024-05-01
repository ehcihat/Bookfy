<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="../css/bookfy.css">
    <script src="../js/viewBook.js"></script>
    <title>Detalles del Libro</title>
</head>

<body>
    <?php include './header.php'; ?>
    <noscript>
        <META HTTP-EQUIV="Refresh" CONTENT="0;URL=noscript.php">
    </noscript>
    <h1>Detalles del Libro</h1>
    <div class="form-container">

        <form id="bookDetailsForm">
            <div id="successMessage" style="display: none;"></div>

            <div id="errMessage" class="message-error" style="display: none;"></div>
            <label for="tit_lib" id="tit_lib_lab">Título:</label><br>
            <input type="text" id="tit_lib" name="tit_lib" readonly><br>
            <label for="id_aut">Autores:</label><br>
            <span id="currentAuthors"></span><br>
            <input type="text" id="authorNames" readonly><br>
            <label for="num_pag">Número de Páginas:</label><br>
            <input type="number" id="num_pag" name="num_pag" min="0" readonly><br>
            <label for="des_lib">Sinopsis:</label><br>
            <textarea id="des_lib" name="des_lib" rows="4" cols="50" readonly></textarea><br>
            <label for="date_pub">Fecha de Publicación:</label><br>
            <input type="date" id="date_pub" name="date_pub" readonly><br>
            <label for="precio">Precio:</label><br>
            <input type="number" id="precio" name="precio" min="0" step="any" readonly /><br>
            <label for="lan_lib">Idioma:</label><br>
            <input type="text" id="lan_lib" name="lan_lib" readonly><br>
            <label for="img_file">Imagen:</label><br>
            <img id="bookImage" class="book-image" src="" alt="Imagen del libro">

            <label for="id_edi">Editorial:</label><br>
            <input type="text" id="id_edi" readonly><br>
            <label for="id_gen">Género:</label><br>
            <input type="text" id="id_gen" readonly><br>
            <label for="cod_tie">Tienda:</label><br>
            <input type="text" id="id_cod" readonly><br>
            <label for="id_cat">Categorías:</label><br>
            <span id="currentCategories"></span><br>
            <input type="text" id="categoryNames" readonly><br>
            <label for="isbn">ISBN:</label><br>
            <input type="text" id="isbn" name="isbn" readonly><br><br>
        </form>

    </div>
</body>

</html>