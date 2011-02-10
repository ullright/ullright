<?php
/**
 * Provides cryptographic key loading and management.
 * 
 * Currently used to secure url parameters
 * 
 * TODO: Add functions for key history and revocation list.
 */
class ullVault implements UllVaultInterface
{
  protected static $haveToLoadKey = true;
  protected static $key;
  protected static $keySize = 2048; //bytes
  
  /**
   * Loads the main cryptographic key from the security.key file located
   * in the app's config dir. Base64-decodes this key and checks for validity
   * (decoded size = self::$keySize).
   * 
   * @throws Exception If there are problems during the key loading process
   */
  protected static function loadCryptographyKeyFromFile()
  {
    //load key from file
    $key = file_get_contents(sfConfig::get('sf_app_config_dir') . '/security.key');
    if ($key === false)
    {
      throw new Exception('Could not read key for cryptography from file. Generate a valid one with ullright:blub');
    }

    
    //decode key if possible
    $decodedKey = base64_decode($key);
    if ($decodedKey === false)
    {
      throw new Exception('Invalid key for cryptography defined. Generate a new one with ullright:blub');
    }
    
    //check for required size
    if (strlen($decodedKey) !== self::$keySize)
    {
      throw new Exception('Key for cryptography is not ' . self::$keySize . ' bytes long. Generate a valid one with ullright:blub');
    }

    self::$key = $decodedKey;
    self::$haveToLoadKey = false;
  }

  /**
   * Returns the cryptographic key ring, i.e. an associative
   * array with two keys: mainKey and hashKey.
   * 
   * @param int $mainKeySize the main key gets cut to this size
   * @param int $hashKeySize the hash key gets cut to this size
   * 
   * @throws UnexpectedValueException if chosen key sizes are invalid
   */
  public static function getCryptographyKeys($mainKeySize, $hashKeySize)
  {
    //first time only: load key from file
    if (self::$haveToLoadKey)
    {
      self::loadCryptographyKeyFromFile();
    }
    
    //are requested key sizes large enough?
    if ($mainKeySize < 32 || $hashKeySize < 32)
    {
      throw new UnexpectedValueException('Requested key size is too small, use 32 bytes or more.');
    }
    
    //but not too large?
    if ($mainKeySize + $hashKeySize > self::$keySize)
    {
      throw new UnexpectedValueException('Requested keys are too large, use 2048 bytes or less.');
    }
    
    $keyRing = array(
    	'mainKey' => substr(self::$key, 0, $mainKeySize),
      'hashKey' => substr(self::$key, $mainKeySize, $hashKeySize)
    );

    return $keyRing;
  }
}