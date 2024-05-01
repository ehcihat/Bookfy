function getBookIdFromUrl() {
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const bookId = urlParams.get('id');

    return bookId;
}

function loadBookData(bookId) {
    const fetchOptions = {
        method: 'GET',
        timeout: 50 
    };

    fetch(`http://localhost/Proyecto/book?id=${bookId}`, fetchOptions)
        .then(response => response.json())
        .then(data => {
            const bookData = data[0];

            console.log(bookData);
            $('#tit_lib').val(bookData.tit_lib);
            $('#num_pag').val(bookData.num_pag);
            $('#des_lib').val(bookData.des_lib);
            $('#date_pub').val(bookData.date_pub);
            $('#precio').val(bookData.precio);
            $('#lan_lib').val(bookData.lan_lib);
            $('#bookImage').attr('src', `../img/${bookData.img_lib}`);
            $('#isbn').val(bookData.isbn);
            $('#id_edi').val(bookData.id_edi);
            $('#id_gen').val(bookData.id_gen);
            $('#cod_tie').val(bookData.cod_tie);
            $('#currentAuthors').text(bookData.autores);
            $('#currentCategories').text(bookData.categorias);   
        })
        .catch(error => console.error('Error obteniendo detalles del libro:', error));
}

$(document).ready(function() {
    const bookId = getBookIdFromUrl();
    loadBookData(bookId);

    fetch(`http://localhost/Proyecto/book?id=${bookId}`)
        .then(response => response.json())
        .then(data => {
            const bookData = data[0];
            Promise.all([
                fetch(`http://localhost/Proyecto/publisher?id=${bookData.id_edi}`).then(response => response.json()),
                fetch(`http://localhost/Proyecto/genre?id=${bookData.id_gen}`).then(response => response.json()),
                fetch(`http://localhost/Proyecto/store?id=${bookData.cod_tie}`).then(response => response.json())
            ])
            .then(([publisherData, genreData, storeData]) => {
                const publisherName = publisherData[0].nom_edi;
                const genreName = genreData[0].nom_gen;
                const storeName = storeData[0].nom_tie;

                // Asignar nombres a los campos correspondientes en el formulario
                $('#id_edi').val(publisherName);
                $('#id_gen').val(genreName);
                $('#id_cod').val(storeName);
            })
            .catch(error => console.error('Error obteniendo el género, editorial y tienda:', error));
        })
        .catch(error => console.error('Error obteniendo detalles del libro:', error));
});
