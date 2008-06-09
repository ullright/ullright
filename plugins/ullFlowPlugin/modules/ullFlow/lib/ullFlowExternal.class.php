<?php

abstract class ullFlowExternal
{
  
  protected
    $module
    , $action
    , $doc
    , $request
  ;
  
  abstract function initialize();
  
  public function setDoc($doc) {
    $this->doc = $doc;
  }
  
  public function setRequest($request) {
    $this->request = $request;
  }
  
  public function getModule() {
    return $this->module;
  }
  
  public function getAction() {
    return $this->action;
  }
  
}

?>