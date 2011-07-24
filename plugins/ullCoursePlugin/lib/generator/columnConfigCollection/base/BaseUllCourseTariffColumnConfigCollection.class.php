<?php 

class BaseUllCourseTariffColumnConfigCollection extends ullColumnConfigCollection
{
  
  /**
   * Applies model specific custom column configuration
   * 
   */
  protected function applyCustomSettings()
  {
    $this['display_name']
      ->setLabel(__('Tariff', null, 'ullCourseMessages'))
    ;
    
    $this['name']
      ->setLabel(__('Name', null, 'ullCoreMessages'))
    ;
    
    $this['price']
      ->setLabel(__('Price', null, 'ullCourseMessages'))
      ->setMetaWidgetClassName('ullMetaWidgetFloat')
      // right align in list view
      ->setWidgetAttribute('class', 'ull_widget_time')
    ;
    
    if ($this->isCreateOrEditAction())
    {
      $this->disable(array(
        'display_name'
      ));
    } 

  }
 
}