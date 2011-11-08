<?php

class ullTableToolBreadcrumbTree extends breadcrumbTree
{
  
  public function __construct()
  {
    parent::__construct();
    $this->add(__('Admin', null, 'ullCoreMessages') . ' ' . __('Home', null, 'common'), 'ullAdmin/index');
  }
}