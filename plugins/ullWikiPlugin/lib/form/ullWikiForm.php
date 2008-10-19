<?php

class ullWikiForm extends PluginUllWikiForm
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
      'body'                      => new sfWidgetFormTextareaFCKEditor(array('width'                    => '800',
                                                                             'height'                   => '400',
                                                                             'CustomConfigurationsPath' => '/ullWikiPlugin/js/FCKeditor_config.js',
                                                                             'BasePath'                 => '/ullWikiPlugin/js/fckeditor/')),
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

    $this->setValidators(array(
      'subject'  => new sfValidatorPass(),
    ));

  }

  public function getModelName()
  {
    return 'UllWiki';
  } 

  public function updateObject()
  {
    $object = parent::updateObject();

    #$object->setFile(str_replace(sfConfig::get('sf_upload_dir').'/', '', $object->getFile()));

    return $object;
  }

}
