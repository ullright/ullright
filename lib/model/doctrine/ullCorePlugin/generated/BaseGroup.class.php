<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseGroup extends Entity
{
  public function setUp()
  {
    parent::setUp();
    $this->hasMany('User', array('refClass' => 'EntityGroup',
                                 'local' => 'group_id',
                                 'foreign' => 'entity_id'));
  }
}