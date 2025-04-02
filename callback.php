<?php
// Include Composer autoloader
require 'vendor/autoload.php';

use GuzzleHttp\Client;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();


// Set your Shopify app credentials
$shopifyApiKey = getenv('SHOPIFY_API_KEY');
$shopifyApiSecret = getenv('SHOPIFY_API_SECRET');
$redirectUri = 'https://your-app-name.herokuapp.com/callback';  // The URL Shopify will send the user back to after authorization

if (isset($_GET['code']) && isset($_GET['shop'])) {
    $shop = $_GET['shop'];
    $code = $_GET['code'];

    // Exchange the code for an access token
    $client = new Client();
    $response = $client->post("https://{$shop}/admin/oauth/access_token", [
        'form_params' => [
            'client_id' => $shopifyApiKey,
            'client_secret' => $shopifyApiSecret,
            'code' => $code,
        ],
    ]);

    $data = json_decode($response->getBody()->getContents(), true);
    $accessToken = $data['access_token'];

    // Store the access token in session or database for future use
    $_SESSION['access_token'] = $accessToken;

    // Redirect the user to the page where they can change the payment method name
    header("Location: /change_payment_method.php?shop={$shop}");
    exit;
}
?>
