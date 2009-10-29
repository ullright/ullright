<?php

class ullUserGenerator extends ullTableToolGenerator
{

  public function __construct($defaultAccess = null, $requestAction = null, $columns = array())
  {
    parent::__construct('UllUser', $defaultAccess, $requestAction, $columns);
  }
  
  
  public function configure()
  {
    $this->setAllowDelete(false);
  }
  
}