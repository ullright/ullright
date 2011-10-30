<?php

class ullCmsBreadcrumbTree extends breadcrumbTree
{
  
  public function __construct()
  {
    parent::__construct();
    $this->add(__('Content', null, 'ullCmsMessages') . ' ' . __('Home', null, 'common'), 'ullCms/index');
  }
  
  public function addDefaultListEntry()
  {
    $this->add(__('Result list', null, 'common'), 'ullCms/list');
  }
}