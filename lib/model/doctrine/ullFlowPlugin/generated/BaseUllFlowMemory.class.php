<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseUllFlowMemory extends UllRecord
{
  public function setTableDefinition()
  {
    parent::setTableDefinition();
    $this->setTableName('ull_flow_memory');
    $this->hasColumn('ull_flow_doc_id', 'integer', null, array('type' => 'integer', 'notnull' => true));
    $this->hasColumn('ull_flow_step_id', 'integer', null, array('type' => 'integer'));
    $this->hasColumn('ull_flow_action_id', 'integer', null, array('type' => 'integer'));
    $this->hasColumn('assigned_to_ull_entity_id', 'integer', null, array('type' => 'integer', 'notnull' => true));
    $this->hasColumn('comment', 'string', 255, array('type' => 'string', 'length' => '255'));
    $this->hasColumn('creator_ull_entity_id', 'integer', null, array('type' => 'integer', 'notnull' => true));
  }

  public function setUp()
  {
    parent::setUp();
    $this->hasOne('UllFlowDoc', array('local' => 'ull_flow_doc_id',
                                      'foreign' => 'id',
                                      'onDelete' => 'CASCADE'));

    $this->hasOne('UllEntity as AssignedToUllEntity', array('local' => 'assigned_to_ull_entity_id',
                                                            'foreign' => 'id'));

    $this->hasOne('UllEntity as CreatorUllEntity', array('local' => 'creator_ull_entity_id',
                                                         'foreign' => 'id'));
  }
}