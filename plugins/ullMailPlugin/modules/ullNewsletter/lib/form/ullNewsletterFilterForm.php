<?php

class ullNewsletterFilterForm extends ullFilterForm
{
  public function configure()
  {
    parent::configure();
    
    $model = 'UllNewsletterMailingList';
    $toStringColumn = 'id';
    
    $q = new Doctrine_Query();
    $q
      ->select($toStringColumn)
      ->from($model)
      ->orderBy('name')
    ;
    
    $columnConfig = new ullColumnConfiguration;
    $columnConfig
      ->setWidgetOption('model', $model)
      ->setWidgetOption('query', $q)
      ->setValidatorOption('model', $model)
      ->setValidatorOption('query', $q)
      ->setWidgetAttribute('onchange', 'submit()')
      ->setOption('show_search_box', true)
      ->setWidgetOption('add_empty', true)
    ;        
    
    $widget = new ullMetaWidgetForeignKey($columnConfig, $this);
    $widget->addToFormAs('ull_newsletter_mailing_list_id');
    
    $this->widgetSchema->setLabels(array(
      'ull_newsletter_mailing_list_id' => __('Mailing lists', null, 'ullMailMessages'),
    ));    
  }
}
