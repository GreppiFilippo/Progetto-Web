<?php
require_once 'bootstrap.php';

// Base Template
$templateParams["titolo"] = "Mensa Campus - Home";

$templateParams["nav_items"] = array(
    getNewNavItem("Home", "index.php", "bi bi-house-door"),
    getNewNavItem("Menu", "menu.php", "bi bi-book")
);

if (isUserLoggedIn()) {
    $templateParams["nav_items"][] = getNewNavItem("Dashboard", "user-dashboard.php", "bi bi-speedometer2");
    $templateParams["nav_items"][] = getNewNavItem("Prenotazioni", "user-bookings.php", "bi bi-calendar-check");
    $templateParams["nav_items"][] = getNewNavItem("Profilo", "user-profile.php", "bi bi-person-circle");
    $templateParams["link_utili"][] = array(
        "name" => "Menu",
        "link" => "menu.php",
    );
    $templateParams["link_utili"][] = array(
        "name" => "Profilo",
        "link" => "user-profile.php",
    );
} else {
    $templateParams["nav_items"][] = getNewNavItem("Accedi", "login.html", "bi bi-box-arrow-in-right");
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

$templateParams["content"] = "template/content-home.php";

require 'template/base-user.php';
?>