<?php

class ullTimeBreadcrumbTree extends breadcrumbTree
{
  
  public function __construct()
  {
    parent::__construct();
    
    $request = sfContext::getInstance()->getRequest();
    
    $this->add(
      __('Project time reporting', null, 'ullTimeMessages') . ' ' . __('Home', null, 'common'),
      'ullTime/index' . (
        $request->hasParameter('username') 
        ? '?username=' . $request->getParameter('username')
        : ''
      )
    );    
  }
  
  public function addDefaultListEntry()
  {
    $this->add(
      __('Period overview', null, 'ullTimeMessages'),
      array('action' => 'list', 'date' => null)
    );
  }

}