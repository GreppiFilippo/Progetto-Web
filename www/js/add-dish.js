document.addEventListener("DOMContentLoaded", () => { 
    
    document.getElementById("confirm").innerHTML = `<i class="bi bi-check-lg me-2"></i>Aggiungi Piatto`;
    // Bottone annulla: va alla dashboard
    const cancelBtn = document.getElementById("cancel");
    if (cancelBtn) {
        cancelBtn.addEventListener("click", (e) => {
            window.location.href = "admin-dashboard.php";
        });
    }
});
