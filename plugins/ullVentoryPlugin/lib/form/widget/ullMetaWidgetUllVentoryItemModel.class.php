<?php
/**
 * ullMetaWidgetUllVentoryItemModel
 *
 */
class ullMetaWidgetUllVentoryItemModel extends ullMetaWidget
{
  
  protected function configureWriteMode()
  {
//    $this->columnConfig['widgetOptions']['model'] = 'UllVentoryItemModel';
    $this->columnConfig['widgetOptions']['choices'] = array();
    $this->columnConfig['widgetOptions']['renderer_class'] = 'sfWidgetFormJQueryAutocompleter';
    $this->columnConfig['widgetOptions']['renderer_options'] = array('url' => '/ullVentory/itemModels');
    
    
//    $this->addWidget(new sfWidgetFormDoctrineChoice($this->columnConfig['widgetOptions'], $this->columnConfig['widgetAttributes']));
    $this->addWidget(new sfWidgetFormChoice($this->columnConfig['widgetOptions'], $this->columnConfig['widgetAttributes']));
    $this->addValidator(new sfValidatorPass());
  }
  
  protected function configureReadMode()
  {
    $this->addWidget(new ullWidget($this->columnConfig['widgetOptions'], $this->columnConfig['widgetAttributes']));
    $this->addValidator(new sfValidatorPass());    
  }
  
}
