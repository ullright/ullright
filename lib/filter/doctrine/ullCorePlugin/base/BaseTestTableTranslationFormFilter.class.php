<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * TestTableTranslation filter form base class.
 *
 * @package    filters
 * @subpackage TestTableTranslation *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseTestTableTranslationFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'my_string' => new sfWidgetFormFilterInput(),
      'my_text'   => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'my_string' => new sfValidatorPass(array('required' => false)),
      'my_text'   => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('test_table_translation_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'TestTableTranslation';
  }

  public function getFields()
  {
    return array(
      'id'        => 'Number',
      'my_string' => 'Text',
      'my_text'   => 'Text',
      'lang'      => 'Text',
    );
  }
}