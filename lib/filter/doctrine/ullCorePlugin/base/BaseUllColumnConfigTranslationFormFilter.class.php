<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * UllColumnConfigTranslation filter form base class.
 *
 * @package    filters
 * @subpackage UllColumnConfigTranslation *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseUllColumnConfigTranslationFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'label'       => new sfWidgetFormFilterInput(),
      'description' => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'label'       => new sfValidatorPass(array('required' => false)),
      'description' => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('ull_column_config_translation_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'UllColumnConfigTranslation';
  }

  public function getFields()
  {
    return array(
      'id'          => 'Number',
      'label'       => 'Text',
      'description' => 'Text',
      'lang'        => 'Text',
    );
  }
}