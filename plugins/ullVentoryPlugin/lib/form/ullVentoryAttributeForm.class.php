<?php
/**
 * sfForm for ullVentory
 *
 */
class ullVentoryAttributeForm extends sfForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'attr1'  => new sfWidgetFormInput,
      'attr2'  => new sfWidgetFormInput
    ));
    
    $this->widgetSchema->setNameFormat('attributes[%s]');
//    $this->widgetSchema->setFormFormatterName('ullTable');
  
//    $this->widgetSchema->setLabels(array(
//      'username'    => __('Username', null, 'common'),
//      'password'    => __('Password', null, 'common'),
//    ));
    
    $this->setValidators(array(
        'attr1' => new sfValidatorPass(),
        'attr2' => new sfValidatorPass(),
    ));
  }
}