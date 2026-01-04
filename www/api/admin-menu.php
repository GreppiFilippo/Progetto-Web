<?php
    // mostra errori a video solo durante lo sviluppo
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once "../bootstrap.php";
    require_once "../utils/functions.php";

    // filtri di default
    $category = $_GET['category'] ?? 'all';
    $state = $_GET['state'] ?? 'all';
    $name = $_GET['name'] ?? '';

    // parametri paginazione
    $page    = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
    $perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 4;
    $offset  = ($page - 1) * $perPage;

    // prendi piatti + totale in modo sicuro
    list($paginatedDishes, $totalItems) = $dbh->getFilteredDishes($category, $state, $name, $offset, $perPage);
    
    // assicurati che totalItems sia un numero intero
    $totalItems = is_numeric($totalItems) ? (int)$totalItems : 0;

    // calcola numero di pagine
    $totalPages = $perPage > 0 ? ceil($totalItems / $perPage) : 0;

    // restituisci JSON
    header('Content-Type: application/json');
    echo json_encode([
        "dishes" => $paginatedDishes,
        "totalPages" => $totalPages
    ]);
    exit;
