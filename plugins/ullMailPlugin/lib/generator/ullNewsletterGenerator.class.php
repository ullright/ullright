<?php

class ullNewsLetterGenerator extends ullTableToolGenerator
{

  public function __construct($defaultAccess = null, $requestAction = null, $columns = array())
  {
    parent::__construct('UllNewsLetterEdition', $defaultAccess, $requestAction, $columns);
  }
  
  
  public function configure()
  {
    $this->setAllowDelete(false);
  }
  
}