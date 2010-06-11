<?php
/**
 * ullMetaWidgetFCKEditor
 *
 * Used for strings
 */
class ullMetaWidgetFCKEditor extends ullMetaWidget
{
  protected function addToForm()
  {
    if ($this->isWriteMode())
    {
      $defaults = array(
        'width'                    => '800',
        'height'                   => '400',
        'CustomConfigurationsPath' => '/ullCorePlugin/js/FCKeditor_config.js',
        'BasePath'                 => '/ullCorePlugin/js/fckeditor/',
      );

      $this->columnConfig->setWidgetOptions(array_merge($defaults, $this->columnConfig->getWidgetOptions()));
      
      $this->addWidget(new sfWidgetFormTextareaFCKEditor($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
      $this->addValidator(new sfValidatorString($this->columnConfig->getValidatorOptions()));
    }
    else
    {
      // ullWidgets takes no widget options
      foreach ($this->columnConfig->getWidgetOptions() as $option => $value)
      {
        $this->columnConfig->removeWidgetOption($option);
      }
      
      $this->addWidget(new ullWidgetFCKEditorRead($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
      $this->addValidator(new sfValidatorPass());
    }

  }
}
