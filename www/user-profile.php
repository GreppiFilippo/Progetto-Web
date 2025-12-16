<?php
require_once 'bootstrap.php';

// Base Template
$templateParams["titolo"] = "Mensa Campus - Profilo Utente";
$templateParams["user_id"] = $_SESSION["user_id"];

// --- SALVATAGGIO preferenze ---
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // preferenze[] sarà un array di dietary_spec_id
    $selected = isset($_POST["preferenze"]) ? $_POST["preferenze"] : [];
    $res = $dbh->saveUserDietarySpecs($templateParams["user_id"], $selected);

    if ($res["success"]) {
        // redirect per evitare reinvio form al refresh
        header("Location: user-profile.php?saved=1");
        exit;
    } else {
        $templateParams["error"] = $res["error"];
    }
}

$templateParams["nav_items"] = array(
    getNewNavItem("Dashboard", "user-dashboard.php", "bi bi-speedometer2"),
    getNewNavItem("Nuova Prenotazione", "user-booking.php", "bi bi-calendar-plus me-1"),
    getNewNavItem("Profilo", "user-profile.php", "bi bi-person-circle"),
    getNewNavItem("Esci", "logout.php", "bi bi-box-arrow-right me-1")
);
$templateParams["link_utili"][] = array(
    "name" => "Menu",
    "link" => "menu.php",
);
$templateParams["link_utili"][] = array(
    "name" => "Profilo",
    "link" => "user-profile.php",
);

$templateParams["content"] = "template/content-user-profile.php";
$templateParams["dietary_specs"] = $dbh->getDietarySpecifications();
$templateParams["user_selected_spec_ids"] = $dbh->getUserDietarySpecIds($templateParams["user_id"]);
$templateParams["user"] = $dbh->getUserById($templateParams["user_id"]);

require 'template/base-user.php';
?>