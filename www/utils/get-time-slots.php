<?php
require_once __DIR__ . '/../bootstrap.php';

header('Content-Type: application/json');

if (!isUserLoggedIn()) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

$date = $_GET['date'] ?? '';

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

$slots = $dbh->getTimeSlotsByDate($date);

// Format slots for response
$formattedSlots = array_map(function($slot) {
    return [
        'value' => substr($slot['slot_time'], 0, 5),
        'label' => $slot['slot_time']
    ];
}, $slots);

echo json_encode(['slots' => $formattedSlots]);
?>