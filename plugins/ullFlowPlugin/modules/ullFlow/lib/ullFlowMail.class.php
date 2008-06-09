<?php

abstract class ullFlowMail
{
  
  protected
    $app
    , $app_caption
    , $app_doc_caption
    
    , $doc
    , $doc_title
    
    , $creator
    , $creator_name
    , $creator_email
    
    , $user
    , $user_name
    , $user_email
    
    , $ull_flow_action
    , $comment
  ;
  
  public function setApp($app) {
    $this->app = $app;
    $this->loadApp();
  }
  
  public function setDoc($doc) {
    $this->doc = $doc;
    $this->loadDoc();
    $this->loadCreator();
  }
  
  public function setUser($user) {
    $this->user = $user;
    $this->loadUser();
  }
  
  public function setUllFlowAction($ull_flow_action) {
    $this->ull_flow_action = $ull_flow_action;
    $this->loadUllFlowAction();
  }
  
  public function setComment($comment) {
    $this->comment = $comment;
  }
  
  protected function loadCreator() {
    $this->creator        = UllUserPeer::retrieveByPK($this->doc->getCreatorUserId());
    $this->creator_name   = $this->creator->__toString();
    $this->creator_email  = $this->creator->getEmail();
  }
  
  protected function loadUser() {    
    $this->user_name      = $this->user->__toString();
    $this->user_email     = $this->user->getEmail();
  }
  
  protected function loadApp() {    
    $this->app_caption    = ullCoreTools::getI18nField($this->app, 'caption');
    $this->app_doc_caption = ullCoreTools::getI18nField($this->app, 'doc_caption');
  }
  
  protected function loadDoc() {    
    $this->doc_title      = $this->doc->getTitle();
    $this->doc_id         = $this->doc->getId();
  }

  protected function loadUllFlowAction() {    
    $this->ull_flow_action_caption = ullCoreTools::getI18nField($this->ull_flow_action, 'caption');
    $this->ull_flow_action_caption_lower = strtolower(substr($this->ull_flow_action_caption, 0, 1))
      . substr($this->ull_flow_action_caption, 1);
  }
  
  protected function buildEditLink() {
    return 
      __('Link') 
      . ': http://' 
      . $_SERVER['SERVER_NAME'] 
      . '/ullFlow/edit/doc/' 
      . $this->doc_id
    ;
  }
  
  abstract public function send(); 
    
  
}

?>