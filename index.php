<?php
session_start();
require 'config.php'; // Load Shopify API credentials

if (!isset($_GET['shop'])) {
    die("Error: 'shop' parameter is required.");
}

// Ensure correct format for shop domain
$shop = filter_var($_GET['shop'], FILTER_SANITIZE_URL);
if (!str_ends_with($shop, ".myshopify.com")) {
    $shop .= ".myshopify.com";
}

$scopes = "read_orders,write_payment_gateways";
$redirect_uri = SHOPIFY_APP_URL . "/auth/callback.php";

$install_url = "https://$shop/admin/oauth/authorize?"
    . "client_id=" . SHOPIFY_API_KEY
    . "&scope=$scopes"
    . "&redirect_uri=$redirect_uri";

header("Location: $install_url");
exit();
?>
