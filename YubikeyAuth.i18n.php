<?php

$messages = array();
$messages['en'] = array(
    'autherror-header' => 'Authentication error',
    
    'OK' => 'The OTP is valid.',
    'BAD_OTP' => 'The one-time-password has an invalid format.',
    'REPLAYED_OTP' => 'The one-time-password has already been used.',
    'BAD_SIGNATURE' => 'The HMAC signature verification of Yubikey authentication server\'s response failed.',
    'MISSING_PARAMETER' =>  'The Yubikey auth request lacks a parameter.',
    'NO_SUCH_CLIENT' => 'The request id does not exist. (Server-side configuration issue)',
    'OPERATION_NOT_ALLOWED' => 'This wiki is not allowed to verify OTPs by the Yubikey authentication server.',
    'BACKEND_ERROR' => 'Unexpected error in authentication server.',
    'NOT_ENOUGH_ANSWERS' => 'Could not get requested number of syncs from Yubikey authentication server.',
    'REPLAYED_REQUEST' => 'The one-time-password has already been used. (request replay)',
    'BAD_NONCE' => 'The authentication request or answer has been tampered with. (bad nonce)',
    'CONNECTION_ERROR' => 'Could not connect to Yubikey authentication server.',
    'OTP_IS_DIFFERENT' =>  'The authentication request or answer has been tampered with. (OTP differs in reply)',
    'OUT_OF_TIME_WINDOW' => 'Timestamp difference with the Yubikey authentication server is bigger than permitted.',
    'SERVER_TIMEOUT' => 'Timeout while waiting for the Yubikey authentication server.',
);
