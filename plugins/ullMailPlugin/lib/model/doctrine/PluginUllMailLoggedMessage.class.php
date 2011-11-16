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
      
      // We have to check if the related UllNewsletterEdition exists
      // Otherwise we would create (!!!) it (In case the newsletter was deleted)
      // TODO: It would be nicer to update the num_read_emails with a actual db count
      if ($this->UllNewsletterEdition->exists())
      {
        $readCount = $this->UllNewsletterEdition['num_read_emails'];
        $this->UllNewsletterEdition['num_read_emails'] = $readCount + 1;
      }
    }
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

//    //... and the bodies. Depending on the content type of the message,
//    //HTML and plaintext bodies are extracted
//
//    if ($mail->isHtml)
//    
//    $contentType = $mail->getContentType();
//    switch ($contentType)
//    {
//      case 'text/plain':
//        $loggedMessage['plaintext_body'] = $mail->getBody();
//        break;
//
//      case 'text/html':
//        $loggedMessage['html_body'] = $mail->getBody();
//        break;
//
//      default:
//        if (strpos($contentType, 'multipart/') === 0)
//        {
//          $loggedMessage->parseMimeBodyParts($mail);
//        }
//        break;
//    }

    // handle newsletter edition 
    if ($mail instanceof ullsfMail && $mail->getNewsletterEditionId())
    {
      $loggedMessage['ull_newsletter_edition_id'] = $mail->getNewsletterEditionId();
    }

    return $loggedMessage;
  }

  /**
   * Parses plaintext and HTML bodies from a Swift_Message
   * instance. Handles plaintext only, HTMl only, and both.
   * 
   * @param Swift_Message $mail
   */
//  protected function parseMimeBodyParts(Swift_Message $mail)
//  {
//    $children = $mail->getChildren();
//    foreach ($children as $child)
//    {
//      if ($child instanceof Swift_MimePart)
//      {
//        if ($child->getContentType() == 'text/plain')
//        {
//          $plainBody = $child->getBody();
//        }
//        else if ($child->getContentType() == 'text/html')
//        {
//          $htmlBody = $child->getBody();
//        }
//      }
//    }
//
//    //we assume that if only a text/plain part exists,
//    //the main body of the message is text/html; and
//    //if only a text/html part exists, the main body
//    //is text/plain - third case is both parts set
//    if (isset($plainBody))
//    {
//      if (isset($htmlBody))
//      {
//        //both bodies are MIME parts
//        $this['plaintext_body'] = $plainBody;
//        $this['html_body'] = $htmlBody;
//        return;
//      }
//
//      //only the plain part is set
//      $this['plaintext_body'] = $plainBody;
//      $this['html_body'] = $mail->getBody();
//    }
//    else
//    {
//      //only the html part is set
//      $this['html_body'] = $htmlBody;
//      $this['plaintext_body'] = $mail->getBody();
//    }
//  }
}