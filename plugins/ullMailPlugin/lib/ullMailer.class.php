<?php

/**
 * ulright mailer extensions
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class ullMailer extends sfMailer
{
  protected 
    $addToQueue = false
  ;
  
  public function __construct($dispatcher, $options)
  {
    Swift_DependencyContainer::getInstance()
      ->register('transport.eventdispatcher')
      ->asNewInstanceOf('Swift_Events_ullSimpleEventDispatcher')
    ;
    
    parent::__construct($dispatcher, $options);
    
    // add logger to realtime transport, since sfMailer doesn't do it (why?)
    if ($options['logging'] && $this->logger)
    {
      $this->realtimeTransport->registerPlugin($this->logger);
    }
  }
  
  /**
   * Sends the given message without spooling
   *
   * @param Swift_Transport $transport         A transport instance
   * @param string[]        &$failedRecipients An array of failures by-reference
   *
   * @return int|false The number of sent emails
   */
  public function send(Swift_Mime_Message $message, &$failedRecipients = null)
  {
    if (!$this->addToQueue)
    {
      $this->sendNextImmediately(); 
    }
    
    return parent::send($message, $failedRecipients);
  }
  
  /**
   * Sends the given message with spooling
   * 
   * @param $message
   * @param $failedRecipients
   */
  public function sendQueue(Swift_Mime_Message $message, &$failedRecipients = null)
  {
    $this->addToQueue = true;
    
    return $this->send($message, $failedRecipients);
  }
  
  
  /**
   * Like batchSend, but put the messages into the spool queue
   * 
   * @param Swift_Mime_Message $message
   * @param array &$failedRecipients, optional
   * @param Swift_Mailer_RecipientIterator $it, optional
   * @return int
   * @see send()
   */
  public function batchSendQueue(
    Swift_Mime_Message $message,
    &$failedRecipients = null,
    Swift_Mailer_RecipientIterator $it = null
  )
  {
    $this->addToQueue = true;
    
    return $this->batchSend($message, $failedRecipients, $it);
  }
  
}
