<?php
session_start();
require 'vendor/autoload.php'; // For Guzzle

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

$SHOPIFY_API_KEY = getenv('SHOPIFY_API_KEY');
$SHOPIFY_API_SECRET = getenv('SHOPIFY_API_SECRET');
$APP_URL = getenv('SHOPIFY_APP_URL'); // Heroku or Vercel URL

if (!isset($_GET['shop'])) {
    die("Error: 'shop' parameter is required.");
}

$shop = $_GET['shop'];
$scopes = "read_orders,write_payment_gateways";
$redirect_uri = "$APP_URL/auth/callback";

if (!isset($_GET['code'])) {
    die("Error: No code received from Shopify.");
}

$code = $_GET['code'];

// Step 1: Exchange authorization code for access token
$client = new Client();

try {
    $response = $client->post("https://$shop/admin/oauth/access_token", [
        'form_params' => [
            'client_id' => $SHOPIFY_API_KEY,
            'client_secret' => $SHOPIFY_API_SECRET,
            'code' => $code
        ]
    ]);

    $body = json_decode($response->getBody(), true);
    $_SESSION['access_token'] = $body['access_token'];

    // Redirect to the next step (e.g., change payment method)
    header("Location: $APP_URL/change-payment?shop=$shop");
    exit();

} catch (RequestException $e) {
    die("Error getting access token: " . $e->getMessage());
}
?>
