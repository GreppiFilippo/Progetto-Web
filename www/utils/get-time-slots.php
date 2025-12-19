<?php
require_once __DIR__ . '/../bootstrap.php';

header('Content-Type: application/json');

if (!isUserLoggedIn()) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

$date = (isset($_GET['date']) && is_string($_GET['date'])) ? $_GET['date'] : '';

if (empty($date)) {
    http_response_code(400);
    echo json_encode(['error' => 'Date parameter is required']);
    exit();
}

// Validate date format
$dateObj = DateTime::createFromFormat('Y-m-d', $date);
if (!$dateObj || $dateObj->format('Y-m-d') !== $date) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid date format. Use YYYY-MM-DD']);
    exit();
}

// Check if date is not in the past
$today = new DateTime();
$today->setTime(0, 0, 0);
if ($dateObj < $today) {
    http_response_code(400);
    echo json_encode(['error' => 'Cannot book for past dates']);
    exit();
}


// Ensure database helper is available and callable
global $dbh;
/** @var mixed $dbh */
if (!isset($dbh) || !is_object($dbh) || !method_exists($dbh, 'getTimeSlotsByDate')) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error']);
    exit();
}

$slots = $dbh->getTimeSlotsByDate($date);
if (!is_array($slots)) {
    $slots = [];
}

// Format slots for response (guard types)
$formattedSlots = array_map(function($slot) {
    if (!is_array($slot) || !isset($slot['slot_time']) || !is_string($slot['slot_time'])) {
        return ['value' => '', 'label' => ''];
    }
    $time = $slot['slot_time'];
    return [
        'value' => substr($time, 0, 5),
        'label' => $time
    ];
}, $slots);

echo json_encode(['slots' => $formattedSlots]);
?>