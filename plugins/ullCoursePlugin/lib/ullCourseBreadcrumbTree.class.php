<?php

class ullCourseBreadcrumbTree extends breadcrumbTree
{
  
  public function __construct()
  {
    parent::__construct();
    $this->add(__('Course', null, 'ullCourseMessages') . ' ' . __('Home', null, 'common'), 'ullCourse/index');
  }
  
  public function addDefaultListEntry()
  {
    $this->add(__('Result list', null, 'common'), 'ullCourse/list');
  }
}