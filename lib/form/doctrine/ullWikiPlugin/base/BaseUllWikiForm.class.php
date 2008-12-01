<?php

/**
 * UllWiki form base class.
 *
 * @package    form
 * @subpackage ull_wiki
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseUllWikiForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                        => new sfWidgetFormInputHidden(),
      'namespace'                 => new sfWidgetFormInput(),
      'creator_user_id'           => new sfWidgetFormDoctrineSelect(array('model' => 'UllUser', 'add_empty' => true)),
      'updator_user_id'           => new sfWidgetFormDoctrineSelect(array('model' => 'UllUser', 'add_empty' => true)),
      'docid'                     => new sfWidgetFormInput(),
      'culture'                   => new sfWidgetFormInput(),
      'body'                      => new sfWidgetFormTextarea(),
      'subject'                   => new sfWidgetFormInput(),
      'changelog_comment'         => new sfWidgetFormInput(),
      'read_counter'              => new sfWidgetFormInput(),
      'edit_counter'              => new sfWidgetFormInput(),
      'duplicate_tags_for_search' => new sfWidgetFormTextarea(),
      'locked_by_user_id'         => new sfWidgetFormDoctrineSelect(array('model' => 'UllUser', 'add_empty' => true)),
      'locked_at'                 => new sfWidgetFormDateTime(),
      'created_at'                => new sfWidgetFormDateTime(),
      'updated_at'                => new sfWidgetFormDateTime(),
      'deleted'                   => new sfWidgetFormInputCheckbox(),
      'version'                   => new sfWidgetFormInput(),
    ));

    $this->setValidators(array(
      'id'                        => new sfValidatorDoctrineChoice(array('model' => 'UllWiki', 'column' => 'id', 'required' => false)),
      'namespace'                 => new sfValidatorString(array('max_length' => 32, 'required' => false)),
      'creator_user_id'           => new sfValidatorDoctrineChoice(array('model' => 'UllUser', 'required' => false)),
      'updator_user_id'           => new sfValidatorDoctrineChoice(array('model' => 'UllUser', 'required' => false)),
      'docid'                     => new sfValidatorInteger(),
      'culture'                   => new sfValidatorString(array('max_length' => 7, 'required' => false)),
      'body'                      => new sfValidatorString(array('required' => false)),
      'subject'                   => new sfValidatorString(array('max_length' => 255)),
      'changelog_comment'         => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'read_counter'              => new sfValidatorInteger(array('required' => false)),
      'edit_counter'              => new sfValidatorInteger(array('required' => false)),
      'duplicate_tags_for_search' => new sfValidatorString(array('required' => false)),
      'locked_by_user_id'         => new sfValidatorDoctrineChoice(array('model' => 'UllUser', 'required' => false)),
      'locked_at'                 => new sfValidatorDateTime(array('required' => false)),
      'created_at'                => new sfValidatorDateTime(array('required' => false)),
      'updated_at'                => new sfValidatorDateTime(array('required' => false)),
      'deleted'                   => new sfValidatorBoolean(),
      'version'                   => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('ull_wiki[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'UllWiki';
  }

}