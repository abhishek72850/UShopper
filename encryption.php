<?php

	require_once('encryptvendor/autoload.php');

	class Encryption
	{
		
		function __construct($argument)
		{
			
		}

		public static function encrypt($data){

		}

		public static function decrypt($data){

		}
	}
	$protected_key = Key::createNewRandomKey();
    $key_encoded = Key::loadFromAsciiSafeString($protected_key);
    echo $key_encoded;
?>