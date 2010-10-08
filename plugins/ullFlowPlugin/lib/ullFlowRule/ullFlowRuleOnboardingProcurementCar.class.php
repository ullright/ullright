<?php

class ullFlowRuleOnboardingProcurementCar extends ullFlowRule 
{
   
  public function getNext() 
  {
    $next = array();
    
    if ($this->isStep('onboarding_procurement_car_creator')) 
    {
      if ($this->isAction('send'))
      {
        $next['step']    = $this->findStep('onboarding_procurement_car_clerk');
        $next['entity']  = $this->findGroup('Procurement Clerks');
      } 
    }
     
    elseif ($this->isStep('onboarding_procurement_car_clerk')) 
    {
      if ($this->isAction('assign_to_user'))
      {
        $next['step'] = $this->findStep('onboarding_procurement_car_clerk');
      } 
      elseif ($this->isAction('close')) 
      {
        $next['step'] = $this->findStep('onboarding_procurement_car_closed');
      }
    }
      
    elseif ($this->isStep('onboarding_procurement_car_closed')) 
    {
      $next['step']    = $this->findStep('onboarding_procurement_car_clerk');
      $next['entity']  = $this->findGroup('Procurement Clerks');
    }
    
    return $next;
  }
  
}