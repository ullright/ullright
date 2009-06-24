<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * UllVentoryOriginDummyUserTranslation filter form base class.
 *
 * @package    filters
 * @subpackage UllVentoryOriginDummyUserTranslation *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseUllVentoryOriginDummyUserTranslationFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'display_name' => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'display_name' => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('ull_ventory_origin_dummy_user_translation_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'UllVentoryOriginDummyUserTranslation';
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