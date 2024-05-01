<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="../css/bookfy.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="../js/register.js"></script>
</head>

<body>
    <noscript>
    <META HTTP-EQUIV="Refresh" CONTENT="0;URL=noscript.php">

    </noscript>
    <div class="form-container">
        
        <form id="registerForm" action="" method ="post">
        <img src="../img/Bookfy.png" alt="Bookfy">
            <h3>Regístrate</h3>
            <div id="successMessage" style="display: none;">
            </div>
            <input type="text" id="name" name="name" placeholder="Introduzca su nombre" required class="box">
            <input type="email" id= "email" name="email"placeholder="Introduzca su correo" required class="box">
            <input type="password" id="password" name="password" placeholder="Introduzca su contraseña" required class="box">
            <input type="password" id= "cpassword" name="cpassword" placeholder="Confirme su contraseña" required class="box">
            <input type="submit" name="submit" value="Registrar" class="btn">
            <p>¿Ya tienes una cuenta?<a href="login.php"><br>Inicia sesión</a></p>
        <form>
    </div>

</body>
</html>