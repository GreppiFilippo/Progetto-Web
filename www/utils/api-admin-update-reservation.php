<?php
require_once "../bootstrap.php";

if(isset($_POST['reservation_id'], $_POST['status'])) {
    $reservationId = $_POST['reservation_id'];
    $status = $_POST['status'];

    if($status == "Annullato") {
        $user_id = $dbh->getUserByReservation($reservationId);
        if ($user_id === null) {
            echo json_encode([
                'success' => false,
                'message' => 'Prenotazione non trovata.'
            ]);
            exit;
        }
        $result = $dbh->deleteReservation($reservationId, $user_id);
    } else {
        $result = $dbh->updateReservationStatus($reservationId, $status);  
    }

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
