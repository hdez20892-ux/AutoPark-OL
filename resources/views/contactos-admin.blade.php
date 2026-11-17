<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Gestión de Mensajes - AutoPark</title>

    <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap/bootstrap.min.css') }}">
    <script src="{{ asset('assets/vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>

    <link rel="stylesheet" href="{{ asset('components/navbar/navbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/publicas.css') }}">
</head>

<body>
    <header id="navbar"></header>

    <main class="py-5">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12">
                    <span class="badge rounded-pill service-badge mb-2">Administración</span>
                    <h1 class="h2">Bandeja de Mensajes de Contacto</h1>
                    <p class="text-muted">Consulta, revisa y gestiona los mensajes enviados por los usuarios.</p>
                </div>
            </div>

            <!-- Tabla de Mensajes -->
            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">ID</th>
                                    <th>Nombre</th>
                                    <th>Correo</th>
                                    <th>Asunto</th>
                                    <th>Mensaje</th>
                                    <th>Fecha</th>
                                    <th class="text-end pe-4">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tablaContactos">
                                <!-- Los datos se cargan dinámicamente con JavaScript -->
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">Cargando mensajes...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Script para cargar el navbar y la lógica de la tabla -->
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

    <script src="{{ asset('js/contactos-admin.js') }}"></script>
</body>
</html>