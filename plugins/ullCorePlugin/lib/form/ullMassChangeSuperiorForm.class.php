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
      'old_superior'  => __('Superior until now') . ' *',
      'new_superior'  => __('Future superior') . ' *'
    ));
    
    $this->getWidgetSchema()->setNameFormat('fields[%s]');
    $this->getWidgetSchema()->setFormFormatterName('ullTable');
    
    $this->mergePostValidator(
      new sfValidatorSchemaCompare('old_superior', sfValidatorSchemaCompare::NOT_EQUAL, 'new_superior',
      array('throw_global_error' => true),
      array('invalid' => __('The future superior must be different from the superior until now.'))
    ));
    
//    $this->validatorSchema->setOption('allow_extra_fields', true);
    
  }
}