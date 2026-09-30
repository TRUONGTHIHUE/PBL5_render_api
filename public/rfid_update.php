<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

function sendRfidJson(int $statusCode, array $data): void
{
    http_response_code($statusCode);
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    sendRfidJson(405, [
        'success' => false,
        'message' => 'Chi chap nhan HTTP POST.'
    ]);
}

$rawBody = file_get_contents('php://input');
$payload = json_decode($rawBody === false ? '' : $rawBody, true);

if (!is_array($payload)) {
    sendRfidJson(400, [
        'success' => false,
        'message' => 'Du lieu JSON khong hop le.'
    ]);
}

$uid = strtoupper(trim((string) ($payload['uid'] ?? '')));
$storeId = strtoupper(trim((string) ($payload['store_id'] ?? '')));
$wifiSsid = trim((string) ($payload['wifi_ssid'] ?? ''));
$hostUrl = trim((string) ($payload['host_url'] ?? ''));
$wifiStatus = trim((string) ($payload['wifi_status'] ?? ''));

$allowedStores = ['STORE001', 'STORE002', 'STORE003'];

if (!preg_match('/^(?:[0-9A-F]{2} ){3}[0-9A-F]{2}$/', $uid)) {
    sendRfidJson(400, [
        'success' => false,
        'message' => 'UID khong hop le.'
    ]);
}

if (!in_array($storeId, $allowedStores, true)) {
    sendRfidJson(400, [
        'success' => false,
        'message' => 'Store ID khong hop le.'
    ]);
}

if ($wifiSsid === '' || strlen($wifiSsid) > 32) {
    sendRfidJson(400, [
        'success' => false,
        'message' => 'Wi-Fi SSID khong hop le.'
    ]);
}

if (filter_var($hostUrl, FILTER_VALIDATE_URL) === false) {
    sendRfidJson(400, [
        'success' => false,
        'message' => 'Host URL khong hop le.'
    ]);
}

if ($wifiStatus === '' || strlen($wifiStatus) > 50) {
    sendRfidJson(400, [
        'success' => false,
        'message' => 'Trang thai Wi-Fi khong hop le.'
    ]);
}

$statusData = [
    'uid' => $uid,
    'store_id' => $storeId,
    'wifi_ssid' => $wifiSsid,
    'host_url' => $hostUrl,
    'wifi_status' => $wifiStatus,
    'updated_at_utc' => gmdate('c')
];

$statusFile = dirname(__DIR__)
    . DIRECTORY_SEPARATOR . 'data'
    . DIRECTORY_SEPARATOR . 'current_store.json';

$encoded = json_encode(
    $statusData,
    JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT
);

if ($encoded === false || file_put_contents($statusFile, $encoded, LOCK_EX) === false) {
    sendRfidJson(500, [
        'success' => false,
        'message' => 'Khong luu duoc trang thai RFID.'
    ]);
}

sendRfidJson(200, [
    'success' => true,
    'message' => 'Da cap nhat cua hang vua quet.',
    'data' => $statusData
]);
