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
      // no action because it is handled automatically by ullFlowAction "return"       
    }  
      
    elseif ($this->isStep('trouble_ticket_closed')) 
    {
      $next['step']    = $this->findStep('trouble_ticket_dispatcher');
      $next['entity']  = $this->findGroup('Helpdesk');
    }
    
    return $next;
  }
  
  
//  protected function setParamsForCaller() 
//  {
//    
//    $this->params['next_step'] = $this->getStepIdBySlug('question_for_caller ');
//    
//    if (!$value = $this->getFieldValue(3)) { // get caller field
//      $value = $this->doc->getCreatorUserId(); // default to creator if no caller is set
//    }
//    $this->params['next_user'] = $value; 
//    
//  }
  
}
