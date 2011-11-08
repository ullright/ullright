<?php

class ullFlowBreadcrumbTree extends breadcrumbTree
{
  
  public function __construct()
  {
    parent::__construct();
    $this->add(__('Workflows', null, 'ullFlowMessages') . ' ' . __('Home', null, 'common'), 'ullFlow/index');
  }
}