<?php

class HeaderSearchForm extends sfForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'search'  => new sfWidgetFormInput(array(), array('size' => '8',
                                                        'title' => __('Searches for ID, subject and tags', null, 'common'),
                                                        'id' => 'wiki_header_search_search')),
      'fulltext' => new sfWidgetFormInputHidden(array(), array('value' => '1',
                                                               'id' => 'wiki_header_search_fulltext'))
    ));

    $this->widgetSchema->setFormFormatterName('UllSimple');

    $this->widgetSchema->setLabels(array(
      'search'    => ' '
    ));
  }
}
