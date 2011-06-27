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
          $this->setDefault($columnName,
            $this->getPrimaryKeysEfficiently($this->object, $columnName));
        }
      }
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
    
    
    // Handle versionable behaviour
    //
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
   * Override sfFormDoctrine's doUpdateObject() to provide support
   * for our custom many to many relation handling (= prevent
   * thousands of related records from loading/hydrating).
   * 
   * @see sfFormDoctrine::doUpdateObject()
   */
  protected function doUpdateObject($values)
  {
    $unset = array();
    $associations = array();
    $relations = $this->getObject()->getTable()->getManyToManyRelations();
    
    foreach ($relations as $relationName => $relation)
    {
      if (isset($values[$relationName]))
      {
        $unset[] = $relationName;
        if ($this->object->exists())
        {
          unset($values[$relationName]);
        }
      }
    }
    
    parent::doUpdateObject($values);
    
    foreach ($relations as $relationName => $relation)
    {
      if ($this->object->exists() || !in_array($relationName, $unset))
      {
        $componentName = $relation->getClass();
        $collection = Doctrine_Collection::create($componentName);
        $this->getObject()->set($relationName, $collection, false);
      }
    }
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

    $existing = $this->getPrimaryKeysEfficiently($this->object, $columnName);
    $values = $this->getValue($columnName);
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      if ($this->object->exists())
      {
        $relation = $this->object->getTable()->getRelation($columnName);
  
        foreach (array_values($unlink) as $unlinkValue)
        {
          $q = new Doctrine_Query();
          $q
            ->delete($relation->getAssociationTable()->getComponentName())
            ->where($relation->getLocalRefColumnName() . ' = ?', $this->object->id)
            ->andWhere($relation->getForeignRefColumnName() . ' = ?', $unlinkValue)
          ;
          $q->execute();
        }
      }
      else
      {
         $this->object->unlink($columnName, array_values($unlink));
      }
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      if ($this->object->exists())
      {
        $relation = $this->object->getTable()->getRelation($columnName);
        $associationObjectName = $relation->getAssociationTable()->getComponentName();
        
        foreach (array_values($link) as $linkValue)
        {
          $associationObject = new $associationObjectName();
          $associationObject[$relation->getLocalRefColumnName()] = $this->object->id;
          $associationObject[$relation->getForeignRefColumnName()] = $linkValue;
          $associationObject->save();        
        }
      }
      else
      {
        $this->object->link($columnName, array_values($link));
      }
    }
  }
  
  /**
   * This function solves performance problems regarding $this->object->$columnName,
   * which was originally used by this class.
   * Imagine e.g. mailingListObject->Subscribers is not loaded, so lazy loading
   * gets active. Now Doctrine tries to retrieve/hydrate 5000+ UllUser records,
   * -> slowness ensues.
   * Solution: Ask the doctrine record if object->columnName is already available
   * or if lazy loading would be used. If it is not available, retrieve the primary
   * keys via a separate query instead.
   * 
   * @param Doctrine_Record $record
   * @param string $columnName
   */
  protected function getPrimaryKeysEfficiently($record, $columnName)
  {
    //would accessing $columnName trigger lazy load?
    if ($record->get($columnName, false) === null)
    {
      $relation = $record->getTable()->getRelation($columnName);
      $dql = 'SELECT id ' .  $relation->getRelationDql(1);
      $results = Doctrine_Manager::connection()->query($dql,
        array($record->id), Doctrine::HYDRATE_SINGLE_SCALAR);
      if (!is_array($results))
      {
        $results = array($results);
      }
    }
    else
    {
      $results = $record->$columnName->getPrimaryKeys();
    }
    
    return $results;
  }
}