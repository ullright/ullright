<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseUllPermission extends UllRecord
{
  public function setTableDefinition()
  {
    parent::setTableDefinition();
    $this->setTableName('ull_permission');
    $this->hasColumn('slug', 'string', 64, array('type' => 'string', 'length' => '64'));
  }

  public function setUp()
  {
    parent::setUp();
    $this->hasMany('UllGroup', array('refClass' => 'UllGroupPermission',
                                     'local' => 'ull_permission_id',
                                     'foreign' => 'ull_group_id'));

    $this->hasMany('UllFlowApp', array('refClass' => 'UllFlowAppPermission',
                                       'local' => 'ull_permission_id',
                                       'foreign' => 'ull_flow_app_id'));

    $this->hasMany('UllFlowAppPermission', array('local' => 'id',
                                                 'foreign' => 'ull_permission_id'));
  }
}