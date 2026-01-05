

document.addEventListener("DOMContentLoaded", () => {
    const params = new URLSearchParams(window.location.search);
    const dishId = params.get("id");

    fetch(`utils/api-get-dish.php?id=${dishId}`)
        .then(response => response.json())
        .then(data => {
            if (!data) return; // se l'endpoint non restituisce dati, esci
            document.getElementById("dishName").value = data.name || "";
            document.getElementById("dishDescription").value = data.description || "";
            document.getElementById("dishPrice").value = data.price || "";
            document.getElementById("dishCalories").value = data.calories || "";
            document.getElementById("dishAvailability").value = data.availability || "";

            if (data.category_id) {
                document.getElementById("dishCategory").value = data.category_id;
            }

            // Checkbox dietetiche
            if (Array.isArray(data.specs)) {
                data.specs.forEach(specId => {
                    const checkbox = document.getElementById(`spec_${specId}`);
                    if (checkbox) checkbox.checked = true;
                });
            }

            // Anteprima immagine
            if (data.image) {
                const previewImg = document.getElementById("previewImg");
                const previewIcon = document.getElementById("previewIcon");
                const previewText = document.getElementById("previewText");

                previewImg.src = data.image;
                previewImg.classList.remove("d-none");
                previewIcon.classList.add("d-none");
                previewText.classList.add("d-none");
            }
        })
        .catch(err => console.error("Errore nel caricamento piatto:", err));
    
    const cancelBtn = document.getElementById("cancel");
    if (cancelBtn) {
        cancelBtn.addEventListener("click", (e) => {
            window.location.href = "admin-dashboard.php";
        });
    }
});
