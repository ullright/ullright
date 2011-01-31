<?php
/**
 * Provides cryptographic key loading and management.
 * 
 * TODO: Add functions for key history and revocation list.
 */
class ullVault implements UllVaultInterface
{
  protected static $haveToLoadKey = true;
  protected static $key;

  /**
   * Loads the main cryptographic key from the security.key file located
   * in the app's config dir. Base64-decodes this key and checks for validity
   * (minimum size 32 bytes).
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
    
    //check for minimum size
    if (strlen($decodedKey) < 32)
    {
      throw new Exception('Key for cryptography is too small. Generate a valid one with ullright:blub');
    }

    self::$key = $decodedKey;
    self::$haveToLoadKey = false;
  }

 /**
   * Returns the main cryptographic key.
   * 
   * @param int $maxSize if set and > 0, the returned key is cut to this size.
   */
  public static function getCryptographyKey($maxSize = 0)
  {
    //first time only: load key from file
    if (self::$haveToLoadKey)
    {
      self::loadCryptographyKeyFromFile();
    }
    
    //was a truncated key requested?
    if ((int)$maxSize > 0)
    {
      return substr(self::$key, 0, $maxSize);
    }

    return self::$key;
  }
}