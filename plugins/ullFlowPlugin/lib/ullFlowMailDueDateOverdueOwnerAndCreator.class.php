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
      . __('is overdue', null, 'ullFlowMessages')
    ;
    $this->setSubject($subject);      
    
    $comment = __('A %1% which is assigned to you ("%2%") is past its due date.',
      array('%1%' => $this->doc->UllFlowApp->doc_label, '%2%' => $this->doc->subject),
      'ullFlowMessages');
    
    $creatorNote = __('The creator of the overdue %1% has received a copy of this message.',
      array('%1%' => $this->doc->UllFlowApp->doc_label), 'ullFlowMessages');
    
    $this->setBody(
      __('Hello', null, 'ullFlowMessages') . ' ' . $this->doc->UllEntity . ",\n"
      . "\n"
      . $comment
      . "\n"
      . $creatorNote
      . "\n"
      . "\n"
      . __('Kind regards', null, 'ullFlowMessages') . ",\n"
      . $this->user . "\n"
      . "\n"
      . $this->getEditLink() . "\n"
    );
  }
  
}
