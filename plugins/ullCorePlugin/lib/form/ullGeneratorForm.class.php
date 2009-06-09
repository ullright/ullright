<?php
/**
 * Base class for the ullGenerator sfForms
 *
 */
class ullGeneratorForm extends sfFormDoctrine
{
  protected
    $modelName,
    $requestAction
  ;

  /**
   * Constructor
   *
   * @param Doctrine_Record $object
   * @param string $requestAction 		Request Action ('list' or 'edit')
   * @param array $cultures 			List of cultures for i18n forms
   * @param array $options
   * @param string $CSRFSecret
   */
  public function __construct(Doctrine_Record $object, $requestAction = 'list', $defaults = array(), $cultures = array(), $options = array(), $CSRFSecret = null)
  {
    $this->modelName = get_class($object);

    $this->requestAction = $requestAction;

    $this->setCultures($cultures);

    parent::__construct($object, $options, $CSRFSecret);

    // called after parent:__construct, because sfFormDoctrine overwrites defaults with emtpy array *#!?$
    $this->setDefaults($defaults);
    $this->updateDefaultsFromObject();
//    var_dump($this->getDefaults());die;
  }

  /**
   * Form configuration
   *
   */
  public function configure()
  {
    $this->getWidgetSchema()->setNameFormat('fields[%s]');

    if ($this->requestAction == 'list')
    {
      $this->getWidgetSchema()->setFormFormatterName('ullList');
    }
    else
    {
      $this->getWidgetSchema()->setFormFormatterName('ullTable');
    }

//    $this->embedI18n(array('en', 'de'));
  }

  
  /**
   * Renders hidden form fields.
   * Also for embedded forms
   *
   * @param $formFieldSchema  allows to give a formFieldSchema for recursive call
   *                          to render the hidden fields of embedded forms 
   * @return string
   */
  public function renderHiddenFields($formFieldSchema = null)
  {
    // use the form's own formFieldSchema per default
    if ($formFieldSchema === null)
    {
      $formFieldSchema = $this->getFormFieldSchema();
    }
    
    $output = '';
    foreach ($formFieldSchema as $name => $field)
    {
      // call the method recursively for embedded forms
      if ($field instanceof sfFormFieldSchema)
      {
        $output .= $this->renderHiddenFields($field);
        continue;
      }
      
      if ($field->isHidden())
      {
        $output .= $field->render();
      }
    }

    return $output;
  }
  

  /**
   * Call updateObject of every ullWidget
   *
   * @param unknown_type $values
   */
  public function updateObject($values = null)
  {
    if (is_null($values))
    {
      $values = $this->values;
    }
    
    $this->removeUnusedFields(); 
  	
  	$widgets = $this->getWidgetSchema()->getFields();
    foreach ($widgets as $fieldName => $widget)
    {
      if ($widget instanceof ullWidget)
      {
        $this->values = $widget->updateObject($this->getObject(), $this->getValues(), $fieldName);
      }
    }
    
    return parent::updateObject();    
  }
  
  /**
   * get the name of the model
   *
   * @return string
   */
  public function getModelName()
  {
    return $this->modelName;
  }

  /**
   * set Cultures
   *
   * @param array $cultures
   */
  public function setCultures($cultures)
  {
    $this->cultures = $cultures;
  }

  /**
   * get Cultures
   *
   * @return array
   */
  public function getCultures()
  {
    return $this->cultures;
  }
  
  
  /**
   * Removes values that were not set via POST request
   * 
   * @return none
   */
  protected function removeUnusedFields()
  {
    foreach ($this->getValues() as $key => $value)
    {
      if ($key == 'Translation')
      {
        continue;
      }
      
      if (!array_key_exists($key, $this->getTaintedValues()))
      {
        unset($this->values[$key]);
      }
    }  
  }

}
