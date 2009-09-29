<?php

class ullAdminBreadcrumbTree extends breadcrumbTree
{
  
  public function __construct()
  {
    parent::__construct();
    $this->add('Admin' . ' ' . __('Home', null, 'common'), 'ullAdmin/index');
  }

}