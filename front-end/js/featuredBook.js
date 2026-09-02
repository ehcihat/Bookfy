fetch('/book?featured')
  .then(response => response.json())
  .then(data => {
    const featuredBooksContainer = document.querySelector('.featured-books-container');

    // Itera sobre cada libro en los datos recibidos
    data.forEach(book => {
      // Crea un div para la carta del libro
      const cardElement = document.createElement('div');
      cardElement.classList.add('card');

      // Crea un elemento de div para representar cada libro dentro de la carta
      const bookElement = document.createElement('div');
      bookElement.classList.add('book');

      // Crea elementos HTML para mostrar la informacion del libro (titulo, imagen, etc.)
      const titleElement = document.createElement('h3');
      titleElement.textContent = book.tit_lib;

      const imageElement = document.createElement('img');
      // Concatena la ruta de la carpeta de imágenes con el nombre de la imagen
      imageElement.src = '../img/' + book.img_lib;
      imageElement.alt = book.tit_lib;

      // Añade los elementos al div del libro
      bookElement.appendChild(titleElement);
      bookElement.appendChild(imageElement);

      // Añade el div del libro a la carta del libro
      cardElement.appendChild(bookElement);

      // Añade la carta del libro al contenedor principal de libros destacados
      featuredBooksContainer.appendChild(cardElement);
    });
  })
  .catch(error => console.error('Error fetching featured books:', error));