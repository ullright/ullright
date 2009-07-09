<?php

/**
 * This class provides inventory item attribute support
 * for inventory items to the ullSearch framework.
 */
class ullVentorySearch extends ullSearch
{
  /**
   * This function overrides the base implementation and provides support
   * for inventory item attributes.
   *
   * It changes virtual column names to 'virtual'.
   *
   * @param $columnName The current column name
   * @return The modified column name
   */
  protected function modifyColumnName($columnName)
  {
    return (strpos($columnName, 'isVirtual.') === 0) ? 'value' : $columnName;
  }
  
  /**
   * This function overrides the base implementation and provides support
   * for inventory item attributes.
   *
   * It adds a join to the current query, adding the item attribute values table,
   * but first it selects the correct item type attributes with a subselect.
   * Then it modifies the current alias to reference to the correct column
   * value.
   *
   * @param $q The current doctrine query
   * @param $alias The current alias
   * @param $criterion The current search criterion
   * @return the modified alias
   */
  protected function modifyAlias(Doctrine_Query $q, $alias, ullSearchCriterion $criterion)
  {
    $columnName = $criterion->columnName;
    if (strpos($columnName, 'isVirtual.') === 0)
    {
      $columnSlug = substr($columnName, 10);
      $attributeId = Doctrine::getTable('UllVentoryItemAttribute')->findOneBySlug($columnSlug)->id;
      
      $uniqueAlias = str_replace('.', '', uniqid(''));
      $uniqueAliasSubSelect = str_replace('.', '', uniqid(''));
      
      $q->leftJoin($alias . '.UllVentoryItemAttributeValue ' . $uniqueAlias . ' WITH ' .
        $uniqueAlias . '.ull_ventory_item_type_attribute_id IN ' .
        '(SELECT ' . $uniqueAliasSubSelect . '.id FROM UllVentoryItemTypeAttribute ' . $uniqueAliasSubSelect . 
          ' WHERE ' . $uniqueAliasSubSelect . '.ull_ventory_item_attribute_id = ?)', $attributeId);
      
      //$q->leftJoin($alias . '.UllVentoryItemAttributeValue ' . $uniqueAlias . ' WITH ' .
      //  $uniqueAlias . '.UllVentoryItemTypeAttribute.ull_ventory_item_attribute_id = ?', $attributeId);
      //
      //Note: This should work, but doesn't.
      //Doctrine builds the conditions correctly, but somehow it seems to forget to add
      //a necessary additional join, the result is:
      //'left join ull_ventory_item_attribute_value u2 on u.id = u2.ull_ventory_item_id
      //and u3.ull_ventory_item_attribute_id = ?'
      //u3 is the missing join
      
      return $uniqueAlias;
    }

    return $alias;
  }
}