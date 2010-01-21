<?php

class ullTimeReportGenerator extends ullTableToolGenerator
{
  
  public function __construct()
  {
     $columns = array(
      'ull_project_id',
      'duration_seconds_sum',
    );
    
    parent::__construct('UllProjectReporting', 'r', 'list', $columns);
  }  
  
  public function configure()
  {

  }

  
//  protected function customizeTableConfig()
//  {
//    // Override default orderby
//    $this->tableConfig->setOrderBy('last_name_first');
//  }   
//  
//  
  protected function customizeColumnsConfig()
  {
//    $this->columnsConfig['email']
//      ->setWidgetOption('show_icon_only', true)
//      ->setLabel(' ')
//    ;
//    $this->columnsConfig['phone_extension']
//      ->setLabel(__('Ext.', null, 'ullPhoneMessages'))
//    ;        
//    $this->columnsConfig['mobile_number']
//      ->setLabel(__('Mobile', null, 'ullPhoneMessages'))
//      ->setWidgetOption('nowrap', true)
//      ->setOption('show_local_short_form', true)
//    ;

    // Create columnConfig for artificial "last_name_first" column
    $this->getColumnsConfig()
      ->create('duration_seconds_sum')
      ->setLabel(__('Duration', null, 'common'))
      ->setMetaWidgetClassName('ullMetaWidgetTimeDuration')
      ->setIsArtificial(true)
    ;
    
    
  }
//
//  
//  protected function customizeRelationColumns()
//  {
//    $this->columnsConfig['UllLocation->name']
//      ->setWidgetOption('nowrap', true)
//    ;
//  }  
}
