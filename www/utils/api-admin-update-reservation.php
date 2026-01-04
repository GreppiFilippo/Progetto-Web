<?php
require_once "../bootstrap.php";

if(isset($_POST['reservation_id'], $_POST['status'])) {
    $reservationId = $_POST['reservation_id'];
    $status = $_POST['status'];

    $result = $dbh->updateReservationStatus($reservationId, $status);   

    if($result) {
        echo json_encode([
            'success' => true,
            'message' => 'Stato della prenotazione aggiornato con successo.'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Errore durante l\'aggiornamento dello stato della prenotazione.'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Parametri mancanti.'
    ]);
}
?>
