<?php
session_start();
require 'vendor/autoload.php'; // For Guzzle

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

// Get the stored access token and shop domain
if (!isset($_SESSION['access_token']) || !isset($_GET['shop'])) {
    die("Error: Missing required parameters.");
}

$access_token = $_SESSION['access_token'];
$shop = $_GET['shop'];

// Define new payment method name
$new_payment_name = "Custom Payment Name"; // Change this to your desired name

// Shopify API endpoint to update payment method
$url = "https://$shop/admin/api/2023-10/payment_gateways.json";

$client = new Client();

try {
    // Get the list of payment gateways
    $response = $client->get($url, [
        'headers' => [
            'X-Shopify-Access-Token' => $access_token,
            'Content-Type' => 'application/json'
        ]
    ]);

    $body = json_decode($response->getBody(), true);
    
    // Find the gateway to update (e.g., first available gateway)
    if (empty($body['payment_gateways'])) {
        die("No payment gateways found.");
    }

    $gateway_id = $body['payment_gateways'][0]['id']; // Get the first payment gateway ID

    // Update the payment method name
    $update_url = "https://$shop/admin/api/2023-10/payment_gateways/$gateway_id.json";
    $update_response = $client->put($update_url, [
        'headers' => [
            'X-Shopify-Access-Token' => $access_token,
            'Content-Type' => 'application/json'
        ],
        'json' => [
            'payment_gateway' => [
                'id' => $gateway_id,
                'name' => $new_payment_name
            ]
        ]
    ]);

    echo "Payment method name updated successfully to '$new_payment_name'.";

} catch (RequestException $e) {
    echo "Error updating payment method: " . $e->getMessage();
}
?>
