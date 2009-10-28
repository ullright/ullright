<?php
/**
 * Base class for the ullGenerator sfForms
 *
 */
class ullGeneratorForm extends sfFormDoctrine
{
  protected
    $modelName,
    $requestAction,
    $columnsConfig
  ;

  /**
   * Constructor
   *
   * @param Doctrine_Record $object
   * @param ullColumnConfigCollection $columnsConfig
   * @param string $requestAction 		Request Action ('list' or 'edit')
   * @param array $cultures 			List of cultures for i18n forms
   * @param array $options
   * @param string $CSRFSecret
   */
  public function __construct
  (
    Doctrine_Record $object, 
    ullColumnConfigCollection $columnsConfig, 
    $requestAction = 'list', 
    $defaults = array(), 
    $cultures = array(), 
    $options = array(), 
    $CSRFSecret = null
  )
  {
    $this->modelName = get_class($object);

    $this->requestAction = $requestAction;
    
    $this->columnsConfig = $columnsConfig;

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
   * Update defaults for relations
   * 
   * @return none
   */
  public function updateDefaults()
  {
//    var_dump($this->getObject()->toArray());die;
    
    $defaults = $this->getDefaults();
    
//    var_dump($defaults);die;
    
    foreach($this->getWidgetSchema()->getPositions() as $fieldName)
    {
      if (
        ullGeneratorTools::hasRelations($fieldName) ||
        // we have to check for translated columns for the edit action 
        // in the form my_column_translation_xx 
        (isset($this->columnsConfig[$fieldName]) && 
          $this->columnsConfig[$fieldName]->getTranslated())
      )
      {
        $relations = ullGeneratorTools::relationStringToArray($fieldName);
        // remove columnName
        array_pop($relations);
        $relations[] = 'id';
        $idColumn = ullGeneratorTools::relationArrayToString($relations);
        
        $eval = '$id = @$this->getObject()->' . $idColumn . ';';
        eval($eval);
        
        // Handle translated columns
        if ($this->columnsConfig[$fieldName]->getTranslated())
        {
//          var_dump($fieldName);
          $relations = ullGeneratorTools::relationStringToArray($fieldName);
          $columnName = array_pop($relations);
          $relations = array_merge($relations, array('Translation', substr(sfContext::getInstance()->getUser()->getCulture(), 0, 2), $columnName));
          $column = ullGeneratorTools::relationArrayToString($relations);
//          var_dump($fieldName);die;
        }
        else
        {
          $column = $fieldName;
        }
        // We use '@' to supress notice when a field is empty.
        // Maybe there is a cleaner solution?        
        $eval = '$value = @$this->getObject()->' . $column . ';';
//        var_dump($eval);die;
        eval($eval);
        
//        var_dump($value);
//        var_dump($id);
        
        
        $defaults[$fieldName] = array('value' => $value, 'id' => $id);
      }
    }

//    var_dump($defaults);
    
    $this->setDefaults($defaults);
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
  
  /**
   * Outputs human readable information of an sfForm for debbugging purposes 
   * because var_dump($sfForm) is unreadable.
   * 
   * Work in progress.
   * 
   * @return none
   */
  public function debug()
  {
    $output = array();
    
    $output['generic_info'] = array('num_of_fields' => count($this->getFormFieldSchema()));
    
    foreach($this->getFormFieldSchema() as $key => $value)
    {
      $widgetData = array();
      $widget = $value->getWidget(); 

      $widgetData['label'] = $widget->getLabel();
      $widgetData['class'] = get_class($widget);
      $widgetData['default'] = $this->getDefault($key);
      
      if ($widget instanceof sfWidgetFormDoctrineSelect)
      {
        $choices = $widget->getOption('choices');
        if ($choices instanceof sfCallable)
        {
          $widgetData['choices'] = $choices->call();
        }  
        else
        {
          $widgetData['choices'] = ($choices);
        }
      }
      
      $validatorData = array();            
      $validator = $this->getValidator($key);

      $validatorData['class'] = get_class($validator);
      
      if ($validator instanceof sfValidatorChoice)
      {
        $choices = $validator->getOption('choices');
        if ($choices instanceof sfCallable)
        {
          $validatorData['choices'] = $choices->call();
        }  
        else
        {
          $validatorData['choices'] = $choices;
        }
      }
      
      $output[$key] = array(
        'widget' => $widgetData,
        'validator' => $validatorData
      );      
    }

    var_dump($output);
  }
  
  
  /**
   * Add a global validator making sure that the $time_field_1 is
   * less than $time_field_2
   * 
   * @param string $time_field1
   * @param string $time_field2
   * @return none
   */
  public function addGlobalCompareTimeValidator($time_field1, $time_field2)
  {
    $this->mergePostValidator(
      new ullSfValidatorSchemaCompare($time_field1, sfValidatorSchemaCompare::LESS_THAN_EQUAL, $time_field2,
      array('allow_empty' => true),
      array('invalid' => __('Invalid. The begin time must be before the end time', null, 'common'))
    ));
  }

}
