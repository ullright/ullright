<?php

class ullWikiFilterForm extends sfForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'search'  => new sfWidgetFormInput(array(), array('size' => '14',
                                                        //'onchange' => 'submit()', Commented
                                                        //if you type in a keyword for search, you have no possibility to click the checkbox
                                                        'title' => __('Searches for ID, subject and tags', null, 'common'))),
      'fulltext' => new sfWidgetFormInputCheckbox(array(), array('value' => '1'))
    ));
    
    $this->setValidators(array(
      'search'   => new sfValidatorString(array('required' => false)),
      'fulltext' => new sfValidatorBoolean()
    ));

    $this->getWidgetSchema()->setNameFormat('filter[%s]');
    
    $this->widgetSchema->setLabels(array(
      'search'    => __('Search', null, 'common'),
      'fulltext'  => __('Full text', null, 'common'),
    ));
  }
}
