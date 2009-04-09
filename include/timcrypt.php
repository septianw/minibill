<?php
/*
*
* Encrypt a querystring and return a string suitable for _GET transfer
* Author: Tim Crider <tim@g33x.com>
*/

function packageKrypt($dat=false, $key=false) {
   if (!isset($dat))
      return false;

   if (!isset($key))
      return false;

   $dat    = base64_encode(serialize($dat));
   $iv     = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND);
   $eDat   = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $dat, MCRYPT_MODE_ECB, $iv);
   return base64_encode($eDat);
}

/*
*
* Decrypt a querystring suitable for _GET transfer and return the data
*
*/

function unpackageKrypt($dat=false, $key=false) {
   if (!isset($dat))
      return false;

   if (!isset($key))
      return false;

   $dat = base64_decode($dat);
   $iv  = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND);
   return unserialize(base64_decode(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $dat, MCRYPT_MODE_ECB, $iv)));
}

?>
