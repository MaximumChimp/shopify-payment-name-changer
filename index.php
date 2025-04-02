<?php
// Set your Shopify app credentials
require 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();


$shopifyApiKey = getenv('SHOPIFY_API_KEY');  // Set this as an environment variable
$shopifyApiSecret = getenv('SHOPIFY_API_SECRET');  // Set this as an environment variable
$redirectUri = 'https://shopify-payment-name-changer-a2a1c1878c63.herokuapp.com/callback';  // The URL Shopify will send the user back to after authorization

if (isset($_GET['shop'])) {
    $shop = $_GET['shop'];
    $scopes = 'read_payment_methods,write_payment_methods'; // Required OAuth scopes

    // Shopify OAuth URL
    $oauthUrl = "https://{$shop}/admin/oauth/authorize?client_id={$shopifyApiKey}&scope={$scopes}&redirect_uri={$redirectUri}";

    // Redirect to Shopify for OAuth authentication
    header("Location: $oauthUrl");
    exit;
}
?>
