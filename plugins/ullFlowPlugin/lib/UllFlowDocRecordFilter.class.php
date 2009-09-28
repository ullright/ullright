<?php
/**
 * Doctrine_Record_Filter for UllFormDoc
 * 
 * Allows transparent access to the virtual columns
 * 
 * The virtual columns are defined in UllFlowColumnConfig
 * The values are stored in UllFlowValues
 *
 */
class UllFlowDocRecordFilter extends Doctrine_Record_Filter
{
  public function init()
  {
  }

  /**
   * Logic for setting a virtual ullFlow column
   *
   * @param Doctrine_Record $record
   * @param string $name
   * @param string $value
   */
  public function filterSet(Doctrine_Record $record, $name, $value)
  {
//    var_dump($record->toArray());
//    var_dump($name);
//    var_dump($value);
    
//    $q = new Doctrine_Query;
//    $q
//      ->from('UllFlowValue v, v.UllFlowColumnConfig c')
//      ->where('v.ull_flow_doc_id = ?', $record->id)
//      ->addWhere('c.slug = ?', $name)
//    ;

    $cc = UllFlowColumnConfigTable::findByAppIdAndSlug($record->ull_flow_app_id, $name);
    
    $ullFlowValue = null;
    
    

    
    if ($record->exists())
    {
      $ullFlowValue = UllFlowValueTable::findByDocIdAndSlug($record->id, $name);
    }

    if ($ullFlowValue)
    {
                 
      $ullFlowValue->value = $value;
      $ullFlowValue->save();
      // refresh values in parent record 
      $record->refreshRelated('UllFlowValues');
    }
    else
    {
      // create new UllFlowValue objects
      $flowValue = new ullFlowValue();
      $flowValue->value = $value;
      $flowValue->ull_flow_column_config_id = $cc->id;
      
      // WTF - why is this even necessary?
      if ($record->exists())
      {
        $flowValue->ull_flow_doc_id = $record->id;
        $flowValue->save();
        $record->refreshRelated('UllFlowValues');
      }
      else
      {
         $record->UllFlowValues[] = $flowValue;
      }
    }
    
    // also set the native "duplicate" columns of UllFlowDoc
    if ($cc->is_subject)
    {
      $record->subject = $value;
    }
    if ($cc->is_priority)
    {
      $record->priority = $value;
    }
    if ($cc->is_tagging)
    {
      $record->duplicate_tags_for_search = $value;
      // Set tags in taggable behaviour
      $record->setTags($value);
    }        

    return true;
  }

  /**
   * Logic for getting a virtual ullFlow column
   *
   * @param Doctrine_Record $record
   * @param string $name
   * @return mixed
   */
  public function filterGet(Doctrine_Record $record, $name)
  {
    return $record->getValueByColumn($name);
  }
  
}