<?php

class ullNewsGenerator extends ullTableToolGenerator
{

  public function __construct($defaultAccess = null, $requestAction = null, $columns = array())
  {
    parent::__construct('UllNews', $defaultAccess, $requestAction, $columns);
  }
  
  
  public function configure()
  {
    $this->setAllowDelete(false);
  }
  
}