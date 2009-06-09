<?php

class UllVentoryCreateForm extends sfForm
{
  public function configure()
  {
    $choices = UllVentoryItemTypeTable::findChoicesIndexedBySlug();
    $this->setWidgets(array(
      'type'  => new sfWidgetFormSelect(array(
        'choices' => array_merge(array('' => ''), $choices)))
    ));
    
    $this->widgetSchema->setLabels(array(
      'type'    => __('Type'),
    ));
    
    $this->setValidators(array(
      'type' => new sfValidatorChoice(array('choices' => array_keys($choices), 'required' => true)),
    ));
    
    $this->getWidgetSchema()->setNameFormat('fields[%s]');
  }
}
