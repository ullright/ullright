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

  
  protected function customizeColumnsConfig()
  {
    $this->getColumnsConfig()
      ->create('duration_seconds_sum')
      ->setLabel(__('Duration', null, 'common'))
      ->setMetaWidgetClassName('ullMetaWidgetTimeDuration')
      ->setIsArtificial(true)
      ->setCalculateSum(true)
    ;
    
  }
 
  protected function customizeRelationColumns()
  {
    switch ($this->report)
    {
      case 'by_project':      
        $url = urldecode(ull_url_for(array('report' => 'by_user', 'filter[ull_project_id]' => '%d')));
        
        $this->getColumnsConfig()->offsetGet('UllProject->name')
          ->setMetaWidgetClassName('ullMetaWidgetForeignKey')
          ->setWidgetOption('link_name_to_url', $url)
        ;
        break;
        
      case 'by_user':
        $url = urldecode(ull_url_for(array('report' => 'by_project', 'filter[ull_user_id]' => '%d')));
        
        $this->getColumnsConfig()->offsetGet('UllUser->display_name')
          ->setMetaWidgetClassName('ullMetaWidgetForeignKey')
          ->setWidgetOption('link_name_to_url', $url)
          ->setWidgetOption('link_icon_to_popup', true)
        ;
        break;
    }    
    
  }
  
}
