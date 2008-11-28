<?php

class ullWikiFilterForm extends sfForm
{
  public function configure()
  {

    $this->setWidgets(array(
      'search'  => new sfWidgetFormInput(array(), array('size' => '15',
                                                        'onchange' => 'submit()',
                                                        'title' => __('Searches for ID, subject and tags', null, 'common'))),
      'fulltext' => new sfWidgetFormInputHidden(array(), array('id' => 'wiki_header_search_fulltext'))
    ));

    $this->setDefault('fulltext', '0');

    $this->widgetSchema->setLabels(array(
      'search'  => __('Search', null, 'common')
    ));

    $this->setValidators(array(
      'search'   => new sfValidatorPass(),
      'fulltext' => new sfValidatorPass()
    ));

    $this->getWidgetSchema()->setNameFormat('filter[%s]');

    $this->getWidgetSchema()->setFormFormatterName('ullUnorderedList');
  }
}
