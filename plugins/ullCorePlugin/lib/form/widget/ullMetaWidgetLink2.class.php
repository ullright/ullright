<?php
/**
 * ullMetaWidgetLink2
 * 
 * Supply the following widgetOptions:
 *   'name'    column name to display the link name
 *   'model'   doctrine model name
 *   'uri'     symfony uri containing strtr variables 
 *             Example: 'myModule/myAction?slug=%slug%'
 *   'params'  strtr params for the uri. The value has to be a column name of the given model 
 *             Example: array('%slug%' => 'slug')
 */
class ullMetaWidgetLink2 extends ullMetaWidgetString
{
  protected function configureReadMode()
  {
    $this->columnConfig->removeWidgetAttribute('size');
    $this->columnConfig->removeWidgetAttribute('maxlength');
    
    $this->columnConfig->setWidgetOption('model', $this->columnConfig->getModelName());
    $this->columnConfig->setInjectIdentifier(true);

    $this->addWidget(new ullWidgetLink2($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
    $this->addValidator(new sfValidatorPass());
  }  
}
