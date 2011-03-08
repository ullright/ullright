<?php

/**
 * ullValidatorEmail uses Swift Mailer to check the validity of
 * given email addresses. sfValidatorEmail uses a regex which does
 * not detect invalid addresses (according to RFC 2822) like:
 * 
 * .example@example.com
 * example.@example.com
 * rotkäppchen@example.com
 * e,example@example.com
 * 
 * Also, sfValidatorEmail does not accept e.g. 'admin@localhost',
 * which is a valid address.
 */
class ullValidatorEmail extends sfValidatorString
{
  protected function doClean($value)
  {
    $verifier = new ullEmailVerifier();
    
    if ($verifier->checkAddress($value))
    {
      return $value;
    }
    
    throw new sfValidatorError($this, 'invalid', array('value' => $value));  
  }
}

/**
 * This class checks email addresses for validity according
 * to RFC 2822.
 */
class ullEmailVerifier extends Swift_Mime_Headers_MailboxHeader
{
  public function __construct()
  {
    $this->initializeGrammar(); 
  }
  
  /**
   * Checks an address to comply with RFC 2822.
   * 
   * @param string $address an email address
   * @return boolean true if valid, false otherwise
   */
  public function checkAddress($address)
  {
    return preg_match('/^' . $this->getGrammar('addr-spec') . '$/D', $address);
  }
}