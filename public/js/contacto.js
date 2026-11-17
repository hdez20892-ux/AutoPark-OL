"use strict";

const contactForm =
    document.getElementById("contactForm");

const contactToast =
    document.getElementById("contactToast");

contactForm.addEventListener(
    "submit",
    async event => {

        event.preventDefault();
        event.stopPropagation();

        if (!contactForm.checkValidity()) {
            contactForm.classList.add(
                "was-validated"
            );
            return;
        }

        contactForm.classList.add(
            "was-validated"
        );

        // Capturar los datos del formulario de contacto
        const formData = {
            nombre: document.querySelector("#nombre")?.value || "",
            correo: document.querySelector("#correo")?.value || "",
            asunto: document.querySelector("#asunto")?.value || "",
            mensaje: document.querySelector("#mensaje")?.value || "",
        };

        try {
            // Envío real a la API del backend
            const response = await fetch("/api/contacto", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')?.getAttribute("content") || ""
                },
                body: JSON.stringify(formData)
            });

            if (response.ok) {
                const toast =
                    bootstrap.Toast.getOrCreateInstance(
                        contactToast
                    );

                toast.show();

                contactForm.reset();
                contactForm.classList.remove(
                    "was-validated"
                );
            } else {
                const data = await response.json();
                alert(data.message || "Por favor verifica los campos.");
            }

        } catch (error) {
            console.error("Error en la petición:", error);
            alert("Ocurrió un error al intentar enviar el mensaje.");
        }

    }
);