<?php
header('Content-Type: application/json'); // SOLO QUI, all'inizio

require_once "../bootstrap.php";

// Controllo parametri
if(isset($_POST['date']) && isset($_POST['hour'])) {
    $date = (string)$_POST['date'];
    $hour = (string)$_POST['hour'];
} else {
    echo json_encode(["error" => "date and hour parameters are required"]);
    exit;
}

// Chiamata al DB
$result = $dbh->deleteSlotByDateTime($date, $hour);

// Ritorna JSON puro
echo json_encode(["success" => $result]);
?>