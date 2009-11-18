<?php
/**
 * ullMetaWidgetPassword
 *
 * Used for passwords
 */
class ullMetaWidgetPassword extends ullMetaWidget
{
  protected
    $dispatcher
  ;

  public function __construct($columnConfig, sfForm $form)
  {
    $this->dispatcher = sfContext::getInstance()->getEventDispatcher();

    $this->dispatcher->connect('form.update_object', array('ullMetaWidgetPassword', 'listenToUpdateObjectEvent'));

    parent::__construct($columnConfig, $form);
  }

  
  public static function listenToUpdateObjectEvent(sfEvent $event, $values)
  {
    if (isset($values['password']) && $values['password'] == '')
    {
      unset($values['password']);
    }

    return $values;
  }

  
  protected function configureReadMode()
  {
    $this->columnConfig->setWidgetAttribute('maxlength', null);
    $this->addWidget(new ullWidgetPassword($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
    $this->addValidator(new sfValidatorPass());    
  }
  
  
  protected function configureWriteMode()
  {
    // 1st password field
    $widget = new sfWidgetFormInputPassword(
      $this->columnConfig->getWidgetOptions(), 
      $this->columnConfig->getWidgetAttributes()
    );
    $validator = new ullValidatorPassword($this->columnConfig->getValidatorOptions());

    $this->addWidget($widget);
    $this->addValidator($validator);

    // 2nd password field (confirmation)
    $confirmationColumnName = $this->columnName . '_confirmation';
    $this->addWidget($widget, $confirmationColumnName);
    $this->form->getWidgetSchema()->setLabels(array(
      'password_confirmation'  => __('Password confirmation', null, 'common')
    ));
    $this->addValidator(clone $validator, $confirmationColumnName);
    $this->form->getWidgetSchema()->moveField($confirmationColumnName, 'after', $this->columnName);
    $this->form->mergePostValidator(new sfValidatorSchemaCompare(
        $this->columnName,
        sfValidatorSchemaCompare::EQUAL,
        $confirmationColumnName,
        array(),
        array('invalid' => __('Please enter the same password twice', null, 'ullCoreMessages'))
    ));
  }
}
