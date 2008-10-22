<?php

class ullWikiForm extends PluginUllWikiForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'subject'                   => new sfWidgetFormInput(array(), array('size' => '50')),
      'body'                      => new sfWidgetFormTextareaFCKEditor(array('width'                    => '800',
                                                                             'height'                   => '400',
                                                                             'CustomConfigurationsPath' => '/ullWikiPlugin/js/FCKeditor_config.js',
                                                                             'BasePath'                 => '/ullWikiPlugin/js/fckeditor/')),
      'changelog_comment'         => new sfWidgetFormInput(array(), array('size' => '50')),
    ));

    #unset($this->widgetSchema['created_at']);
    unset($this->validatorSchema['id']);
    unset($this->validatorSchema['namespace']);
    unset($this->validatorSchema['creator_user_id']);
    unset($this->validatorSchema['updator_user_id']);
    unset($this->validatorSchema['docid']);
    unset($this->validatorSchema['current']);
    unset($this->validatorSchema['culture']);
    unset($this->validatorSchema['read_counter']);
    unset($this->validatorSchema['edit_counter']);
    unset($this->validatorSchema['duplicate_tags_for_search']);
    unset($this->validatorSchema['locked_by_user_id']);
    unset($this->validatorSchema['locked_at']);
    unset($this->validatorSchema['created_at']);
    unset($this->validatorSchema['updated_at']);

    $this->widgetSchema->setLabels(array(
#      'cultures'                  => __('Language', null, 'common'),
      'subject'                   => __('Subject', null, 'common'),
      'body'                      => __('Text', null, 'common'),
      'changelog_comment'         => __('Changelog comment', null, 'common'),
    ));

    $this->getWidgetSchema()->setFormFormatterName('ullTable');
    $this->widgetSchema->setNameFormat('ull_wiki[%s]');
  }

  public function updateObject()
  {
    $object = parent::updateObject();

    $object->setCurrent(true);
    $object->setEditCounter($object->getEditCounter() + 1);

    
    return $object;
  }

}
