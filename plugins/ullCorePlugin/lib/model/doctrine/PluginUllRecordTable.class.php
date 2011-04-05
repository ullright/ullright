<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PluginUllRecordTable extends Doctrine_Table
{
  public function findMostRecentlyCreated()
  {
    $q = new Doctrine_Query();
    $q
      ->from($this->getComponentName() . ' c')
      ->orderBy('c.created_at desc')
      ->limit(1);
    ;
    
    return $q->fetchOne();
  }
  
  /**
   * Retrieves an array of all many to many relations
   * for this table, where the keys are the relation
   * names and the values the relations itself.
   */
  public function getManyToManyRelations()
  {
    $manyToManyRelations = array();
    $relations = $this->getRelations();
    foreach ($relations as $relationName => $relation)
    {
      if ($relation instanceof Doctrine_Relation_Association)
      {
        $manyToManyRelations[$relationName] = $relation;
      }
    }
    
    return $manyToManyRelations;
  }
}