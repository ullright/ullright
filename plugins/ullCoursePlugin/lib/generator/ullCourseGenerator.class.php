<?php

class ullCourseGenerator extends ullTableToolGenerator
{

  public function __construct($defaultAccess = null, $requestAction = null, $columns = array())
  {
    parent::__construct('UllCourse', $defaultAccess, $requestAction, $columns);
  }
  
  
  public function configure()
  {
  }
  
  
  protected function customizeTableConfig()
  {
    if (sfContext::getInstance()->getActionName() == 'offering')
    {
      $this->getTableConfig()
        ->setOrderBy('begin_date desc')      
        ->setListColumns(array(
          'name', 
          'trainer_ull_user_id',
          'begin_date',
          'end_date' 
        ))      
        ->setFilterColumns(array())
      ;      
      
      
    }
  }
}