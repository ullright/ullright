<?php

class ullTimeReportGenerator extends ullTableToolGenerator
{
  protected
    $report = null
  ;
  
  
  public function __construct($report)
  {
    $this->report = $report;

    switch ($report)
    {
      case 'by_project':      
        $columns = array(
          'UllProject->name',
          'duration_seconds_sum',
        );
        break;
        
      case 'by_user':
        $columns = array(
          'UllUser->display_name',
          'duration_seconds_sum',
        );
        break;
    }
    
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
      ->setCalculateSum(true)
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
