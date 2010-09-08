<?php

/**
 * Helps in resolving constraints for Doctrine records.
 * Can be used to retrieve a list of all records blocking
 * the deletion of a specific record.
 * 
 * Note: This class relies on Doctrine's relation system.
 * If Doctrine messes up relation handling (it happens ...)
 * returned results might be incorrect.
 */
class ullConstraintResolver
{
  /**
   * Returns delete constraining records for a specific Doctrine_Record.
   * The given array of labels will contain a list of all records which
   * would prevent deletion of the given record.
   * 
   * Status: experimental =)
   * 
   * @param $record any Doctrine_Record
   * @param $constrainingLabels an array of labels, passed by reference; will be created if null
   */
  public static function findConstrainingRecords(Doctrine_Record $record, array &$constrainingLabels = null)
  {
    if (!$constrainingLabels)
    {
      $constrainingLabels = array();
    }
    
    //the table name of the component is needed later on
    $componentName = $record->getTable()->getComponentName();
    $componentExport = $record->getTable()->getExportableFormat(false);
    $componentTableName = $componentExport['tableName'];
         
    $relations = $record->getTable()->getRelations();
    if (count($relations) == 0)
    {
      //there are no relations for this component, skipping ...
      continue;
    }
    
    //process relations for this component
    foreach($relations as $key => $relation)
    {
      if ($relation instanceof Doctrine_Relation_ForeignKey)
      {
        //found a foreign key relation, retrieve related records
        $constrainingRecords = $relation->fetchRelatedFor($record);
        
        //do we have related records?
        if (count($constrainingRecords) == 0)
        {
          //there are no related records, skipping this relation ...
          continue;
        }
        
        $export = $relation->getTable()->getExportableFormat(true);
        //looking up foreign keys for this component
        //we have to find exactly one matching, which means:
        //1) foreignTable is table name of $record
        //2) column names match
        $found = false;
        foreach ($export['options']['foreignKeys'] as $foreignKeyName => $foreignKey)
        {
          if ($foreignKey['foreignTable'] == $componentTableName)
          {
            if ($foreignKey['local'] == $relation->getForeignColumnName())
            {
              if ($foreignKey['foreign'] == $relation->getLocalColumnName())
              {
                //found a matching foreign key!
                if ($found)
                {
                  throw new LogicException('Already found a matching foreign key');
                }
                else
                {
                  $found = true;
                }

                //is this foreign key delete cascading?
                $isCascading = (isset($foreignKey['onDelete']) &&
                  $foreignKey['onDelete'] == 'CASCADE') ? true : false;
              }
            }
          }
        }

        $tableConfig = ullTableConfiguration::buildFor($relation->getTable()->getComponentName());
        
        if (!$found)
        {
          //TODO: inspect this case
          //no matching foreign key found, bad :(
          //this should not happen, but it does.
          //observed e.g. for UllFlowDoc and Tagging
          //why? wouldn't this indicate a one-sided Doctrine relation?
          //workaround
          $isCascading = true;
        }

        //now that we have a matching foreign key, lets handle constraining records ...
        foreach($constrainingRecords as $constrainingRecord)
        {
          if (!$isCascading)
          {
            $constrainingLabels[] = $constrainingRecord->getTable()->getComponentName() .
              ' (ID: ' . $constrainingRecord->id . ')';
          }
          //call this function recursively to find constraining records for
          //this constraining record 
          self::findConstrainingRecords($constrainingRecord, $constrainingLabels);
        }
      }
    }
    
    return $constrainingLabels;
  }
}