<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/catalogue.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="../js/catalogue.js"></script>

    <title>Libros</title>
</head>

<body>
    <?php include './header.php'; ?>
    <noscript>
        <META HTTP-EQUIV="Refresh" CONTENT="0;URL=noscript.php">
    </noscript>
    <section class="home">

        <div class="hero">
            <h1>Disfruta del viaje con las siguientes obras</h1>
        </div>
    </section>
    <button onclick="location.href='./addBook.php'" class="add-book-button">
        <i class="fas fa-plus"></i> Agregar Libro
    </button>

    <select id="sortByPrice">
        <option value="">Ordenar por precio...</option>
        <option value="asc">Ascendente</option>
        <option value="desc">Descendente</option>
    </select>

    <select id="sortByGenre">
        <option value="">Todos los géneros</option>
    </select>

    <select id="sortByAuthor">
        <option value="">Todos los autores</option>
    </select>

    <select id="sortByCategory">
        <option value="">Todas las categorías</option>
    </select>


    <input type="text" id="searchByName" placeholder="Buscar por nombre...">
    <input type="text" id="searchByISBN" placeholder="Buscar por ISBN...">
    <section class="featured-books">


    <section class="featured-books">
        <div class="book-list"></div>
    </section>


    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section about">
                <h3>Sobre nosotros</h3>
                <p>Bookfy es tu tienda de libros en línea donde puedes encontrar una amplia variedad de títulos de
                    diferentes géneros.</p>
            </div>
            <div class="footer-section contact">
                <h3>Contacto</h3>
                <p><i class="fas fa-envelope"></i> info@bookfy.com</p>
                <p><i class="fas fa-phone"></i> +1234567890</p>
            </div>

        </div>
        <div class="footer-bottom">
            &copy; 2024 Bookfy. Todos los derechos reservados. Proyecto final de ciclo Tahiche Hernández Almeida
        </div>
    </footer>
</body>

</html>
</body>

</html>