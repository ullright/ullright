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
}