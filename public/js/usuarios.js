"use strict";

const tablaUsuariosBody = document.querySelector("#tablaUsuarios tbody");
const totalUsuariosBadge = document.getElementById("totalUsuarios");
const usuariosMensaje = document.getElementById("usuariosMensaje");

document.addEventListener("DOMContentLoaded", function () {
    cargarUsuarios();
});

function mostrarMensaje(texto, tipo) {
    if (!usuariosMensaje) return;
    usuariosMensaje.textContent = texto;
    usuariosMensaje.className = `alert alert-${tipo === "exito" ? "success" : "danger"} mt-3`;
    usuariosMensaje.classList.remove("d-none");

    setTimeout(() => {
        usuariosMensaje.classList.add("d-none");
    }, 4000);
}

async function cargarUsuarios() {
    try {
        const response = await fetch("/api/usuarios", {
            headers: {
                "Accept": "application/json"
            }
        });

        if (!response.ok) throw new Error("No se pudo obtener la lista de usuarios.");

        const usuarios = await response.json();
        
        tablaUsuariosBody.innerHTML = "";
        if (totalUsuariosBadge) {
            totalUsuariosBadge.textContent = `Total: ${usuarios.length}`;
        }

        if (usuarios.length === 0) {
            tablaUsuariosBody.innerHTML = `
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">No hay usuarios registrados en el sistema.</td>
                </tr>
            `;
            return;
        }

        usuarios.forEach(usuario => {
            const fila = document.createElement("tr");
            fila.innerHTML = `
                <td><strong>#${usuario.id}</strong></td>
                <td>${usuario.name}</td>
                <td>${usuario.email}</td>
                <td><span class="badge bg-light text-dark border">${usuario.tipo_cuenta || 'Conductor'}</span></td>
                <td class="text-end">
                    <button class="btn btn-outline-danger btn-sm" onclick="eliminarUsuario(${usuario.id})">
                        <i class="bi bi-trash"></i> Eliminar
                    </button>
                </td>
            `;
            tablaUsuariosBody.appendChild(fila);
        });

    } catch (error) {
        console.error("Error:", error);
        mostrarMensaje("Error al conectar con el servidor para cargar los usuarios.", "error");
    }
}

async function eliminarUsuario(id) {
    if (!confirm("¿Estás seguro de dar de baja este usuario del sistema?")) {
        return;
    }

    try {
        const response = await fetch(`/api/usuarios/${id}`, {
            method: "DELETE",
            headers: {
                "Accept": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')?.getAttribute("content") || ""
            }
        });

        if (response.ok) {
            mostrarMensaje("El usuario fue eliminado correctamente.", "exito");
            cargarUsuarios(); // Recargar la tabla dinámicamente
        } else {
            const data = await response.json();
            mostrarMensaje(data.error || "No se pudo eliminar el usuario.", "error");
        }
    } catch (error) {
        console.error("Error en la petición:", error);
        mostrarMensaje("Ocurrió un error al intentar eliminar el usuario.", "error");
    }
}