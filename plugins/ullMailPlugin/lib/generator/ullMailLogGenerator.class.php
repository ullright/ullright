<?php

class ullMailLogGenerator extends ullTableToolGenerator
{
//  protected $filterFormClass = 'ullNewsletterFilterForm';
  
  public function __construct($defaultAccess = null, $requestAction = null, $columns = array())
  {
    parent::__construct('UllMailLoggedMessage', $defaultAccess, $requestAction, $columns);
  }
  
  
  public function configure()
  {
    $this->setAllowDelete(false);
  }
  
}