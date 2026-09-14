<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

function sendJson(int $statusCode, array $data): void
{
    http_response_code($statusCode);

    echo json_encode(
        $data,
        JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT
    );

    exit;
}

$storeId = strtoupper(trim($_GET['id'] ?? ''));
$productId = trim($_GET['product'] ?? '');

if ($storeId === '' || $productId === '') {
    sendJson(400, [
        'success' => false,
        'message' => 'Thieu Store ID hoac Product ID.'
    ]);
}

/*
 * Store ID chi duoc chua chu in hoa, so,
 * dau gach ngang va dau gach duoi.
 */
if (!preg_match('/^[A-Z0-9_-]+$/', $storeId)) {
    sendJson(400, [
        'success' => false,
        'message' => 'Store ID khong hop le.'
    ]);
}

/*
 * Barcode MVP chi gom cac chu so.
 */
if (!preg_match('/^[0-9]+$/', $productId)) {
    sendJson(400, [
        'success' => false,
        'message' => 'Product ID khong hop le.'
    ]);
}

/*
 * product.php nam trong thu muc public.
 * CSV nam trong thu muc data.
 */
$csvFile = dirname(__DIR__)
    . DIRECTORY_SEPARATOR
    . 'data'
    . DIRECTORY_SEPARATOR
    . $storeId
    . '.csv';

if (!is_file($csvFile)) {
    sendJson(404, [
        'success' => false,
        'message' => 'Khong tim thay cua hang.',
        'store_id' => $storeId
    ]);
}

$file = fopen($csvFile, 'r');

if ($file === false) {
    sendJson(500, [
        'success' => false,
        'message' => 'Khong the mo file du lieu.'
    ]);
}

/*
 * Doc va bo qua dong tieu de:
 * ProductID,Name,Price,Location,Expiry
 */
$header = fgetcsv($file);

if ($header === false) {
    fclose($file);

    sendJson(500, [
        'success' => false,
        'message' => 'File CSV rong hoac khong hop le.'
    ]);
}

while (($row = fgetcsv($file)) !== false) {
    /*
     * Moi san pham phai co du 5 cot.
     */
    if (count($row) < 5) {
        continue;
    }

    $csvProductId = trim((string) $row[0]);

    if ($csvProductId === $productId) {
        fclose($file);

        sendJson(200, [
            'success' => true,
            'store_id' => $storeId,
            'product_id' => $csvProductId,
            'name' => trim((string) $row[1]),
            'price' => trim((string) $row[2]) . ' VND',
            'location' => trim((string) $row[3]),
            'expiry_date' => trim((string) $row[4])
        ]);
    }
}

fclose($file);

sendJson(404, [
    'success' => false,
    'message' => 'Khong tim thay san pham trong cua hang.',
    'store_id' => $storeId,
    'product_id' => $productId
]);