<?php

class ullVentoryFilterForm extends ullFilterForm
{
  public function configure()
  {
    parent::configure();
    
    $this->getWidget('search')->setAttribute('size', 14);
    $this->getWidget('search')->setAttribute('title', 
      __('Searches for inventory number, item type, manufacturer, model, serial number and item comment', null, 'ullVentoryMessages'))
    ;
    
//    $this->setValidators(array(
//      'search'   => new sfValidatorString(array('required' => false)),
//    ));

    $c = new ullColumnConfiguration;
    $c
      ->setOption('entity_classes', array('UllVentoryStatusDummyUser', 'UllUser'))
      ->setOption('show_search_box', true)
      ->setWidgetOption('add_empty', true)
      ->setWidgetAttribute('onchange', 'submit()')
      ->setValidatorOption('required', false)
    ;
    $widget = new ullMetaWidgetUllEntity($c, $this);
    $widget->addToFormAs('ull_entity_id');
    
    $this->widgetSchema->setLabels(array(
      'ull_entity_id' => __('Owner', null, 'common'),
    ));    
  }
}
