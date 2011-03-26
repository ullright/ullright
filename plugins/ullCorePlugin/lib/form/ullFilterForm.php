<?php

class ullFilterForm extends sfForm
{
  protected
    $bindBlacklist = array(
      'search',
    )
  ;
  
  public function configure()
  {
    $this->setWidgets(array(
      'search'  => new sfWidgetFormInput(array(), array('size' => '15',
                                                        'title' => __('Search', null, 'common'))),
    ));

    $this->widgetSchema->setLabels(array(
      'search'  => __('Search', null, 'common')
    ));
    
    $this->setValidators(array(
      'search'  => new sfValidatorPass(),
    ));
    
    $this->getWidgetSchema()->setNameFormat('filter[%s]');
    
    $this->getWidgetSchema()->setFormFormatterName('ullUnorderedList');
  }
  
  
  /**
   * Blacklist some values
   * 
   * @see plugins/ullCorePlugin/lib/vendor/symfony/lib/form/sfForm#bind($taintedValues, $taintedFiles)
   */
  public function bind(array $taintedValues = null, array $taintedFiles = null)
  {
    if (isset($taintedValues))
    {
      foreach ($taintedValues as $key => $taintedValue)
      {
        if (in_array($key, $this->bindBlacklist))
        {
          unset($taintedValues[$key]);
        }
        
        // Necessary for dates
        $taintedValues[$key] = ullCoreTools::esc_decode($taintedValue);
      }
    }
    
    parent::bind($taintedValues, $taintedFiles);
  }
  
  
  /**
   * Manually sets a value
   * 
   * Be careful, since this method ignores the validation process
   *
   * @param string $field The name of the value required
   * @param string $value 
   * @return self
   */
   // tried to change a value after bind - does not work
//  public function setValue($field, $value)
//  {
//    if ($this->isBound)
//    {
//      $this->values[$field] = $value;
//    }
//    
//    return $this;
//  }

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
//      $widgetData['options'] = $widget->getOptions();
      
      if ($widget instanceof sfWidgetFormDoctrineChoice)
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
   *  Allow to set a Value
   *  
   * @param $field
   * @param $value
   * @return unknown_type
   */
  public function setValue($field, $value)
  {
    if ($this->isBound)
    {
      $this->values[$field] = $value;
    }
    
    return $this;
  }
  
}
