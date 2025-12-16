<?php

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
?>