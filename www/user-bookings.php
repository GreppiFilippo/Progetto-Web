<?php
require_once 'bootstrap.php';

if (!isUserLoggedIn()) {
    http_response_code(403);
    require "login.php";
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user_id'];
    $date = $_POST['booking-date'] ?? '';
    $time = $_POST['booking-time'] ?? '';
    $notes = !empty($_POST['booking-notes']) ? trim($_POST['booking-notes']) : null;
    
    // Combine date and time
    $dateTime = $date . ' ' . $time . ':00';
    
    // Collect items from form - now clean array with dish IDs
    $items = [];
    if (isset($_POST['dishes']) && is_array($_POST['dishes'])) {
        foreach ($_POST['dishes'] as $dish_id => $quantity) {
            $quantity = (int)$quantity;
            if ($quantity > 0) {
                $items[] = [
                    'dish_id' => (int)$dish_id,
                    'quantity' => $quantity
                ];
            }
        }
    }
    
    if (!empty($items)) {
        $result = $dbh->setNewReservation($userId, $dateTime, $items, $notes);
        
        if ($result['success']) {
            $_SESSION['success_message'] = "Prenotazione creata con successo!";
            header("Location: single-order.php?reservation_id=" . $result['reservation_id']);
            exit();
        } else {
            $_SESSION['error_message'] = $result['error'];
            header("Location: user-bookings.php");
            exit();
        }
    } else {
        $_SESSION['error_message'] = "Seleziona almeno un piatto per la prenotazione.";
        header("Location: user-bookings.php");
        exit();
    }
}

$templateParams["titolo"] = "Mensa Campus - Prenotazioni";

$templateParams["nav_items"] = array(
    getNewNavItem("Dashboard", "user-dashboard.php", "bi bi-speedometer2"),
    getNewNavItem("Menu", "menu.php", "bi bi-book"),
    getNewNavItem("Nuova Prenotazione", "user-bookings.php", "bi bi-calendar-check"),
    getNewNavItem("Profilo", "user-profile.php", "bi bi-person-circle"),
    getNewNavItem("Esci", "logout.php", "bi bi-box-arrow-right")
);

$templateParams["link_utili"][] = array(
    "name" => "Menu",
    "link" => "menu.php",
);
$templateParams["link_utili"][] = array(
    "name" => "Profilo",
    "link" => "user-profile.php",
);

$templateParams["categories"] = $dbh->getAllCategories();
$templateParams["content"] = "template/user-bookings-content.php";
// include JS for booking page
$templateParams["js"][] = "js/user-bookings.js";

require 'template/base-user.php';
?>