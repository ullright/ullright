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

      $this->columnConfig['widgetOptions'] = array_merge($defaults, $this->columnConfig['widgetOptions']);
      
      $this->addWidget(new sfWidgetFormTextareaFCKEditor($this->columnConfig['widgetOptions'], $this->columnConfig['widgetAttributes']));
      $this->addValidator(new sfValidatorString($this->columnConfig['validatorOptions']));
    }
    else
    {
      $this->addWidget(new ullWidget($this->columnConfig['widgetOptions'], $this->columnConfig['widgetAttributes']));
      $this->addValidator(new sfValidatorPass());
    }

  }
}

?>