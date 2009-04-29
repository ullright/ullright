<?php

/**
 * UllWikiAccessLevelAccess form base class.
 *
 * @package    form
 * @subpackage ull_wiki_access_level_access
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseUllWikiAccessLevelAccessForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'               => new sfWidgetFormInputHidden(),
      'namespace'        => new sfWidgetFormInput(),
      'ull_group_id'     => new sfWidgetFormDoctrineChoice(array('model' => 'UllGroup', 'add_empty' => true)),
      'ull_privilege_id' => new sfWidgetFormDoctrineChoice(array('model' => 'UllPrivilege', 'add_empty' => true)),
      'model_id'         => new sfWidgetFormDoctrineChoice(array('model' => 'UllWikiAccessLevel', 'add_empty' => true)),
      'created_at'       => new sfWidgetFormDateTime(),
      'updated_at'       => new sfWidgetFormDateTime(),
      'creator_user_id'  => new sfWidgetFormDoctrineChoice(array('model' => 'UllUser', 'add_empty' => true)),
      'updator_user_id'  => new sfWidgetFormDoctrineChoice(array('model' => 'UllUser', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'id'               => new sfValidatorDoctrineChoice(array('model' => 'UllWikiAccessLevelAccess', 'column' => 'id', 'required' => false)),
      'namespace'        => new sfValidatorString(array('max_length' => 32, 'required' => false)),
      'ull_group_id'     => new sfValidatorDoctrineChoice(array('model' => 'UllGroup', 'required' => false)),
      'ull_privilege_id' => new sfValidatorDoctrineChoice(array('model' => 'UllPrivilege', 'required' => false)),
      'model_id'         => new sfValidatorDoctrineChoice(array('model' => 'UllWikiAccessLevel', 'required' => false)),
      'created_at'       => new sfValidatorDateTime(array('required' => false)),
      'updated_at'       => new sfValidatorDateTime(array('required' => false)),
      'creator_user_id'  => new sfValidatorDoctrineChoice(array('model' => 'UllUser', 'required' => false)),
      'updator_user_id'  => new sfValidatorDoctrineChoice(array('model' => 'UllUser', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('ull_wiki_access_level_access[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'UllWikiAccessLevelAccess';
  }

}