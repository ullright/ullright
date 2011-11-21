<?php

class ullMailLogBreadcrumbTree extends ullNewsletterBreadcrumbTree
{
  
  public function __construct()
  {
    parent::__construct();
    $uriMemory = new UriMemory();
    $this->add(__('Newsletter list', null, 'ullMailMessages'), $uriMemory->get('list', 'ullNewsletter'));
  }
  
  public function addDefaultListEntry()
  {
  }
}