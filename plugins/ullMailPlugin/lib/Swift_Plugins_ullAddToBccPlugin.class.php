<?php

/**
 * This class is a plugin for Swift Mailer which includes
 * a given recipient in the BCC list of all sent mails.
 */
class Swift_Plugins_ullAddToBccPlugin
  extends Swift_Plugins_ullPrioritizedPlugin
  implements Swift_Events_SendListener
{
  protected $recipient;
  protected $slugExcludeList;
  
  /**
   * Returns a new instance of Swift_Plugins_ullAddToBccPlugin.
   */
  public function __construct($recipient, $slugExcludeList)
  {
    $this->recipient = $recipient;
    $this->slugExcludeList = $slugExcludeList;
  }

  /**
   * Invoked immediately before a mail is sent, adds the
   * recipient to the BCC list.
   *
   * @param Swift_Events_SendEvent $evt
   */
  public function beforeSendPerformed(Swift_Events_SendEvent $evt)
  {
    $message = $evt->getMessage();
    
    //if the message is an ullsfMail and its slug is
    //in the exclude list, do not add the recipient
    //to the BCC list
    if ($message instanceof ullsfMail)
    {
      if (in_array($message->getSlug(), $this->slugExcludeList))
      {
        return;
      }
    }

    $message->addBcc($this->recipient);
  }

  /**
   * Invoked immediately after a mail was sent, removes
   * the recipient from the BCC list so that the
   * original mail object is pristine.
   *
   * @param Swift_Events_SendEvent $evt
   */
  public function sendPerformed(Swift_Events_SendEvent $evt)
  {
    $message = $evt->getMessage();
    $bcc = $message->getBcc();
    unset($bcc[$this->recipient]);
    $message->setBcc($bcc);
  }
}

