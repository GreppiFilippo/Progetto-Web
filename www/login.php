<?php
require_once 'bootstrap.php';

// Base Template
$templateParams["titolo"] = "Mensa Campus - Login";

if (!isUserLoggedIn()) {
    $templateParams["nav_items"][] = getNewNavItem("Home", "index.php", "bi bi-house-door");
    $templateParams["nav_items"][] = getNewNavItem("Menu", "menu.php", "bi bi-book");
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


require 'template/base-user.php';
?>