<?php

class ullsfMail extends sfMail
{
  
  protected
    $reroute_flag = true
    , $reroute_to = array()
    , $reroute_cc = array()
    , $reroute_bcc = array()
    , $to = array() // temp storage for 'to' recipients
  ;
  
  public function initialize() {
    
    $this->setCharset(sfConfig::get('app_mailing_charset', 'utf-8'));
    $this->setMailer(sfConfig::get('app_mailing_mailer', 'sendmail'));    
    $this->setHostname(sfConfig::get('app_mailing_smtp_hostname'));
    $this->setUsername(sfConfig::get('app_mailing_smtp_username'));
    $this->setPassword(sfConfig::get('app_mailing_smtp_password'));
    
    // reroute mails except in the productive environment
    if (sfConfig::get('sf_environment') == 'prod') {      
      $this->reroute_flag = false;
    }
   
  }
  
  
  public function addAddress($address, $name = null)
  {
    if ($name == null) {
      list($address, $name) = $this->splitAddress($address);
    }
    
    //handle non-prod rerouting
    if ($this->reroute_flag) {
      $this->AddAddressTempStore(sfConfig::get('app_mailing_debug_address', 'me@example.com'));
      
      $this->reroute_to[] = $address; 
      
    } else {
      $this->AddAddressTempStore($address, $name);
    }
  }

  public function addCc($address, $name = null)
  {
    if ($name == null) {
      list($address, $name) = $this->splitAddress($address);
    }
    
    //handle non-prod rerouting    
    if ($this->reroute_flag) {
      $this->mailer->AddCc(sfConfig::get('app_mailing_debug_address', 'me@example.com'));
      
      $this->reroute_to[] = $address; 
      
    } else {
      $this->mailer->AddCc($address, $name);
    }
  }

  public function addBcc($address, $name = null)
  {
    if ($name == null) {
      list($address, $name) = $this->splitAddress($address);
    }
    
    //handle non-prod rerouting    
    if ($this->reroute_flag) {
      $this->mailer->AddBcc(sfConfig::get('app_mailing_debug_address', 'me@example.com'));
      
      $this->reroute_to[] = $address; 
      
    } else {
      $this->mailer->AddBcc($address, $name);
    }
  }
  
  // proxy function to temp store 'to' addresses
  public function addAddressTempStore($address, $name = null) {
    
    if ($name == null) {
      list($address, $name) = $this->splitAddress($address);
    }
    
    $this->to[$address] = $name;
    $this->mailer->AddAddress($address, $name); 
    
  }

  // check if any 'to' addresses habe been supplied
  public function hasAddresses() {
    if ($this->to) {
      return true;
    }
    
  }
  
  public function send() {
    
    if ($this->reroute_flag) {
      
      $reroute_info = 'Original to: ';
      $reroute_info .= implode(', ',  $this->reroute_to) . "\n";
      if ($this->reroute_cc) {  
        $reroute_info = 'Original cc: ';
        $reroute_info .= implode(', ',  $this->reroute_cc) . "\n";
      }
      if ($this->reroute_bcc) {
        $reroute_info = 'Original bcc: ';
        $reroute_info .= implode(', ',  $this->reroute_bcc) . "\n";
      }
      $reroute_info .= "\n";
      
      $this->setBody($reroute_info . $this->getBody());
    
    // send copy to debugger's email
    } else {
      if (sfConfig::get('app_mailing_send_copy_flag')) {
        $this->mailer->AddBcc(sfConfig::get('app_mailing_debug_address', 'me@example.com'));
      }
      
    
    }
    
    // check for 'to' addresses
    if ($this->hasAddresses()) {
      if (!$this->mailer->Send())
      {
        throw new sfException($this->mailer->ErrorInfo);
      }
    }
  }

  
  // has to be redeclared here, since it is private in sfMail 
  private function splitAddress($address)
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
  
}

?>