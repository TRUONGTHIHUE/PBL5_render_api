<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Cache-Control: no-store');

$statusFile = dirname(__DIR__)
    . DIRECTORY_SEPARATOR . 'data'
    . DIRECTORY_SEPARATOR . 'current_store.json';

if (!is_file($statusFile)) {
    echo json_encode(
        [
            'success' => true,
            'status' => 'waiting_for_rfid',
            'data' => null
        ],
        JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT
    );
    exit;
}

$rawData = file_get_contents($statusFile);
$statusData = json_decode($rawData === false ? '' : $rawData, true);

if (!is_array($statusData)) {
    http_response_code(500);
    echo json_encode(
        [
            'success' => false,
            'message' => 'Du lieu trang thai RFID khong hop le.'
        ],
        JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT
    );
    exit;
}

echo json_encode(
    [
        'success' => true,
        'status' => 'rfid_received',
        'data' => $statusData
    ],
    JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT
);
