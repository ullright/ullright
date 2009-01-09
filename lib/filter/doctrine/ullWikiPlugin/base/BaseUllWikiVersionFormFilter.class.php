<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * UllWikiVersion filter form base class.
 *
 * @package    filters
 * @subpackage UllWikiVersion *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseUllWikiVersionFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'namespace'                 => new sfWidgetFormFilterInput(),
      'subject'                   => new sfWidgetFormFilterInput(),
      'body'                      => new sfWidgetFormFilterInput(),
      'read_counter'              => new sfWidgetFormFilterInput(),
      'edit_counter'              => new sfWidgetFormFilterInput(),
      'duplicate_tags_for_search' => new sfWidgetFormFilterInput(),
      'created_at'                => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'updated_at'                => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'creator_user_id'           => new sfWidgetFormFilterInput(),
      'updator_user_id'           => new sfWidgetFormFilterInput(),
      'deleted'                   => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
    ));

    $this->setValidators(array(
      'namespace'                 => new sfValidatorPass(array('required' => false)),
      'subject'                   => new sfValidatorPass(array('required' => false)),
      'body'                      => new sfValidatorPass(array('required' => false)),
      'read_counter'              => new sfValidatorInteger(array('required' => false)),
      'edit_counter'              => new sfValidatorInteger(array('required' => false)),
      'duplicate_tags_for_search' => new sfValidatorPass(array('required' => false)),
      'created_at'                => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'updated_at'                => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'creator_user_id'           => new sfValidatorInteger(array('required' => false)),
      'updator_user_id'           => new sfValidatorInteger(array('required' => false)),
      'deleted'                   => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
    ));

    $this->widgetSchema->setNameFormat('ull_wiki_version_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'UllWikiVersion';
  }

  public function getFields()
  {
    return array(
      'id'                        => 'Number',
      'namespace'                 => 'Text',
      'subject'                   => 'Text',
      'body'                      => 'Text',
      'read_counter'              => 'Number',
      'edit_counter'              => 'Number',
      'duplicate_tags_for_search' => 'Text',
      'created_at'                => 'Date',
      'updated_at'                => 'Date',
      'creator_user_id'           => 'Number',
      'updator_user_id'           => 'Number',
      'deleted'                   => 'Boolean',
      'version'                   => 'Number',
    );
  }
}