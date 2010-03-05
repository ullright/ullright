<?php

class ullUserFilterForm extends ullFilterForm
{
  public function configure()
  {
    parent::configure();
    
    $this->getWidget('search')->setAttribute('title', __('Searches for name, username, email, location and department', null, 'ullCoreMessages'));
    
    $c = new ullColumnConfiguration;
    $c
      ->setOption('entity_classes', array('UllUser'))
      ->setOption('show_search_box', true)
      ->setWidgetOption('add_empty', true)
      ->setWidgetAttribute('onchange', 'submit()')
      ->setValidatorOption('required', false)
    ;
    $widget = new ullMetaWidgetUllEntity($c, $this);
    $widget->addToFormAs('id');
    
    $this->widgetSchema->setLabels(array(
      'id' => __('User selection', null, 'common'),
    ));      
  }
}
