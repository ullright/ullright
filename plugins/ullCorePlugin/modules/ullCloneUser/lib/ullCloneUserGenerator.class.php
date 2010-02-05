<?php

class ullCloneUserGenerator extends ullTableToolGenerator
{

  public function __construct($defaultAccess = null, $requestAction = null, $columns = array())
  {
    parent::__construct('UllCloneUser', $defaultAccess, $requestAction, $columns);
  }
  
  
  public function configure()
  {
    $this->setAllowDelete(false);
    $this->setFormClassName('ullCloneUserGeneratorForm');
  }
  
}