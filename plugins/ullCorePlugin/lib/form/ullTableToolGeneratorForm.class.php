<?php
/**
 * sfForm for the table tool
 *
 */
class ullTableToolGeneratorForm extends ullGeneratorForm
{  
  /**
   * Override getting the default values form the object
   *
   */
  protected function updateDefaultsFromObject()
  {
    //parent::updateDefaultsFromObject();
    if ($this->isNew())
    {
      $this->setDefaults(array_merge($this->getObject()->toArray(false), $this->getDefaults()));
    }
    else
    {
      //$this->setDefaults(array_merge($this->getDefaults(), $this->getObject()->toArray(false)));
      $this->setDefaults(array_merge($this->getDefaults(), $this->getObject()->getData()));
    }
    
    if ($this->isI18n()) 
    { 
      $defaults = $this->getDefaults();
      
      $translations = $this->object->Translation->toArray();
      
      if (isset($translations[@$this->cultures[0]])) 
      {

        $i18nFields = $translations[$this->cultures[0]];
        unset($i18nFields['id']);
        unset($i18nFields['lang']);

        foreach ($i18nFields as $fieldName => $value)
        {
          foreach ($this->cultures as $culture)
          {
            //only retrieve the translations if there are any
            if (isset($translations[$culture]))
            {
              $newFieldName = $fieldName . '_translation_' . $culture; 
              $defaults[$newFieldName] = $translations[$culture][$fieldName];
            }
          }
        }
      }

      $this->setDefaults($defaults);
    }
  }

  /**
   * Override the update functionality of the object
   *
   * @return Doctrine_Record
   */
  public function updateObject($values = null)
  {
    if (is_null($values))
    {
      $values = $this->values;
    }

//      var_dump($values);die;
    
//    var_dump(sfContext::getInstance()->getEventDispatcher()->getListeners('form.update_object'));die;
    
    $values = sfContext::getInstance()->getEventDispatcher()->filter(
        new sfEvent($this, 'form.update_object'), $values
    )->getReturnValue();
    
    
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
      
      //removed because this caused checkboxes to re-enable themselves
      //in sfFormDoctrine#updateObject when set to default
      
      // create proper null entries
//      if ($value == '')
//      {
//        $values[$fieldName] = null;
//      }
    }
    
    $this->values = $values;
    
    if ($this->object->getTable()->hasTemplate('Doctrine_Template_SuperVersionable'))
    {
      if (isset($this->values['scheduled_update_date']))
      {
        $this->object->mapValue('scheduled_update_date', $this->values['scheduled_update_date']);
      	unset($this->values['scheduled_update_date']);
      }
    }

    return parent::updateObject();
    
//    $this->object->fromArray($values);
//    
//    var_dump($values);
//    var_dump($this->object->toArray(false));die;
//    
//    return $this->object;
  }

}