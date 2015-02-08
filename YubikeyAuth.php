<?php

// includes:
require_once(__DIR__ . '/YubikeyAuthPlugin.php');
require_once(__DIR__ . '/YubikeyAuthError.php');

$wgExtensionCredits['other'][] = array(
    'path' => __file__,
    'name' => 'YubikeyAuth',
    'version' => '1.0',
    'author' =>'Tilo Eckert',
    'url' => '',
    'description' => 'Yubikey OTP authentication',
);


$wgExtensionMessagesFiles['YubikeyAuth'] = dirname( __FILE__ ) . '/YubikeyAuth.i18n.php';

?>
