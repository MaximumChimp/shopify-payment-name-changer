<?php
session_start();
require '../config.php'; // Load API credentials
require '../vendor/autoload.php'; // Load Guzzle for API requests

use GuzzleHttp\Client;

if (!isset($_GET['code']) || !isset($_GET['shop'])) {
    die("Error: Missing 'code' or 'shop' parameter.");
}

$shop = filter_var($_GET['shop'], FILTER_SANITIZE_URL);
$code = $_GET['code'];

$client = new Client();
$response = $client->post("https://$shop/admin/oauth/access_token", [
    'json' => [
        'client_id' => SHOPIFY_API_KEY,
        'client_secret' => SHOPIFY_API_SECRET,
        'code' => $code
    ]
]);

$body = json_decode($response->getBody(), true);

if (isset($body['access_token'])) {
    $_SESSION['access_token'] = $body['access_token'];
    $_SESSION['shop'] = $shop;
    echo "✅ App Installed Successfully! <a href='/update_payment.php'>Update Payment Name</a>";
} else {
    die("Error: Failed to get access token.");
}
?>
