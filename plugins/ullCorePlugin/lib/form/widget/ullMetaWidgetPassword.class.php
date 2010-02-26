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

  /**
   * Connect to form.update_object event
   * 
   * @param $columnConfig
   * @param sfForm $form
   * @return none
   */
  public function __construct($columnConfig, sfForm $form)
  {
    $this->dispatcher = sfContext::getInstance()->getEventDispatcher();

    $this->dispatcher->connect('form.update_object', array('ullMetaWidgetPassword', 'listenToUpdateObjectEvent'));

    parent::__construct($columnConfig, $form);
  }

  
  /**
   * Handle unchanged and empty (=removal) of password
   * 
   * @param sfEvent $event
   * @param array $values
   * @return array
   */
  public static function listenToUpdateObjectEvent(sfEvent $event, $values)
  {
    // ******** means no password change
    if (isset($values['password']) && $values['password'] == '********')
    {
      unset($values['password']);
    }
    
    if (isset($values['password']) && $values['password'] == '')
    {
      $values['password'] = null;
    }    

    return $values;
  }

  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/form/widget/ullMetaWidget#configureReadMode()
   */
  protected function configureReadMode()
  {
    $this->columnConfig->setWidgetAttribute('maxlength', null);
    $this->addWidget(new ullWidgetPassword($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
    $this->addValidator(new sfValidatorPass());    
  }
  
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/form/widget/ullMetaWidget#configureWriteMode()
   */
  protected function configureWriteMode()
  {
    // 1st password field
    $widget = new ullWidgetPasswordWrite(
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
