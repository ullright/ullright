<?php

class ullTimeBreadcrumbTree extends breadcrumbTree
{
  
  public function __construct()
  {
    parent::__construct();
    $this->add(__('Project time reporting', null, 'ullTimeMessages') . ' ' . __('Home', null, 'common'), 'ullTime/index');
  }
  
  public function addDefaultListEntry()
  {
    $this->add(__('Monthly overview', null, 'ullTimeMessages'), 'ullTime/list');
  }

}