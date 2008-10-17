<?php

/**
 * Tag form base class.
 *
 * @package    form
 * @subpackage tag
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseTagForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'               => new sfWidgetFormInputHidden(),
      'name'             => new sfWidgetFormInput(),
      'is_triple'        => new sfWidgetFormInputCheckbox(),
      'triple_namespace' => new sfWidgetFormInput(),
      'triple_key'       => new sfWidgetFormInput(),
      'triple_value'     => new sfWidgetFormInput(),
    ));

    $this->setValidators(array(
      'id'               => new sfValidatorDoctrineChoice(array('model' => 'Tag', 'column' => 'id', 'required' => false)),
      'name'             => new sfValidatorPass(array('required' => false)),
      'is_triple'        => new sfValidatorBoolean(array('required' => false)),
      'triple_namespace' => new sfValidatorPass(array('required' => false)),
      'triple_key'       => new sfValidatorPass(array('required' => false)),
      'triple_value'     => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('tag[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Tag';
  }

}