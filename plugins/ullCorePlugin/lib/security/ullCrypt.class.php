<?php

/**
 * This singleton class provides encryption and decryption
 * functionality. Uses keys provided by the UllVault class.
 * 
 * Based on Andrew Johnson's work:
 * http://www.itnewb.com/v/PHP-Encryption-Decryption-Using-the-MCrypt-Library-libmcrypt 
 */
class ullCrypt
{
  //Note: If the cipher or IV size is changed, everything
  //encrypted up to now will not decrypt correctly anymore!
  
  //we use Rijndael (=AES) with a key size of 256 bit
  protected $cipher = 'rijndael-256';
  protected $ivSize;
  protected $keySize;
  protected $key;
  protected $cryptModule;

  //child classes should use ::getInstance()
  private static $cryptInstance;

  /**
   * Returns a new instance of the ullCrypt class.
   * Initializes the cryptographic module for the configured cipher
   * and determines IV and key size.
   * Also retrieves a key with a matching size from the ullVault.
   */
  protected function __construct()
  {
    //open crypto module based on chosen cipher and mode
    $this->cryptModule = mcrypt_module_open($this->cipher, '', 'ctr', '');

    //for rijndael-256, IV and key size is 32 byte
    $this->ivSize = mcrypt_enc_get_iv_size($this->cryptModule);
    $this->keySize = mcrypt_enc_get_key_size($this->cryptModule);
    
    //retrieve en/decryption key from vault with correct size for chosen cipher
    $this->key = ullVault::getCryptographyKey($this->keySize);
  }

  /**
   * Called on de-initialization of this object, usually at end of request.
   * Closes the used cryptographic module.
   */
  public function __destruct() {
    //close crypt module
    mcrypt_module_close($this->cryptModule);
  }

  /**
   * Encrypts an arbitrary variable, usually a string.
   * Returns a non-printable (use binary-to-text encoding) encrypted
   * representation (the ciphertext) for the given input.
   * A randomly chosen IV is prepended.
   * 
   * @param mixed $cleartext
   * @return string ciphertext for $cleartext - non-printable
   */
  public function encrypt($cleartext)
  {
    //create random IV
    $iv = mcrypt_create_iv($this->ivSize, MCRYPT_RAND);
    //init crypt. module with key and IV
    mcrypt_generic_init($this->cryptModule, $this->key, $iv);

    //encrypt cleartext and prepend IV
    $ciphertext = mcrypt_generic($this->cryptModule, $cleartext);
    $ciphertext = $iv . $ciphertext;

    mcrypt_generic_deinit($this->cryptModule);

    return $ciphertext;
  }

  /**
   * Decrypts a given ciphertext and returns the cleartext representation.
   * Only works if the IV which was used during encryption is prepended to
   * the ciphertext.
   * 
   * @param string $ciphertext
   * @return string cleartext for $ciphertext 
   */
  public function decrypt($ciphertext)
  {
    //extract IV from ciphertext
    $iv = substr($ciphertext, 0, $this->ivSize);
    //init crypt. module with key and IV
    mcrypt_generic_init($this->cryptModule, $this->key, $iv);

    //remove IV from ciphertext and decrypt
    $ciphertext = substr($ciphertext, $this->ivSize);
    $cleartext = mdecrypt_generic($this->cryptModule, $ciphertext);
     
    mcrypt_generic_deinit($this->cryptModule);
    
    return $cleartext;
  }

  /**
   * Returns the global ullCrypt instance.
   */
  public static function getInstance()
  {
    if (!isset(self::$cryptInstance))
    {
      self::$cryptInstance = new ullCrypt();
    }

    return self::$cryptInstance;
  }
}