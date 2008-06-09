<?php

class ullFlowExternalWikiLink extends ullFlowExternal
{
  
  public function initialize() {
    
    $this->module = 'ullFlow';
    
    $this->action = 'wikiLink';
    
    $this->request->setParameter('doc', $this->doc->getId());
    
  }
  
  
  
}

?>