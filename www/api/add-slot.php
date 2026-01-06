<?php
require_once "../bootstrap.php";

// Controllo parametri
if(isset($_POST['date']) && isset($_POST['hour'])) {
    $date = (string)$_POST['date'];
    $hour = (string)$_POST['hour'];
} else {
    echo json_encode(["error" => "date and hour parameters are required"]);
    exit;
}

// Validazione: lo slot deve essere almeno 15 minuti dopo l'ora corrente
$slotDateTime = new DateTime("$date $hour");
$now = new DateTime();
$minAllowedTime = (clone $now)->modify('+15 minutes');

if ($slotDateTime <= $now) {
    echo json_encode(["success" => false, "error" => "Non puoi aggiungere uno slot nel passato o nell'ora corrente"]);
    exit;
}

if ($slotDateTime < $minAllowedTime) {
    echo json_encode(["success" => false, "error" => "Lo slot deve essere almeno 15 minuti dopo l'ora corrente"]);
    exit;
}

// Inserisci slot nel DB
$result = $dbh->addSlot($date, $hour);

// Ritorna JSON puro
echo json_encode(["success" => $result]);
?>