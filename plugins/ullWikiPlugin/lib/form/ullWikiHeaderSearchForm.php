<?php

class ullWikiHeaderSearchForm extends sfForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'search'  => new sfWidgetFormInput(array(), array('size' => '8',
                                                        'title' => __('Searches for ID, subject and tags', null, 'common'),
                                                        'id' => 'wiki_header_search_search')),
      'fulltext' => new sfWidgetFormInputHidden(array(), array('id' => 'wiki_header_search_fulltext'))
    ));

    $this->setDefault('fulltext', '1');

    $this->widgetSchema->setLabels(array(
      'search'    => ' '
    ));
  }
}
