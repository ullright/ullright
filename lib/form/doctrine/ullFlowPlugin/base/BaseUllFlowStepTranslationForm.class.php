<?php

/**
 * UllFlowStepTranslation form base class.
 *
 * @package    form
 * @subpackage ull_flow_step_translation
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseUllFlowStepTranslationForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'    => new sfWidgetFormInputHidden(),
      'label' => new sfWidgetFormInput(),
      'lang'  => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'id'    => new sfValidatorDoctrineChoice(array('model' => 'UllFlowStepTranslation', 'column' => 'id', 'required' => false)),
      'label' => new sfValidatorString(array('max_length' => 32)),
      'lang'  => new sfValidatorDoctrineChoice(array('model' => 'UllFlowStepTranslation', 'column' => 'lang', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('ull_flow_step_translation[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'UllFlowStepTranslation';
  }

}