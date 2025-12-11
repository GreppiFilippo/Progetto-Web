<?php
    function getNewNavItem($name, $link, $iconClass) {
        return array(
            "name" => $name,
            "link" => $link,
            "iconClass" => $iconClass
        );
    }

    function isUserLoggedIn(){
        return !empty($_SESSION['idautore']);
    }

    function isAdmin() {
        return !empty($_SESSION['is_admin']) && $_SESSION['is_admin'] === true;
    }
?>