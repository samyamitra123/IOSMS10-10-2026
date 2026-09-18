<?php
class cryptography{
	
	//function to encrypt the string
	//for get and post
	function encode($str, $num)
	{
	  for($i=0; $i<$num;$i++)
	  {
		$str=strrev(base64_encode($str)); //apply base64 first and then reverse the string
	  }
	  return $str;
	}
	
	//function to decrypt the string
	function decode($str, $num)
	{
	  for($i=0; $i<$num;$i++)
	  {
		$str=base64_decode(strrev($str)); //reverse the string first and then apply base64 
	  }
	  return $str;
	}
	
	//do not use in get and post
	function encrypt_text($value)
	{
	   if(!$value) return false;
	 
	   $crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, 'SECURE_STRING_1', $value, MCRYPT_MODE_ECB, 'SECURE_STRING_2');
	   return trim(base64_encode($crypttext));
	}
	 
	function decrypt_text($value)
	{
	   if(!$value) return false;
	 
	   $crypttext = base64_decode($value);
	   $decrypttext = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, 'SECURE_STRING_1', $crypttext, MCRYPT_MODE_ECB, 'SECURE_STRING_2');
	   return trim($decrypttext);
	}
}
?>
