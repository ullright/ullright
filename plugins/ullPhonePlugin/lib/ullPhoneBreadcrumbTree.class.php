<?php

class ullPhoneBreadcrumbTree extends breadcrumbTree
{
  
  public function __construct()
  {
    parent::__construct();
    $this->add(__('Telephone directory', null, 'ullPhoneMessages'), 'ullPhone/index');
  }
  
  public function addDefaultListEntry()
  {
    $this->add(__('Result list', null, 'common'), 'ullPhone/list');
  }

}