<?php

class ullFlowRuleTroubleTicket extends ullFlowRule 
{
   
  public function getNext() 
  {
    $next = array();
    
    if ($this->isStep('trouble_ticket_creator')) 
    {
      $next['step']    = $this->findStep('trouble_ticket_dispatcher');
      $next['entity']  = $this->findGroup('Helpdesk');
    }
     
    elseif ($this->isStep('trouble_ticket_dispatcher')) 
    {
      if ($this->isAction('assign_to_user'))
      {
        $next['step'] = $this->findStep('trouble_ticket_troubleshooter');
      } 
      elseif ($this->isAction('close')) 
      {
        $next['step'] = $this->findStep('trouble_ticket_closed');
      }
    }
      
    elseif ($this->isStep('trouble_ticket_troubleshooter')) 
    {
      // no action because it is handled automatically by ullFlowAction "return" / "reject"      
    }  
      
    elseif ($this->isStep('trouble_ticket_closed')) 
    {
      $next['step']    = $this->findStep('trouble_ticket_dispatcher');
      $next['entity']  = $this->findGroup('Helpdesk');
    }
    
//    $this->debug($next);die;
    
    return $next;
  }
  
  
  /**
   * Debugging method for $next
   * 
   * @param unknown_type $next
   * @return unknown_type
   */
  protected function debug($next)
  {
    foreach ($next as $key => $value)
    {
      var_dump('___' . $key);
      if ($value instanceof Doctrine_Record)
      {
        var_dump(get_class($value));
        var_dump($value->toArray());
      }
      else
      {
        var_dump($value); 
      }
    }
  }
  
}
