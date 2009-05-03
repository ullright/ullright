<?php

/**
 * UllColumnConfigTranslation form base class.
 *
 * @package    form
 * @subpackage ull_column_config_translation
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseUllColumnConfigTranslationForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'label'       => new sfWidgetFormInput(),
      'description' => new sfWidgetFormTextarea(),
      'lang'        => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorDoctrineChoice(array('model' => 'UllColumnConfigTranslation', 'column' => 'id', 'required' => false)),
      'label'       => new sfValidatorString(array('max_length' => 64, 'required' => false)),
      'description' => new sfValidatorString(array('required' => false)),
      'lang'        => new sfValidatorDoctrineChoice(array('model' => 'UllColumnConfigTranslation', 'column' => 'lang', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('ull_column_config_translation[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'UllColumnConfigTranslation';
  }

}
