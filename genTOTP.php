<?php
require_once("GoogleAuthenticator.php");

$name = "";
while ($name == "") {
	print ("Provide a familiar name for this key; like 'mytrackapp': ");
	$name = trim(fgets(STDIN));
}
$g = new GoogleAuthenticator();
$newSecret = $g->createSecret();
printf("Secret Code: %s\n", $newSecret);
$gUrl = $g->getQRCodeGoogleUrl($name, $newSecret);
printf("URL to generate QR code: %s\n", $gUrl);
printf("Local URL: otpauth://totp/%s?secret=%s\n", $name, $newSecret);
?>
