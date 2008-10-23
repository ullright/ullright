<?php

class ullForm extends sfFormDoctrine
{
  protected 
    $modelName
  ;

  public function __construct($object = null, $cultures = null, $options = array(), $CSRFSecret = null)
  {
    $this->modelName = get_class($object);
    
    $this->setCultures($cultures);
    
    parent::__construct($object, $options, $CSRFSecret);
  }  
  
  public function configure()
  {
    $this->getWidgetSchema()->setNameFormat('fields[%s]');
    //TODO: refactor
    if (sfContext::getInstance()->getRequest()->getParameter('action') == 'list')
    {
      $this->getWidgetSchema()->setFormFormatterName('ullList');
    }
    else
    {
      $this->getWidgetSchema()->setFormFormatterName('ullTable');
    }
    
//    $this->embedI18n(array('en', 'de'));
  }
  
  public function getModelName()
  {
    return $this->modelName;
  }  
  
  public function addUllMetaWidget($fieldName, $ullMetaWidget)
  {
    $WidgetSchema     = $this->getWidgetSchema();
    $ValidatorSchema  = $this->getValidatorSchema();
    
    $WidgetSchema[$fieldName] = $ullMetaWidget->getSfWidget();
    $ValidatorSchema[$fieldName] = $ullMetaWidget->getSfValidator();
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
  
  protected function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();
    
    if ($this->isI18n()) 
    {         
      $defaults = $this->getDefaults();
      
      if (isset($defaults[$this->cultures[0]])) 
      {
      
        $i18nFields = $defaults[$this->cultures[0]];
        unset($i18nFields['id']);
        unset($i18nFields['lang']);
        
        foreach ($i18nFields as $fieldName => $value)
        {
          foreach ($this->cultures as $culture)
          {
            $newFieldName = $fieldName . '_translation_' . $culture; 
            $defaults[$newFieldName] = $defaults[$culture][$fieldName];
          }
        }
      }

      $this->setDefaults($defaults);
    }  
  }
  
  public function updateObject()
  {
    $values = $this->getValues();
    
    // remove special columns that are updated automatically
    unset($values['id'], $values['updated_at'], $values['updated_on'], $values['created_at'], $values['created_on']);
        
    foreach ($values as $fieldName => $value)
    {
      if (strstr($fieldName, '_translation_'))
      {
        unset($values[$fieldName]);
        
        $parts = explode('_', $fieldName);
        $culture = array_pop($parts);
        array_pop($parts);
        $realFieldName = implode('_', $parts);
        
        $values['Translation'][$culture]['lang'] = $culture;
        $values['Translation'][$culture][$realFieldName] = $value;
      }
    }
    
    $this->object->fromArray($values);

    return $this->object;
  }
}
