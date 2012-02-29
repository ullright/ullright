<?php

/**
 * Represents a sms message
 *
 * @author klemens.ullmann-marx@ull.at
 *
 */
class ullSms
{
  
  protected
    $from,
    $to,
    $text
  ;
  
  /**
   * Return the sms in string format like this:
   * 
   *   sender number
   *   recipient number
   *   text
   * 
   */
  public function __toString() 
  {
    $return = '';
    $return .= $this->getFrom() . "\n";
    $return .= $this->getTo() . "\n";
    $return .= $this->getText(); 
    
    return $return;
  }
  
  /**
   * Set the from number
   * 
   * @param string $from
   */
  public function setFrom($from)
  {
    $this->from = self::normalizeNumber($from);
    
    return $this;
  }
  
  /**
   * Get the from number
   *
   */
  public function getFrom()
  {
    return $this->from;
  }
  
  /**
   * Set the to number
   * 
   * @param string $to
   */
  public function setTo($to)
  {
    $this->to = self::normalizeNumber($to);
    
    return $this;
  }
  
  /**
   * Get the to number
   * 
   */
  public function getTo()
  {
    return $this->to;
  }
  
  /**
   * Set the text
   * 
   * @param string $text
   */
  public function setText($text)
  {
    $this->text = substr($text, 0, 160);
    
    return $this;
  }
  
  /**
   * Get the text
   * 
   */
  public function getText()
  {
    return $this->text;
  }
  
  /**
   * Shortcut function to directly send the sms through the configured transport
   * 
   * @throws RuntimeException
   */
  public function send()
  {
    $transport = sfConfig::get('app_sms_transport');
    
    if (!class_exists($transport))
    {
      throw new RuntimeException('ullSmsTransport class not found: ' . $transport);
    }
    
    $transport = new $transport;
    
    return $transport->send($this);
  }
  
  /**
   * Normalize a mobile number
   * 
   * Output contains only numeric characters
   * 
   * @param string $number
   */
  public static function normalizeNumber($number)
  {
    $number = str_replace(array(' ', '-', '/', '(', ')'), '', $number);
    $number = str_replace('+', '00', $number);
    
    return $number;
  }
  
  
  
}