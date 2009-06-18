<?php

/**
 * This class is a search configuration for ullFlow documents.
 * It provides the ullSearch framework with information regarding
 * searchable fields for the UllFlowDoc model.
 */
class ullFlowDocSearchConfig extends ullSearchConfig 
{
  protected $ullFlowApp;
  protected $virtualBlacklist;

  /**
   * Constructs a new instance of the ullFlowDocSearchConfig class.
   *
   * @param an optional ullFlowApp for virtual column support
   * @return a new ullFlowDocSearchConfig instance
   */
  public function __construct(array $params = null)
  {
    $this->ullFlowApp = ($params != null && count($params) > 0) ? $params[0] : null;
    $this->blacklist = array('namespace', 'dirty', 'duplicate_tags_for_search');
    // blacklist if virtual columns
    $this->virtualBlacklist = array('wiki_link');
  }

  /**
   * Configures an array of search form entries describing often
   * used columns when searching for a user.
   * 
   * NOTE: Uuids are not assigned to the entries.  
   */
  protected function configureDefaultSearchColumns()
  {
    $sfeArray = array();

    $sfe = new ullSearchFormEntry();
    $sfe->columnName = "assigned_to_ull_entity_id";
    $sfeArray[] = $sfe;
    
    $sfe = new ullSearchFormEntry();
    $sfe->columnName = "creator_user_id";
    $sfeArray[] = $sfe;
    
    $sfe = new ullSearchFormEntry();
    $sfe->columnName = "updator_user_id";
    $sfeArray[] = $sfe;

    $sfe = new ullSearchFormEntry();
    if ($this->ullFlowApp == null)
    {
      $sfe->columnName = "priority";
      $sfe->isVirtual = false;
    }
    else
    {
      $sfe->columnName = "column_priority";
      $sfe->isVirtual = true;
    }
    $sfeArray[] = $sfe;
    
    return $sfeArray;
  }

  /**
   * Returns an array of search form entries describing all
   * available columns when searching for a document.
   *
   * Also adds virtual columns.
   */
  public function getAllSearchableColumns() 
  {
    $sfeArray = array();

    $fieldNames = Doctrine::getTable('UllFlowDoc')->getFieldNames();
    foreach ($fieldNames as $key => $value)
    {
      if (array_search($value, $this->blacklist) === false)
      {
        $sfe = new ullSearchFormEntry();
        $sfe->columnName = $value;
        $sfeArray[] = $sfe;
      }
    }

    $virtualSfe = array();
    
    //add virtual columns, if an application is set
    if ($this->ullFlowApp != NULL)
    {
      $virtualColumns = $this->ullFlowApp->findOrderedColumns();

      foreach ($virtualColumns as $virtualColumn)
      {
        if (array_search($virtualColumn->slug, $this->virtualBlacklist) === false)
        {
          $vsfe = new ullSearchFormEntry();

          $vsfe->columnName = $virtualColumn->slug;
          $vsfe->isVirtual = true;

          $virtualSfe[] = $vsfe;

          //manually remove ull flow doc priority and subject columns
          //to prevent duplicate entries
          foreach ($sfeArray as $sfeKey => $sfe)
          {
            if ((($sfe->columnName == 'priority') && ($sfe->isVirtual == false)) ||
                (($sfe->columnName == 'subject') && ($sfe->isVirtual == false)))
            {
              unset($sfeArray[$sfeKey]);
            }
          }
        }
      }

      $sfeArray = array_values($sfeArray);
    }

    return array_merge($sfeArray, $virtualSfe);
  }
}
