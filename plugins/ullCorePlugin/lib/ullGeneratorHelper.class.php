<?php
/**
 * This class provides helper functions to the generator
 * framework.
 */
class ullGeneratorHelper
{
  /**
   * Returns a simplified array describing a doctrine relation.
   * @param $modelName a model name
   * @return array
   */
  public static function resolveDoctrineRelations($modelName)
  {
    // get Doctrine relations
    $relations = Doctrine::getTable($modelName)->getRelations();

    $columnRelations = array();

    foreach($relations as $relation)
    {
      // take the first relation for each column and don't overwrite them lateron
      if (!isset($columnRelations[$relation->getLocal()]))
      {
        $columnRelations[$relation->getLocal()] = array(
          'model' => $relation->getClass(), 
          'foreign_id' => $relation->getForeign()
        );
      }
    }

    return $columnRelations;
  }
}

