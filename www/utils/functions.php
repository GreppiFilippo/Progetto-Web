<?php
if (!defined('IN_APP')) {
  http_response_code(404);
  exit;
}

    /**
     * Create a new navigation item.
     * 
     * @param string $name The name of the navigation item
     * @param string $link The link URL of the navigation item
     * @param string $iconClass The CSS class for the icon of the navigation item
     * @return array{name: string, link: string, iconClass: string}
     */
    function getNewNavItem(string $name, string $link, string $iconClass): array {
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
    function isUserLoggedIn(): bool {
        return !empty($_SESSION['user_id']);
    }

    /**
     * Check if the current user is an admin.
     * 
     * @return bool Returns true if the user is an admin, false otherwise
     */
    function isAdmin(): bool {
        return !empty($_SESSION['is_admin']) && $_SESSION['is_admin'] === true;
    }

    /**
     * Register a logged-in user in the session.
     * 
     * @param array<string, mixed> $user The user data
     * @return void
     */
    function registerLoggedUser(array $user): void {
        if (isset($user["email"])) {
            $_SESSION["email"] = is_scalar($user["email"]) ? (string)$user["email"] : '';
        }
        if (isset($user["user_id"])) {
            $_SESSION["user_id"] = is_numeric($user["user_id"]) ? (int)$user["user_id"] : 0;
        }
        if (isset($user["first_name"])) {
            $_SESSION["first_name"] = is_scalar($user["first_name"]) ? (string)$user["first_name"] : '';
        }
        if (isset($user["last_name"])) {
            $_SESSION["last_name"] = is_scalar($user["last_name"]) ? (string)$user["last_name"] : '';
        }
        if (isset($user["admin"])) {
            $_SESSION["is_admin"] = (bool)$user["admin"];
        }
        if (isset($user["phone_number"])) {
            $_SESSION["phone_number"] = is_scalar($user["phone_number"]) ? (string)$user["phone_number"] : '';
        }
    }

    /**
     * Generate a valid HTML ID from a given name.
     * 
     * @param string $name The input name
     * @return string Returns a valid HTML ID
     */
    function getIdFromName(string $name): string {
        $result = preg_replace("/[^a-z]/", '', strtolower($name));
        return is_string($result) ? $result : '';
    }

    /**
     * Display dietary tags for a dish.
     * 
     * @param array<int, array<string, mixed>> $tags An array of dietary tags
     * @return void
     */
    function getTags(array $tags): void {
        foreach($tags as $tag) {
            $specName = isset($tag["dietary_spec_name"]) && is_string($tag["dietary_spec_name"]) ? $tag["dietary_spec_name"] : "";
            echo match ($specName) {
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

    /**
     * Display available badge based on stock
     * @param int $stock
     * @return void
     */
    function availableBadge(int $stock): void {
        $stock = (int)$stock;
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

    /**
     * Format amount in Euro
     * @param float|int|string $amount
     * @return string
     */
    function formatEuro(float|int|string $amount): string {
        return number_format((float)$amount, 2, ',', '.');
    }

    /**
     * Formatta la data/ora in modo leggibile con "oggi", "ieri", "domani"
     * @param string $dt
     * @return string
     */
    function formatWhen(DateTimeInterface|string|int $dt): string {
        if ($dt instanceof DateTimeInterface) {
            $ts = $dt->getTimestamp();
        } elseif (is_int($dt)) {
            $ts = $dt;
        } else {
            $ts = strtotime((string)$dt);
        }
        if ($ts === false) {
            return '';
        }

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

        $monthName = $months[$monthNum];

        return $day . ' ' . $monthName . ' ' . $year . ', ' . $time;
    }

    /**
     * Upload image file
     * @param string $path
     * @param array<string, mixed> $image
     * @return array{result: int, msg: string, filename?: string}
     */
    function uploadImage(string $path, array $image): array {
        $imageName = basename(isset($image['name']) && is_scalar($image['name']) ? (string)$image['name'] : '');
        $fullPath = $path . $imageName;

        $maxKB = 500;
        $acceptedExtensions = array("jpg", "jpeg", "png", "gif");
        $result = 0;
        $msg = "";
        //Controllo se immagine è veramente un'immagine
        $tmpName = isset($image['tmp_name']) && is_scalar($image['tmp_name']) ? (string)$image['tmp_name'] : '';
        $imageSize = $tmpName !== '' ? getimagesize($tmpName) : false;
        if($imageSize === false) {
            $msg .= "File caricato non è un'immagine! ";
        }
        //Controllo dimensione dell'immagine < 500KB
        $fileSize = isset($image['size']) && is_numeric($image['size']) ? (int)$image['size'] : 0;
        if ($fileSize > $maxKB * 1024) {
            $msg .= "File caricato pesa troppo! Dimensione massima è $maxKB KB. ";
        }

        //Controllo estensione del file
        $imageFileType = strtolower(pathinfo($fullPath,PATHINFO_EXTENSION));
        if(!in_array($imageFileType, $acceptedExtensions)){
            $msg .= "Accettate solo le seguenti estensioni: ".implode(",", $acceptedExtensions);
        }

        //Controllo se esiste file con stesso nome ed eventualmente lo rinomino
        if ($imageName !== '' && file_exists($fullPath)) {
            $i = 1;
            $originalName = isset($image['name']) && is_scalar($image['name']) ? (string)$image['name'] : '';
            do{
                $i++;
                $imageName = pathinfo(basename($originalName), PATHINFO_FILENAME)."_$i.".$imageFileType;
            }
            while(file_exists($path.$imageName));
            $fullPath = $path.$imageName;
        }

        //Se non ci sono errori, sposto il file dalla posizione temporanea alla cartella di destinazione
        if(strlen($msg)==0){
            if(!move_uploaded_file($tmpName, $fullPath)){
                $msg .= "Errore nel caricamento dell'immagine.";
            } else {
                $result = 1;
                $msg = "OK";
            }
        }
        $ret = [
            'result' => $result,
            'msg' => $msg
        ];
        if ($result === 1) {
            $ret['filename'] = $imageName;
        }
        return $ret;
    }
?>