<?php

/**
 * TestTableTranslation form base class.
 *
 * @package    form
 * @subpackage test_table_translation
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseTestTableTranslationForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'        => new sfWidgetFormInputHidden(),
      'my_string' => new sfWidgetFormInput(),
      'my_text'   => new sfWidgetFormTextarea(),
      'lang'      => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'id'        => new sfValidatorDoctrineChoice(array('model' => 'TestTableTranslation', 'column' => 'id', 'required' => false)),
      'my_string' => new sfValidatorString(array('max_length' => 64)),
      'my_text'   => new sfValidatorString(array('required' => false)),
      'lang'      => new sfValidatorDoctrineChoice(array('model' => 'TestTableTranslation', 'column' => 'lang', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('test_table_translation[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'TestTableTranslation';
  }

}
