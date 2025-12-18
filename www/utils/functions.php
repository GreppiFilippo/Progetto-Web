<?php
if (!defined('IN_APP')) {
    http_response_code(403);
    exit('Access denied');
}

    /**
     * Create a new navigation item.
     * 
     * @param mixed $name The name of the navigation item
     * @param mixed $link The link URL of the navigation item
     * @param mixed $iconClass The CSS class for the icon of the navigation item
     * @return array{iconClass: mixed, link: mixed, name: mixed}
     */
    function getNewNavItem($name, $link, $iconClass) {
        return array(
            "name" => $name,
            "link" => $link,
            "iconClass" => $iconClass
        );
    }

    /**
     * Check if a user is logged in.
     * 
     * @return bool Returns true if a user is logged in, false otherwise
     */
    function isUserLoggedIn(){
        return !empty($_SESSION['user_id']);
    }

    /**
     * Check if the current user is an admin.
     * 
     * @return bool Returns true if the user is an admin, false otherwise
     */
    function isAdmin() {
        return !empty($_SESSION['is_admin']) && $_SESSION['is_admin'] === true;
    }

    /**
     * Register a logged-in user in the session.
     * 
     * @param mixed $user The user data
     * @return void
     */
    function registerLoggedUser($user){
        $_SESSION["email"] = $user["email"];
        $_SESSION["user_id"] = $user["user_id"];
        $_SESSION["first_name"] = $user["first_name"];
        $_SESSION["last_name"] = $user["last_name"];
        $_SESSION["is_admin"] = $user["admin"];
        $_SESSION["phone_number"] = $user["phone_number"];
    }

    /**
     * Generate a valid HTML ID from a given name.
     * 
     * @param string $name The input name
     * @return string Returns a valid HTML ID
     */
    function getIdFromName($name){
        return preg_replace("/[^a-z]/", '', strtolower($name));
    }

    /**
     * Display dietary tags for a dish.
     * 
     * @param array $tags An array of dietary tags
     * @return void
     */
    function getTags($tags) {
        foreach($tags as $tag) {
            echo match ($tag["dietary_spec_name"]) {
                "Vegano" =>
                    '<span class="badge bg-success text-white p-2">Vegano</span>',

                "Vegetariano" =>
                    '<span class="badge bg-primary text-white p-2">Vegetariano</span>',

                "Senza glutine" =>
                    '<span class="badge bg-warning text-dark p-2">Senza glutine</span>',

                "Senza lattosio" =>
                    '<span class="badge bg-dark text-white p-2">Senza lattosio</span>',

                default => ""
            };
        }
    }

    function availableBadge($stock) {
        if ($stock > 10) {
            echo '
            <span class="badge bg-success text-white p-2">
                <i class="bi bi-check-circle me-1" aria-hidden="true"></i>
                Disponibile
            </span>';
        } elseif ($stock > 0) {
            echo '
            <span class="badge bg-warning text-dark p-2">
                <i class="bi bi-exclamation-triangle text-black me-1" aria-hidden="true"></i>
                Disponibilità limitata
            </span>';
        } elseif ($stock == 0) {
            echo '<span class="badge bg-danger text-white p-2">
                <i class="bi bi-x-circle me-1" aria-hidden="true"></i>
                Non disponibile
            </span>';

        }
    }

    function reservationBadgeClass(string $status): string {
        return match ($status) {
            'Completato'        => 'text-bg-success',
            'Annullato'         => 'text-bg-danger',
            'Pronto al ritiro'  => 'text-bg-info',
            'In Preparazione'   => 'text-bg-warning',
            'Da Visualizzare'   => 'text-bg-secondary',
            default             => 'text-bg-dark',
        };
    }

    function reservationCardClass(string $status): string {
        return match ($status) {
            'Completato'        => 'bg-success-subtle border-success',
            'Annullato'         => 'bg-danger-subtle border-danger',
            'Pronto al ritiro'  => 'bg-info-subtle border-info',
            'In Preparazione'   => 'bg-warning-subtle border-warning',
            'Da Visualizzare'   => 'bg-body-tertiary border-secondary',
            default             => 'bg-light border-dark',
        };
    }

    function reservationIconClass(string $status): string {
        // Bootstrap Icons
        return match ($status) {
            'Completato'        => 'bi-check-circle',
            'Annullato'         => 'bi-x-circle',
            'Pronto al ritiro'  => 'bi-bag-check',
            'In Preparazione'   => 'bi-hourglass-split',
            'Da Visualizzare'   => 'bi-bell',
            default             => 'bi-info-circle',
        };
    }

    function isNewReservation(string $status): bool {
        return $status === 'Da Visualizzare';
    }

    function canCancelReservation(string $status): bool {
        // regola “pulita”: annullabile solo prima che sia pronta
        return in_array($status, ['Da Visualizzare', 'In Preparazione'], true);
    }

    function formatEuro($amount): string {
        return number_format((float)$amount, 2, ',', '.');
    }

    // Formatta la data/ora in modo leggibile con "oggi", "ieri", "domani"
    function formatWhen($dt) {
        $ts = strtotime($dt);

        $today = date('Y-m-d');
        $yesterday = date('Y-m-d', strtotime('-1 day'));
        $tomorrow = date('Y-m-d', strtotime('+1 day'));

        $d = date('Y-m-d', $ts);
        $time = date('H:i', $ts);

        if ($d === $today) return "Oggi, $time";
        if ($d === $yesterday) return "Ieri, $time";
        if ($d === $tomorrow) return "Domani, $time";

        // formato: "11 Settembre 2001, 13:00"
        $months = [
            1 => 'Gennaio', 2 => 'Febbraio', 3 => 'Marzo', 4 => 'Aprile',
            5 => 'Maggio', 6 => 'Giugno', 7 => 'Luglio', 8 => 'Agosto',
            9 => 'Settembre', 10 => 'Ottobre', 11 => 'Novembre', 12 => 'Dicembre'
        ];

        $day = (int)date('j', $ts);
        $monthNum = (int)date('n', $ts);
        $year = (int)date('Y', $ts);

        $monthName = $months[$monthNum] ?? date('F', $ts);

        return $day . ' ' . $monthName . ' ' . $year . ', ' . $time;
    }

    function uploadImage($path, $image){
        $imageName = basename($image["name"]);
        $fullPath = $path.$imageName;
        
        $maxKB = 500;
        $acceptedExtensions = array("jpg", "jpeg", "png", "gif");
        $result = 0;
        $msg = "";
        //Controllo se immagine è veramente un'immagine
        $imageSize = getimagesize($image["tmp_name"]);
        if($imageSize === false) {
            $msg .= "File caricato non è un'immagine! ";
        }
        //Controllo dimensione dell'immagine < 500KB
        if ($image["size"] > $maxKB * 1024) {
            $msg .= "File caricato pesa troppo! Dimensione massima è $maxKB KB. ";
        }

        //Controllo estensione del file
        $imageFileType = strtolower(pathinfo($fullPath,PATHINFO_EXTENSION));
        if(!in_array($imageFileType, $acceptedExtensions)){
            $msg .= "Accettate solo le seguenti estensioni: ".implode(",", $acceptedExtensions);
        }

        //Controllo se esiste file con stesso nome ed eventualmente lo rinomino
        if (file_exists($fullPath)) {
            $i = 1;
            do{
                $i++;
                $imageName = pathinfo(basename($image["name"]), PATHINFO_FILENAME)."_$i.".$imageFileType;
            }
            while(file_exists($path.$imageName));
            $fullPath = $path.$imageName;
        }

        //Se non ci sono errori, sposto il file dalla posizione temporanea alla cartella di destinazione
        if(strlen($msg)==0){
            if(!move_uploaded_file($image["tmp_name"], $fullPath)){
                $msg.= "Errore nel caricamento dell'immagine.";
            }
            else{
                $result = 1;
                $msg = $imageName;
            }
        }
        return array($result, $msg);
    }
?>