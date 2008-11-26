<?php

class ullFlowMailNotifyCreator extends ullFlowMail
{

  /**
   * custom prepare method
   *
   */
  public function prepare() 
  {
    
    // don't send mails to oneselfes
    if ($this->doc->Creator->email == $this->user->email) 
    {
      return true;
    }
    
    $this->setFrom($this->user->email, $this->user->display_name);
    
    $this->addAddress($this->doc->Creator);
    
    $subject = 
      $this->doc->UllFlowApp->doc_label 
      . ' "'
      . $this->doc->title
      . '" '
      . __('has been %1%', array('%1%' => strtolower($this->doc->UllFlowAction->label)))
    ;
    $this->setSubject($subject);      
    
    $comment = ($this->doc->memory_comment) ? __('Comment') . ': ' . 
        $this->doc->memory_comment . "\n\n" : ''; 
    
    $this->setBody(
      __('Hello') . ' ' . $this->doc->Creator . ",\n"
      . "\n"
      . $subject . ".\n"
      . "\n"
      . $comment
      . __('Kind regards') . ",\n"
      . $this->user . "\n"
      . "\n"
      . $this->getEditLink() . "\n"
    );
  }
  
}
