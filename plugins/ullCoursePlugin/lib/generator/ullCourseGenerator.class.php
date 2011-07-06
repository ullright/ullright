<?php

class ullCourseGenerator extends ullTableToolGenerator
{

  public function __construct($defaultAccess = null, $requestAction = null, $columns = array())
  {
    parent::__construct('UllCourse', $defaultAccess, $requestAction, $columns);
  }
  
  
  public function configure()
  {
    //$this->setAllowDelete(false);
  }
  
}