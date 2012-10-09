<?php

/**
 * PluginUllMailLoggedMessage represents a log record for a sent email.
 */
abstract class PluginUllMailLoggedMessage extends BaseUllMailLoggedMessage
{
  
  /**
   * Notify a symfony event upon post save
   * 
   * @see lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/lib/vendor/doctrine/Doctrine/Doctrine_Record::postSave()
   */
  public function postSave($event)
  {
    $dispatcher = sfProjectConfiguration::getActive()->getEventDispatcher();
    
    $dispatcher->notify(new sfEvent($this, 'ull_mail_logged_message.post_save'));
  }
  
  /**
   * Handles tracking for an incoming request, usually originating
   * from online view mode or directly from the email client (loading
   * the beacon image)
   * 
   * Also updates the read counter in UllNewsletterEdition via event dispatching
   * @see: UllNewsletterEdition::listenToUllMailLoggedMessageReadDetectedEvent()
   * 
   * @param sfRequest $request the incoming request
   */
  public function handleTrackingRequest(sfRequest $request)
  {
    $this['num_of_readings'] = $this['num_of_readings'] + 1;
    $this['last_user_agent'] = $request->getHttpHeader('User-Agent');
    $this['last_ip'] = $request->getRemoteAddress();
    
    //is this the first time this message was opened?
    if (!$this['first_read_at'])
    {
      $this['first_read_at'] = date('c');
    }
    
    $this->save();
    
    $dispatcher = sfProjectConfiguration::getActive()->getEventDispatcher();
    
    $dispatcher->notify(new sfEvent($this, 'ull_mail_logged_message.read_detected'));
  }
  
  
  /**
   * Returns a new (unsaved) PluginUllMailLoggedMessage instance generated
   * from a Swift_Message instance.+
   * 
   * @param Swift_Message $mail
   */
  public static function fromSwiftMessage(Swift_Message $mail)
  {
    $loggedMessage = new UllMailLoggedMessage();

    $headers = $mail->getHeaders();

    //save all headers ...
    $loggedMessage['headers'] = $headers->toString();

    $loggedMessage['sender'] = $headers->get('from')->getFieldBody();

    //.. and the recipients
    foreach (array('to', 'cc', 'bcc') as $recipientType)
    {
      if ($headers->has($recipientType))
      {
        $recipientList = implode($headers->get($recipientType)->getNameAddressStrings(), ',');
        $loggedMessage[$recipientType . '_list'] = $recipientList;
      }
    }

    // handle subject
    $loggedMessage['subject'] = $mail->getSubject();
    
    // handle body
    if ($mail instanceof ullsfMail && $mail->getIsHtml())
    {
      $loggedMessage['html_body'] = $mail->getHtmlBody();
      $loggedMessage['plaintext_body'] = $mail->getPlaintextBody();
    }
    else
    {
      // We assume a plaintext email
      $loggedMessage['plaintext_body'] = $mail->getBody();
    }

    // handle newsletter edition 
    if ($mail instanceof ullsfMail && $mail->getNewsletterEditionId())
    {
      $loggedMessage['ull_newsletter_edition_id'] = $mail->getNewsletterEditionId();
    }

    return $loggedMessage;
  }

}