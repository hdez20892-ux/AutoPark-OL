<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Contacto - AutoPark</title>

    <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap/bootstrap.min.css') }}">
    <script src="{{ asset('assets/vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>

    <link rel="stylesheet" href="{{ asset('components/navbar/navbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/publicas.css') }}">
</head>

<body>
    <header id="navbar"></header>

    <main>
        <section class="public-hero">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8">
                        <span class="badge rounded-pill service-badge mb-3">Contacto</span>
                        <h1>¿Necesitas ayuda con AutoPark?</h1>
                        <p class="lead mb-0">
                            Envíanos un mensaje mediante el formulario y cuéntanos
                            cómo podemos ayudarte.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <section class="public-section">
            <div class="container">
                <div class="row g-4">
                    <div class="col-lg-8">
                        <section class="contact-card">
                            <h2 class="h3 mb-4">Enviar mensaje</h2>

                            <form id="contactForm" class="needs-validation" novalidate>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="nombre" class="form-label">
                                            Nombre completo
                                        </label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            id="nombre"
                                            minlength="3"
                                            maxlength="80"
                                            required
                                        >
                                        <div class="invalid-feedback">
                                            Ingresa tu nombre completo.
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="correo" class="form-label">
                                            Correo electrónico
                                        </label>
                                        <input
                                            type="email"
                                            class="form-control"
                                            id="correo"
                                            placeholder="correo@ejemplo.com"
                                            maxlength="120"
                                            required
                                        >
                                        <div class="invalid-feedback">
                                            Ingresa un correo electrónico válido.
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <label for="asunto" class="form-label">
                                            Asunto
                                        </label>
                                        <select
                                            class="form-select"
                                            id="asunto"
                                            required
                                        >
                                            <option value="" selected disabled>
                                                Selecciona una opción
                                            </option>
                                            <option value="Ayuda con una reserva">Ayuda con una reserva</option>
                                            <option value="Publicación de espacios">Publicación de espacios</option>
                                            <option value="Problema con mi cuenta">Problema con mi cuenta</option>
                                            <option value="Comentario general">Comentario general</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            Selecciona un asunto.
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <label for="mensaje" class="form-label">
                                            Mensaje
                                        </label>
                                        <textarea
                                            class="form-control"
                                            id="mensaje"
                                            minlength="10"
                                            maxlength="500"
                                            required
                                        ></textarea>
                                        <div class="invalid-feedback">
                                            Escribe un mensaje de al menos 10 caracteres.
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-check">
                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                id="privacidadContacto"
                                                required
                                            >
                                            <label
                                                class="form-check-label"
                                                for="privacidadContacto"
                                            >
                                                Acepto el aviso de privacidad.
                                            </label>
                                            <div class="invalid-feedback">
                                                Debes aceptar el aviso de privacidad.
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <button
                                            type="submit"
                                            class="btn autopark-btn-primary"
                                        >
                                            Enviar mensaje
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </section>
                    </div>

                    <div class="col-lg-4">
                        <aside class="card contact-info-card h-100">
                            <div class="card-body p-4">
                                <h2 class="h4">AutoPark</h2>
                                <p class="public-copy">
                                    Plataforma académica para búsqueda, reserva y
                                    administración de espacios de estacionamiento.
                                </p>
                                <hr>
                                <p class="mb-2"><strong>Atención:</strong></p>
                                <p class="public-copy mb-0">
                                    El formulario demuestra validación HTML5 y
                                    componentes Bootstrap. El envío se conecta directamente con la base de datos.
                                </p>
                            </div>
                        </aside>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="public-footer">
        <div class="container">
            AutoPark · estacionamiento simple, claro y accesible
        </div>
    </footer>

    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div
            id="contactToast"
            class="toast"
            role="status"
            aria-live="polite"
            aria-atomic="true"
        >
            <div class="toast-header">
                <strong class="me-auto">AutoPark</strong>
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="toast"
                    aria-label="Cerrar"
                ></button>
            </div>
            <div class="toast-body">
                ¡Mensaje enviado con éxito! Nos pondremos en contacto pronto.
            </div>
        </div>
    </div>

    <script>
        fetch("{{ asset('components/navbar/navbar.html') }}")
            .then(response => response.text())
            .then(data => {
                document.getElementById("navbar").innerHTML = data;
                const script = document.createElement("script");
                script.src = "{{ asset('components/navbar/navbar.js') }}";
                document.body.appendChild(script);
            })
            .catch(error => console.error("Error al cargar el navbar:", error));
    </script>

    <script src="{{ asset('js/contacto.js') }}"></script>
</body>
</html>