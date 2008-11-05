<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseUllGroup extends UllEntity
{
  public function setUp()
  {
    parent::setUp();
    $this->hasMany('UllUser', array('refClass' => 'UllEntityGroup',
                                    'local' => 'group_id',
                                    'foreign' => 'entity_id'));

    $this->hasMany('UllFlowAppAccess', array('local' => 'id',
                                             'foreign' => 'ull_group_id'));
  }
}