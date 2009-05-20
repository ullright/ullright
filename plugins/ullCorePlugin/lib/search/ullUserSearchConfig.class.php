<?php

/**
 * This class is a search configuration for UllUsers.
 * It provides the ullSearch framework with information regarding
 * searchable fields for the UllUser model.
 */
class ullUserSearchConfig implements ullSearchConfig {

  //a blacklist of columns we do not want the user to search for
  private $blacklist = array('namespace', 'password', 'is_virtual_group', 'type', 'version');

  /**
   * Returns an array of search field entries describing often
   * used columns when searching for a user.
   */
  public function getDefaultSearchColumns() {

    $sfeArray = array();

    $sfe = new ullSearchFormEntry();
    $sfe->columnName = 'last_name';
    //$sfe->multipleCount = 1;
    $sfeArray[] = $sfe;

    $sfe = new ullSearchFormEntry();
    $sfe->columnName = 'ull_department_id';
    $sfeArray[] = $sfe;

    $sfe = new ullSearchFormEntry();
    $sfe->columnName = 'ull_location_id';
    $sfeArray[] = $sfe;

    $sfe = new ullSearchFormEntry();
    $sfe->columnName = 'ull_user_status_id';
    $sfeArray[] = $sfe;

    //    $sfe = new ullSearchFormEntry();
    //    $sfe->relations[] = 'Creator';
    //    $sfe->relations[] = 'UllLocation';
    //    $sfe->columnName = 'country';
    //    $sfeArray[] = $sfe;

    return $sfeArray;
  }

  /**
   * Returns an array of search field entries describing all
   * available columns when searching for a user.
   */
  public function getAllSearchableColumns() {
    $sfeArray = array();

    $fieldNames = Doctrine::getTable('UllUser')->getFieldNames();
    foreach ($fieldNames as $key => $value)
    {
      if (array_search($value, $this->blacklist) === false)
      {
        $sfe = new ullSearchFormEntry();
        $sfe->columnName = $value;
        $sfeArray[] = $sfe;
      }
    }

    //TBA: If needed: Relations like the following:

    /*
     $sfe = new ullSearchFormEntry();
     $sfe->relations[] = 'Creator';
     $sfe->relations[] = 'UllLocation';
     $sfe->columnName = 'country';
     $sfeArray[] = $sfe;

     $sfe = new ullSearchFormEntry();
     $sfe->relations[] = 'UllLocation';
     $sfe->columnName = 'country';
     $sfeArray[] = $sfe;
     */

    return $sfeArray;
  }

  /**
   * Returns a blacklist of columns we do not want the user to search for
   * @return array the blacklist
   */
  public function getBlacklist()
  {
    return $this->blacklist;
  }
}