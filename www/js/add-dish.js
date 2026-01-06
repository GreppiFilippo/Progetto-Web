document.addEventListener("DOMContentLoaded", () => { 

    document.getElementById("axt").innerHTML = "*";
    document.getElementById("confirm").innerHTML = `<i class="bi bi-check-lg me-2" aria-hidden="true"></i>Aggiungi Piatto`;
    document.getElementById("title").innerHTML = `<i class="bi bi-plus-circle admin-icon fs-2" aria-hidden="true"></i> Aggiungi Piatto`;
    // Bottone annulla: va alla dashboard
    const cancelBtn = document.getElementById("cancel");
    if (cancelBtn) {
        cancelBtn.addEventListener("click", (e) => {
            window.location.href = "admin-dashboard.php";
        });
    }
});
