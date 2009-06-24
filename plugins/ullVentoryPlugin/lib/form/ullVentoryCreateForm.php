<?php

class UllVentoryCreateForm extends sfForm
{
  public function configure()
  {
    $choices = UllVentoryItemTypeTable::findChoicesIndexedBySlug();
    $this->setWidgets(array(
      'type'  => new sfWidgetFormSelect(array(
        'choices' => array_merge(array('' => ''), $choices))),
//      'entity' => new sfWidgetFormInputHidden()
    ));
    
    $this->widgetSchema->setLabels(array(
      'type'    => __('Type', null, 'common'),
    ));
    
    $this->setValidators(array(
      'type' => new sfValidatorChoice(array('choices' => array_keys($choices), 'required' => true)),
//      'entity' => new sfValidatorPass()
    ));
    
    $this->getWidgetSchema()->setNameFormat('fields[%s]');
  }
}
