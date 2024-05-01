
$(document).ready(function () {
    $('#registerForm').submit(function (event) {

        event.preventDefault();
        var name = $('#name').val()
        var password = $('#password').val();
        var cpassword = $('#cpassword').val();
        var email = $('#email').val();


        $('.message-error').remove();

        switch (true) {
            case /[!@#$%^&*(),;=¿'¡./?":{}|<>]/.test(name):
                $('#name').after(getErrMsgRegister
                    ('specialCharacters'));
                return;
            case name.length > 50:
                $('#name').after(getErrMsgRegister
                    ('usernameMaxLength'));
                return;
            case name.length < 4:
                $('#name').after(getErrMsgRegister
                    ('usernameLength'));
                return;
            case password !== cpassword:
                $('#password').after(getErrMsgRegister
                    ('passwordMismatch'));
                return;
            case password.length < 4:
                $('#password').after(getErrMsgRegister
                    ('passwordLength'));
                return;
            case /[!#$%^&*(),;/?":{}|<>]/.test(email):
                $('#email').after(getErrMsgRegister
                    ('specialCharactersEmail'));
                return;

        }


        // Si todas las validaciones pasan, enviar la solicitud AJAX
        var formData = {
            'nom_usu': $('input[name=name]').val(),
            'email_usu': $('input[name=email]').val(),
            'pass_usu': $('input[name=password]').val()
        };

        $.ajax({
            type: 'POST',
            url: 'http://localhost/Proyecto/user',
            data: JSON.stringify(formData),
            contentType: 'application/json',
            dataType: 'json',
            success: function () {

                $('#successMessage').after('<div class="message-success" id="formMessage">Registro completado. Serás redirigido al inicio de sesión en breve.</div>')

                setTimeout(function () {
                    window.location.href = 'login.php';
                }, 3000);
            },
            error: function (xhr, status, error) {

                console.error(xhr.status);

                // Verificar si el error es debido a un email duplicado
                if (xhr.status === 406) {
                    $('#email').after(getErrMsgRegister
                        ('emailExists'));
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


    function getErrMsgRegister
        (errorCode) {
        switch (errorCode) {

            case 'specialCharacters':
                return '<div class="message-error" id="nameMessage">El nombre no puede contener caracteres especiales.</div>';

            case 'specialCharactersEmail':
                return '<div class="message-error" id="emailMessage">El email no puede contener caracteres especiales.</div>';

            case 'passwordMismatch':
                return '<div class="message-error" id="passMessage">Las contraseñas no coinciden.</div>';

            case 'usernameLength':
                return '<div class="message-error" id="nameMessage">El nombre de usuario debe tener al menos 4 caracteres.</div>';

            case 'passwordLength':
                return '<div class="message-error" id="passMessage">La contraseña debe tener al menos 4 caracteres.</div>';

            case 'usernameMaxLength':
                return '<div class="message-error" id="nameMessage">El nombre de usuario no puede superar los 50 caracteres.</div>';

            case 'emailExists':
                return '<div class="message-error" id="emailMessage">El correo electrónico ya está registrado.</div>';

            default:
                return '<div class="message-error" id="passMessage">Error en el formulario.</div>';
        }
    }
});


