<?php
session_start();
require 'config.php';
require 'vendor/autoload.php';

use GuzzleHttp\Client;

if (!isset($_SESSION['access_token']) || !isset($_SESSION['shop'])) {
    die("Error: Not authenticated. Please install the app again.");
}

$access_token = $_SESSION['access_token'];
$shop = $_SESSION['shop'];

$client = new Client();
$response = $client->put("https://$shop/admin/api/2023-01/payment_gateways.json", [
    'headers' => [
        'X-Shopify-Access-Token' => $access_token,
        'Content-Type' => 'application/json'
    ],
    'json' => [
        'payment_gateway' => [
            'name' => "New Payment Name"
        ]
    ]
]);

$body = json_decode($response->getBody(), true);
echo "✅ Payment method name updated successfully!";
?>
