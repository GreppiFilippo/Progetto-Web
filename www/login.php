<?php
require_once 'bootstrap.php';

if (isUserLoggedIn()) {
    if (isAdmin()) {
        header("Location: admin-add-dish.php");
        exit();
    }
    header("Location: index.php");
    exit();
}

if (isset($_POST["email"]) && isset($_POST["password"])) {
    $login_result = $dbh->checkLogin($_POST["email"], $_POST["password"]);
    if (count($login_result) == 0) {
        // Login fallito
        $templateParams["login_error"] = "Errore! Controllare username o password!";
    } else {
        registerLoggedUser($login_result[0]);
        if (isAdmin()) {
            header("Location: admin-add-dish.php");
            exit();
        }
        header("Location: index.php");
        exit();
    }
}

$templateParams["titolo"] = "Mensa Campus - Login";
$templateParams["nav_items"][] = getNewNavItem("Home", "index.php", "bi bi-house-door");
$templateParams["nav_items"][] = getNewNavItem("Menu", "menu.php", "bi bi-book");
$templateParams["nav_items"][] = getNewNavItem("Accedi", "login.php", "bi bi-box-arrow-in-right");
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

$templateParams["content"] = "template/content-login.php";

require 'template/base-user.php';
?>