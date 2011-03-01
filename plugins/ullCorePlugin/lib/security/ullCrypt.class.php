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
  //Note: changes to the cryptographic configuration below
  //will result in the invalidation of any content encrypted
  //up to now.
  
  //main encryption (privacy)
  //we use Rijndael (=AES); which uses a key and IV
  //size of 256 bit (set via mcrypt later on)
  protected $cipher = 'rijndael-256';
  protected $keySize;
  protected $ivSize;
  
  //secure hashing (authentication and integrity)
  //RIPEMD-160 produces a 160 bit (= 20 byte) digest; key size
  //is arbitrary, so we define 256 bit (= 32 byte)
  protected $hashAlgo = 'ripemd160';
  protected $hashSize = 20;
  protected $hashKeySize = 32;
  
  protected $mainKey;
  protected $hashKey;
  protected $cryptModule;

  //child classes should use ::getInstance()
  private static $cryptInstance;

  /**
   * Returns a new instance of the ullCrypt class.
   * Initializes the cryptographic module for the configured cipher
   * and determines IV and key size.
   * Also retrieves the key ring with matching key sizes from the ullVault.
   */
  protected function __construct()
  {
    //open crypto module based on chosen cipher and mode
    $this->cryptModule = mcrypt_module_open($this->cipher, '', 'cbc', '');

    //for rijndael-256, IV and key size is 32 byte
    $this->ivSize = mcrypt_enc_get_iv_size($this->cryptModule);
    $this->keySize = mcrypt_enc_get_key_size($this->cryptModule);
    
    //retrieve en/decryption and hash keys from vault with correct size for chosen cipher
    $keyRing = ullVault::getCryptographyKeys($this->keySize, $this->hashKeySize);
    $this->mainKey = $keyRing['mainKey'];
    $this->hashKey = $keyRing['hashKey'];
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
   * A randomly chosen IV is prepended and a HMAC (hash-based
   * message authentication code) is appended.
   * 
   * @param mixed $cleartext
   * @return string ciphertext for $cleartext - non-printable
   */
  public function encrypt($cleartext)
  {
    //create random IV
    $iv = mcrypt_create_iv($this->ivSize, MCRYPT_RAND);
    //init crypt. module with key and IV
    mcrypt_generic_init($this->cryptModule, $this->mainKey, $iv);

    //encrypt cleartext and prepend IV
    $ciphertext = mcrypt_generic($this->cryptModule, $cleartext);
    $ciphertext = $iv . $ciphertext;
    
    //generate hmac and append to message (EtA, encrypt-then-authenticate))
    $hmac = hash_hmac('ripemd160', $ciphertext, $this->hashKey, true);
    $ciphertext .= $hmac;

    mcrypt_generic_deinit($this->cryptModule);

    return $ciphertext;
  }
  
/**
   * Encrypts an arbitrary variable, usually a string.
   * Returns a string with the ciphertext (base64 encodiert).
   * 
   * @param mixed $cleartext
   * @return string ciphertext for $cleartext - base64 encodiert
   */
  public function encryptBase64($cleartext)
  {
    $ciphertext = $this->encrypt($cleartext);
    $base64Ciphertext = base64_encode($ciphertext);

    return $base64Ciphertext;
  }

  /**
   * Decrypts a given ciphertext and returns the cleartext representation.
   * Only works if the IV which was used during encryption is prepended to
   * the ciphertext and a valid HMAC is appended.
   * 
   * @param string $ciphertext
   * @return string cleartext for $ciphertext
   * 
   * @throws UnexpectedValueException if the ciphertext is too small
   * @throws ullSecurityNotGenuineException if the HMAC is invalid (usually caused by tampering)
   */
  public function decrypt($ciphertext)
  {
    //the ciphertext length has to be at least ivSize + hashSize + 1
    if (strlen($ciphertext) < ($this->ivSize + $this->hashSize + 1))
    {
      throw new UnexpectedValueException('Ciphertext is too small.');
    }

    //extract and remove hmac from end of ciphertext
    $hmacGiven = substr($ciphertext, (-1) * $this->hashSize);
    $ciphertext = substr($ciphertext, 0, (-1) * $this->hashSize);
    
    //generate real hmac ...
    $hmacReal = hash_hmac('ripemd160', $ciphertext, $this->hashKey, true);
    //... and compare with given one for authentication
    if ($hmacGiven !== $hmacReal)
    {
      throw new ullSecurityNotGenuineException('Invalid HMAC received.');
    }
    
    //extract IV from ciphertext
    $iv = substr($ciphertext, 0, $this->ivSize);
    //init crypt. module with key and IV
    mcrypt_generic_init($this->cryptModule, $this->mainKey, $iv);

    //remove IV from ciphertext and decrypt
    $ciphertext = substr($ciphertext, $this->ivSize);
    $cleartext = mdecrypt_generic($this->cryptModule, $ciphertext);
    
    //right trim zero-padding (caused by CBC mode)
    $cleartext = rtrim($cleartext, "\0");
    
    mcrypt_generic_deinit($this->cryptModule);
    
    return $cleartext;
  }
  
  /**
   * Decrypts a given base64 encoded ciphertext and returns the cleartext representation.
   * Only works if the IV which was used during encryption is prepended to
   * the ciphertext and a valid HMAC is appended.
   * 
   * @param string base64 encoded ciphertext
   * @return string cleartext for $base64Ciphertext
   * 
   * @throws UnexpectedValueException if the ciphertext is too small
   * @throws ullSecurityNotGenuineException if the HMAC is invalid (usually caused by tampering)
   */
  public function decryptBase64($base64Ciphertext)
  {
    $ciphertext = base64_decode($base64Ciphertext);
    
    return $this->decrypt($ciphertext);
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

/**
 * Used for indicating a trust issue encountered during decrypting,
 * such as an invalid HMAC
 */
class ullSecurityNotGenuineException extends Exception { }