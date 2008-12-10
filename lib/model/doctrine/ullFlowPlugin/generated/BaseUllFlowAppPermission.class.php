<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseUllFlowAppPermission extends UllRecord
{
  public function setTableDefinition()
  {
    parent::setTableDefinition();
    $this->setTableName('ull_flow_app_permission');
    $this->hasColumn('ull_flow_app_id', 'integer', null, array('type' => 'integer', 'notnull' => true));
    $this->hasColumn('ull_permission_id', 'integer', null, array('type' => 'integer', 'notnull' => true));
  }

  public function setUp()
  {
    parent::setUp();
    $this->hasOne('UllFlowApp', array('local' => 'ull_flow_app_id',
                                      'foreign' => 'id'));

    $this->hasOne('UllPermission', array('local' => 'ull_permission_id',
                                         'foreign' => 'id'));
  }
}