<?php

abstract class ullFlowActionHandler
{
  
  protected
    $options = array()
    , $doc
    , $doc_id
    , $params = array (
        'next_step'     => 0
        , 'next_user'   => 0
        , 'next_group'  => 0
      )
  ;
  
  abstract function getEditWidget();
  
  public function setOptions($options) {
    if (!is_array($options)) {
      $options = sfToolkit::stringToArray($options);
    }
    
    $this->options = $options;
  }
  
  public function setDoc($doc) {
    
    $this->doc = $doc;
    $this->doc_id = $doc->getId();
    
  }
  
  // set the assigned to params one step back.
  //  used for example by actions reject and return
  protected function setParamsOneStepBackwards() {
    
    // get latest action
    $c = new Criteria();
    $c->add(UllFlowMemoryPeer::ULL_FLOW_DOC_ID, $this->doc_id);
    $c->addJoin(UllFlowMemoryPeer::ULL_FLOW_ACTION_ID, UllFlowActionPeer::ID, Criteria::LEFT_JOIN);
    $c->add(UllFlowActionPeer::STATUS_ONLY, 1, Criteria::NOT_EQUAL);
    $c->addDescendingOrderByColumn(UllFlowMemoryPeer::CREATED_AT);
    $memory = UllFlowMemoryPeer::doSelectOne($c);
    
//    ullCoreTools::printR($memory);
//    exit();
    
    $this->params['next_step'] = $memory->getUllFlowStepId();
    if ($memory->getCreatorGroupId()) {
      $this->params['next_group'] = $memory->getCreatorGroupId();
    } else {
      $this->params['next_user'] = $memory->getCreatorUserId();
    }
    
  }
  
}

?>