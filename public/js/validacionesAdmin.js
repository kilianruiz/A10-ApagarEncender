document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById('editUserForm');
    const name = document.getElementById('editName');
    const email = document.getElementById('editEmail');
    const password = document.getElementById('editPassword');
    const passwordConfirmation = document.getElementById('editPasswordConfirmation');
    const role = document.getElementById('editRoleId');
    const sede = document.getElementById('editSedeId');
    const submitButton = form.querySelector('button[type="submit"]');

    // Errores
    let nameError = document.createElement('div');
    let emailError = document.createElement('div');
    let passwordError = document.createElement('div');
    let passwordConfirmError = document.createElement('div');

    [nameError, emailError, passwordError, passwordConfirmError].forEach(error => {
        error.style.color = 'red';
        error.style.fontSize = '12px';
    });

    name.after(nameError);
    email.after(emailError);
    password.after(passwordError);
    passwordConfirmation.after(passwordConfirmError);

    // Estado inicial del formulario
    let originalValues = {
        name: name.value,
        email: email.value,
        password: "",
        passwordConfirmation: "",
        role: role.value,
        sede: sede.value
    };

    function checkChanges() {
        let isChanged = (
            name.value !== originalValues.name ||
            email.value !== originalValues.email ||
            password.value !== "" ||
            passwordConfirmation.value !== "" ||
            role.value !== originalValues.role ||
            sede.value !== originalValues.sede
        );
        submitButton.disabled = !isChanged;
    }

    function validateName() {
        const value = name.value.trim();
        const regex = /^[A-Za-zÁáÉéÍíÓóÚúÑñ\s]+$/;

        if (value === "") {
            nameError.textContent = "El nombre está vacío";
            name.style.borderColor = "red";
            return false;
        } else if (value.length < 3) {
            nameError.textContent = "Debe tener al menos 3 caracteres";
            name.style.borderColor = "red";
            return false;
        } else if (!regex.test(value)) {
            nameError.textContent = "No puede contener números ni caracteres especiales";
            name.style.borderColor = "red";
            return false;
        } else {
            nameError.textContent = "";
            name.style.borderColor = "";
            return true;
        }
    }

    function validateEmail() {
        const value = email.value.trim();
        const regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

        if (value === "") {
            emailError.textContent = "El email está vacío";
            email.style.borderColor = "red";
            return false;
        } else if (!regex.test(value)) {
            emailError.textContent = "El email no tiene un formato válido";
            email.style.borderColor = "red";
            return false;
        } else {
            emailError.textContent = "";
            email.style.borderColor = "";
            return true;
        }
    }

    function validatePassword() {
        const value = password.value.trim();
        if (value !== "" && value.length < 6) {
            passwordError.textContent = "Debe tener al menos 6 caracteres";
            password.style.borderColor = "red";
            return false;
        } else {
            passwordError.textContent = "";
            password.style.borderColor = "";
            return true;
        }
    }

    function validatePasswordConfirmation() {
        if (passwordConfirmation.value.trim() !== password.value.trim()) {
            passwordConfirmError.textContent = "Las contraseñas no coinciden";
            passwordConfirmation.style.borderColor = "red";
            return false;
        } else {
            passwordConfirmError.textContent = "";
            passwordConfirmation.style.borderColor = "";
            return true;
        }
    }

    function validateForm(event) {
        let isValid = true;
        if (!validateName()) isValid = false;
        if (!validateEmail()) isValid = false;
        if (!validatePassword()) isValid = false;
        if (!validatePasswordConfirmation()) isValid = false;

        if (!isValid) {
            event.preventDefault();
        }
    }

    // Eventos
    name.addEventListener('input', () => { validateName(); checkChanges(); });
    email.addEventListener('input', () => { validateEmail(); checkChanges(); });
    password.addEventListener('input', () => { validatePassword(); checkChanges(); });
    passwordConfirmation.addEventListener('input', () => { validatePasswordConfirmation(); checkChanges(); });
    role.addEventListener('change', checkChanges);
    sede.addEventListener('change', checkChanges);
    form.addEventListener('submit', validateForm);

    // Deshabilitar el botón de guardar al inicio
    submitButton.disabled = true;
});
