<?php

/**
 * UllFlowColumnConfigTranslation form base class.
 *
 * @package    form
 * @subpackage ull_flow_column_config_translation
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseUllFlowColumnConfigTranslationForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'    => new sfWidgetFormInputHidden(),
      'label' => new sfWidgetFormInput(),
      'lang'  => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'id'    => new sfValidatorDoctrineChoice(array('model' => 'UllFlowColumnConfigTranslation', 'column' => 'id', 'required' => false)),
      'label' => new sfValidatorString(array('max_length' => 64, 'required' => false)),
      'lang'  => new sfValidatorDoctrineChoice(array('model' => 'UllFlowColumnConfigTranslation', 'column' => 'lang', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('ull_flow_column_config_translation[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'UllFlowColumnConfigTranslation';
  }

}
