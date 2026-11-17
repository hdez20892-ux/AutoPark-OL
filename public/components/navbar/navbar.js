/* =========================
   NAVEGACIÓN Y SESIÓN
========================= */

const logoutButton =
    document.getElementById("logoutButton");

const navbarBrand =
    document.getElementById("navbarBrand");

const navbarHome =
    document.getElementById("navbarHome");

// Detecta la ruta actual de Laravel (ej. "/busqueda" o "/")
const currentPath = window.location.pathname;
const currentPage = currentPath === "/" ? "index" : currentPath.split("/").pop() || "index";

const paginasPrivadas = [
    "inicio",
    "busqueda",
    "reservas",
    "espacios",
    "perfil"
];

const paginasDeAcceso = [
    "login",
    "registro"
];

function obtenerCsrfToken() {
    const meta = document.querySelector('meta[name="csrf-token"]');
    return meta ? meta.content : '';
}

function aplicarEstadoNavbar(sesionActiva) {
    if (
        paginasPrivadas.includes(currentPage) &&
        !sesionActiva
    ) {
        const destinoOriginal =
            window.location.pathname +
            window.location.search +
            window.location.hash;

        sessionStorage.setItem(
            "redirectAfterLogin",
            destinoOriginal
        );

        window.location.replace("/login");
        return;
    }

    const paginaInicio =
        sesionActiva
            ? "/inicio"
            : "/index";

    if (navbarBrand) {
        navbarBrand.href = paginaInicio;
    }

    if (navbarHome) {
        navbarHome.href = paginaInicio;
    }

    if (paginasDeAcceso.includes(currentPage)) {
        document.body.dataset.navbarMode = "guest";
    } else {
        document.body.dataset.navbarMode =
            sesionActiva
                ? "user"
                : "guest";
    }
}

/* =========================
   VERIFICAR SESIÓN REAL CON LARAVEL
========================= */
async function verificarSesionServidor() {
    try {
        const response = await fetch("/api/usuario-actual", {
            method: "GET",
            headers: {
                "Accept": "application/json"
            }
        });

        if (response.ok) {
            const data = await response.json();
            if (data.authenticated) {
                localStorage.setItem("sesionActiva", "true");
                if (data.user) {
                    localStorage.setItem("usuarioActual", JSON.stringify({
                        nombre: data.user.name,
                        correo: data.user.email
                    }));
                }
                aplicarEstadoNavbar(true);
                return;
            }
        }

        // Si el servidor dice que no hay sesión activa
        localStorage.removeItem("sesionActiva");
        localStorage.removeItem("usuarioActual");
        aplicarEstadoNavbar(false);

    } catch (error) {
        console.error("Error verificando sesión:", error);
        // Respaldo visual por si falla la red
        const respaldoLocal = localStorage.getItem("sesionActiva") === "true";
        aplicarEstadoNavbar(respaldoLocal);
    }
}

// Ejecutar verificación al cargar el navbar
verificarSesionServidor();


/* =========================
   PÁGINA ACTIVA
========================= */

document
    .querySelectorAll(".nav-link")
    .forEach(link => {

        const href =
            link.getAttribute("href");

        if (!href) {
            return;
        }

        // Extrae el nombre de la ruta limpia de Laravel
        const linkPage =
            href === "/" ? "index" : href.split("/").pop();

        if (
            linkPage === currentPage &&
            link.offsetParent !== null
        ) {
            link.classList.add("active");
            link.setAttribute(
                "aria-current",
                "page"
            );
        }

    });


/* =========================
   CERRAR COLLAPSE AL NAVEGAR
========================= */

document
    .querySelectorAll("#navbarLinks a.nav-link")
    .forEach(link => {

        link.addEventListener(
            "click",
            () => {

                const collapseElement =
                    document.getElementById(
                        "navbarLinks"
                    );

                if (
                    collapseElement &&
                    window.bootstrap
                ) {
                    const instance =
                        bootstrap.Collapse.getInstance(
                            collapseElement
                        );

                    if (instance) {
                        instance.hide();
                    }
                }

            }
        );

    });


/* =========================
   CERRAR SESIÓN
========================= */

if (logoutButton) {
    logoutButton.addEventListener(
        "click",
        cerrarSesion
    );
}

async function cerrarSesion() {
    try {
        await fetch("/logout", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": obtenerCsrfToken(),
                "Accept": "application/json"
            }
        });
    } catch (error) {
        console.error("Error al cerrar sesión en servidor:", error);
    }

    localStorage.removeItem("sesionActiva");
    localStorage.removeItem("usuarioSesion");
    localStorage.removeItem("usuarioActual");

    sessionStorage.removeItem(
        "estacionamientoSeleccionado"
    );
    sessionStorage.removeItem(
        "reservaPendiente"
    );
    sessionStorage.removeItem(
        "redirectAfterLogin"
    );

    window.location.href = "/index";
}