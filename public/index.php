<?php

header('Content-Type: application/json; charset=utf-8');

echo json_encode(
    [
        'success' => true,
        'service' => 'PBL5 Product API',
        'message' => 'Server is running'
    ],
    JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT
);