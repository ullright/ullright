<?php

/**
 * This class represents a configuration object for a model.
 * It allows custom additions to the TableTool like added
 * action buttons or removal of the delete option.
 * 
 * Inheriting classes should name themselves:
 * modelNameTableToolEditConfig
 * so that the static loadClass function can find
 * them.
 * 
 * @author martin
 */
abstract class TableToolEditConfig
{
  /**
   * Returns true or false depending on the
   * deleteability of the underlying model.
   * @return true or false
   */
  public function allowDelete()
  {
    return true;
  }
  
  /**
   * Returns true if this edit config specifies
   * additional action buttons.
   * @return true or false
   */
  public function hasActionButtons()
  {
    return false;
  }
  
  /**
   * Returns the code for additional action buttons.
   * @return free-form
   */
  public function getActionButtons()
  {
  }
  
  /**
   * Returns an instance of an edit config for the
   * specified model name.
   *  
   * @param $modelName
   * @return An edit config instance.
   */
  public static function loadClass($modelName)
  {
    $editConfigClassName = $modelName . 'TableToolEditConfig';

    if (class_exists($editConfigClassName))
    {
      $editConfig = new $editConfigClassName;
      if (!($editConfig instanceof TableToolEditConfig))
      {
        throw new Exception("A table tool edit config must extend TableToolEditConfig.");
      }

      return $editConfig;
    }

    //don't throw an exception on purpose
    return NULL;
    //throw new Exception("Could not find an edit config for the given model name.");
  }
}
