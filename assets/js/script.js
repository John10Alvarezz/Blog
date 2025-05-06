/*Funciones para validación de formularios y confirmaciones
 */

document.addEventListener('DOMContentLoaded', function () {
    // Validación del formulario de registro
    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', function (e) {
            const username = document.getElementById('username').value.trim();
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;

            let isValid = true;
            let errorMessage = '';

            // Validar nombre de usuario
            if (username.length < 3) {
                isValid = false;
                errorMessage += '• El nombre de usuario debe tener al menos 3 caracteres.\n';
            }

            // Validar email
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                isValid = false;
                errorMessage += '• Por favor, introduce un email válido.\n';
            }

            // Validar contraseña
            if (password.length < 6) {
                isValid = false;
                errorMessage += '• La contraseña debe tener al menos 6 caracteres.\n';
            }

            // Confirmar contraseña
            if (password !== confirmPassword) {
                isValid = false;
                errorMessage += '• Las contraseñas no coinciden.\n';
            }

            if (!isValid) {
                e.preventDefault();
                alert('Por favor, corrige los siguientes errores:\n\n' + errorMessage);
            }
        });
    }

    // Validación del formulario de login
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', function (e) {
            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value;

            if (username === '' || password === '') {
                e.preventDefault();
                alert('Por favor, completa todos los campos.');
            }
        });
    }

    // Validación del formulario de creación/edición de posts
    const postForm = document.getElementById('postForm');
    if (postForm) {
        postForm.addEventListener('submit', function (e) {
            const title = document.getElementById('title').value.trim();
            const content = document.getElementById('content').value.trim();

            if (title === '') {
                e.preventDefault();
                alert('Por favor, ingresa un título para el post.');
                return;
            }

            if (content === '') {
                e.preventDefault();
                alert('Por favor, ingresa el contenido del post.');
                return;
            }
        });
    }

    // Confirmación para eliminar posts
    const deleteButtons = document.querySelectorAll('.delete-post');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            if (!confirm('¿Estás seguro de que deseas eliminar este post? Esta acción no se puede deshacer.')) {
                e.preventDefault();
            }
        });
    });

    // Mostrar/Ocultar contraseña en formularios
    const togglePasswordButtons = document.querySelectorAll('.toggle-password');
    togglePasswordButtons.forEach(button => {
        button.addEventListener('click', function () {
            const passwordInput = document.getElementById(this.getAttribute('data-target'));

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                this.textContent = 'Ocultar';
            } else {
                passwordInput.type = 'password';
                this.textContent = 'Mostrar';
            }
        });
    });
});