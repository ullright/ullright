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
    $generator,
    // used to store the form fields for one handler to allow setting the
    //  validators to required for all widgets of one handler
    $formFields = array()    
  ;
  
  /**
   * Constructor
   * 
   * @param sfForm $form
   * @param array $options
   */
  public function __construct(ullGenerator $generator, $options = array())
  {
    $this->setGenerator($generator);
    $this->setOptions($options);
    $this->configure();
  }
  
  /**
   * Template configure method
   * 
   */
  public function configure()
  {
  }
  
  /**
   * Set options for the current action
   * 
   * @param string or array $options
   */
  public function setOptions($options) 
  {
    if (!is_array($options)) 
    {
      $options = sfToolkit::stringToArray($options);
    }
    
    $this->options = $options;
  }

  
  /**
   * Set the ullGenerator
   * 
   * @param ullGenerator $generator
   */
  public function setGenerator(ullGenerator $generator) 
  {
    $this->generator = $generator;
  }
  
  
  /**
   * Get the ullGenerator
   * 
   * @return ullGenerator
   */
  public function getGenerator()
  {
    return $this->generator;
  }
  
  /**
   * Get the sfForm
   * 
   * @return sfForm
   */
  public function getForm()
  {
    return $this->getGenerator()->getForm();
  }  
  
  /**
   * Get form fields
   * 
   * @return array
   */
  public function getFormFields()
  {
    return $this->formFields;
  }
  
  
  /**
   * Adds a metaWidget
   * 
   * @param string $className classname of the ullMetaWidget
   * @param string $name
   * @param array $widgetOptions
   * @param array $widgetAttributes
   * @param array $validatorOptions
   * @param array $columnConfigOptions
   */
  public function addMetaWidget(
    $className, 
    $name, 
    $widgetOptions = array(), 
    $widgetAttributes = array(), 
    $validatorOptions = array (), 
    $columnConfigOptions = array())
  {
    $columnConfig = new ullColumnConfiguration();
    $columnConfig->setOptions($columnConfigOptions);
    $columnConfig->setWidgetOptions($widgetOptions);
    $columnConfig->setWidgetAttributes($widgetAttributes);
    $columnConfig->setValidatorOptions($validatorOptions);
    // We do not want the action handler form fields in the edit table
    $columnConfig->setAutoRender(false);
    
    $ullMetaWidget = new $className($columnConfig, $this->generator->getForm());
    $ullMetaWidget->addToFormAs($name);
    
    $this->formFields[] = $name;
    
    $this->getGenerator()->getColumnsConfig()->offsetSet($name, $columnConfig);
  }
  
  
  /**
   * Render the action form field
   */
  abstract public function render();
  
  
  /**
   * Template function to define to whom to assign the doc
   * 
   * @return array Format: array('entity' => UllEntity, 'step' => UllFlowStep)
   */
  public function getNext()
  {
    return array();
  }

  /**
   * Returns "next" array with entity and step for the previous workflow step
   *
   * @return array
   */
  public function getNextFromPreviousStep() 
  {
    $memory = $this->getForm()->getObject()->findPreviousNonStatusOnlyMemory();
    
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
}

