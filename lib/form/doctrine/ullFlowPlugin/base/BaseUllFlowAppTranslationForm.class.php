<?php

/**
 * UllFlowAppTranslation form base class.
 *
 * @package    form
 * @subpackage ull_flow_app_translation
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseUllFlowAppTranslationForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'        => new sfWidgetFormInputHidden(),
      'label'     => new sfWidgetFormInput(),
      'doc_label' => new sfWidgetFormInput(),
      'lang'      => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'id'        => new sfValidatorDoctrineChoice(array('model' => 'UllFlowAppTranslation', 'column' => 'id', 'required' => false)),
      'label'     => new sfValidatorString(array('max_length' => 64, 'required' => false)),
      'doc_label' => new sfValidatorString(array('max_length' => 64, 'required' => false)),
      'lang'      => new sfValidatorDoctrineChoice(array('model' => 'UllFlowAppTranslation', 'column' => 'lang', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('ull_flow_app_translation[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'UllFlowAppTranslation';
  }

}
