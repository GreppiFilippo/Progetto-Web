<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once "bootstrap.php";

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
$dishId = $_GET["id"];
$currentPage = $_GET["currentPage"];
// Parametri per template
$templateParams["errors"] = $errors;
$templateParams["content"] = "template/dish-form.php";
$templateParams["titolo"] = "Modifica Piatto";
$templateParams["js"] = ["js/image-preview.js", "js/edit-dish.js"]; 
$templateParams["icon"] = `<div class="bg-primary bg-opacity-10 rounded-3 p-3" aria-hidden="true">
                        <i class="bi bi-calendar-check text-primary fs-2" aria-hidden="true"></i>
                        Modifica Piatto
                    </div>`;

$templateParams["nav_items"] = array(
        getNewNavItem("Dashboard", "admin-dashboard.php", "bi bi-speedometer2 me-1"),
        getNewNavItem("Gestione menu", "admin-menu.php", "bi bi-book me-1"),
        getNewNavItem("Prenotazioni", "admin-bookings.php", "bi bi-calendar-check me-1"),
        getNewNavItem("Esci", "logout.php", "bi bi-box-arrow-right me-1")
    );

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
    $imageName = "";
    if (
        empty($errors) &&
        isset($_FILES['dishImage']) &&
        $_FILES['dishImage']['error'] !== UPLOAD_ERR_NO_FILE
    ) {
        $res = uploadImage(UPLOAD_DIR, $_FILES['dishImage']);

        if (($res['result'] ?? 0) == 0) {
            $errors[] = $res['msg'] ?? 'Errore upload sconosciuto';
        } else {
            $imageName = $res['filename'] ?? "";
        }
    }

    // 3) Inserimento DB
    if (empty($errors)) {
        $res = $dbh->modifyDish($dishId, $name, $price, $stock, $categoryId, $description, $calories, $imageName, $specIds);
        if (!($res["success"] ?? false)) {
            $errors[] = "Errore DB: " . ($res["error"] ?? "sconosciuto");
        } else {
            header("Location: admin-menu.php?currentPage=".$currentPage);
            exit;
        }
    }
}

require "template/base-admin.php";
?>