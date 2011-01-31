<?php
/**
 * This interface specifies the functionality of an ullVault object
 * which stores cryptographic keys.
 * 
 * TODO: Add function specifications for key history and revocation list.
 */
interface UllVaultInterface
{
  /**
   * Returns the main cryptographic key.
   * 
   * @param int $maxSize if set and > 0, the returned key is cut to this size.
   */
  public static function getCryptographyKey($maxSize = 0);
}