<?php
session_start();

// Load environment variables (Heroku)
$SHOPIFY_API_KEY = getenv('SHOPIFY_API_KEY');
$SHOPIFY_APP_URL = getenv('SHOPIFY_APP_URL'); // e.g., https://your-app.herokuapp.com

if (!isset($_GET['shop'])) {
    die("Error: 'shop' parameter is required.");
}

// Ensure correct format for the shop domain
$shop = str_replace(["https://", "admin.shopify.com/store/"], "", $_GET['shop']);
$shop = preg_replace("/[^a-zA-Z0-9.-]+/", "", $shop); // Sanitize input
if (!str_ends_with($shop, ".myshopify.com")) {
    $shop .= ".myshopify.com";
}

$scopes = "read_orders,write_payment_gateways";
$redirect_uri = "$SHOPIFY_APP_URL/auth/callback";

// Step 1: Redirect user to Shopify for OAuth authentication
$install_url = "https://$shop/admin/oauth/authorize?"
    . "client_id=$SHOPIFY_API_KEY"
    . "&scope=$scopes"
    . "&redirect_uri=$redirect_uri";

header("Location: $install_url");
exit();
?>
