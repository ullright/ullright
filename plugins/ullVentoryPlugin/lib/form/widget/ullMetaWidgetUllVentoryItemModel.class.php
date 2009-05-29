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
    $this->columnConfig->setWidgetOption('choices', array());
    $this->columnConfig->setWidgetOption('renderer_class', 'sfWidgetFormJQueryAutocompleter');
    $this->columnConfig->setWidgetOption('renderer_options', array('url' => '/ullVentory/itemModels'));
    
    
//    $this->addWidget(new sfWidgetFormDoctrineChoice($this->columnConfig['widgetOptions'], $this->columnConfig['widgetAttributes']));
    $this->addWidget(new sfWidgetFormChoice($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
    $this->addValidator(new sfValidatorPass());
  }
  
  protected function configureReadMode()
  {
    $this->addWidget(new ullWidget($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
    $this->addValidator(new sfValidatorPass());    
  }
  
}
