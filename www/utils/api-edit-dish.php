<?php
    require_once "../bootstrap.php";
    if(isset($_POST['dish_id'], $_POST['name'], $_POST['price'], $_POST['stock'], $_POST['category_id'], $_POST['description'])) {
        $dishid = $_POST['dish_id'];
        $name = $_POST['name'];
        $price = floatval($_POST['price']);
        $stock = intval($_POST['stock']);
        $category_id = intval($_POST['category_id']);
        $description = $_POST['description'];

        $result = $dbh->modifyDish($dishid, $name, $price, $stock, $category_id, $description);
        if($result) {
            echo json_encode(["success" => true, "message" => "Piatto modificato con successo."]);
        } else {
            echo json_encode(["success" => false, "message" => "Errore durante la modifica del piatto."]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Dati mancanti per la modifica del piatto."]);
    }
?>