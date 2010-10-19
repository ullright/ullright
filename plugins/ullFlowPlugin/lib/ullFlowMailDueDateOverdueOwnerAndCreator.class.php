<?php

class ullFlowMailDueDateOverdueOwnerAndCreator extends ullFlowMail
{
  /**
   * custom prepare method
   *
   */
  public function prepare() 
  { 
    $this->setFrom($this->user->email, $this->user->display_name);
    
    $this->addAddress($this->doc->UllEntity);
    $this->addCc($this->doc->Creator);
    
    $subject = 
      $this->doc->UllFlowApp->doc_label 
      . ' "'
      . $this->doc->subject
      . '" '
      . __('is overdue')
    ;
    $this->setSubject($subject);      
    
    $comment = 'A ' . $this->doc->UllFlowApp->doc_label . ' which is assigned to you ("' .
      $this->doc->subject . '") is past its due date.';
    
    $creatorNote = 'The creator of the overdue ' . $this->doc->UllFlowApp->doc_label .
    	' has received a copy of this message.';
    
    $this->setBody(
      __('Hello') . ' ' . $this->doc->UllEntity . ",\n"
      . "\n"
      . $comment
      . "\n"
      . $creatorNote
      . "\n"
      . "\n"
      . __('Kind regards') . ",\n"
      . $this->user . "\n"
      . "\n"
      . $this->getEditLink() . "\n"
    );
  }
  
}
