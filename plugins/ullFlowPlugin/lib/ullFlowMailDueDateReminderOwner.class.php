<?php

class ullFlowMailDueDateReminderOwner extends ullFlowMail
{
  protected $dueDate;
  
  public function __construct(UllFlowDoc $doc, $user = null, $dueDate)
  {
    parent::__construct($doc, $user);
    $this->dueDate = $dueDate;
  }
  
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
      . __('is due on %1%', array('%1%' => ull_format_date($this->dueDate)), 'ullFlowMessages')
    ;
    $this->setSubject($subject);      
    
    $comment = __('A %1% which is assigned to you ("%2%") is due on %3%.',
      array('%1%' => $this->doc->UllFlowApp->doc_label, '%2%' => $this->doc->subject,
      '%3%' => ull_format_date($this->dueDate)), 'ullFlowMessages');
    
    $this->setBody(
      __('Hello', null, 'ullFlowMessages') . ' ' . $this->doc->UllEntity . ",\n"
      . "\n"
      . $comment
      . "\n"
      . "\n"
      . __('Kind regards', null, 'ullFlowMessages') . ",\n"
      . $this->user . "\n"
      . "\n"
      . $this->getEditLink() . "\n"
    );
  }
  
}
