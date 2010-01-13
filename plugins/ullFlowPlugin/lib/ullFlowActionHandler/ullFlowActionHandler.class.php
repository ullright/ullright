<?php

/**
 * ullFlowActionHandler
 * 
 * Base class for all ullFlow actions
 *
 */

abstract class ullFlowActionHandler
{
  
  protected
    $options = array(),
    $form,
    // used to store the form fields for one handler to allow setting the
    //  validators to required for all widgets of one handler
    $formFields = array()    
  ;
  
  public function __construct(sfForm $form, $options = array())
  {
    $this->setForm($form);
    $this->setOptions($options);
    $this->configure();
  }
  
  public function configure()
  {
  }
  
  public function setOptions($options) 
  {
    if (!is_array($options)) 
    {
      $options = sfToolkit::stringToArray($options);
    }
    
    $this->options = $options;
  }

  public function setForm($form) 
  {
    $this->form = $form;
  }
  
  public function getForm()
  {
    return $this->form;
  }
  
  public function getFormFields()
  {
    return $this->formFields;
  }
  

  public function addMetaWidget($class, $name, $widgetOptions = array(), 
    $widgetAttributes = array(), $validatorOptions = array (), $columnConfigOptions = array())
  {
    $columnConfig = new ullColumnConfiguration();
    $columnConfig->setOptions($columnConfigOptions);
    $columnConfig->setWidgetOptions($widgetOptions);
    $columnConfig->setWidgetAttributes($widgetAttributes);
    $columnConfig->setValidatorOptions($validatorOptions);
    
    $ullMetaWidget = new $class($columnConfig, $this->form);
    $ullMetaWidget->addToFormAs($name);
    
    $this->formFields[] = $name;
  }
  
  abstract public function render();
  
  public function getNext()
  {
  }

  /**
   * Returns "next" array with entity and step for the previous workflow step
   *
   * @return array
   */
  public function getNextFromPreviousStep() 
  {
    $memory = $this->form->getObject()->findPreviousNonStatusOnlyMemory();
    
//    var_dump($memory->toArray());
    
    return array(
      'entity' => $memory->AssignedToUllEntity, 
      'step' => $memory->UllFlowStep
    );
  }
  
  /**
   * Empty method called on post-save of the UllFlowDoc object
   * 
   * Custom Mails can be sent here
   *
   */
  public function sendMail()
  {
  }
  
  /**
   * Intended to be overwritten by child classes in case of
   * added form fields, since we need to remove them before
   * further processing in ullFlowForm's updateObject().
   * 
   * @return none
   */
  public static function getFormFieldNames()
  {
    return array();
  }
}

