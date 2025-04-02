<?php
session_start();

// Load environment variables (Heroku)
$SHOPIFY_API_KEY = getenv('SHOPIFY_API_KEY');
$SHOPIFY_API_SECRET = getenv('SHOPIFY_API_SECRET');
$SHOPIFY_APP_URL = getenv('SHOPIFY_APP_URL'); // e.g., https://your-app.herokuapp.com

// Check for the 'shop' and 'code' parameters in the query string
if (!isset($_GET['shop']) || !isset($_GET['code'])) {
    die("Error: Missing 'shop' or 'code' parameters.");
}

$shop = $_GET['shop'];
$code = $_GET['code'];

// Step 2: Request an access token from Shopify using the authorization code
$token_url = "https://$shop/admin/oauth/access_token";

// Prepare the POST data for the request
$data = array(
    'client_id' => $SHOPIFY_API_KEY,
    'client_secret' => $SHOPIFY_API_SECRET,
    'code' => $code,
);

// Initialize cURL to request the access token
$ch = curl_init($token_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

// Execute the request and get the response
$response = curl_exec($ch);
curl_close($ch);

// Decode the JSON response
$token_info = json_decode($response, true);

// Check if access token is present
if (isset($token_info['access_token'])) {
    $_SESSION['access_token'] = $token_info['access_token'];
    $_SESSION['shop'] = $shop;

    // Redirect to your app's main page or desired route
    header("Location: /dashboard.php");
    exit();
} else {
    die("Error: Unable to retrieve access token.");
}
?>
