<?php

class ullWikiForm extends sfFormDoctrine
{
  public function configure()
  {
    $this->setWidgets(array(
      //hidden fields
      'id'                => new sfWidgetFormInputHidden(),
      'docid'             => new sfWidgetFormInputHidden(),
      'edit_counter'      => new sfWidgetFormInputHidden(),
      'creator_user_id'   => new sfWidgetFormInputHidden(),
      'created_at'        => new sfWidgetFormInputHidden(),

      #'cultures'                  => new sfWidgetFormSelect(array('choices' => Array())),
      'subject'                   => new sfWidgetFormInput(array(), array('size' => '50')),
      'body'                      => new sfWidgetFormTextarea(array(), array (
                                     'rich' => 'fck', 'size' => '80x40', 'config' => '../ullWikiPlugin/js/FCKeditor_config.js')),
      'changelog_comment'         => new sfWidgetFormInput(array(), array('size' => '50')),
      #'duplicate_tags_for_search' => new sfWidgetFormInput(array(), array('size' => '80')),
    ));

    $this->widgetSchema->setLabels(array(
#      'cultures'                  => __('Language', null, 'common'),
      'subject'                   => __('Subject', null, 'common'),
      'body'                      => __('Text', null, 'common'),
      'changelog_comment'         => __('Changelog comment', null, 'common'),
#      'duplicate_tags_for_search' => __('Tags'),
    ));
    
    $this->getWidgetSchema()->setFormFormatterName('ullTable');
    
  }

  public function getModelName()
  {
    return 'UllWiki';
  } 

}
