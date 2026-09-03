# 📚 Bookfy

**Catálogo de libros con sistema de usuarios y reseñas**, desplegado en la nube con Docker.

🔗 **Demo en vivo:** [bookfy-890w.onrender.com](https://bookfy-890w.onrender.com)
*(nota: el hosting gratuito de Render "duerme" tras 15 min sin uso — la primera carga puede tardar 30-60 segundos en despertar)*

---

## 📖 Sobre el proyecto

Bookfy nació como proyecto de portfolio para practicar desarrollo full-stack con PHP, JavaScript y MySQL. Permite explorar un catálogo de libros, registrarse, iniciar sesión, filtrar por género/autor/precio, y dejar reseñas.

El proyecto está desplegado con una arquitectura completa: contenedores Docker, base de datos gestionada en la nube (Aiven) y despliegue continuo (Render), replicando un flujo de trabajo similar al de un entorno profesional.

## 🛠️ Stack tecnológico

- **Backend:** PHP 8.2
- **Frontend:** JavaScript, jQuery, HTML/CSS
- **Base de datos:** MySQL 8.0
- **Infraestructura:** Docker, Docker Compose
- **Despliegue:** Render (web) + Aiven (MySQL gestionado)
- **Control de versiones:** Git, con flujo de ramas `dev`/`main`

## ✨ Funcionalidades

- Registro e inicio de sesión de usuarios
- Catálogo de libros con imágenes, autores, categorías y géneros
- Filtros: por precio, nombre, género, autor, categoría, ISBN
- Sistema de reseñas y puntuaciones
- Panel para añadir nuevos libros al catálogo
- Libros destacados según valoración

## 🚀 Cómo ejecutarlo en local

### Requisitos
- Docker Desktop instalado

### Pasos

1. Clona el repositorio:
   ```bash
   git clone https://github.com/ehcihat/Bookfy.git
   cd Bookfy
   ```

2. Copia los archivos de configuración de ejemplo:
   ```bash
   cp classes/config.example classes/config
   cp .env.example .env
   ```

3. Levanta los contenedores:
   ```bash
   docker compose up --build
   ```

4. Abre [http://localhost:8080](http://localhost:8080) en tu navegador.

La base de datos se inicializa automáticamente con datos de ejemplo la primera vez que arrancas los contenedores.

## 🗺️ Roadmap

Este proyecto está en evolución activa. Próximos pasos planeados:

- [ ] Migración de backend a **Laravel** (Eloquent ORM, migraciones versionadas, autenticación robusta)
- [ ] Migración de frontend a **Vue.js**
- [ ] Integración con **Google Books API** para importar libros en vez de añadirlos manualmente
- [ ] Pivote de "catálogo estático" a **tracker de lectura personal**: estados de lectura (quiero leer / leyendo / terminado), historial y estadísticas
- [ ] Dashboard de analítica de usuario (Chart.js) con hábitos de lectura
- [ ] Dashboard de analítica de negocio (Power BI) conectado a la base de datos: usuarios activos, géneros más populares, retención
- [ ] Recomendador simple de libros por género/autor

## 👤 Autor

**Tahiche Acorán Hernández Almeida**
Desarrollador Full Stack Junior
[GitHub](https://github.com/ehcihat) · [LinkedIn](https://linkedin.com/in/tahiche-acorán/)
