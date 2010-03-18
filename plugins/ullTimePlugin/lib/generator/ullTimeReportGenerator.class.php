<?php

class ullTimeReportGenerator extends ullTableToolGenerator
{
  protected
    $report = null,
    $filterParams = array()
  ;
  
  
  public function __construct($report, $filterParams)
  {
    $this->report = $report;
    $this->filterParams = $filterParams;

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
        
      case 'details':
        $columns = array(
          'date',
          'duration_seconds',
          'comment'
        );
        
        if (!isset($filterParams['ull_project_id']))
        {
          array_unshift($columns, 'UllProject->name');
        }
        
        if (!isset($filterParams['ull_user_id']))
        {
          array_unshift($columns, 'UllUser->display_name');
        }
        
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
//    $url = null;
//    
//    // Intelligently forward to the details report if both project and user are selected
//    if (isset($this->filterParams['ull_project_id']) && isset($this->filterParams['ull_user_id']))
//    {
//      $url = urldecode(ull_url_for(array(
//        'report' => 'details', 
//        'filter[ull_project_id]' => $this->filterParams['ull_project_id'],
//        'filter[ull_user_id]' => $this->filterParams['ull_user_id'],
//      )));
//    }

    
    switch ($this->report)
    {
      case 'by_project':
        if (isset($this->filterParams['ull_user_id']))
        {
          $url = urldecode(ull_url_for(array(
            'report' => 'details', 
            'filter[ull_user_id]' => $this->filterParams['ull_user_id'],
            'filter[ull_project_id]' => '%d',
            'order' => '',
          )));
        }
        else
        {
          $url = urldecode(ull_url_for(array('report' => 'by_user', 'filter[ull_project_id]' => '%d')));
        }
        
        $this->getColumnsConfig()->offsetGet('UllProject->name')
          ->setMetaWidgetClassName('ullMetaWidgetForeignKey')
          ->setWidgetOption('link_name_to_url', $url)
        ;
        break;
        
      case 'by_user':
        if (isset($this->filterParams['ull_project_id']))
        {
          $url = urldecode(ull_url_for(array(
            'report' => 'details', 
            'filter[ull_project_id]' => $this->filterParams['ull_project_id'],
            'filter[ull_user_id]' => '%d',
            'order' => '',
          )));
        }
        else
        {
          $url = urldecode(ull_url_for(array('report' => 'by_project', 'filter[ull_user_id]' => '%d')));
        }
        
        
        $this->getColumnsConfig()->offsetGet('UllUser->display_name')
          ->setMetaWidgetClassName('ullMetaWidgetForeignKey')
          ->setWidgetOption('link_name_to_url', $url)
          ->setWidgetOption('link_icon_to_popup', true)
        ;
        break;
    }    
    
  }
  
}
