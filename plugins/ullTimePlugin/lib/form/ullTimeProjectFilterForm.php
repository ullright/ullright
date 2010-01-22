<?php

class ullTimeProjectFilterForm extends ullFilterForm
{
  public function configure()
  {
    parent::configure();
    
    $c = new ullColumnConfiguration;
    $c
      ->setOption('entity_classes', array('UllUser'))
      ->setOption('show_search_box', true)
      ->setWidgetOption('add_empty', true)
      ->setWidgetAttribute('onchange', 'submit()')
      ->setValidatorOption('required', false)
    ;
    $widget = new ullMetaWidgetUllEntity($c, $this);
    $widget->addToFormAs('ull_user_id');
    
    $this->widgetSchema->setLabels(array(
      'ull_user_id' => __('User', null, 'common'),
    ));    
  }
}
