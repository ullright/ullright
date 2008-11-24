<?php

class ullFlowRuleTodo extends ullFlowRule {
   
  public function getParams() {
    
      if ($this->action_slug == 'save') {
        
        $this->params['next_step'] = $this->getStepIdBySlug('open') ;
        
      } elseif ($this->action_slug == 'close') {
        
        $this->params['next_step'] = $this->getStepIdBySlug('closed') ;
        
      }
      
//  ullCoreTools::printR($this->params);
    
  return $this->params;
    
  }
  
}
?>