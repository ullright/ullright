<?php

class ullFlowMailNotifyCreator extends ullFlowMail
{
   
  public function send() {
    
    $mail = new ullsfMail();
    $mail->initialize();
    
    // don't send emails to oneself
    if ($this->creator_email <> $this->user_email) {
      $mail->addAddress($this->creator_email, $this->creator_name);
      
      $mail->setFrom($this->user_email, $this->user_name);
      
      $subject = 
        $this->app_doc_caption 
        . ' "'
        . $this->doc_title
        . '" '
        . __('has been %1%', array('%1%' => $this->ull_flow_action_caption_lower))
      ;
      
      $mail->setSubject($subject);
      
      $comment = ($this->comment) ? __('Comment') . ': ' . $this->comment . "\n\n" : '';
      
      $mail->setBody(
        __('Hello') . ' ' . $this->creator_name . ",\n"
        . "\n"
        . $subject . ".\n"
        . "\n"
        . $comment
        . __('Kind regards') . ",\n"
        . $this->user_name . "\n"
        . "\n"
        . $this->buildEditLink()
      );
   
      // send the email
      $mail->send();
    } // end of don't send email to oneself
  }
  
  
}

?>