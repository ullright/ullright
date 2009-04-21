<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseUllPrivilege extends UllRecord
{
  public function setTableDefinition()
  {
    parent::setTableDefinition();
    $this->setTableName('ull_privilege');
    $this->hasColumn('slug', 'string', 64, array('type' => 'string', 'length' => '64'));
  }

  public function setUp()
  {
    parent::setUp();
    $this->hasMany('UllWikiAccessLevelAccess', array('local' => 'id',
                                                     'foreign' => 'ull_privilege_id'));
  }
}