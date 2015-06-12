<?php

Autoloader::add_core_namespace('Totp');

Autoloader::add_classes(array(
	'Totp\\Totp' => __DIR__ . '/classes/totp.php',
	'Totp\\TotpException' => __DIR__ . '/classes/totp.php',
	'Totp\\Totp\\OptInterface'	=> __DIR__.'classes/src/OptInterface.php',
	'Totp\\Totp\\Opt'	=> __DIR__.'classes/src/Opt.php',
	'Totp\\Totp\\GoogleAuthenticator'	=> __DIR__.'classes/src/GoogleAuthenticator.php',
	'Totp\\Totp\\Base32'	=> __DIR__.'classes/src/Base32.php',

));
