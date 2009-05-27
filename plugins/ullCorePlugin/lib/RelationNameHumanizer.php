<?php
/**
 * This class returns simple string representations of
 * internal relationship and model names.
 * 
 * It also does translation.
 */
class RelationNameHumanizer
{
  private static $relationLabels = array(
    'Creator' => 'Created by',
    'Updator' => 'Updated by',
    'UllLocation' => 'Location',
    'UllCompany' => 'Company',
    'UllDepartment' => 'Department',
    'UllEmploymentType' => 'Employment type',
    'UllJobTitle' => 'Job title',
  );

  /**
   * Returns a human readable string, expects an
   * internal relationship or model name.
   * 
   * @param $relationLabel the relationship or model name to translate
   * @return string a human readable string
   */
  public static function humanize($relationLabel)
  {
    if (isset(self::$relationLabels[$relationLabel]))
    {
      return __(self::$relationLabels[$relationLabel], null, 'common');
    }

    return $relationLabel;
  }
}