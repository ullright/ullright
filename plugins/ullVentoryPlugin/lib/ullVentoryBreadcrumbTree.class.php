<?php

class ullVentoryBreadcrumbTree extends breadcrumbTree
{
  
  public function __construct()
  {
    parent::__construct();
    $this->add(__('Inventory', null, 'ullVentoryMessages') . ' ' . __('Home', null, 'common'), 'ullVentory/index');
  }
  
  public function addDefaultListEntry()
  {
    $this->add(__('Result list', null, 'common'), 'ullVentory/list');
  }
}