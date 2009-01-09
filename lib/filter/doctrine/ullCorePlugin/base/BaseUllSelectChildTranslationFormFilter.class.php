<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * UllSelectChildTranslation filter form base class.
 *
 * @package    filters
 * @subpackage UllSelectChildTranslation *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseUllSelectChildTranslationFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'label' => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'label' => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('ull_select_child_translation_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'UllSelectChildTranslation';
  }

  public function getFields()
  {
    return array(
      'id'    => 'Number',
      'label' => 'Text',
      'lang'  => 'Text',
    );
  }
}