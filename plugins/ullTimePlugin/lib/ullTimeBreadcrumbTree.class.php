<?php

class ullTimeBreadcrumbTree extends breadcrumbTree
{
  
  public function __construct()
  {
    parent::__construct();
    $this->add(
      __('Project time reporting', null, 'ullTimeMessages') . ' ' . __('Home', null, 'common'), 
      array('action' => 'index', 'period' => null, 'date' => null)
    );
    
//    $this->add(
//      __('Project time reporting', null, 'ullTimeMessages') . ' ' . __('Home', null, 'common'),
//      'ullTime/index' . (
//        $this->hasRequestParameter('username') 
//        ? '?username=' . $this->getRequestParameter('username')
//        : ''
//      )
//    );    
  }
  
  public function addDefaultListEntry()
  {
    $this->add(
      __('Period overview', null, 'ullTimeMessages'),
      array('action' => 'list', 'date' => null)
    );
  }

}