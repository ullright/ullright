<?php

class ullNewsletterBreadcrumbTree extends breadcrumbTree
{
  
  public function __construct()
  {
    parent::__construct();
    $this->add(__('Newsletter', null, 'ullMailMessages') . ' ' . __('Home', null, 'common'), 'ullNewsletter/index');
  }
  
  public function addDefaultListEntry()
  {
    $this->add(__('Result list', null, 'common'), 'ullNewsletter/list');
  }
}