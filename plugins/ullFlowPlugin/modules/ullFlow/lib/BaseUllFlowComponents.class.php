<?php

class BaseUllFlowComponents extends sfComponents
{
  public function executeUllFlowHeader() {
    
//    $show_link = 'ull_flow/show?docid='.$this->ull_flow->getDocid();
//    $edit_link = 'ull_flow/edit?docid='.$this->ull_flow->getDocid();
//    
//    if ($this->getActionName() == 'show') {
//      
//      // show edit link only with sufficent rights
//      if (UllUserPeer::userHasGroup(1)) {
//        $this->subject_link = $edit_link;
//      } else {
//        $this->subject_link = $show_link;
//      }
//      
//    } else {
////      $this->subject_link = 'wiki/show?id='.$this->wiki->getID().'&cursor='.$this->cursor;          
//      $this->subject_link = $show_link;
//    }
    
  }
  
  public function executeUllFlowHeaderShow() {
    
    $this->executeUllFlowHeader();
    
  }
  
  public function executeUllFlowHeadFootActionIcons() {
    
    $this->access = false;
    
    if (UllUserPeer::userHasGroup(1)) {
      $this->access = true;      
    }
    
  }
  
}

?>