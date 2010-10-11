<?php

class ullFlowRuleProcurementRequestCar extends ullFlowRule 
{
   
  public function getNext() 
  {
    $next = array();
    
    if ($this->isStep('procurement_car_creator')) 
    {
      if ($this->isAction('send'))
      {
        $next['step']    = $this->findStep('procurement_car_clerk');
        $next['entity']  = $this->findGroup('Procurement Clerks');
      } 
    }
     
    elseif ($this->isStep('procurement_car_clerk')) 
    {
      if ($this->isAction('assign_to_user'))
      {
        $next['step'] = $this->findStep('procurement_car_clerk');
      } 
      elseif ($this->isAction('close')) 
      {
        $next['step'] = $this->findStep('procurement_car_closed');
      }
    }
      
    elseif ($this->isStep('procurement_car_closed')) 
    {
      $next['step']    = $this->findStep('procurement_car_clerk');
      $next['entity']  = $this->findGroup('Procurement Clerks');
    }
    
    return $next;
  }
  
}