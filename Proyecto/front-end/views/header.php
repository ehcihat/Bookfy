<header class="header">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script src="../js/token.js"></script>

<script>
   $(document).ready(function() {
    $('#user-btn').click(function() {
        $('.logout-dropdown').toggleClass('active');
    });

    $(document).click(function(event) {
        var $target = $(event.target);
        if (!$target.closest('.logout-dropdown').length &&
            !$target.is('#user-btn')) {
            $('.logout-dropdown').removeClass('active');
        }
    });
});
</script>

   <div class="flex">
   <div id="messageContainer" style="display: none;"></div>

      <a href="bookfy.php" class="logo"><span>   <img src="../img/Bookfy.png" alt="Bookfy" class="bookfy-logo"></span></a>
      
      <nav class="navbar">
         <a href="bookfy.php">Inicio</a>
         <a href="catalogue.php">Libros</a>
       

      </nav>

      <div class="icons">
    <div id="menu-btn" class="fas fa-bars"></div>
    <div id="user-btn" class="fas fa-user"></div>
    <div class="logout-dropdown">
        <a href="#" id="logoutButton" class="logoutButton">Cerrar Sesión</a>
    </div>
</div>
   </div>

</header>
