$(document).ready(function () {
    $('#loginForm').submit(function (event) {

        event.preventDefault();
        var email = $('#email').val()


        $('.message-error').remove();

        switch (true) {
            case /[!#$%^&*(),;/?":{}|<>]/.test(email):
                $('#email').after(getErrMsgLogin('specialCharactersEmail'));
                return;

        }


        // Si todas las validaciones pasan, enviar la solicitud AJAX
        var formData = {
            'email_usu': $('input[name=email]').val(),
            'pass_usu': $('input[name=password]').val()
        };

        $.ajax({
            type: 'POST',
            url: 'http://localhost/Proyecto/auth',
            data: JSON.stringify(formData),
            contentType: 'application/json',
            dataType: 'json',
            success: function (data) {

                if (data.result && data.result.token && data.result.expiration_date) {
                    $('#successMessage').after('<div class="message-success" id="formMessage">Credenciales correctas.</div>')
                    var tokenData = {
                        token: data.result.token,
                        expiration_date: data.result.expiration_date
                    };
                    sessionStorage.setItem('tokenData', JSON.stringify(tokenData)); // Almacenar como una cadena JSON
                    setTimeout(function () {
                        window.location.href = 'bookfy.php';
                    }, 3000);
                }
            },
            error: function (xhr, status, error) {

                console.error(xhr.status);


                if (xhr.status === 408) {
                    $('#email').after(getErrMsgLogin('invalidEmail'));
                }
                if (xhr.status === 409) {
                    $('#password').after(getErrMsgLogin("invalidPass"));
                }
            }
        });

    });
    $(window).on('pageshow', function (event) {
        // Verificar si el evento de pageshow fue provocado por una navegacion hacia atras
        if (event.originalEvent.persisted) {
            // Ocultar el mensaje de registro exitoso si esta visible
            $('#formMessage').remove();
        }
    });



    function getErrMsgLogin(errorCode) {
        switch (errorCode) {

            case 'specialCharactersEmail':
                return '<div class="message-error" id="emailMessage">El email no puede contener caracteres especiales.</div>';

            case 'invalidEmail':
                return '<div class="message-error" id="invalidEmail">El email introducido no está registrado.</div>';

            case 'invalidPass':
                return '<div class="message-error" id="invalidPass">La contraseña no es correcta.</div>';

            default:
                return '<div class="message-error" id="passMessage">Error en el formulario.</div>';
        }
    }
});
