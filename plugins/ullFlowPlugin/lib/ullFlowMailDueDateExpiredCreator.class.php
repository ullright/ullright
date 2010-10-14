<?php

class ullFlowMailDueDateExpiredCreator extends ullFlowMail
{
  /**
   * custom prepare method
   *
   */
  public function prepare() 
  {
    // don't send mail if creator and user are the same person
    if ($this->doc->Creator->email == $this->user->email) 
    {
      return true;
    }
    
    $this->setFrom($this->user->email, $this->user->display_name);
    
    $this->addAddress($this->doc->Creator);
    
    $subject = 
      $this->doc->UllFlowApp->doc_label 
      . ' "'
      . $this->doc->subject
      . '" '
      . __('has expired')
    ;
    $this->setSubject($subject);      
    
    $comment = 'A ' . $this->doc->UllFlowApp->doc_label . ' you created ("' . $this->doc->subject .
      '") is past its due date.';
    
    $this->setBody(
      __('Hello') . ' ' . $this->doc->Creator . ",\n"
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
