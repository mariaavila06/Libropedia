<!-- Modal Iniciar sesión -->
<div class="modal-backdrop" id="modal-login">
    <div class="modal">
        <div class="modal-header">
            <span class="modal-title">Iniciar sesión</span>
            <button class="modal-close" data-close-modal="modal-login">&times;</button>
        </div>
        <div class="modal-body">
            <form method="post" action="index.php?action=login">
                <div class="form-group">
                    <label for="login-usuario">Usuario</label>
                    <input type="text" id="login-usuario" name="usuario" required maxlength="30">
                </div>
                <div class="form-group">
                    <label for="login-contrasena">Contraseña</label>
                    <input
                        type="password"
                        id="login-contrasena"
                        name="contrasena"
                        required
                        maxlength="15"
                    >
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn-primary">Entrar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Registrarse -->
<div class="modal-backdrop" id="modal-register">
    <div class="modal">
        <div class="modal-header">
            <span class="modal-title">Registrarse</span>
            <button class="modal-close" data-close-modal="modal-register">&times;</button>
        </div>
        <div class="modal-body">
            <form method="post" action="index.php?action=register">
                <div class="form-group">
                    <label for="reg-nombre">Nombre</label>
                    <input type="text" id="reg-nombre" name="nombre" minlength="2" maxlength="30" required>
                    <span class="field-error" id="error-nombre" style="color: #dc3545; font-size: 0.8rem; display: none;"></span>
                </div>
                <div class="form-group">
                    <label for="reg-apellido">Apellido</label>
                    <input type="text" id="reg-apellido" name="apellido" minlength="2" maxlength="30" required>
                    <span class="field-error" id="error-apellido" style="color: #dc3545; font-size: 0.8rem; display: none;"></span>
                </div>
                <div class="form-group">
                    <label for="reg-usuario">Usuario</label>
                    <input type="text" id="reg-usuario" name="usuario" minlength="3" maxlength="20" required>
                    <span class="field-error" id="error-usuario" style="color: #dc3545; font-size: 0.8rem; display: none;"></span>
                </div>
                <div class="form-group">
                    <label for="reg-correo">Correo</label>
                    <input
                        type="email"
                        id="reg-correo"
                        name="correo"
                        minlength="5"
                        maxlength="50"
                        required
                    >
                    <span class="field-error" id="error-correo" style="color: #dc3545; font-size: 0.8rem; display: none;"></span>
                </div>
                <div class="form-group">
                    <label for="reg-cedula">Cédula</label>
                    <input type="text" id="reg-cedula" name="cedula" minlength="7" maxlength="9" required>
                    <span class="field-error" id="error-cedula" style="color: #dc3545; font-size: 0.8rem; display: none;"></span>
                </div>
                <div class="form-group">
                    <label for="reg-contrasena">Contraseña</label>
                    <input
                        type="password"
                        id="reg-contrasena"
                        name="contrasena"
                        minlength="6"
                        maxlength="16"
                        required
                    >
                    <span class="field-error" id="error-contrasena" style="color: #dc3545; font-size: 0.8rem; display: none;"></span>
                </div>
                <div class="form-group">
                    <label for="reg-contrasena-confirmacion">Confirmar contraseña</label>
                    <input type="password" id="reg-contrasena-confirmacion" name="contrasena_confirmacion" minlength="6" maxlength="16" required>
                    <span class="field-error" id="error-contrasena-confirmacion" style="color: #dc3545; font-size: 0.8rem; display: none;"></span>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn-primary">Crear cuenta</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    (function() {
        const registerForm = document.querySelector('#modal-register form');
        if (!registerForm) return;

        function showFieldError(fieldId, msg) {
            const errorSpan = document.getElementById('error-' + fieldId);
            if (errorSpan) {
                errorSpan.textContent = msg;
                errorSpan.style.display = 'block';
            }
        }

        function hideFieldError(fieldId) {
            const errorSpan = document.getElementById('error-' + fieldId);
            if (errorSpan) {
                errorSpan.style.display = 'none';
            }
        }

        function hideAllErrors() {
            const errors = registerForm.querySelectorAll('.field-error');
            errors.forEach(e => e.style.display = 'none');
        }

        function validateNombre(value) {
            if (value.length < 2) return 'El nombre debe tener al menos 2 caracteres.';
            if (value.length > 30) return 'El nombre no puede tener más de 30 caracteres.';
            if (!/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/.test(value)) return 'El nombre solo puede contener letras.';
            return null;
        }

        function validateApellido(value) {
            if (value.length < 2) return 'El apellido debe tener al menos 2 caracteres.';
            if (value.length > 30) return 'El apellido no puede tener más de 30 caracteres.';
            if (!/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/.test(value)) return 'El apellido solo puede contener letras.';
            return null;
        }

        function validateCorreo(value) {
            if (value.length < 5) return 'El correo debe tener al menos 5 caracteres.';
            if (value.length > 50) return 'El correo no puede tener más de 50 caracteres.';
            const emailRegex = /^[^@\s]+@[^@\s]+\.[a-zA-Z]{2,}$/;
            if (!emailRegex.test(value)) return 'Ingresa un correo válido (ej: usuario@dominio.com).';
            return null;
        }

        function validateCedula(value) {
            if (!/^\d+$/.test(value)) return 'La cédula solo puede contener números.';
            if (value.length < 7) return 'La cédula debe tener al menos 7 dígitos.';
            if (value.length > 9) return 'La cédula no puede tener más de 9 dígitos.';
            return null;
        }

        function validateContrasena(value) {
            if (value.length < 6) return 'La contraseña debe tener al menos 6 caracteres.';
            if (value.length > 16) return 'La contraseña no puede tener más de 16 caracteres.';
            if (!/(?=.*[A-Za-z])(?=.*\d)/.test(value)) return 'La contraseña debe contener al menos una letra y un número.';
            return null;
        }

        function validateConfirmacion(value, contrasena) {
            if (value !== contrasena) return 'Las contraseñas no coinciden.';
            return null;
        }

        // Validación en tiempo real
        document.getElementById('reg-nombre').addEventListener('blur', function() {
            const error = validateNombre(this.value.trim());
            if (error) showFieldError('nombre', error);
            else hideFieldError('nombre');
        });

        document.getElementById('reg-apellido').addEventListener('blur', function() {
            const error = validateApellido(this.value.trim());
            if (error) showFieldError('apellido', error);
            else hideFieldError('apellido');
        });

        document.getElementById('reg-correo').addEventListener('blur', function() {
            const error = validateCorreo(this.value.trim());
            if (error) showFieldError('correo', error);
            else hideFieldError('correo');
        });

        document.getElementById('reg-cedula').addEventListener('blur', function() {
            const error = validateCedula(this.value.trim());
            if (error) showFieldError('cedula', error);
            else hideFieldError('cedula');
        });

        document.getElementById('reg-contrasena').addEventListener('blur', function() {
            const error = validateContrasena(this.value);
            if (error) showFieldError('contrasena', error);
            else hideFieldError('contrasena');
        });

        document.getElementById('reg-contrasena-confirmacion').addEventListener('blur', function() {
            const contrasena = document.getElementById('reg-contrasena').value;
            const error = validateConfirmacion(this.value, contrasena);
            if (error) showFieldError('contrasena-confirmacion', error);
            else hideFieldError('contrasena-confirmacion');
        });

        registerForm.addEventListener('submit', function (event) {
            hideAllErrors();

            let hasErrors = false;

            const nombre = document.getElementById('reg-nombre').value.trim();
            const apellido = document.getElementById('reg-apellido').value.trim();
            const correo = document.getElementById('reg-correo').value.trim();
            const cedula = document.getElementById('reg-cedula').value.trim();
            const contrasena = document.getElementById('reg-contrasena').value;
            const confirmacion = document.getElementById('reg-contrasena-confirmacion').value;

            let error = validateNombre(nombre);
            if (error) { showFieldError('nombre', error); hasErrors = true; }

            error = validateApellido(apellido);
            if (error) { showFieldError('apellido', error); hasErrors = true; }

            error = validateCorreo(correo);
            if (error) { showFieldError('correo', error); hasErrors = true; }

            error = validateCedula(cedula);
            if (error) { showFieldError('cedula', error); hasErrors = true; }

            error = validateContrasena(contrasena);
            if (error) { showFieldError('contrasena', error); hasErrors = true; }

            error = validateConfirmacion(confirmacion, contrasena);
            if (error) { showFieldError('contrasena-confirmacion', error); hasErrors = true; }

            if (hasErrors) {
                event.preventDefault();
            }
        });
    })();
</script>

