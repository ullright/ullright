<?php
/**
 * This interface specifies the functionality of an ullVault object
 * which stores cryptographic keys and allows retrieval.
 * 
 * TODO: Add function specifications for key history and revocation list.
 */
interface UllVaultInterface
{
  /**
   * Returns the cryptographic key ring, i.e. an associative
   * array with two keys: mainKey and hashKey.
   * 
   * @param int $mainKeySize the main key gets cut to this size
   * @param int $hashKeySize the hash key gets cut to this size
   */
  public static function getCryptographyKeys($mainKeySize, $hashKeySize);
}