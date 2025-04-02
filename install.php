<?php
$shop = $_GET['shop'];
$clientId = "9371a5c98b94e496b4299b96c11d4904";
$scopes = "read_payments,write_payments";
$redirectUri = "https://3ce2b960bab877c88e9f250210392079.serveo.net/callback.php"; // Ensure it matches Shopify

$installUrl = "https://$shop/admin/oauth/authorize?client_id=$clientId&scope=$scopes&redirect_uri=" . urlencode($redirectUri);

header("Location: $installUrl");
exit;
?>
