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
    if ($values['password'] == '')
    {
      unset($values['password']);
    }
    
    return $values;
  }
  
  protected function addToForm()
  {
    if ($this->isWriteMode())
    {
//      $this->columnConfig['widgetOptions']['always_render_empty'] = false;
      
      $widget = new sfWidgetFormInputPassword($this->columnConfig['widgetOptions'], $this->columnConfig['widgetAttributes']);
      $validator = new sfValidatorString($this->columnConfig['validatorOptions']);
      
      $this->addWidget($widget);
      $this->addValidator($validator);
      
      $confirmationColumnName = $this->columnName . '_confirmation';
      $this->addWidget($widget, $confirmationColumnName);
      $this->addValidator(clone $validator, $confirmationColumnName);
      $this->form->getWidgetSchema()->moveField($confirmationColumnName, 'after', $this->columnName); 
      $this->form->mergePostValidator(new sfValidatorSchemaCompare(
          $this->columnName, 
          sfValidatorSchemaCompare::EQUAL, 
          $confirmationColumnName, 
          array(), 
          array('invalid' => 'Please enter the same password twice')
      )); 
    }
    else
    {
      unset($this->columnConfig['widgetAttributes']['maxlength']);
      $this->addWidget(new ullWidgetPassword($this->columnConfig['widgetOptions'], $this->columnConfig['widgetAttributes']));
      $this->addValidator(new sfValidatorPass());
    }

  }
}

?>