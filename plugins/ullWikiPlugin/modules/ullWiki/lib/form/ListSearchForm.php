<?php

class ListSearchForm extends sfForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'search'  => new sfWidgetFormInput(array(), array('size' => '15',
                                                        'onchange' => 'submit()',
                                                        'title' => __('Searches for ID, subject and tags', null, 'common')))
    ));

    $this->widgetSchema->setFormFormatterName('ullSimple');
  
    $this->widgetSchema->setLabels(array(
      'search'    => ' '
    ));
  }
}
