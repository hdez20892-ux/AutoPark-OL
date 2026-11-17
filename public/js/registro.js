const registroForm =
    document.getElementById("registroForm");

const nombreInput =
    document.getElementById("nombre");

const emailInput =
    document.getElementById("email");

const password =
    document.getElementById("password");

const confirmPassword =
    document.getElementById("confirmPassword");

const mensaje =
    document.getElementById("registroMensaje");

const accountOptions =
    document.querySelectorAll(".account-option");


/* =========================
   OBTENER TOKEN CSRF
========================= */
function obtenerCsrfToken() {
    const meta = document.querySelector('meta[name="csrf-token"]');
    return meta ? meta.content : '';
}


/* =========================
   TIPO DE CUENTA
========================= */

accountOptions.forEach(option => {

    option.addEventListener("click", () => {

        accountOptions.forEach(item => {

            item.classList.remove("selected");

        });


        option.classList.add("selected");

    });

});


/* =========================
   REGISTRO
========================= */

registroForm.addEventListener("submit", async event => {

    event.preventDefault();


    mensaje.textContent = "";


    /* VALIDAR CONTRASEÑAS */

    if (password.value !== confirmPassword.value) {

        mensaje.textContent =
            "Las contraseñas no coinciden.";

        mensaje.style.color = "#B42318";

        return;

    }


    /* VALIDAR LONGITUD */

    if (password.value.length < 8) {

        mensaje.textContent =
            "La contraseña debe tener mínimo 8 caracteres.";

        mensaje.style.color = "#B42318";

        return;

    }


    /* =========================
       PETICIÓN AL BACKEND (LARAVEL)
    ========================= */

    mensaje.textContent =
        "Registrando cuenta...";

    mensaje.style.color = "#475467";

    const nombreUsuario =
        nombreInput && nombreInput.value.trim()
            ? nombreInput.value.trim()
            : "Fernando Ruiz";

    const correoUsuario =
        emailInput && emailInput.value.trim()
            ? emailInput.value.trim()
            : "";

    // Obtener el tipo de cuenta seleccionado si aplica (opcional para el backend)
    const tipoCuentaSeleccionada = document.querySelector(".account-option.selected");
    const tipoCuenta = tipoCuentaSeleccionada ? tipoCuentaSeleccionada.dataset.type || "conductor" : "conductor";

    try {
        const response = await fetch("/register", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": obtenerCsrfToken(),
                "Accept": "application/json"
            },
            body: JSON.stringify({
                name: nombreUsuario,
                email: correoUsuario,
                password: password.value,
                password_confirmation: confirmPassword.value,
                tipo_cuenta: tipoCuenta
            })
        });

        const data = await response.json();

        if (response.ok && data.success) {

            mensaje.textContent =
                data.message || "Registro exitoso. Redirigiendo...";

            mensaje.style.color = "#027A48"; // Verde de éxito


            /* =========================
               REDIRECCIÓN
            ========================= */

            setTimeout(() => {
                window.location.href = data.redirect || "/inicio";
            }, 800);

        } else {

            mensaje.textContent =
                data.message || "Error al procesar el registro en el servidor.";

            mensaje.style.color = "#B42318";

        }

    } catch (error) {
        console.error("Error de conexión:", error);

        mensaje.textContent =
            "Ocurrió un error al conectar con el servidor.";

        mensaje.style.color = "#B42318";
    }

});