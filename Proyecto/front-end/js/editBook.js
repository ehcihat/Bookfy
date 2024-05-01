function getBookIdFromUrl() {
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const bookId = urlParams.get('id');

    return bookId;
}

// Funcion para cargar los datos del libro

function loadBookData(bookId) {
    const fetchOptions = {
        method: 'GET',
        timeout: 50 
    };
    fetch(`http://localhost/Proyecto/book?id=${bookId}`)
    
        .then(response => response.json())
        .then(data => {


            const bookData = data[0];
            const originalAuthorIds = bookData.id_autores.split(',').map(id => parseInt(id.trim()));
            const originalCategoryIds = bookData.id_categorias.split(',').map(id => parseInt(id.trim()));
//Guardamos los datos originales del libro para su posterior comparacion
            originalBookData = {
                'tit_lib': bookData.tit_lib,
                'num_pag': bookData.num_pag,
                'des_lib': bookData.des_lib,
                'date_pub': bookData.date_pub,
                'precio': bookData.precio,
                'lan_lib': bookData.lan_lib,
                'img_lib': bookData.img_lib,
                'id_edi': bookData.id_edi,
                'id_gen': bookData.id_gen,
                'cod_tie': bookData.cod_tie,
                'isbn': bookData.isbn,
                'id_aut': originalAuthorIds,
                'id_cat': originalCategoryIds
            };
         
            console.log("Libro originall");
            console.log(originalBookData);

            $('#tit_lib').val(bookData.tit_lib);
            $('#num_pag').val(bookData.num_pag);
            $('#des_lib').val(bookData.des_lib);
            $('#date_pub').val(bookData.date_pub);
            $('#precio').val(bookData.precio);
            $('#lan_lib').val(bookData.lan_lib);
            $('#bookImage').attr('src', `../img/${bookData.img_lib}`);
            $('#isbn').val(bookData.isbn);
            $('#id_edi').val(originalBookData.id_edi);
            $('#id_gen').val(originalBookData.id_gen);
            $('#cod_tie').val(originalBookData.cod_tie); 
            $('#currentAuthors').text(bookData.autores);
            $('#currentCategories').text(bookData.categorias);
        })
        .catch(error => console.error('Error obteniendo detalles del libro:', error));

    // Agregar evento de envío al formulario
    $('#editBookForm').on('submit', function (event) {
        // Evitar que el formulario se envíe de forma predeterminada
        event.preventDefault();
        $('.message-error').remove();
      
        var selectedAuthors = $('#id_aut').val(); // Obtener los valores seleccionados del selector de autores
        console.log('Autores seleccionados:', selectedAuthors);
    
        
     
        var selectedCategories = $('#id_cat').val(); 
        console.log('Categorías seleccionadas:', selectedCategories);
        if (!selectedAuthors || selectedAuthors.length === 0) {
            selectedAuthors = originalBookData.id_aut;
        }
        
        if (!selectedCategories || selectedCategories.length === 0) {
            selectedCategories = originalBookData.id_cat;
        }
        
        // Obtener el nombre del archivo de la imagen seleccionada
        var imgName = $('#img_file').val().split('\\').pop();
        var imgFile = $('#img_file')[0].files[0];
        var validExtensions = ['jpeg', 'jpg', 'png']; 
        var imgExtension = imgName.split('.').pop().toLowerCase();
    
        
        // Obtener los datos del libro
        var editBookData = {
            'id_lib': bookId,
            'tit_lib': $('#tit_lib').val(),
            'num_pag': $('#num_pag').val(),
            'des_lib': $('#des_lib').val(),
            'date_pub': $('#date_pub').val(),
            'precio': $('#precio').val(),
            'lan_lib': $('#lan_lib').val(),
            'img_lib': imgName,
            'id_edi': $('#id_edi').val(),
            'id_gen': $('#id_gen').val(),
            'cod_tie': $('#cod_tie').val(),
            'isbn': $('#isbn').val(),
            'id_aut': selectedAuthors,
            'id_cat': selectedCategories 
        };

        switch(true){

            case ($('#tit_lib').val().length> 255):
                $('#tit_lib').after(getErrMsgBook
                    ('textLength'));
                return;
            case ($('#precio').val()> 100000000):
                $('#precio').after(getErrMsgBook
                    ('invalidPrice'));
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
  

            case !/^(\d{10}|\d{13})$/.test($('#isbn').val()):
                    $('#isbn').after(getErrMsgBook('invalidISBN'));
                    return;
        }

        var imgFile = $('#img_file')[0].files[0];
        if (imgFile) { // Verificar si se ha seleccionado una imagen
            var imgName = imgFile.name;
            var validExtensions = ['jpeg', 'jpg', 'png']; 
            var imgExtension = imgName.split('.').pop().toLowerCase();
        
            // Verificar si la extension de la imagen es valida
            if ($.inArray(imgExtension, validExtensions) !== -1) {
                // Enviar la imagen al servidor
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
                    editBookData['img_lib'] = imgName; // Actualizar el nombre de la imagen en los datos del libro
                    updateBook(bookId, editBookData); // Llamar a la función para actualizar el libro
                })
                .fail(function (response) {
                    console.error('Error uploading image:', response.status);
                    // Manejar el error si falla la carga de la imagen
                });
            } else {
                // Mostrar mensaje de error si la extensión de la imagen no es válida
                $('#img_file').after(getErrMsgBook('imgErrExtension'));
            }
        } else {
           
         
       

        console.log(editBookData);
        for (var key in originalBookData) {
            // Si el campo del formulario esta vacio o no ha cambiado, mantener el valor original
            if (!editBookData[key] || editBookData[key] === originalBookData[key]) {
                editBookData[key] = originalBookData[key];
            }
        }
    
        updateBook(bookId, editBookData);
    }
    });
    
}

function updateBook(bookId, data) {
    console.log('Datos a enviar al servidor:', data); 
    $.ajax({
        url: `http://localhost/Proyecto/book?id=${bookId}`, // URL con el id del libro en la ruta
        type: 'PUT',
        contentType: 'application/json',
        data: JSON.stringify(data),
        success: function(response) {
            console.log('Libro actualizado correctamente', response);
            // Mostrar un mensaje de exito al usuario
            $('#tit_lib_lab').before('<div class="message-success" id="formMessage">Libro actualizado.</div>')
            setTimeout(function() {
                $('#formMessage').fadeOut(500, function() {
                    $(this).remove();
                });
            }, 1500);
       
        },
        error: function(xhr, status, error,) {
            console.error('Error al actualizar el libro:', error);
            if (xhr.status === 406) {
                $('#tit_lib_lab').before(getErrMsgBook('emptyFields'));
         
            }
            if (xhr.status === 416) {
                $('#tit_lib_lab').after(getErrMsgBook('existISBN'));
               
            }
            console.log(xhr.status);
            console.error(xhr.status);
            console.error('Error adding book:', xhr);
            $('#errMessage').show();
        }
    });
}

// Obtener el id del libro de la URL y cargar los datos del libro al cargar la pagina
$(document).ready(function() {
    const bookId = getBookIdFromUrl();
    loadBookData(bookId);
});
function fetchData(url) {
    return new Promise(function(resolve, reject) {
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
        }, 100); 
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
        $('#id_edi').val(originalBookData.id_edi);
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

        $('#id_gen').val(originalBookData.id_gen);
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
        $('#cod_tie').val(originalBookData.cod_tie);
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

    $(window).on('pageshow', function (event) {
        // Verificar si el evento de pageshow fue provocado por una navegacion hacia atras
        if (event.originalEvent.persisted) {
            // Ocultar el mensaje de registro exitoso si esta visible
            $('#tit_lib_lab').remove();
        }
    });
//Funcion de mensajes de error. 
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