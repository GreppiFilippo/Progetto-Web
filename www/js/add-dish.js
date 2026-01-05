document.addEventListener("DOMContentLoaded", () => {   
    // Bottone annulla: va alla dashboard
    const cancelBtn = document.getElementById("cancel");
    if (cancelBtn) {
        cancelBtn.addEventListener("click", (e) => {
            window.location.href = "admin-dashboard.php";
        });
    }
});
