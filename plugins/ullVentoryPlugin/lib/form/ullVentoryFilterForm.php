<?php

class ullVentoryFilterForm extends sfForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'search'  => new sfWidgetFormInput(array(), array('size' => '14',
                                                        //'onchange' => 'submit()', Commented
                                                        //if you type in a keyword for search, you have no possibility to click the checkbox
                                                        'title' => __('Searches for inventory number, serial number and item comment'))),
    ));
    
    $this->setValidators(array(
      'search'   => new sfValidatorString(array('required' => false)),
    ));

    $this->getWidgetSchema()->setNameFormat('filter[%s]');
    
    $cc = new ullColumnConfiguration;
    $cc->setOption('entity_classes', array('UllVentoryStatusDummyUser', 'UllUser'));
    $cc->setWidgetOption('add_empty', true);
    $cc->setWidgetAttribute('onchange', 'submit()');
    $cc->setValidatorOption('required', false);
    $widget = new ullMetaWidgetUllEntity($cc, $this);
    $widget->addToFormAs('ull_entity_id');
    
    $this->widgetSchema->setLabels(array(
      'search'        => __('Search', null, 'common'),
      'ull_entity_id' => __('Owner', null, 'common'),
    ));    
    
  }
}
