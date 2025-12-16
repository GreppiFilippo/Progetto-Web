<?php
require_once 'bootstrap.php';

// Base Template
$templateParams["titolo"] = "Mensa Campus - Menu";


if (isUserLoggedIn()) {
    $templateParams["nav_items"] = array(
        getNewNavItem("Dashboard", "user-dashboard.php", "bi bi-speedometer2"),
        getNewNavItem("Menu", "menu.php", "bi bi-book"),
        getNewNavItem("Prenotazioni", "user-bookings.php", "bi bi-calendar-check"),
        getNewNavItem("Profilo", "user-profile.php", "bi bi-person-circle"),
        getNewNavItem("Esci", "logout.php", "bi bi-box-arrow-right"),
    );
    $templateParams["link_utili"][] = array(
        "name" => "Menu",
        "link" => "menu.php",
    );
    $templateParams["link_utili"][] = array(
        "name" => "Profilo",
        "link" => "user-profile.php",
    );
} else {
    $templateParams["nav_items"] = array(
        getNewNavItem("Home", "index.php", "bi bi-house-door"),
        getNewNavItem("Menu", "menu.php", "bi bi-book"),
        getNewNavItem("Accedi", "login.php", "bi bi-box-arrow-in-right")
    );
    $templateParams["link_utili"][] = array(
        "name" => "Menu",
        "link" => "menu.php",
    );
    $templateParams["link_utili"][] = array(
        "name" => "Accedi",
        "link" => "login.php",
    );
    $templateParams["link_utili"][] = array(
        "name" => "Registrati",
        "link" => "register.php",
    );
}

$templateParams["categorie"] = $dbh->getAllCategories();
$templateParams["js"] = array("js/menu.js");
$templateParams["content"] = "template/content-menu.php";

require 'template/base-user.php';
?>