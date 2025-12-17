<?php
    require_once "bootstrap.php";

    /** 
    // Admin template access control
    if (!isUserLoggedIn()) {
        http_response_code(403);
        require "login.php";
        exit();
    }
 
    if (!isAdmin()) {
        http_response_code(401);
        require "not-authorized.php";
        exit();
    }*/

    define("UPLOAD_DIR", "./upload/");

    $errors = [];

    $templateParams["titolo"] = "Mensa Campus - Aggiunta piatto al menù";
    $templateParams["categories"] = $dbh->getCategories();
    $templateParams["dietary_specs"] = $dbh->getDietarySpecifications();
    $templateParams["content"] = "template/content-admin-add-dish.php";

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        // 1) Leggo e valido
        $name = trim($_POST["dishName"] ?? "");
        $description = trim($_POST["dishDescription"] ?? "");
        $price = (float)($_POST["dishPrice"] ?? 0);
        $stock = (int)($_POST["dishAvailability"] ?? -1);
        $calories = (int)($_POST["dishCalories"] ?? -1);
        $categoryId = (int)($_POST["dishCategory"] ?? 0);

        $specIds = $_POST["specs"] ?? [];

        if ($name === "") $errors[] = "Nome piatto obbligatorio.";
        if ($description === "") $errors[] = "Descrizione obbligatoria.";
        if ($price <= 0) $errors[] = "Prezzo non valido.";
        if ($stock < 0) $errors[] = "Quantità non valida.";
        if ($calories < 0) $errors[] = "Calorie non valide.";
        if ($categoryId <= 0) $errors[] = "Categoria obbligatoria.";

        if (!isset($_FILES["dishImage"]) || $_FILES["dishImage"]["error"] !== UPLOAD_ERR_OK) {
            $errors[] = "Immagine obbligatoria (upload non riuscito).";
        }

        // 2) Upload immagine (stile LAB)
        $imageName = null;
        if (empty($errors)) {
            list($result, $msg) = uploadImage(UPLOAD_DIR, $_FILES["dishImage"]);
            if ($result == 0) {
                $errors[] = $msg;
            } else {
                $imageName = $msg;
            }
        }

        if (empty($errors)) {
            $imagePath = $imageName;

            $res = $dbh->createDish(
                $name,
                $description,
                $price,
                $stock,
                $imagePath,
                $calories,
                $categoryId,
                $specIds
            );

            if (!($res["success"] ?? false)) {
                $errors[] = "Errore DB: " . ($res["error"] ?? "sconosciuto");
            } else {
                header("Location: admin-menu.php?msg=" . urlencode("Piatto inserito correttamente!"));
                exit;
            }
        }
    }

    require "template/base-admin.php";
?>