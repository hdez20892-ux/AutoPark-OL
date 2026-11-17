"use strict";

document.addEventListener("DOMContentLoaded", function () {
    const tablaContactos = document.getElementById("tablaContactos");

    // Función para cargar los mensajes de la base de datos
    async function cargarContactos() {
        try {
            const response = await fetch("/api/contactos");
            if (!response.ok) throw new Error("Error al obtener los mensajes");

            const contactos = await response.json();

            if (contactos.length === 0) {
                tablaContactos.innerHTML = `
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">No hay mensajes registrados.</td>
                    </tr>
                `;
                return;
            }

            let filas = "";
            contactos.forEach(c => {
                // Formatear la fecha de forma legible
                const fecha = new Date(c.created_at).toLocaleDateString();

                filas += `
                    <tr>
                        <td class="ps-4 fw-bold">#${c.id}</td>
                        <td>${escapeHtml(c.nombre)}</td>
                        <td>${escapeHtml(c.correo)}</td>
                        <td><span class="badge bg-secondary">${escapeHtml(c.asunto)}</span></td>
                        <td>${escapeHtml(c.mensaje)}</td>
                        <td>${fecha}</td>
                        <td class="text-end pe-4">
                            <button class="btn btn-sm btn-outline-danger" onclick="eliminarContacto(${c.id})">
                                Eliminar
                            </button>
                        </td>
                    </tr>
                `;
            });

            tablaContactos.innerHTML = filas;

        } catch (error) {
            console.error("Error:", error);
            tablaContactos.innerHTML = `
                <tr>
                    <td colspan="7" class="text-center py-4 text-danger">Error al cargar los mensajes del servidor.</td>
                </tr>
            `;
        }
    }

    // Función global para eliminar un mensaje
    window.eliminarContacto = async function (id) {
        if (!confirm("¿Estás segura de que deseas eliminar este mensaje?")) return;

        try {
            const response = await fetch(`/api/contactos/${id}`, {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                    "Accept": "application/json"
                }
            });

            if (response.ok) {
                alert("Mensaje eliminado correctamente.");
                cargarContactos(); // Recargar la tabla
            } else {
                alert("No se pudo eliminar el mensaje.");
            }
        } catch (error) {
            console.error("Error:", error);
            alert("Ocurrió un error en la red.");
        }
    };

    // Función auxiliar de seguridad contra XSS básico
    function escapeHtml(text) {
        if (!text) return "";
        return text.replace(/&/g, "&amp;")
                   .replace(/</g, "&lt;")
                   .replace(/>/g, "&gt;")
                   .replace(/"/g, "&quot;")
                   .replace(/'/g, "&#039;");
    }

    // Ejecutar al cargar la vista
    cargarContactos();
});