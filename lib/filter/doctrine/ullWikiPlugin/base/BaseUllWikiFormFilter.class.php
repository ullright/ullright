<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * UllWiki filter form base class.
 *
 * @package    filters
 * @subpackage UllWiki *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseUllWikiFormFilter extends BaseFormFilterDoctrine
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
      'ull_wiki_access_level_id'  => new sfWidgetFormDoctrineChoice(array('model' => 'UllWikiAccessLevel', 'add_empty' => true)),
      'created_at'                => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'updated_at'                => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'creator_user_id'           => new sfWidgetFormDoctrineChoice(array('model' => 'UllUser', 'add_empty' => true)),
      'updator_user_id'           => new sfWidgetFormDoctrineChoice(array('model' => 'UllUser', 'add_empty' => true)),
      'deleted'                   => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'version'                   => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'namespace'                 => new sfValidatorPass(array('required' => false)),
      'subject'                   => new sfValidatorPass(array('required' => false)),
      'body'                      => new sfValidatorPass(array('required' => false)),
      'read_counter'              => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'edit_counter'              => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'duplicate_tags_for_search' => new sfValidatorPass(array('required' => false)),
      'ull_wiki_access_level_id'  => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'UllWikiAccessLevel', 'column' => 'id')),
      'created_at'                => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'updated_at'                => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'creator_user_id'           => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'UllUser', 'column' => 'id')),
      'updator_user_id'           => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'UllUser', 'column' => 'id')),
      'deleted'                   => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'version'                   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('ull_wiki_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'UllWiki';
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
      'ull_wiki_access_level_id'  => 'ForeignKey',
      'created_at'                => 'Date',
      'updated_at'                => 'Date',
      'creator_user_id'           => 'ForeignKey',
      'updator_user_id'           => 'ForeignKey',
      'deleted'                   => 'Boolean',
      'version'                   => 'Number',
    );
  }
}