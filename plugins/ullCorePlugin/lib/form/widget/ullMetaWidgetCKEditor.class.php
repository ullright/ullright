<?php
/**
 * ullMetaWidgetCKEditor
 *
 * Used for strings
 */
class ullMetaWidgetCKEditor extends ullMetaWidget
{
  protected function addToForm()
  {
    if ($this->isWriteMode())
    {
      $defaults = array(
        'width'                    => '800',
        'height'                   => '400',
//        'CustomConfigurationsPath' => '/ullCorePlugin/js/CKeditor_config.js',
//        'BasePath'                 => '/ullCorePlugin/js/CKeditor/',
      );

      $this->columnConfig->removeWidgetOption('decode_mime');
      
      $this->columnConfig->setWidgetOptions(array_merge($defaults, $this->columnConfig->getWidgetOptions()));
      
      $this->addWidget(new sfWidgetFormTextareaCKEditor($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
      $this->addValidator(new sfValidatorString($this->columnConfig->getValidatorOptions()));
    }
    else
    {
      // ullWidgets takes no widget options
      foreach ($this->columnConfig->getWidgetOptions() as $option => $value)
      {
        $this->columnConfig->removeWidgetOption($option);
      }
      
      $this->addWidget(new ullWidgetCKEditorRead($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
      $this->addValidator(new sfValidatorPass());
    }

  }
}
