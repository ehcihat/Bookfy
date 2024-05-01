// Funcion para realizar una solicitud AJAX y obtener datos del servidor
function fetchData(url) {
    // Funcion para realizar una solicitud AJAX y obtener datos del servidor
    return new Promise(function(resolve, reject) {
        // Funcion para realizar una solicitud AJAX y obtener datos del servidor
        setTimeout(function() {
            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    resolve(data);
                },
                error: function(xhr, status, error) {
                    reject(error);
                }
            });
        }, 50); 
    });
}

// Llamada AJAX para obtener editores
fetchData('http://localhost/Proyecto/publisher')
    .then(function(data) {
        const id_ediSelect = document.getElementById('id_edi');
        data.forEach(publisher => {
            const option = document.createElement('option');
            option.value = publisher.id_edi;
            option.textContent = publisher.nom_edi;
            id_ediSelect.appendChild(option);
        });
    })
    .catch(function(error) {
        console.error('Error fetching publishers:', error);
    });


// Llamada AJAX para obtener generos
fetchData('http://localhost/Proyecto/genre')
    .then(function(data) {
        const id_genSelect = document.getElementById('id_gen');
        data.forEach(genre => {
            const option = document.createElement('option');
            option.value = genre.id_gen;
            option.textContent = genre.nom_gen;
            id_genSelect.appendChild(option);
        });
    })
    .catch(function(error) {
        console.error('Error fetching genres:', error);
    });
// Llamada AJAX para obtener tiendas
fetchData('http://localhost/Proyecto/store')
    .then(function(data) {
        const cod_tieSelect = document.getElementById('cod_tie');
        data.forEach(store => {
            const option = document.createElement('option');
            option.value = store.cod_tie;
            option.textContent = store.nom_tie;
            cod_tieSelect.appendChild(option);
        });
    })
    .catch(function(error) {
        console.error('Error fetching stores:', error);
    });

// Llamada AJAX para obtener categorias
fetchData('http://localhost/Proyecto/category')
    .then(function(data) {
        const id_catSelect = document.getElementById('id_cat');
        data.forEach(category => {
            const option = document.createElement('option');
            option.value = category.id_cat;
            option.textContent = category.nom_cat;
            id_catSelect.appendChild(option);
        });
    })
    .catch(function(error) {
        console.error('Error fetching categories:', error);
    });

// Llamada AJAX para obtener autores
fetchData('http://localhost/Proyecto/author')
    .then(function(data) {
        const id_autSelect = document.getElementById('id_aut');
        data.forEach(author => {
            const option = document.createElement('option');
            option.value = author.id_aut;
            option.textContent = author.nom_aut;
            id_autSelect.appendChild(option);
        });
    })
    .catch(function(error) {
        console.error('Error fetching authors:', error);
    });

    $(document).on('DOMContentLoaded', function () {
        // Agregar evento de envio al formulario
        $('#addBookForm').on('submit', function (event) {
            // Evitar que el formulario se envie de forma predeterminada
            event.preventDefault();
            $('.message-error').remove();
            // Obtener los autores seleccionados
            var selectedAuthors = $('#id_aut').find(':selected');

   
            var selectedCategories = $('#id_cat').find(':selected');

   

            // Obtener el nombre del archivo de la imagen seleccionada
            var imgName = $('#img_file').val().split('\\').pop();
            var imgFile = $('#img_file')[0].files[0];
            var validExtensions = ['jpeg', 'jpg', 'png']; 
            var imgExtension = imgName.split('.').pop().toLowerCase();

            // Obtener los datos del libro
            var bookData = {
                'tit_lib': $('#tit_lib').val(),
                'id_aut': selectedAuthors.map(function () {
                    return this.value;
                }).get(),
                'num_pag': $('#num_pag').val(),
                'des_lib': $('#des_lib').val(),
                'date_pub': $('#date_pub').val(),
                'precio': $('#precio').val(),
                'lan_lib': $('#lan_lib').val(),
                'img_lib': imgName,
                'id_edi': $('#id_edi').val(),
                'id_gen': $('#id_gen').val(),
                'cod_tie': $('#cod_tie').val(),
                'id_cat': selectedCategories.map(function () {
                    return this.value;
                }).get(),
                'isbn': $('#isbn').val()
            };
            //Validaciones del formulario
            switch(true){

                case ($('#tit_lib').val().length> 255):
                    $('#tit_lib').after(getErrMsgBook
                        ('textLength'));
                    return;
                case selectedAuthors.length > 2:
                    $('#id_aut').after(getErrMsgBook
                        ('lengthAuth'));
                    return;
                case selectedAuthors.length < 1:
                    $('#id_aut').after(getErrMsgBook
                        ('emptyAuth'));
                        return;
                case selectedCategories.length > 4:
                    $('#id_cat').after(getErrMsgBook
                        ('lengthCat'));
                        return;
                case selectedCategories.length < 1:
                    $('#id_cat').after(getErrMsgBook
                        ('emptyCat'));
                        return;
                case /[!#$%^&*(),;/?":{}|<>]/.test($('#num_pag').val()) || $('#num_pag').val() < 0:
                    $('#num_pag').after(getErrMsgBook
                        ('pagesLength'));
                    return;
                case /[!#$%^&*(),;/?":{}|<>]/.test($('#precio').val()) || $('#precio').val() < 0:
                    $('#num_pag').after(getErrMsgBook
                        ('pagesLength'));
                    return;
                case $('#des_lib').val().length > 255:
                    $('#des_lib').after(getErrMsgBook
                        ('textLength'));
                    return;
                case $('#img_file').val().length < 1:
                    $('#img_file').after(getErrMsgBook
                        ('emptyImage'));
                        return;
                case ($.inArray(imgExtension, validExtensions) === -1):
                    $('#img_file').after(getErrMsgBook
                        ('imgErrExtension'));
                        return;
                case !/^(\d{10}|\d{13})$/.test($('#isbn').val()):
                        $('#isbn').after(getErrMsgBook('invalidISBN'));
                        return;
            }
            console.log(bookData);

            // Realizar la solicitud AJAX para enviar los datos del libro
            $.ajax({
                url: 'http://localhost/Proyecto/book',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(bookData)
            })
            .done(function (response) {
                $('#tit_lib_lab').before('<div class="message-success" id="formMessage">Libro agregado.</div>')
                console.log('Book added successfully:', response);

                setTimeout(function() {
                    $('#formMessage').fadeOut(500, function() {
                        $(this).remove();
                    });
                }, 2000);

                var imgFormData = new FormData();
                imgFormData.append('img_file', imgFile);

                $.ajax({
                    url: 'http://localhost/Proyecto/image',
                    method: 'POST',
                    processData: false,
                    contentType: false,
                    data: imgFormData,
                    dataType: 'text',
                })
                .done(function (response) {
                    
                    console.log('Image uploaded:', response.status);
                })
                .fail(function (response) {
                    console.error('Error uploading image:', response.status);

                    
       
                });
            })
            .fail(function (response, error) {
                if (response.status === 406) {
                    $('#tit_lib_lab').before(getErrMsgBook('emptyFields'));
             
                }
                if (response.status === 416) {
                    $('#tit_lib_lab').after(getErrMsgBook('existISBN'));
                   
                }
                console.log(response.status);
                console.error(response.status);
                console.error('Error adding book:', response);
            });
        });
    });
    $(window).on('pageshow', function (event) {
        // Verificar si el evento de pageshow fue provocado por una navegacion hacia atras
        if (event.originalEvent.persisted) {
            // Ocultar el mensaje de registro exitoso si esta visible
            $('#tit_lib_lab').remove();
        }
    });

        function getErrMsgBook(errorCode) {
            switch (errorCode) {
                
                
                case 'emptyFields':
                    return '<div class="message-error" id="emptyFields">Rellene todos los campos.</div>';

                case 'textLength':
                    return '<div class="message-error" id="textLength">Se ha sobrepasado el límite de caracteres.</div>';
    
                case 'pagesLength':
                    return '<div class="message-error" id="pagesLength">Número de páginas del libro no válido.</div>';
                
                case 'invalidPrice':
                    return '<div class="message-error" id="invalidPrice">Introduzca un precio válido.</div>';

                case 'invalidISBN':
                    return '<div class="message-error" id="invalidISBN">Introduzca un ISBN válido, Formato de 10 o 13 dígitos.</div>';

                case 'emptyImage':
                    return '<div class="message-error" id="emptyImage">Suba una foto para el libro.</div>';

                case 'lengthAuth':
                    return '<div class="message-error" id="lengthAuth">Seleccione como máximo dos autores.</div>';
                
                case 'emptyAuth':
                    return '<div class="message-error" id="emptyAuth">Seleccione mínimo un autor.</div>';

                case 'lengthCat':
                    return '<div class="message-error" id="lengthCat">Seleccione como máximo cuatro categorías.</div>';
                
                case 'emptyCat':
                    return '<div class="message-error" id="emptyCat">Seleccione mínimo una categoría.</div>';

                case 'existISBN':
                    return '<div class="message-error" id="existISBN">El ISBN introducido ya existe.</div>';
                
                case 'imgErrExtension':
                    return '<div class="message-error" id="imgErrExtension">Los formatos de la foto deben ser: ".jpeg" o ".png".</div>';

                default:
                    return '<div class="message-error" id="errForm">Error en el formulario.</div>';
            }
        }
    

