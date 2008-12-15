<?php

class ullTableToolFilterForm extends sfForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'search'  => new sfWidgetFormInput(array(), array('size' => '15',
                                                        'onchange' => 'submit()',
                                                        'title' => __('Search', null, 'common'))),
    ));

    $this->widgetSchema->setLabels(array(
      'search'  => __('Search', null, 'common')
    ));
    
    $this->setValidators(array(
      'search'  => new sfValidatorPass(),
    ));
    
    $this->getWidgetSchema()->setNameFormat('filter[%s]');
    
    $this->getWidgetSchema()->setFormFormatterName('ullUnorderedList');
  }
}
