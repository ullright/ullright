<?php

abstract class ullSmsTransport
{
  protected 
    $enable = false,
    $reroute = true,
    $send_debug_bcc = false,
    $debug_mobile_number = ''
  ;
  
  public function __construct()
  {
    $this->loadConfig();
  }
  
  protected function loadConfig()
  {
    $this->enable = sfConfig::get('app_sms_enable', false);
    $this->reroute = sfConfig::get('app_sms_reroute', true);
    $this->send_debug_bcc = sfConfig::get('app_sms_send_debug_bcc', false);
    $this->debug_mobile_number = sfConfig::get('app_sms_debug_mobile_number', '');
  }
  
  abstract protected function doSend(ullSms $sms);
  
  public function send(ullSms $sms)
  {
    if (! $this->enable)
    {
      return false;
    }
    
    $this->sendReal($sms);
    
    $this->sendDebug($sms);
    
  }
  
  protected function sendReal(ullSms $sms)
  {
    if ($this->reroute)
    {
      return false;
    }
    
    $this->doSend($sms);
    
  }
  
  protected function sendDebug(ullSms $sms)
  {
    //Send debug copy only when rerouting or debug bcc
    if (! ($this->send_debug_bcc || $this->reroute))
    {
      return false;
    }
    
    $number = ullSms::normalizeNumber($this->debug_mobile_number);
    $sms->setTo($number);
    
    $this->doSend($sms);
  }
  
  
}