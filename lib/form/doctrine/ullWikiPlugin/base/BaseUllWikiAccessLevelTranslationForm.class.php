<?php

/**
 * UllWikiAccessLevelTranslation form base class.
 *
 * @package    form
 * @subpackage ull_wiki_access_level_translation
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseUllWikiAccessLevelTranslationForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'   => new sfWidgetFormInputHidden(),
      'name' => new sfWidgetFormInput(),
      'lang' => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'id'   => new sfValidatorDoctrineChoice(array('model' => 'UllWikiAccessLevelTranslation', 'column' => 'id', 'required' => false)),
      'name' => new sfValidatorString(array('max_length' => 128, 'required' => false)),
      'lang' => new sfValidatorDoctrineChoice(array('model' => 'UllWikiAccessLevelTranslation', 'column' => 'lang', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('ull_wiki_access_level_translation[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'UllWikiAccessLevelTranslation';
  }

}
