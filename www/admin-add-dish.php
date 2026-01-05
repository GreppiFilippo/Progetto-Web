<?php
// Admin template access control
    if (!isUserLoggedIn()) {
        http_response_code(403);
        require "login.php";
        exit();
    }

    if (!isAdmin()) {
        http_response_code(401);
        require "not-authorized.php";
        exit();
    }

$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // 1) Leggi e valida
    $name = trim($_POST["dishName"] ?? "");
    $description = trim($_POST["dishDescription"] ?? "");
    $price = (float)($_POST["dishPrice"] ?? 0);
    $stock = (int)($_POST["dishAvailability"] ?? -1);
    $calories = (int)($_POST["dishCalories"] ?? -1);
    $categoryId = (int)($_POST["dishCategory"] ?? 0);
    $specIds = $_POST["specs"] ?? [];

    // 2) Upload immagine
    $imageName = null;
    if (empty($errors)) {
        $res = uploadImage(UPLOAD_DIR, $_FILES['dishImage'] ?? []);
        if (($res['result'] ?? 0) == 0) {
            $errors[] = $res['msg'] ?? 'Errore upload sconosciuto';
        } else {
            $imageName = $res['filename'] ?? null;
        }
    }

    // 3) Inserimento DB
    if (empty($errors)) {
        $res = $dbh->createDish($name, $description, $price, $stock, $imageName, $calories, $categoryId, $specIds);
        if (!($res["success"] ?? false)) {
            $errors[] = "Errore DB: " . ($res["error"] ?? "sconosciuto");
        } else {
            // Redirect alla dashboard
            $previous = $_SERVER['HTTP_REFERER'] ?? 'admin-dashboard.php';
            header($previous);
            exit;
        }
    }
}

$templateParams["nav_items"] = array(
        getNewNavItem("Dashboard", "admin-dashboard.php", "bi bi-speedometer2 me-1"),
        getNewNavItem("Gestione menu", "admin-menu.php", "bi bi-book me-1"),
        getNewNavItem("Prenotazioni", "admin-bookings.php", "bi bi-calendar-check me-1"),
        getNewNavItem("Esci", "logout.php", "bi bi-box-arrow-right me-1")
    );


$templateParams["errors"] = $errors;
$templateParams["content"] = "template/dish-form.php";
$templateParams["titolo"] = "Aggiungi Piatto";
$templateParams["js"] = ["js/image-preview.js", "js/add-dish.js"];
require "template/base-admin.php";
?>
