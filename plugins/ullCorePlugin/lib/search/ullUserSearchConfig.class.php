<?php

/**
 * This class is a search configuration for UllUsers.
 * It provides the ullSearch framework with information regarding
 * searchable fields for the UllUser model.
 */
class ullUserSearchConfig extends ullSearchConfig {

  public function __construct()
  {
    //a blacklist of columns we do not want the user to search for
    $this->blacklist = array('photo', 'namespace', 'password', 'is_virtual_group',
      'type', 'version', 'last_name_first', 'parent_ull_user_id');
  }
  
  /**
   * Configures an array of search form entries describing often
   * used columns when searching for a user.
   * 
   * NOTE: Uuids are not assigned to the entries.  
   */
  protected function configureDefaultSearchColumns() {

    $sfeArray = array();

    $sfe = new ullSearchFormEntry();
    $sfe->columnName = 'ull_company_id';
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
 
    $sfe = new ullSearchFormEntry();
    $sfe->relations[] = 'UllEntityGroup';
    $sfe->columnName = 'ull_group_id';
    $sfeArray[] = $sfe;
    
    $sfe = new ullSearchFormEntry();
    $sfe->columnName = 'ull_employment_type_id';
    $sfeArray[] = $sfe;
    
    $sfe = new ullSearchFormEntry();
    $sfe->columnName = 'ull_job_title_id';
    $sfeArray[] = $sfe;
    
    $sfe = new ullSearchFormEntry();
    $sfe->columnName = 'superior_ull_user_id';
    $sfeArray[] = $sfe;

    return $sfeArray;
  }

  /**
   * Returns an array of search form entries describing all
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
  
    $sfe = new ullSearchFormEntry();
    $sfe->relations[] = 'UllEntityGroup';
    $sfe->columnName = 'ull_group_id';
    $sfeArray[] = $sfe;
    
    return $sfeArray;
  }
}