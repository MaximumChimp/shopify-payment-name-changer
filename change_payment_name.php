<?php
// Include Composer autoloader
require 'vendor/autoload.php';

use GuzzleHttp\Client;
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Get the shop and access token (either from session or database)
$shop = $_GET['shop'];
$accessToken = $_SESSION['access_token'];

// Shopify Admin API endpoint to get payment methods
$client = new Client();
$response = $client->get("https://{$shop}/admin/api/2023-01/payment_gateways.json", [
    'headers' => [
        'X-Shopify-Access-Token' => $accessToken,
    ],
]);

$paymentMethods = json_decode($response->getBody()->getContents(), true);

// For demonstration, let's change the name of the first payment method
$paymentMethodId = $paymentMethods['payment_gateways'][0]['id'];  // Get the ID of the first payment method

$newPaymentMethodName = "New Payment Method Name";  // The new name for the payment method

// Shopify Admin API endpoint to update the payment method name
$response = $client->put("https://{$shop}/admin/api/2023-01/payment_gateways/{$paymentMethodId}.json", [
    'json' => [
        'payment_gateway' => [
            'name' => $newPaymentMethodName,
        ],
    ],
    'headers' => [
        'X-Shopify-Access-Token' => $accessToken,
    ],
]);

// Check if the update was successful
if ($response->getStatusCode() === 200) {
    echo "Payment method name updated successfully!";
} else {
    echo "Failed to update payment method name.";
}
?>
