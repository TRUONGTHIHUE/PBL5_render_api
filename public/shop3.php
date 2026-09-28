<?php
declare(strict_types=1);

/*
 * Endpoint co dinh cho STORE003.
 * Chap nhan Product ID tu GET hoac POST, sau do tai su dung product.php.
 */
$_GET['id'] = 'STORE003';
$_GET['product'] = $_GET['product'] ?? $_POST['product'] ?? '';

require __DIR__ . DIRECTORY_SEPARATOR . 'product.php';
