<?php

class ullFlowMailNotifyNext extends ullFlowMail
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
        $this->doc->title .
        '"'
    ;
    $this->setSubject($subject);
    
    $request =
        __('Please take care of') .
        ' ' .
        $this->doc->UllFlowApp->slug . 
        ' "' .
        $this->doc->title .
        '"'
    ;
    
    $comment = ($this->doc->memory_comment) ? __('Comment') . ': ' . 
        $this->doc->memory_comment . "\n\n" : ''; 
    
    $this->setBody(
      __('Hello') . ' ' . $this->doc->UllEntity . ",\n" .
      "\n" .
      $request . ".\n" .
      "\n" .
      $comment .
      $this->getEditLink() . "\n" .
      "\n" .
      __('Kind regards') . ",\n" .
      $this->user . "\n"
    );
  }
  
}
