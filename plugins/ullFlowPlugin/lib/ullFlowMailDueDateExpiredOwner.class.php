<?php

class ullFlowMailDueDateExpiredOwner extends ullFlowMail
{
  /**
   * custom prepare method
   *
   */
  public function prepare() 
  { 
    $this->setFrom($this->user->email, $this->user->display_name);
    
    $this->addAddress($this->doc->UllEntity);
    
    $subject = 
      $this->doc->UllFlowApp->doc_label 
      . ' "'
      . $this->doc->subject
      . '" '
      . __('has expired')
    ;
    $this->setSubject($subject);      
    
    $comment = 'A ' . $this->doc->UllFlowApp->doc_label . ' which is assigned to you ("' . $this->doc->subject .
      '") is past its due date.';
    
    $this->setBody(
      __('Hello') . ' ' . $this->doc->UllEntity . ",\n"
      . "\n"
      . $comment
      . "\n"
      . "\n"
      . __('Kind regards') . ",\n"
      . $this->user . "\n"
      . "\n"
      . $this->getEditLink() . "\n"
    );
  }
  
}
