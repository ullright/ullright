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
  
  // set the assigned to params one step back.
  //  used for example by actions reject and return
  public function getHistoryOneStepBack() 
  {
    $q = new Doctrine_Query;
    
    $q
//      ->select('m.ull_flow_step_id, m.creator_ull_entity_id')
      ->from('UllFlowMemory m, m.UllFlowAction a')
      ->where('m.ull_flow_doc_id = ?', $this->form->getObject()->id)
      ->addWhere('a.is_status_only = ?', false)
      ->orderBy('m.created_at DESC')
    ;
    
    $memory = $q->execute()->getFirst();
//    var_dump($memory->toArray());die;
   
    return array(
      'entity' => $memory->CreatorUllEntity, 
      'step' => $memory->UllFlowStep
    );
  }
  
}

