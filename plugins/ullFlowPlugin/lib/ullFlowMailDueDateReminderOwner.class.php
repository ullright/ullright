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
      . __('is due on %1%', array('%1%' => ull_format_date($this->dueDate)))
    ;
    $this->setSubject($subject);      
    
    $comment = 'A ' . $this->doc->UllFlowApp->doc_label . ' which is assigned to you ("' . $this->doc->subject .
      '") is due to expire on ' . ull_format_date($this->dueDate) . '.';
    
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
