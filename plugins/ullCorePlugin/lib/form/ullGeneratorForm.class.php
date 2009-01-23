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
    
    if ($this->requestAction == 'list')
    { 
      $defaultsESC = array();
      foreach ($this->getDefaults() as $key => $value)
      {
        if (is_string($value))
        {
          $defaultsESC[$key] = htmlentities($value);
        }
        else
        {
          $defaultsESC[$key] = $value;
        }
      }
      $this->setDefaults($defaultsESC);
    }
//    var_dump($this->getDefaults());
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
   * Call updateObject of every ullWidget
   *
   * @param unknown_type $values
   */
  public function updateObject($values = null)
  {
  	//local $values not used
  	
  	$widgets = $this->getWidgetSchema()->getFields();
    foreach ($widgets as $fieldName => $widget)
    {
      if ($widget instanceof ullWidget)
      {
        $this->values = $widget->updateObject($this->getObject(), $this->getValues(), $fieldName);
      }
    }
    
    return parent::updateObject($this->getValues());
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

}
