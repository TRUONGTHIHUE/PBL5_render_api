<?php
declare(strict_types=1);

/*
 * Endpoint co dinh cho STORE002.
 * Chap nhan Product ID tu GET hoac POST, sau do tai su dung product.php.
 */
$_GET['id'] = 'STORE002';
$_GET['product'] = $_GET['product'] ?? $_POST['product'] ?? '';

require __DIR__ . DIRECTORY_SEPARATOR . 'product.php';
