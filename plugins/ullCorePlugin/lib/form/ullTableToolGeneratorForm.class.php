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
    parent::updateDefaultsFromObject();
    
    //iterate all column configs and load defaults for
    //many to many relationships
    foreach ($this->columnsConfig as $columnName => $columnConfig)
    {
      if ($columnConfig->getMetaWidgetClassName() == 'ullMetaWidgetManyToMany')
      {
        // Get all connected objects for an existing object 
        if ($this->getObject()->exists())
        {
          $this->setDefault($columnName, $this->object->$columnName->getPrimaryKeys());
        }
      }
    } 

    //parent updateDefaultsFromObject begins here:
    /*
    if ($this->isNew())
    {
      $this->setDefaults(array_merge($this->getObject()->toArray(false), $this->getDefaults()));
    }
    else
    {
      //$this->setDefaults(array_merge($this->getDefaults(), $this->getObject()->toArray(false)));
      $this->setDefaults(array_merge($this->getDefaults(), $this->getObject()->getData()));
    }
    
     $defaults = $this->getDefaults();
    foreach ($this->embeddedForms as $name => $form)
    {
      if ($form instanceof sfFormDoctrine)
      {
        $form->updateDefaultsFromObject();
        $defaults[$name] = $form->getDefaults();
      }
    }
    $this->setDefaults($defaults);
    */
    //parent updateDefaultsFromObject ends here:
    
    
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

    $values = sfContext::getInstance()->getEventDispatcher()->filter(
        new sfEvent($this, 'form.update_object'), $values
    )->getReturnValue();
    
    
    // i18n translations
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
    
    // refresh translation objects
    //
    // this is necessary for the following example: set the interface lang to de
    // when editing a translated column, the action only retrieves the german
    // translation objects. sfFormObject::doUpdateObject()'s fromArray() method
    // subsequently fails because it tries to insert the english translation 
    // instead of updating it.
    if ($this->getObject()->hasRelation('Translation'))
    {
      $this->getObject()->refreshRelated('Translation');
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
    
    $object = parent::updateObject();
    
    return $object;
  }
  
  /**
   * Override parent's doSave() to provide support for many
   * to many relationships (also see saveManyToMany()).
   */
  protected function doSave($connection = null)
  {
    //iterate all column configs and call saveManyToMany
    //which handles updating of many to many association
    //tables
    foreach ($this->columnsConfig as $columnName => $columnConfig)
    {
      if ($columnConfig->getMetaWidgetClassName() == 'ullMetaWidgetManyToMany')
      {
        $this->saveManyToMany($columnName, $connection);
      }
    } 

    parent::doSave($connection);
  }


  /**
   * Handles updating of association tables for a given many to many
   * relationship. Note: This was mostly copied from generated
   * symfony form code and then adapted for general use.
   *
   * @param string $columnName the name of the many to many relationship
   */
  protected function saveManyToMany($columnName, $connection = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema[$columnName]))
    {
      // somebody has unset this widget
      return;
    }

    if ($connection === null)
    {
      $connection = $this->getConnection();
    }

    $existing = $this->object->$columnName->getPrimaryKeys();
    $values = $this->getValue($columnName);
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink($columnName, array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link($columnName, array_values($link));
    }
  }
}