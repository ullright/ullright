<?php

abstract class ullFlowRule {
  
  protected
    $doc
    , $app
    , $request
    , $params = array (
        'next_step'     => 0
        , 'next_user'   => 0
        , 'next_group'  => 0
      )
    
    // aliases
    , $doc_id
    , $action_id
    , $step_id
  ;
  
  public function setDoc($doc) {
    
    $this->doc = $doc;
    
    $this->doc_id = $doc->getId();
    
    $this->step_id = $this->doc->getAssignedToUllFlowStepID();
    
    $this->app = $doc->getUllFlowApp();
    
  }
  
  public function setRequest($request) {
    
    $this->request = $request;
    
    // aliases for request properties
    $this->action_slug = $this->request->getParameter('ull_flow_action');
    
  }
  
  abstract function getParams();
  
  protected function is_step($slug) {
    
    // get step
    $c = new Criteria();
    $c->add(UllFlowStepPeer::ULL_FLOW_APP_ID, $this->app->getId());
    $c->add(UllFlowStepPeer::SLUG, $slug);
    $step = UllFlowStepPeer::doSelectOne($c);
        
    if ($this->step_id == $step->getId()) {
      return true;
    }
    
  }
  
  protected function getFieldValue($ull_flow_field_id) {

    $c = new Criteria();
    $c->add(UllFlowValuePeer::ULL_FLOW_DOC_ID, $this->doc_id);
    $c->add(UllFlowValuePeer::ULL_FLOW_FIELD_ID, $ull_flow_field_id);
    $value = UllFlowValuePeer::doSelectOne($c);
    
    return $value->getValue();
    
  }
  
  protected function getStepIdBySlug($slug) {
    $c = new Criteria();
    $c->add(UllFlowStepPeer::ULL_FLOW_APP_ID, $this->app->getId());
    $c->add(UllFlowStepPeer::SLUG, $slug);
    
    return UllFlowStepPeer::doSelectOne($c)->getId();
  }
  
}
?>