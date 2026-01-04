<?php
require_once '../bootstrap.php';

header('Content-Type: application/json');

$categoriaSelezionata = isset($_GET['categoria']) ? intval($_GET['categoria']) : 0;
$ricerca = $_GET['cerca'] ?? "";

$categorie = $dbh->getAllCategories();
$risultati = [];
$totaleRisultati = 0;

foreach($categorie as $categoria) {
    if ($categoriaSelezionata != 0 && $categoriaSelezionata != $categoria['category_id']) {
        continue;
    }

    $piatti = $dbh->getAllDishes($categoria['category_id']);
    $piattiFiltrati = array_filter($piatti, function($piatto) use ($ricerca) {
        return empty($ricerca) || mb_stripos($piatto['name'], $ricerca) !== false;
    });

    if (empty($piattiFiltrati)) {
        continue;
    }

    // Add dietary tags for each dish
    $piattiConTags = array_map(function($piatto) use ($dbh) {
        $piatto['dietary_tags'] = $dbh->getDietaryTagsForDish($piatto['dish_id']);
        return $piatto;
    }, $piattiFiltrati);

    $risultati[] = [
        'categoria' => $categoria,
        'piatti' => array_values($piattiConTags)
    ];
    
    $totaleRisultati += count($piattiFiltrati);
}

echo json_encode([
    'risultati' => $risultati,
    'totale' => $totaleRisultati,
    'ricerca' => $ricerca,
    'categoria' => $categoriaSelezionata
]);
