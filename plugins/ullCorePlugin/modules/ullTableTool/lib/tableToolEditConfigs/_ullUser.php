<?php

/**
 * Implementation of the abstract TableToolEditConfig class
 * for the ulluser model.
 * 
 * @author martin
 */
class UllUserTableToolEditConfig extends TableToolEditConfig 
{
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/modules/ullTableTool/lib/TableToolEditConfig#allowDelete()
   */
  public function allowDelete()
  {
    return false;
  }
  
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/modules/ullTableTool/lib/TableToolEditConfig#hasActionButtons()
   */
  public function hasActionButtons()
  {
    return true;
  }
  
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/modules/ullTableTool/lib/TableToolEditConfig#getActionButtons()
   */
  public function getActionButtons()
  {
    //return submit_tag(__('Generate Email and username', null, 'common'));
  }
}
