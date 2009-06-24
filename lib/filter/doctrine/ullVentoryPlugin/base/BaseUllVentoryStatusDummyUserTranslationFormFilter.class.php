<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * UllVentoryStatusDummyUserTranslation filter form base class.
 *
 * @package    filters
 * @subpackage UllVentoryStatusDummyUserTranslation *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseUllVentoryStatusDummyUserTranslationFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'display_name' => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'display_name' => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('ull_ventory_status_dummy_user_translation_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'UllVentoryStatusDummyUserTranslation';
  }

  public function getFields()
  {
    return array(
      'id'           => 'Number',
      'display_name' => 'Text',
      'lang'         => 'Text',
    );
  }
}