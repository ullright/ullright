<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * UllFlowAppTranslation filter form base class.
 *
 * @package    filters
 * @subpackage UllFlowAppTranslation *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseUllFlowAppTranslationFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'label'     => new sfWidgetFormFilterInput(),
      'doc_label' => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'label'     => new sfValidatorPass(array('required' => false)),
      'doc_label' => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('ull_flow_app_translation_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'UllFlowAppTranslation';
  }

  public function getFields()
  {
    return array(
      'id'        => 'Number',
      'label'     => 'Text',
      'doc_label' => 'Text',
      'lang'      => 'Text',
    );
  }
}