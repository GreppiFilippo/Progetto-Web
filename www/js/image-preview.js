document.addEventListener("DOMContentLoaded", () => {
    const input = document.getElementById("dishImage");
    const previewImg = document.getElementById("previewImg");
    const previewText = document.getElementById("previewText");
    const previewIcon = document.getElementById("previewIcon");
    const btnReset = document.getElementById("btnReset");

    if (!input || !previewImg || !previewText || !previewIcon) {
        return; // sicurezza se la pagina non è quella giusta
    }

    function resetPreview() {
        previewImg.src = "";
        previewImg.classList.add("d-none");
        previewIcon.classList.remove("d-none");
        previewText.textContent = "Nessuna immagine selezionata";
    }

    input.addEventListener("change", () => {
        const file = input.files && input.files[0];

        if (!file) {
            resetPreview();
            return;
        }

        // Controllo base: deve essere immagine
        if (!file.type.startsWith("image/")) {
            alert("Il file selezionato non è un'immagine valida.");
            input.value = "";
            resetPreview();
            return;
        }

        const reader = new FileReader();
        reader.onload = (e) => {
            previewImg.src = e.target.result;
            previewImg.classList.remove("d-none");
            previewIcon.classList.add("d-none");
            previewText.textContent = file.name;
        };

        reader.readAsDataURL(file);
    });

    if (btnReset) {
        btnReset.addEventListener("click", () => {
            // timeout per aspettare il reset del form
            setTimeout(resetPreview, 0);
        });
    }

    // stato iniziale
    resetPreview();
});
