<?php
/**
 * This class returns simple string representations of
 * internal relationship and model names.
 *
 * It also does translation.
 */
class ullHumanizer
{
  private static $relationLabels = array(
    'Creator' => 'Created by',
    'Updator' => 'Updated by',
    'UllLocation' => 'Location',
    'UllCompany' => 'Company',
    'UllDepartment' => 'Department',
    'UllEmploymentType' => 'Employment type',
    'UllJobTitle' => 'Job title',
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
    'label'               => 'Label',
    'description'         => 'Description',
    'slug'                => 'Unique identifier',
    'options'             => 'Options',
    'ull_column_type_id'  => 'Type',
    'sequence'            => 'Sequence',
    'default_value'       => 'Default value',
    'ull_group_id'        => 'Group',
    'ull_privilege_id'    => 'Privilege',
    'comment'             => 'Comment'
    );

    /**
     * Returns a human readable string, expects an
     * internal relationship or model name.
     *
     * @param $relationLabel the relationship or model name to humanize
     * @return string a human readable string
     */
    public static function humanizeAndTranslate($relationLabel)
    {
      if (isset(self::$relationLabels[$relationLabel]))
      {
        return __(self::$relationLabels[$relationLabel], null, 'common');
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
    public static function hasHumanization($relationLabel)
    {
      return (isset(self::$relationLabels[$relationLabel])) ? true : false;
    }
}