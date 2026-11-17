<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios - AutoPark</title>

    <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="{{ asset('assets/vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>

    <link rel="stylesheet" href="{{ asset('components/navbar/navbar.css') }}">
    <!-- Reutilizamos la misma paleta y estilos generales -->
    <link rel="stylesheet" href="{{ asset('css/perfil.css') }}">
    <link rel="stylesheet" href="{{ asset('css/usuarios.css') }}">
</head>

<body data-navbar-mode="admin">

    <!-- NAVBAR REUTILIZABLE -->
    <div id="navbar"></div>

    <main class="perfil-page">

        <!-- ENCABEZADO -->
        <header class="perfil-header">
            <h1>Gestión de Usuarios</h1>
            <p class="perfil-subtitle">
                Administra las cuentas registradas en el sistema y da de baja accesos si es necesario.
            </p>
        </header>

        <!-- DISTRIBUCIÓN PRINCIPAL -->
        <div class="usuarios-layout">

            <section class="card-form card-usuarios-wide">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Usuarios Registrados</h2>
                    <span class="badge bg-cyan text-dark" id="totalUsuarios">Total: 0</span>
                </div>

                <!-- MENSAJE DE ALERTA DINÁMICO -->
                <div id="usuariosMensaje" class="alert d-none" role="alert"></div>

                <div class="table-responsive">
                    <table class="table align-middle" id="tablaUsuarios">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Correo electrónico</th>
                                <th>Tipo de cuenta</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Se llena dinámicamente con JavaScript -->
                        </tbody>
                    </table>
                </div>
            </section>

        </div>

    </main>

    <!-- CARGAR NAVBAR REUTILIZABLE -->
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

    <script src="{{ asset('js/usuarios.js') }}"></script>

</body>

</html>