<?php

/**
 * This class provides build support for ullVentory search
 * forms. It inherits ullSearchGenerator which is
 * responsible for most of the work, see the class for
 * reference.
 */
class ullVentorySearchGenerator extends ullSearchGenerator
{
  /**
   * Provides custom column configuration for inventory
   * item attributes, similar to ullFlow virtual columns.
   * 
   * @param $columnConfig the column configuration object
   * @returns the modified column configuration
   */
  protected function customColumnConfig($columnConfig)
  {
    $columnConfig = parent::customColumnConfig($columnConfig);

    $sfe = $columnConfig->getCustomAttribute('searchFormEntry');
    if ($sfe->isVirtual == true)
    {
      $attribute = Doctrine::getTable('UllVentoryItemAttribute')->findOneBySlug($sfe->columnName);
      if ($attribute == null)
      {
        throw new RuntimeException("Invalid slug, no inventory attribute found.");
      }

      $columnConfig->setLabel($attribute->name);
      $columnConfig->setMetaWidgetClassName($attribute->UllColumnType->class);

      return $columnConfig;
    }
    
    //|\     /|(  ___  )(  ____ \| \    /\
    //| )   ( || (   ) || (    \/|  \  / /
    //| (___) || (___) || |      |  (_/ / 
    //|  ___  ||  ___  || |      |   _ (  
    //| (   ) || (   ) || |      |  ( \ \ 
    //| )   ( || )   ( || (____/\|  /  \ \
    //|/     \||/     \|(_______/|_/    \/
    //Manually set entity types of UllMetaWidgetUllEntity.
    //Refactoring of column configuration handling should resolve this.
    if ($columnConfig->getColumnName() == 'ull_entity_id')
    {
      $columnConfig->setOption('entity_classes', array('UllVentoryStatusDummyUser', 'UllUser'));
    }
    
    return $columnConfig;
  }
}