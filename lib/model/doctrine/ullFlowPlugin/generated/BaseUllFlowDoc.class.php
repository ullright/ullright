<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseUllFlowDoc extends UllRecord
{
  public function setTableDefinition()
  {
    parent::setTableDefinition();
    $this->setTableName('ull_flow_doc');
    $this->hasColumn('ull_flow_app_id', 'integer', null, array('type' => 'integer', 'notnull' => true));
    $this->hasColumn('title', 'string', 255, array('type' => 'string', 'length' => '255'));
    $this->hasColumn('ull_flow_action_id', 'integer', null, array('type' => 'integer'));
    $this->hasColumn('assigned_to_ull_entity_id', 'integer', null, array('type' => 'integer', 'notnull' => true));
    $this->hasColumn('assigned_to_ull_flow_step_id', 'integer', null, array('type' => 'integer'));
  }

  public function setUp()
  {
    parent::setUp();
    $this->hasOne('UllFlowApp', array('local' => 'ull_flow_app_id',
                                      'foreign' => 'id',
                                      'onDelete' => 'CASCADE'));

    $this->hasOne('UllEntity', array('local' => 'assigned_to_ull_entity_id',
                                     'foreign' => 'id'));

    $this->hasMany('UllFlowValue as UllFlowValues', array('local' => 'id',
                                                          'foreign' => 'ull_flow_doc_id'));
  }
}