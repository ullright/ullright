<?php

/**
 * Abstract base class for ullSms transports
 * 
 * Subclass and define doSend for your needs
 * 
 * Supports dev rerouting and production bcc
 * 
 * See ullSmsTransportTest.php for an implementation example
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
abstract class ullSmsTransport
{
  protected 
    $enable = false,
    $reroute = true,
    $send_debug_bcc = false,
    $debug_mobile_number = ''
  ;
  
  /**
   * Constructor
   * 
   */
  public function __construct()
  {
    $this->loadConfig();
  }
  
  /**
   * Load config
   * 
   */
  protected function loadConfig()
  {
    $this->enable = sfConfig::get('app_sms_enable', false);
    $this->reroute = sfConfig::get('app_sms_reroute', true);
    $this->send_debug_bcc = sfConfig::get('app_sms_send_debug_bcc', false);
    $this->debug_mobile_number = sfConfig::get('app_sms_debug_mobile_number', '');
  }
  
  /**
   * Template function for the actual sms sending.
   * 
   * Implement the actual functionality in your subclass here
   *
   * @param ullSms $sms
   */
  abstract protected function doSend(ullSms $sms);
  
  
  /**
   * Send a given ullSms
   *
   * @param ullSms $sms
   */
  public function send(ullSms $sms)
  {
    if (! $this->enable)
    {
      return false;
    }
    
    $this->sendReal($sms);
    
    $this->sendDebug($sms);
    
  }
  
  
  /**
   * Send the real Sms to the recipient
   * 
   * @param ullSms $sms
   */
  protected function sendReal(ullSms $sms)
  {
    if ($this->reroute)
    {
      return false;
    }
    
    $this->doSend($sms);
    
  }
  
  /**
   * Send debugging sms
   *
   * @param ullSms $sms
   */
  protected function sendDebug(ullSms $sms)
  {
    //Send debug copy only when rerouting or debug bcc
    if (! ($this->send_debug_bcc || $this->reroute))
    {
      return false;
    }
    
    // Don't modify the original sms
    $debugSms = clone $sms;
    
    $originalTo = $debugSms->getTo();
    
    $number = ullSms::normalizeNumber($this->debug_mobile_number);
    $debugSms->setTo($number);
    $debugSms->setText('Original to: ' . $originalTo . '. ' . $debugSms->getText());
    
    $this->doSend($debugSms);
  }
  
}