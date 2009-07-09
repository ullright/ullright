<?php

/**
 * This class is a search configuration for ullVentory items.
 * It provides the ullSearch framework with information regarding
 * searchable fields for the ullVentoryItem model.
 */
class ullVentoryItemSearchConfig extends ullSearchConfig
{
  public function __construct()
  {
    //a blacklist of columns we do not want the user to search for
    $this->blacklist = array('id', 'namespace');
  }

  /**
   * Configures an array of search form entries describing often
   * used columns when searching for a user.
   *
   * NOTE: Uuids are not assigned to the entries.
   */
  protected function configureDefaultSearchColumns() {

    $sfeArray = array();
    
    //ullentity
    $sfe = new ullSearchFormEntry();
    $sfe->columnName = 'ull_entity_id';
    $sfeArray[] = $sfe;

    //ullentity-location
    $sfe = new ullSearchFormEntry();
    $sfe->relations[] = 'UllEntity';
    $sfe->columnName = 'ull_location_id';
    $sfeArray[] = $sfe;

    //ullventoryitemtype
    $sfe = new ullSearchFormEntry();
    $sfe->relations[] = 'UllVentoryItemModel';
    $sfe->columnName = 'ull_ventory_item_type_id';
    $sfeArray[] = $sfe;
    
    //serial number
    $sfe = new ullSearchFormEntry();
    $sfe->columnName = 'serial_number';
    $sfeArray[] = $sfe;
    
    //comment
    $sfe = new ullSearchFormEntry();
    $sfe->columnName = 'comment';
    $sfeArray[] = $sfe;
    
    //attribute:pc-name
    //pc-name doesn't exist yet
    //$sfe = new ullSearchFormEntry();
    //$sfe->columnName = 'pc_name';
    //$sfe->isVirtual = true;
    //$sfeArray[] = $sfe;

    return $sfeArray;
  }

  /**
   * Returns an array of search form entries describing all
   * available columns when searching for a user.
   */
  public function getAllSearchableColumns() {
    $sfeArray = array();

    $fieldNames = Doctrine::getTable('UllVentoryItem')->getFieldNames();
    foreach ($fieldNames as $key => $value)
    {
      if (array_search($value, $this->blacklist) === false)
      {
        $sfe = new ullSearchFormEntry();
        $sfe->columnName = $value;
        $sfeArray[] = $sfe;
      }
    }

    //UllVentoryItemModel -> ullVentoryItemType -> name
    $sfe = new ullSearchFormEntry();
    $sfe->relations[] = 'UllVentoryItemModel';
    $sfe->columnName = 'ull_ventory_item_type_id';
    $sfeArray[] = $sfe;
    
    //UllVentoryItemModel -> ullVentoryItemManufacturer -> name
    $sfe = new ullSearchFormEntry();
    $sfe->relations[] = 'UllVentoryItemModel';
    $sfe->columnName = 'ull_ventory_item_manufacturer_id';
    $sfeArray[] = $sfe;
    
    //all from ullEntity
    $userBlacklist = array('id', 'namespace', 'password', 'is_virtual_group', 'type', 'version');
    $fieldNames = Doctrine::getTable('UllUser')->getFieldNames();
    foreach ($fieldNames as $key => $value)
    {
      if (array_search($value, $userBlacklist) === false)
      {
        $sfe = new ullSearchFormEntry();
        $sfe->relations[] = 'UllEntity';
        $sfe->columnName = $value;
        $sfeArray[] = $sfe;
      }
    }
    
    //ullEntity->group
    $sfe = new ullSearchFormEntry();
    $sfe->relations[] = 'UllEntity';
    $sfe->relations[] = 'UllEntityGroup';
    $sfe->columnName = 'ull_group_id';
    $sfeArray[] = $sfe;
    
    //alle attribute
    $attributes = Doctrine::getTable('UllVentoryItemAttribute')->findAll();

    foreach ($attributes as $attribute)
    {
      $sfe = new ullSearchFormEntry();
      $sfe->columnName = $attribute->slug;
      $sfe->isVirtual = true;
      $sfeArray[] = $sfe;
    }
    
    return $sfeArray;
  }
}
