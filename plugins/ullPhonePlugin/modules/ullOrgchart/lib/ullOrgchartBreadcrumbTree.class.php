<?php

class ullOrgchartBreadcrumbTree extends breadcrumbTree
{
  
  public function __construct()
  {
    parent::__construct();
    $this->add(__('Orgchart', null, 'ullOrchartMessages'), 'ullOrgchart/index');
  }
  
  public function addDefaultListEntry()
  {
    $this->add(__('Result list', null, 'common'), 'ullOrgchart/list');
  }

}