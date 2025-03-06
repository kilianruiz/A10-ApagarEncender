// Captura de eventos
document.getElementById('nombre_usuario').onblur = validaUserName;
document.getElementById('pwd').onblur = validaPwd;
document.getElementById('nombre_usuario').oninput = actualizarEstadoBoton;
document.getElementById('pwd').oninput = actualizarEstadoBoton;

// Función para verificar si hay errores y actualizar el estado del botón
function actualizarEstadoBoton() {
    const boton = document.querySelector('.btn-login');
    const userName = document.getElementById('nombre_usuario').value;
    const pwd = document.getElementById('pwd').value;
    const hayErrores = document.querySelector('.error-input') !== null;
    const camposVacios = userName === '' || pwd === '';
    
    boton.disabled = hayErrores || camposVacios;
    if (hayErrores || camposVacios) {
        boton.style.opacity = '0.6';
        boton.style.cursor = 'not-allowed';
    } else {
        boton.style.opacity = '1';
        boton.style.cursor = 'pointer';
    }
}

// Funcion para la validacion del campo del username
function validaUserName(){
    let userName = document.getElementById('nombre_usuario').value;
    let inputUserName = document.getElementById('nombre_usuario');
    let errorMessage = document.getElementById('error_username');

    if(userName === "" || userName.length === 0){
        errorMessage.textContent = "El campo no debe estar vacío.";
        inputUserName.classList.add('error-input');
        actualizarEstadoBoton();
        return false;
    } else if(!isNaN(userName)){
        errorMessage.textContent = "El campo no puede contener números.";
        inputUserName.classList.add('error-input');
        actualizarEstadoBoton();
        return false;
    } else {
        errorMessage.textContent = "";
        inputUserName.classList.remove('error-input');
        actualizarEstadoBoton();
        return true;
    }
}

// Funcion para la validacion del campo del password
function validaPwd(){
    let pwd = document.getElementById('pwd').value;
    let inputPwd = document.getElementById('pwd');
    let errorPwd = document.getElementById('error_password');

    if(pwd === "" || pwd.length === 0){
        errorPwd.textContent = "El campo no debe estar vacío.";
        inputPwd.classList.add('error-input');
        actualizarEstadoBoton();
        return false;
    } else if(pwd.length < 8){
        errorPwd.textContent = "La contraseña debe tener al menos 8 caracteres.";
        inputPwd.classList.add('error-input');
        actualizarEstadoBoton();
        return false;
    } else {
        errorPwd.textContent = "";
        inputPwd.classList.remove('error-input');
        actualizarEstadoBoton();
        return true;
    }
}

// Validación inicial al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    actualizarEstadoBoton();
});