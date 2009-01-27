<?php

class ullFlowMailReject extends ullFlowMail
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
        $this->doc->UllFlowApp->doc_label . 
        ': "' .
        $this->doc->subject .
        '" ' .
        __('has been %1%', array('%1%' => strtolower($this->doc->UllFlowAction)))
    ;
    $this->setSubject($subject);
    
    $comment = ($this->doc->memory_comment) ? __('Comment') . ': ' . 
        $this->doc->memory_comment . "\n\n" : ''; 
    
    $this->setBody(
      __('Hello') . ' ' . $this->doc->UllEntity . ",\n" .
      "\n" .
      $subject . ".\n" .
      "\n" .
      $comment .
      $this->getEditLink() . "\n" .
      "\n" .
      __('Kind regards') . ",\n" .
      $this->user . "\n"
    );
  }
  
}
