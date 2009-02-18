<?php
/**
 * sfForm for the table tool
 *
 */
class ullTableToolForm extends ullGeneratorForm
{

  /**
   * Override getting the default values form the object
   *
   */
  protected function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();
    
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
            $newFieldName = $fieldName . '_translation_' . $culture; 
            $defaults[$newFieldName] = $translations[$culture][$fieldName];
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
    $values = $this->getValues();
    
//    var_dump(sfContext::getInstance()->getEventDispatcher()->getListeners('form.update_object'));die;
    
    $values = sfContext::getInstance()->getEventDispatcher()->filter(
        new sfEvent($this, 'form.update_object'), $values
    )->getReturnValue();
    
    // remove special columns that are updated automatically
    unset(
      $values['id'], 
      $values['updated_at'], 
      $values['updator_user_id'], 
      $values['created_at'], 
      $values['creator_user_id']
    );

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