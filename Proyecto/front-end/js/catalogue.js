function createBookCard(book) {
    
    
    const bookCard = document.createElement('div');
    bookCard.classList.add('book-card');
  
    // Contenedor para la informacion del libro
    const bookInfo = document.createElement('div');
    bookInfo.classList.add('book-info');
  
    // Crea un elemento de imagen para mostrar la imagen del libro
    const imageElement = document.createElement('img');
    // Concatena la ruta de la carpeta de imágenes con el nombre de la imagen
    imageElement.src = '../img/' + book.img_lib;
    imageElement.alt = book.tit_lib;
  
    // Crea elementos HTML para mostrar la informacion del libro (titulo, autor, precio, etc.)
    const titleElement = document.createElement('h2');
    titleElement.classList.add('book-title');
    titleElement.textContent = book.tit_lib;
  
    const authorElement = document.createElement('h2');
    authorElement.classList.add('book-author');
    if (book.autores.includes(',')) {
      authorElement.textContent = `Autores: ${book.autores}`;
    } else {
      authorElement.textContent = `Autor: ${book.autores}`;
    }
  
    const priceElement = document.createElement('h2');
    priceElement.classList.add('book-price');
    priceElement.textContent = `Precio: ${book.precio} €`;
  
    // Botones de editar, eliminar y añadir al carrito
    const editButton = document.createElement('button');
    const editLink = document.createElement('a');
    editLink.href = `editBook.php?id=${book.id_lib}`;
    editLink.innerHTML = '<i class="fas fa-edit"></i> Editar';
    editButton.appendChild(editLink);
  

    const viewButton = document.createElement('button');
    const viewLink = document.createElement('a');
    viewLink.href = `viewBook.php?id=${book.id_lib}`;
    viewLink.innerHTML = '<i class="fa fa-book" aria-hidden="true"></i> Ver Libro';
    viewButton.appendChild(viewLink);

    const deleteButton = document.createElement('button');
    deleteButton.innerHTML = '<i class="fas fa-trash"></i> Eliminar';
    deleteButton.addEventListener('click', () => {
      const confirmed = window.confirm("¿Estás seguro de que deseas eliminar este libro? Puede estar asociado a Categorías y Autores.");
    if (confirmed) {
      // Si el usuario confirma la eliminacion, llamar a la funcion deleteBook con el id del libro
      deleteBook(book.id_lib);
      alert("Libro eliminado."); 
      window.location.reload(); 
  }
  });

  
    // Contenedor para los botones
    const buttonContainer = document.createElement('div');
    buttonContainer.classList.add('book-buttons');
    buttonContainer.appendChild(editButton);
    buttonContainer.appendChild(deleteButton);
  
  
    // Añade los elementos al div del libro
    bookInfo.appendChild(titleElement);
    bookInfo.appendChild(authorElement);
    bookInfo.appendChild(priceElement);
  
    // Añade el div del libro al contenedor de la lista de libros
    bookCard.appendChild(imageElement);
    bookCard.appendChild(bookInfo);
    bookCard.appendChild(buttonContainer);
    bookCard.appendChild(viewButton);
    // Devuelve el div del libro creado
    return bookCard;
  }
  document.addEventListener("DOMContentLoaded", function() {
    const sortByPriceSelect = document.getElementById('sortByPrice');
    const searchByNameInput = document.getElementById('searchByName');
    const searchByISBNInput = document.getElementById('searchByISBN');
    const sortByGenreSelect = document.getElementById('sortByGenre');
    const sortByAuthorSelect = document.getElementById('sortByAuthor');
    const sortByCategorySelect = document.getElementById('sortByCategory');
    const bookListContainer = document.querySelector('.book-list');
  
  
    // Hacer una solicitud AJAX para obtener los generos disponibles
    fetch('http://localhost/Proyecto/genre')
      .then(response => response.json())
      .then(genres => {
        // Limpiar el select actual
        sortByGenreSelect.innerHTML = '';
    
        // Agregar una opción predeterminada
        const defaultOption = document.createElement('option');
        defaultOption.value = '';
        defaultOption.textContent = 'Todos los géneros';
        sortByGenreSelect.appendChild(defaultOption);
    
        // Llenar el select con los generos obtenidos
        genres.forEach(genre => {
          const option = document.createElement('option');
          option.value = genre.id_gen;
          option.textContent = genre.nom_gen;
          sortByGenreSelect.appendChild(option);
        });
      })
      .catch(error => console.error('Error fetching genres:', error));
  
      fetch('http://localhost/Proyecto/author')
      .then(response => response.json())
      .then(authors => {
        // Limpiar el select actual
        sortByAuthorSelect.innerHTML = '';
    
        // Agregamos una opcion predeterminada
        const defaultOption = document.createElement('option');
        defaultOption.value = '';
        defaultOption.textContent = 'Todos los autores';
        sortByAuthorSelect.appendChild(defaultOption);
    
        // Llenar el select con los generos obtenidos
        authors.forEach(author => {
          const option = document.createElement('option');
          option.value = author.id_aut;
          option.textContent = author.nom_aut;
          sortByAuthorSelect.appendChild(option);
        });
      })
      .catch(error => console.error('Error fetching authors:', error));
  
      fetch('http://localhost/Proyecto/category')
      .then(response => response.json())
      .then(categories => {
        // Limpiar el select actual
        sortByCategorySelect.innerHTML = '';
    
        // Agregar una opcion predeterminada
        const defaultOption = document.createElement('option');
        defaultOption.value = '';
        defaultOption.textContent = 'Todas las categorías';
        sortByCategorySelect.appendChild(defaultOption);
    
        // Llenar el select con los generos obtenidos
        categories.forEach(category => {
          const option = document.createElement('option');
          option.value = category.id_cat;
          option.textContent = category.nom_cat;
          sortByCategorySelect.appendChild(option);
        });
      })
      .catch(error => console.error('Error fetching categories:', error));
  
  
  
  
    sortByPriceSelect.addEventListener('change', () => {
        const sortByPriceValue = sortByPriceSelect.value;
  
        // Si se selecciona la opción vacia, volvemos a listar todos los libros.
        if (sortByPriceValue === '') {
            fetchBooks();
        } else {
            // Hacer una nueva solicitud AJAX para obtener los libros ordenados por precio
            fetch(`http://localhost/Proyecto/book?getBookByPrice=${sortByPriceValue}`)
                .then(response => response.json())
                .then(sortedBooks => {
                    // Limpiar la lista de libros actual
                    bookListContainer.innerHTML = '';
  
                    // Iterar sobre cada libro en los libros ordenados
                    sortedBooks.forEach(book => {
                        // Crear el contenido de un libro y añadirlo al contenedor de la lista de libros
                        bookListContainer.appendChild(createBookCard(book));
                    });
                })
                .catch(error => console.error('Error fetching sorted books:', error));
        }
    });
  
    // Manejar el evento de entrada en el campo de busqueda por nombre
    searchByNameInput.addEventListener('input', () => {
        const searchValue = searchByNameInput.value.trim();
  
        // Si el campo de busqueda esta vacio, volvemos a listar todos los libros
        if (searchValue === '') {
            fetchBooks();
        } else {
            // Hacer una solicitud AJAX para buscar libros por nombre
            fetch(`http://localhost/Proyecto/book?getBookByName=${searchValue}`)
                .then(response => response.json())
                .then(searchedBooks => {
                    // Limpiar la lista de libros actual
                    bookListContainer.innerHTML = '';
  
                    // Iterar sobre cada libro en los libros buscados
                    searchedBooks.forEach(book => {
                        // Crear el contenido de un libro y añadirlo al contenedor de la lista de libros
                        bookListContainer.appendChild(createBookCard(book));
                    });
                })
                .catch(error => console.error('Error fetching searched books:', error));
        }
    });
  
    searchByISBNInput.addEventListener('input', () => {
      const searchValue = searchByISBNInput.value.trim();
  
      // Si el campo de busqueda esta vacio, volvemos a listar todos los libros
      if (searchValue === '') {
          fetchBooks();
      } else {
          // Hacer una solicitud AJAX para buscar libros por nombre
          fetch(`http://localhost/Proyecto/book?getBookByISBN=${searchValue}`)
              .then(response => response.json())
              .then(searchedBooks => {
                  // Limpiar la lista de libros actual
                  bookListContainer.innerHTML = '';
  
                  // Iterar sobre cada libro en los libros buscados
                  searchedBooks.forEach(book => {
                      // Crear el contenido de un libro y añadirlo al contenedor de la lista de libros
                      bookListContainer.appendChild(createBookCard(book));
                  });
              })
              .catch(error => console.error('Error fetching searched books:', error));
      }
  });
  
  
  
  
     // Manejar el evento de cambio en el filtro de ordenamiento por precio
     sortByGenreSelect.addEventListener('change', () => {
      const sortByGenreValue = sortByGenreSelect.value;
  
      // Si se selecciona la opcion vacia, volvemos a listar todos los libros
      if (sortByGenreValue === '') {
          fetchBooks();
      } else {
          // Hacer una nueva solicitud AJAX para obtener los libros ordenados por precio
          fetch(`http://localhost/Proyecto/book?getBookByGenre=${sortByGenreValue}`)
              .then(response => response.json())
              .then(sortedBooks => {
                  // Limpiar la lista de libros actual
                  bookListContainer.innerHTML = '';
  
                  // Iterar sobre cada libro en los libros ordenados
                  sortedBooks.forEach(book => {
                      // Crear el contenido de un libro y añadirlo al contenedor de la lista de libros
                      bookListContainer.appendChild(createBookCard(book));
                  });
              })
              .catch(error => console.error('Error fetching sorted books:', error));
      }
  });
  
  sortByAuthorSelect.addEventListener('change', () => {
    const sortByAuthorValue = sortByAuthorSelect.value;
  
    // Si se selecciona la opcion vacia, volvemos a listar todos los libros
    if (sortByAuthorValue === '') {
        fetchBooks();
    } else {
        // Hacer una nueva solicitud AJAX para obtener los libros ordenados por precio
        fetch(`http://localhost/Proyecto/book?getBookByAuthor=${sortByAuthorValue}`)
            .then(response => response.json())
            .then(sortedBooks => {
                // Limpiar la lista de libros actual
                bookListContainer.innerHTML = '';
  
                // Iterar sobre cada libro en los libros ordenados
                sortedBooks.forEach(book => {
                    // Crear el contenido de un libro y añadirlo al contenedor de la lista de libros
                    bookListContainer.appendChild(createBookCard(book));
                });
            })
            .catch(error => console.error('Error fetching sorted books:', error));
    }
  });
  
  sortByCategorySelect.addEventListener('change', () => {
    const sortByCategoryValue = sortByCategorySelect.value;
  
    // Si se selecciona la opcion vacia, volvemos a listar todos los libros
    if (sortByCategoryValue === '') {
        fetchBooks();
    } else {
        // Hacer una nueva solicitud AJAX para obtener los libros ordenados por precio
        fetch(`http://localhost/Proyecto/book?getBookByCategory=${sortByCategoryValue}`)
            .then(response => response.json())
            .then(sortedBooks => {
                // Limpiar la lista de libros actual
                bookListContainer.innerHTML = '';
  
                // Iterar sobre cada libro en los libros ordenados
                sortedBooks.forEach(book => {
                    // Crear el contenido de un libro y añadirlo al contenedor de la lista de libros
                    bookListContainer.appendChild(createBookCard(book));
                });
            })
            .catch(error => console.error('Error fetching sorted books:', error));
    }
  });
  
  
    // Función para cargar todos los libros
    function fetchBooks() {
        fetch('http://localhost/Proyecto/book?author')
            .then(response => response.json())
            .then(data => {
                // Limpiar la lista de libros actual
                bookListContainer.innerHTML = '';
  
                // Iterar sobre cada libro en los datos recibidos
                data.forEach(book => {
                    // Crea el contenido de un libro y añádelo al contenedor de la lista de libros
                    bookListContainer.appendChild(createBookCard(book));
                });
            })
            .catch(error => console.error('Error fetching books:', error));
    }
  
    // Cargar todos los libros al cargar la página
    fetchBooks();
  });
  function deleteBook(bookId) {
    // Mostrar un mensaje de confirmación

    const requestData = {
      id_lib: bookId
  };
    // Verificar si el usuario confirmo la eliminación
  
        // Realizar una solicitud DELETE al servidor para eliminar el libro
        fetch(`http://localhost/Proyecto/book?id=${bookId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(requestData) // Convertir el objeto a JSON
        })
        .then(response => {
          console.log('Response:', response);
            if (response.ok) {
                const bookCard = document.getElementById(`book-${bookId}`);
                if (bookCard) {
                    // Mostrar la alerta de que se ha eliminado el libro

                    bookCard.remove();
    
                }
            } else {
              
                console.error('Error deleting book:', response.statusText);
            }
        })
        .catch(error => console.error('Error deleting book:', error));

}
