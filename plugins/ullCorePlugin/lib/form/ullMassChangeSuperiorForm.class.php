<?php
class ullMassChangeSuperiorForm extends sfForm
{
  public function configure()
  {
    $tempCC = new ullColumnConfiguration();
    $tempCC
      ->setValidatorOption('required', true)
      ->setWidgetOption('add_empty', true)
      ->setOption('entity_classes', array('UllUser'))
    ;
    //ullMetaWidgetUllEntity modifies the column config
    //(removes add_empty)
    $tempCC2 = clone $tempCC;
    $oldSuperiorWidget = new ullMetaWidgetUllEntity($tempCC, $this);
    $newSuperiorWidget = new ullMetaWidgetUllEntity($tempCC2, $this);
    
    $oldSuperiorWidget->addToFormAs('old_superior');
    $newSuperiorWidget->addToFormAs('new_superior');
    
    $this->widgetSchema->setLabels(array(
      'old_superior'  => __('Current superior') . ' *',
      'new_superior'  => __('Replacing superior') . ' *'
    ));
    
    $this->getWidgetSchema()->setNameFormat('fields[%s]');
    $this->getWidgetSchema()->setFormFormatterName('ullTable');
    $this->validatorSchema->setOption('allow_extra_fields', true);
    
  }
}