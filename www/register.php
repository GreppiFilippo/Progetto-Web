<?php
require_once 'bootstrap.php';

//Messaggi di errore e successo
$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $cognome = trim($_POST['cognome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confermapassword = $_POST['confermapassword'] ?? '';

    // Validazioni di base
    if (empty($nome)) {
        $errors[] = "Il campo Nome è obbligatorio.";    
    }
    if (empty($cognome)) {
        $errors[] = "Il campo Cognome è obbligatorio.";    
    }
    if (empty($email)) {
        $errors[] = "Il campo Email è obbligatorio.";    
    } 
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Il formato dell'email non è valido.";    
    } 
    if (empty($errors)) {
        if ($dbh->emailExists($email)) {
            $errors[] = "Esiste già un account con questa email.";
        } else {
            $res = $dbh->createUser($email, $password, $nome, $cognome, false);

            if ($res["success"]) {
                $success = true;
                header("Location: login.php?registered=1");
                exit();
            } else {
                $errors[] = "Errore durante la registrazione: " . $res["error"];
            }
        }
    }
}

$templateParams["errors"] = $errors;
$templateParams["success"] = $success;

// Base Template
$templateParams["titolo"] = "Mensa Campus - Registrazione";

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

$templateParams["content"] = "template/content-register.php";

require 'template/base-user.php';
?>