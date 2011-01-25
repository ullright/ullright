<?php

class ullTimeProjectFilterForm extends ullFilterForm
{
  public function configure()
  {
    parent::configure();
    
    // user
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

    // project
    $c = new ullColumnConfiguration;
    $c
      ->setRelation(array(
        'model' => 'UllProject',
        'foreign_id' => 'id',
      ))
      ->setOption('show_search_box', true)
      ->setWidgetOption('model', 'UllProject')
      ->setWidgetOption('add_empty', true)
      ->setWidgetAttribute('onchange', 'submit()')
      ->setValidatorOption('required', false)
    ;
    $widget = new ullMetaWidgetForeignKey($c, $this);
    $widget->addToFormAs('ull_project_id');

    // date
    $c = new ullColumnConfiguration;
    $c
      ->setValidatorOption('required', false)
    ;        
    
    $widget = new ullMetaWidgetDate(clone $c, $this);
    $widget->addToFormAs('from_date');    
    
    $widget = new ullMetaWidgetDate(clone $c, $this);
    $widget->addToFormAs('to_date');
    
    
    $this->widgetSchema->setLabels(array(
      'ull_user_id' => __('User', null, 'common'),
      'ull_project_id' => __('Project', null, 'ullTimeMessages'),
      'from_date' => __('Begindate', null, 'common'),
      'to_date' => __('Enddate', null, 'common'),
    ));    
    
    $this->setDefault('from_date', date('Y') . '-01-01');
  }
  
  
}
