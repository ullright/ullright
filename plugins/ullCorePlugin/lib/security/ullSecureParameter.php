<?php

/**
 * This class provides functionality for secure parameters, i.e.
 * parameters which names are marked by a specifiy prefix
 * and which values are encrypted.
 * 
 * @author martin
 *
 */
class ullSecureParameter
{
  //the prefix which marks secure parameters
  public static $secureParamIdentifier = 's_';
  
  /**
   * Determines if a parameter is a secure parameter,
   * i.e. if its value should be encrypted or decrypted.
   * 
   * @param string $parameterName
   */
  public static function isSecureParameter($parameterName)
  {
    return (strpos($parameterName, self::$secureParamIdentifier) === 0);
  }
  
  /**
   * Encrypts a parameter value using the global ullCrypt instance
   * and base64-encodes it url-safe.
   * 
   * @param string $parameterValue
   */
  public static function encryptParameter($parameterValue)
  {
    $crypt = ullCrypt::getInstance();
    $encryptedParameter = $crypt->encrypt($parameterValue);
    
    return ullCoreTools::base64_encode_urlsafe($encryptedParameter);
  }

  /**
   * Decrypts a parameter value using the global ullCrypt instance
   * after base64-decoding it.
   * 
   * @param string $parameterValue
   */
  public static function decryptParameter($parameterValue)
  {
    $crypt = ullCrypt::getInstance();
    $encryptedParameter = ullCoreTools::base64_decode_urlsafe($parameterValue);
    
    return $crypt->decrypt($encryptedParameter);
  }
}