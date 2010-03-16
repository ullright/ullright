<?php

class ullCmsGenerator extends ullTableToolGenerator
{

  public function __construct($defaultAccess = null, $requestAction = null, $columns = array())
  {
    parent::__construct('UllPage', $defaultAccess, $requestAction, $columns);
  }
  
  
//  public function configure()
//  {
//    $this->setAllowDelete(false);
//  }
  
}