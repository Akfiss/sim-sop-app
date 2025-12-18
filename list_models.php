<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Http;

$apiKey = env('GOOGLE_AI_API_KEY');

if (empty($apiKey)) {
    echo "API Key missing in .env";
    exit;
}

echo "Using API Key: " . substr($apiKey, 0, 5) . "...\n";

$url = "https://generativelanguage.googleapis.com/v1beta/models?key={$apiKey}";

$response = Http::get($url);

if ($response->successful()) {
    $models = $response->json()['models'] ?? [];
    echo "Available Models:\n";
    foreach ($models as $model) {
        if (in_array('generateContent', $model['supportedGenerationMethods'])) {
            echo "- " . $model['name'] . " (Version: " . $model['version'] . ")\n";
        }
    }
} else {
    echo "Error: " . $response->status() . "\n";
    echo $response->body();
}
