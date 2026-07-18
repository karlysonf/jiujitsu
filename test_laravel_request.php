<?php
require 'vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$referenceData = [
    ['id' => 1, 'image_base64' => base64_encode(file_get_contents('public/favicon.ico'))]
];
$response = \Illuminate\Support\Facades\Http::attach(
    'group_photo', file_get_contents('public/favicon.ico'), 'test.jpg'
)->post('http://127.0.0.1:8002/recognize', [
    'reference_data' => json_encode($referenceData)
]);
echo "Status: " . $response->status() . "\n";
echo "Body: " . $response->body() . "\n";
