"use strict";

const reservasContainer =
    document.getElementById(
        "reservasContainer"
    );


const emptyState =
    document.getElementById(
        "emptyState"
    );


const tabButtons =
    document.querySelectorAll(
        ".tab-button"
    );


let tabActual =
    "activas";

let reservas = [];

/* =========================
   CARGAR DATOS DESDE POSTGRESQL (LARAVEL API)
========================= */

async function cargarReservas() {
    try {
        // Corregido: Se apunta a /api/reservas para obtener el JSON correctamente
        const response = await fetch('/api/reservas', {
            method: 'GET',
            headers: {
                "Accept": "application/json"
            }
        });

        const data = await response.json();

        if (data.success && Array.isArray(data.reservas)) {
            // Mapeamos los datos para adaptarlos al formato que ya usa tu render
            reservas = data.reservas.map(reserva => {
                const estacionamiento = reserva.espacio || {
                    id: reserva.espacio_id || 1,
                    nombre: "Estacionamiento AutoPark",
                    ubicacion: "Pachuca, Hgo.",
                    precio: reserva.total ? Number(reserva.total) / 2 : 20,
                    disponibles: 5
                };

                return {
                    id: reserva.id,
                    estacionamiento: estacionamiento,
                    fecha: reserva.fecha,
                    horaEntrada: reserva.hora_entrada || reserva.entrada || "",
                    horaSalida: reserva.hora_salida || reserva.salida || "",
                    total: Number(reserva.total) || 0,
                    estado: reserva.estado || "activa"
                };
            });
        } else {
            reservas = [];
        }
    } catch (error) {
        console.error("Error al cargar las reservas desde el servidor:", error);
        reservas = [];
    }

    mostrarReservas();
}


/* =========================
   FORMATEAR FECHA
========================= */

function formatearFecha(fecha) {
    if (!fecha) return "";
    const fechaObjeto =
        new Date(
            `${fecha}T00:00:00`
        );


    return fechaObjeto
        .toLocaleDateString(
            "es-MX",
            {
                day: "2-digit",
                month: "short",
                year: "numeric"
            }
        )
        .replace(".", "");

}


/* =========================
   RENDER
========================= */

function mostrarReservas() {
    if (!reservasContainer) return;

    reservasContainer.innerHTML =
        "";


    const filtradas =
        reservas.filter(
            reserva => {

                if (
                    tabActual ===
                    "activas"
                ) {

                    return (
                        reserva.estado ===
                        "activa"
                    );

                }


                return (
                    reserva.estado !==
                    "activa"
                );

            }
        );


    if (
        filtradas.length === 0
    ) {
        if (emptyState) {
            emptyState.classList.remove(
                "hidden"
            );
        }
        return;

    }


    if (emptyState) {
        emptyState.classList.add(
            "hidden"
        );
    }


    filtradas.forEach(
        reserva => {

            const card =
                document.createElement(
                    "article"
                );


            card.className =
                "reserva-card card";


            const estadoTexto =
                reserva.estado ===
                "activa"
                    ? "Confirmada"
                    : reserva.estado ===
                      "cancelada"
                        ? "Cancelada"
                        : "Finalizada";


            card.innerHTML = `

                <div class="reserva-image">
                    P
                </div>


                <div class="reserva-info">

                    <h2>
                        ${reserva.estacionamiento.nombre}
                    </h2>

                    <p>
                        ${formatearFecha(reserva.fecha)}
                        ·
                        ${reserva.horaEntrada}
                        –
                        ${reserva.horaSalida}
                    </p>

                    <p>
                        ${reserva.estacionamiento.ubicacion}
                    </p>

                    <p>
                        Total:
                        $${reserva.total} MXN
                    </p>

                </div>


                <div class="reserva-actions">

                    <span
                        class="status-badge badge rounded-pill ${reserva.estado}"
                    >
                        ${estadoTexto}
                    </span>


                    <button
                        type="button"
                        class="btn btn-detail"
                        data-action="detalle"
                        data-id="${reserva.id}"
                    >
                        Ver detalle
                    </button>


                    ${
                        reserva.estado ===
                        "activa"
                            ? `
                                <button
                                    type="button"
                                    class="btn btn-detail"
                                    data-action="editar"
                                    data-id="${reserva.id}"
                                >
                                    Editar
                                </button>

                                <button
                                    type="button"
                                    class="btn btn-cancel"
                                    data-action="cancelar"
                                    data-id="${reserva.id}"
                                >
                                    Cancelar
                                </button>
                              `
                            : ""
                    }

                </div>
            `;


            reservasContainer
                .appendChild(
                    card
                );

        }
    );

}


/* =========================
   PESTAÑAS
========================= */

tabButtons.forEach(
    button => {

        button.addEventListener(
            "click",
            () => {

                tabActual =
                    button.dataset.tab;


                tabButtons.forEach(
                    item => {

                        item.classList.remove(
                            "active"
                        );

                        item.setAttribute(
                            "aria-selected",
                            "false"
                        );

                    }
                );


                button.classList.add(
                    "active"
                );

                button.setAttribute(
                    "aria-selected",
                    "true"
                );


                mostrarReservas();

            }
        );

    }
);


/* =========================
   ACCIONES (VER, EDITAR, CANCELAR)
========================= */

reservasContainer.addEventListener(
    "click",
    async event => {

        const button =
            event.target.closest(
                "button"
            );


        if (!button) {

            return;

        }


        const id =
            Number(
                button.dataset.id
            );


        const reserva =
            reservas.find(
                item =>
                    item.id === id
            );


        if (!reserva) {

            return;

        }


        /* VER DETALLE */

        if (
            button.dataset.action ===
            "detalle"
        ) {

            sessionStorage.setItem(
                "estacionamientoSeleccionado",
                JSON.stringify(
                    reserva.estacionamiento
                )
            );


            window.location.href =
                "/detalle";

        }


        /* EDITAR */
        if (button.dataset.action === "editar" && reserva.estado === "activa") {
            sessionStorage.setItem("estacionamientoSeleccionado", JSON.stringify(reserva.estacionamiento));
            window.location.href = "/detalle?editar=" + encodeURIComponent(reserva.id);
            return;
        }


        /* CANCELAR EN BASE DE DATOS */

        if (
            button.dataset.action ===
            "cancelar"
        ) {

            const confirmar =
                confirm(
                    "¿Deseas cancelar esta reserva?"
                );


            if (!confirmar) {

                return;

            }

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

            try {
                // Corregido: Coincide con la ruta /reservas/{id}/cancelar definida en web.php
                const response = await fetch(`/reservas/${reserva.id}/cancelar`, {
                    method: 'PUT',
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken,
                        "Accept": "application/json"
                    }
                });

                const data = await response.json();

                if (response.ok && (data.success !== false)) {
                    reserva.estado = "cancelada";
                    mostrarReservas();
                } else {
                    alert(data.message || "No se pudo cancelar la reserva.");
                }
            } catch (error) {
                console.error("Error al cancelar:", error);
                alert("Error de conexión al intentar cancelar.");
            }

        }

    }
);


/* =========================
   INICIO
========================= */

document.addEventListener("DOMContentLoaded", cargarReservas);