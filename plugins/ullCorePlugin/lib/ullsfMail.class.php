<?php

/**
 * extension of sfMail
 * 
 * adds functionality to reroute emails to the developer's email address for
 * the dev environment
 * 
 * adds functionality to send a bcc copy of every email to the developers email
 * adress
 * 
 * allows giving a UllEntity as address
 *
 */
class ullsfMail extends sfMail
{
  
  protected
    $to = array(),  // temp storage, since phpmailer offers no methods to get the addresses
    $cc = array(),  // temp storage, since phpmailer offers no methods to get the addresses
    $bcc = array(), // temp storage, since phpmailer offers no methods to get the addresses
    $reroute_flag = true,
    $reroute_to = array(),
    $reroute_cc = array(),
    $reroute_bcc = array()
  ;
  
  /**
   * Constructor
   * 
   * Initialisation / set defaults
   * 
   * Turn of dev-env rerouting functionality for the production environment
   *
   */
  public function __construct() 
  {
    parent::__construct();
    
    $this->setCharset(sfConfig::get('app_mailing_charset', 'utf-8'));
    $this->setMailer(sfConfig::get('app_mailing_mailer', 'sendmail'));    
    $this->setHostname(sfConfig::get('app_mailing_smtp_hostname'));
    $this->setUsername(sfConfig::get('app_mailing_smtp_username'));
    $this->setPassword(sfConfig::get('app_mailing_smtp_password'));
    
    // reroute mails except in the production environment
    if (!sfConfig::get('app_mailing_reroute', true)) 
    {      
      $this->reroute_flag = false;
    }
    
  }
  
  /**
   * @see sfMail
   * 
   * adds dev-env rerouting
   *
   * @param mixed $address  UllEntity or string
   * @param string $name
   */
  public function addAddress($address, $name = null)
  {
    if ($address instanceof UllEntity)
    {
      $entityAddresses = $this->getUllEntityEmail($address);
      
      foreach ($entityAddresses as $entityAddress)
      {
        $this->addAddress($entityAddress['email'], $entityAddress['name']);
      }
    }
    else 
    {
      if ($name == null) 
      {
        list($address, $name) = $this->splitAddress($address);
      }
      
      $this->to[$address] = $name;
      
      //handle non-prod rerouting
      if ($this->reroute_flag) 
      {
        $this->mailer->AddAddress(sfConfig::get('app_mailing_debug_address', 'me@example.com'));
        $this->reroute_to[] = $address; 
      } 
      else 
      {
        $this->mailer->AddAddress($address, $name);
      }
    }
  }

  /**
   * @see sfMail
   * 
   * adds dev-env rerouting
   *
   * @param mixed $address  UllEntity or string
   * @param string $name
   */
  public function addCc($address, $name = null)
  {
    if ($address instanceof UllEntity)
    {
      $entityAddresses = $this->getUllEntityEmail($address);
      
      foreach ($entityAddresses as $entityAddress)
      {
        $this->addCc($entityAddress['email'], $entityAddress['name']);
      }
    }
    else 
    {    
      if ($name == null) 
      {
        list($address, $name) = $this->splitAddress($address);
      }
      
      $this->cc[$address] = $name;
      
      //handle non-prod rerouting    
      if ($this->reroute_flag) 
      {
  //      $this->mailer->AddCc(sfConfig::get('app_mailing_debug_address', 'me@example.com'));
        $this->reroute_to[] = $address; 
      } 
      else 
      {
        $this->mailer->AddCc($address, $name);
      }
    }
  }

  /**
   * @see sfMail
   * 
   * adds dev-env rerouting
   *
   * @param mixed $address  UllEntity or string
   * @param string $name
   */  
  public function addBcc($address, $name = null)
  {
    if ($address instanceof UllEntity)
    {
      $entityAddresses = $this->getUllEntityEmail($address);
      
      foreach ($entityAddresses as $entityAddress)
      {
        $this->addBcc($entityAddress['email'], $entityAddress['name']);
      }
    }
    else 
    {    
      if ($name == null) 
      {
        list($address, $name) = $this->splitAddress($address);
      }
      
      $this->bcc[$address] = $name;
      
      //handle non-prod rerouting    
      if ($this->reroute_flag) 
      {
        $this->mailer->AddBcc(sfConfig::get('app_mailing_debug_address', 'me@example.com'));
        $this->reroute_to[] = $address; 
      } 
      else 
      {
        $this->mailer->AddBcc($address, $name);
      }
    }
  }
  
  /**
   * get to addresses
   *
   * @return array
   */
  public function getAddresses()
  {
    return $this->to;
  }
  
  /**
   * get cc addresses
   *
   * @return array
   */
  public function getCcs()
  {
    return $this->cc;
  }  
  
  /**
   * get bcc addresses
   *
   * @return array
   */
  public function getBccs()
  {
    return $this->bcc;
  }  
  
  /**
   * check if any 'to' addresses have been supplied
   *
   * @return boolean
   */
  public function hasAddresses() 
  {
    if ($this->to) 
    {
      return true;
    }
  }
  
  /**
   * prepares for sending
   * 
   * normally empty -> used for testing
   *
   */
  public function prepare() {}
  
  /**
   * send email
   *
   */
  public function send() 
  {
    $this->prepare();
    
    // generally disable mailing for certain environments
    if (!sfConfig::get('app_mailing_enable', false))
    {
      return true;
    }
    
    if ($this->reroute_flag) 
    {
      $reroute_info = 'Original to: ';
      $reroute_info .= implode(', ',  $this->reroute_to) . "\n";
      
      if ($this->reroute_cc) 
      {  
        $reroute_info = 'Original cc: ';
        $reroute_info .= implode(', ',  $this->reroute_cc) . "\n";
      }
      
      if ($this->reroute_bcc) 
      {
        $reroute_info = 'Original bcc: ';
        $reroute_info .= implode(', ',  $this->reroute_bcc) . "\n";
      }
      
      $reroute_info .= "\n";
      $this->setBody($reroute_info . $this->getBody());
    } 
    else 
    {
      // send copy to debugger's email if configured
      if (sfConfig::get('app_mailing_send_debug_cc', false)) 
      {
        $this->mailer->AddBcc(sfConfig::get('app_mailing_debug_address', 'me@example.com'));
      }
    }
    
    // check for 'to' addresses
    if ($this->hasAddresses()) 
    {
      if (!$this->mailer->Send())
      {
        throw new sfException($this->mailer->ErrorInfo);
      }
    }
  }

  
  /**
   * @see sfMail
   *
   * has to be redeclared here, since it is private in sfMail (@#!"?*+)
   * 
   * @param string $address
   * @return array
   */
  protected function splitAddress($address)
  {
    if (preg_match('/^(.+)\s<(.+?)>$/', $address, $matches))
    {
      return array($matches[2], $matches[1]);
    }
    else
    {
      return array($address, '');
    }
  }  
  

  /**
   * get array of email addresses and names for the addXxx methods
   * 
   * supports UllGroups without group email address -> returns all addresses of its members
   *
   * @param UllEntity $entity
   * @return array array(array('email' => 'me@example.com', 'name' => 'me'), ...)
   */
  protected function getUllEntityEmail(UllEntity $entity)
  {
    $return = array();
    
    // if no group email -> get list of users and send to their email addresses
    if ($entity instanceof UllGroup && !$entity->email) 
    {
      foreach ($entity->UllUser as $user) 
      {
        if (($email = $user->email) && $user->isActive()) 
        {
          $return[] = array('email' => $email, 'name' => (string) $user);
        }
      }
    } 
    else 
    {
      if ($email = $entity->email) 
      {
        $return[] = array('email' => $email, 'name' => (string) $entity);
      }       
    }

    return $return;
  }
  
}