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
//    $doc,
//    $doc_id,
//    $params = array (
//      'next_step'   => 0,
//      'next_user'   => 0,
//      'next_group'  => 0,
//    )
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
    $widgetAttributes = array(), $validatorOptions = array ())
  {
    $columnConfig = array(
        'access'              => 'w',
        'widgetOptions'       => $widgetOptions,
        'widgetAttributes'    => $widgetAttributes,
        'validatorOptions'    => $validatorOptions
    );
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
  
}

