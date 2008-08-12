<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseGroup extends UllRecord
{
  public function setTableDefinition()
  {
    parent::setTableDefinition();
    $this->setTableName('ull_group');
    $this->hasColumn('caption', 'string', 128, array('type' => 'string', 'length' => '128'));
    $this->hasColumn('email', 'string', 64, array('type' => 'string', 'length' => '64'));
    $this->hasColumn('system', 'boolean', null, array('type' => 'boolean', 'default' => false, 'notnull' => true));
  }

  public function setUp()
  {
    parent::setUp();
    $this->hasMany('User as Users', array('refClass' => 'UserGroup',
                                          'local' => 'group_id',
                                          'foreign' => 'user_id'));
  }
}