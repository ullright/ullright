<?php

class ullFlowRuleTodo extends ullFlowRule 
{
   
  public function getNext() 
  {
    $next = array();
    
    if ($this->isStep('todo_creator')) 
    {
      $next['step']    = $this->findStep('todo_open');
    }

    elseif ($this->isStep('todo_open')) 
    {
      if ($this->isAction('close')) 
      {
        $next['step'] = $this->findStep('todo_closed');
      }
    } 
    
    elseif ($this->isStep('todo_closed')) 
    {
      $next['step']    = $this->findStep('todo_open');
    }    

    return $next;
  }

}
