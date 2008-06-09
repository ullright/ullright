<?php

class ullFlowExternalUpload extends ullFlowExternal
{
  
  public function initialize() {
    
    $this->module = 'ullFlow';
    
    $this->action = 'upload';
    
    $this->request->setParameter('doc', $this->doc->getId());
    
  }
  
  
  
}

?>