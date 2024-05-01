$(document).ready(function() {
    var tokenDataString = sessionStorage.getItem('tokenData');
    if (!tokenDataString) {
        // Si no hay datos de token en la sesión, redirige al usuario a la pagina de login
        window.location.href = 'login.php';
        return; 
    }
    try {
        var tokenData = JSON.parse(tokenDataString);
    } catch (error) {
        // Si hay un error al parsear el JSON, redirige al usuario a la  pagina de login
        console.error('Error al parsear el JSON:', error);
        window.location.href = 'login.php';
        return;
    }
    
    var token = tokenData.token;
    var expirationDate = tokenData.expiration_date;

    if (!token || !expirationDate || new Date(expirationDate) < new Date()) {
        // Si no hay token o ha caducado, redirige al usuario a la pagina de login
        window.location.href = 'login.php';
        return;
    }

    // Hacer la petición AJAX para verificar el token en el backend
    $.ajax({
        type: 'GET',
        url: 'http://localhost/Proyecto/auth?token=' + token,
        contentType: 'application/json',
        dataType: 'json',
        success: function (data) {
            // Si la respuesta es exitosa, verifica si el token es valido
            if (data.token && data.expiration_date) {
                var expirationDateDB = new Date(data.expiration_date);
                var currentDate = new Date();
    
                // Verificar si la fecha de expiracion del token es anterior a la fecha actual o si es diferente de la fecha almacenada en la base de datos
                if (expirationDateDB < currentDate || expirationDateDB.getTime() !== new Date(expirationDate).getTime()) {
                    alert("Sesión caducada o token manipulado.");
                    window.location.href = 'login.php';
                    return;
                }
            } else {
      
                window.location.href = 'login.php';
                return;
            }
        },
        error: function (xhr, status, error) {

            window.location.href = 'login.php';
            return;
        }
    });

    $('#logoutButton').click(function() {
        var confirmLogout = confirm('¿Estás seguro de que quieres cerrar sesión?');
        if (confirmLogout) {
            // Si el usuario confirma, borrar los datos de token de la sesion
            sessionStorage.removeItem('tokenData');
            // Mostrar un mensaje de alerta
            alert('Sesión cerrada con éxito.');
            // Redirige al usuario a la pagina de inicio de sesion
            window.location.href = 'login.php';
        }
    });
    

});