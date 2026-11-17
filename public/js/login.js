const loginForm =
    document.getElementById(
        "loginForm"
    );


const email =
    document.getElementById(
        "email"
    );


const password =
    document.getElementById(
        "password"
    );


const loginMessage =
    document.getElementById(
        "loginMessage"
    );


/* =========================
   OBTENER TOKEN CSRF
========================= */
function obtenerCsrfToken() {
    const meta = document.querySelector('meta[name="csrf-token"]');
    return meta ? meta.content : '';
}


/* =========================
   INICIAR SESIÓN
========================= */

loginForm.addEventListener(
    "submit",
    async event => {

        event.preventDefault();


        loginMessage.textContent =
            "";


        /* =========================
           VALIDACIÓN LOCAL
        ========================= */

        if (
            email.value.trim() === "" ||
            password.value.trim() === ""
        ) {

            loginMessage.textContent =
                "Completa todos los campos.";


            loginMessage.style.color =
                "#B42318";


            return;

        }


        /* =========================
           PETICIÓN AL BACKEND (LARAVEL)
        ========================= */

        loginMessage.textContent =
            "Iniciando sesión...";

        loginMessage.style.color =
            "#475467";

        try {
            const response = await fetch("/login", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": obtenerCsrfToken(),
                    "Accept": "application/json"
                },
                body: JSON.stringify({
                    email: email.value.trim(),
                    password: password.value
                })
            });

            const data = await response.json();

            if (response.ok && data.success) {

                loginMessage.textContent =
                    data.message || "Inicio de sesión correcto.";


                loginMessage.style.color =
                    "#027A48"; // Verde de éxito


                /* =========================
                   REDIRECCIÓN POSTERIOR
                ========================= */

                const destinoPendiente =
                    sessionStorage.getItem(
                        "redirectAfterLogin"
                    );


                if (destinoPendiente) {

                    sessionStorage.removeItem(
                        "redirectAfterLogin"
                    );


                    setTimeout(() => {
                        window.location.href =
                            destinoPendiente;
                    }, 800);

                    return;

                }


                setTimeout(() => {
                    window.location.href =
                        data.redirect || "/inicio";
                }, 800);

            } else {

                loginMessage.textContent =
                    data.message || "Credenciales incorrectas.";


                loginMessage.style.color =
                    "#B42318";

            }

        } catch (error) {
            console.error("Error de conexión:", error);

            loginMessage.textContent =
                "Ocurrió un error al conectar con el servidor.";


            loginMessage.style.color =
                "#B42318";
        }

    }
);