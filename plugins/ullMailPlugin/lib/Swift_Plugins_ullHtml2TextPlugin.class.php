<?php

/**
 * This class is a plugin for Swift Mailer which 
 * converts a html body to a plaintext body
 * 

 */
class Swift_Plugins_ullHtml2TextPlugin
  extends Swift_Plugins_ullPrioritizedPlugin
  implements Swift_Events_SendListener
{ 
  /**
   * @param Swift_Events_SendEvent $evt
   */
  public function beforeSendPerformed(Swift_Events_SendEvent $evt)
  {
    $mail = $evt->getMessage();
    
    if (
      $mail instanceof ullsfMail && 
      $mail->getIsHtml() == true && 
      !$mail->getPlaintextBody()
    )
    {
      $mail->setPlaintextBody(ullHtml2Text::transform($mail->getBody()));
    }
  }
  
  /**
   *
   * @param Swift_Events_SendEvent $evt
   */
  public function sendPerformed(Swift_Events_SendEvent $evt)
  { 
  }  
 
}


