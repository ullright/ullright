<?php

class ullWikiBreadcrumbTree extends breadcrumbTree
{
  
  public function __construct()
  {
    parent::__construct();
    $this->add(__('Wiki', null, 'ullWikiMessages') . ' ' . __('Home', null, 'common'), 'ullWiki/index');
  }
}