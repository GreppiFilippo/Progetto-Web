<?php
require_once 'bootstrap.php';

if (!isUserLoggedIn()) {
    http_response_code(403);
    require "login.php";
    exit();
}

if (isAdmin()) {
    header("Location: admin-dashboard.php");
    exit();
}

$templateParams["titolo"] = "Mensa Campus - Profilo Utente";
$templateParams["user_id"] = $_SESSION["user_id"];
$templateParams["success"] = (isset($_GET["saved"]) && $_GET["saved"] === "1");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $selected = $_POST["preferenze"] ?? [];
    $selected = array_map('intval', (array) $selected);

    $res = $dbh->saveUserDietarySpecs($templateParams["user_id"], $selected);

    if (!empty($res["success"])) {
        header("Location: user-profile.php?saved=1");
        exit;
    } else {
        $templateParams["error"] = $res["error"] ?? "Errore sconosciuto.";
    }
}

$templateParams["nav_items"] = array(
    getNewNavItem("Dashboard", "user-dashboard.php", "bi bi-speedometer2"),
    getNewNavItem("Menu", "menu.php", "bi bi-book me-1"),
    getNewNavItem("Nuova Prenotazione", "user-bookings.php", "bi bi-calendar-plus me-1"),
    getNewNavItem("Profilo", "user-profile.php", "bi bi-person-circle"),
    getNewNavItem("Esci", "logout.php", "bi bi-box-arrow-right me-1")
);

$templateParams["link_utili"][] = array("name" => "Menu", "link" => "menu.php");
$templateParams["link_utili"][] = array("name" => "Profilo", "link" => "user-profile.php");

$templateParams["content"] = "template/content-user-profile.php";
$templateParams["dietary_specs"] = $dbh->getDietarySpecifications();
$templateParams["user_selected_spec_ids"] = $dbh->getUserDietarySpecIds($templateParams["user_id"]);
$templateParams["user"] = $dbh->getUserById($templateParams["user_id"]);

require 'template/base-user.php';
?>