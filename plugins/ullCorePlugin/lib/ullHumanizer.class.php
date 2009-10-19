<?php
/**
 * This class returns simple string representations of
 * common internal relationship, model and column names.
 *
 * It also does translation.
 */
class ullHumanizer
{
  private static $relations = array(
    'Creator'             => 'Created by',
    'Updator'             => 'Updated by',
    'UllLocation'         => 'Location',
    'UllCompany'          => 'Company',
    'UllDepartment'       => 'Department',
    'UllEmploymentType'   => 'Employment type',
    'UllJobTitle'         => 'Job title',
    'UllEntityGroup'      => 'Group membership',
    'UllEntity'           => 'User',
    );
  
  //Some fields (e.g. first_name, last_name, ...)
  //are not listed because they're correctly humanized
  //by the fallback sfInflector::humanize call anyway.
  private static $columnNames = array(
    'id'                  => 'ID',
    'creator_user_id'     => 'Created by', 
    'created_at'          => 'Created at',
    'updator_user_id'     => 'Updated by',
    'updated_at'          => 'Updated at',
    'db_table_name'       => 'Table name',
    'db_column_name'      => 'Column name',
    'field_type'          => 'Field Type',
    'is_enabled'          => 'Enabled',
    'is_in_list'          => 'Show in list',
    'is_mandatory'        => 'Mandatory',
    'slug'                => 'Unique identifier',
    'ull_column_type_id'  => 'Type',
    'ull_group_id'        => 'Group',
    'ull_user_id'         => 'User',
    'ull_privilege_id'    => 'Privilege',
    'ull_company_id'      => 'Company',
    'ull_department_id'   => 'Department',
    'ull_location_id'     => 'Location',
    'superior_ull_user_id'=> 'Superior',
    'ull_user_status_id'  => 'Status',
    'ull_permission_id'      => 'Permission'
    );

    /**
     * Returns a human readable string, expects an
     * internal relationship or model name.
     *
     * @param $relationLabel the relationship or model name to humanize
     * @return string a human readable string
     */
    public static function humanizeAndTranslateRelation($relationLabel)
    {
      if (isset(self::$relations[$relationLabel]))
      {
        return __(self::$relations[$relationLabel], null, 'common');
      }

      return $relationLabel;
    }

    /**
     * Returns true if this class can offer a humanization
     * for the given argument, false otherwise.
     *
     * @param $relationLabel the relationship or model name to humanize
     * @return boolean true if the argument can be humanized, false otherwise
     */
    public static function hasRelationHumanization($relationLabel)
    {
      return (isset(self::$relations[$relationLabel])) ? true : false;
    }
    
    /**
     * Returns a human readable string, expects a
     * column name.
     * 
     * Looks up a dictionary of common column names.
     * If not found in the dictionary it uses sfInflector::humanize()
     *
     * @param string $columnName 
     * @return string a human readable column name
     */
    public static function humanizeAndTranslateColumnName($columnName)
    {
      if (isset(self::$columnNames[$columnName]))
      {
        $humanizedName = self::$columnNames[$columnName];
      }
      else 
      {
        $humanizedName = sfInflector::humanize($columnName);
      }
      
      return __($humanizedName, null, 'common');
    }

    /**
     * Returns true if this class can offer a humanization
     * for the given argument, false otherwise.
     *
     * @param $relationLabel the column name to humanize
     * @return boolean true if the argument can be humanized, false otherwise
     */
    public static function hasColumnNameHumanization($relationLabel)
    {
      return (isset(self::$columnNames[$relationLabel])) ? true : false;
    }
}