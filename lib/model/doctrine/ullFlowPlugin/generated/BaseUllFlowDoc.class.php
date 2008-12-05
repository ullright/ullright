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
    $this->hasColumn('subject', 'string', 255, array('type' => 'string', 'length' => '255'));
    $this->hasColumn('ull_flow_action_id', 'integer', null, array('type' => 'integer'));
    $this->hasColumn('assigned_to_ull_entity_id', 'integer', null, array('type' => 'integer', 'notnull' => true));
    $this->hasColumn('assigned_to_ull_flow_step_id', 'integer', null, array('type' => 'integer', 'notnull' => true));
    $this->hasColumn('duplicate_tags_for_search', 'clob', null, array('type' => 'clob'));
    $this->hasColumn('dirty', 'integer', null, array('type' => 'integer'));
  }

  public function setUp()
  {
    parent::setUp();
    $this->hasOne('UllFlowApp', array('local' => 'ull_flow_app_id',
                                      'foreign' => 'id',
                                      'onDelete' => 'CASCADE'));

    $this->hasOne('UllFlowAction', array('local' => 'ull_flow_action_id',
                                         'foreign' => 'id'));

    $this->hasOne('UllEntity', array('local' => 'assigned_to_ull_entity_id',
                                     'foreign' => 'id'));

    $this->hasOne('UllFlowStep', array('local' => 'assigned_to_ull_flow_step_id',
                                       'foreign' => 'id'));

    $this->hasMany('Tagging', array('local' => 'id',
                                    'foreign' => 'taggable_id'));

    $this->hasMany('UllFlowValue as UllFlowValues', array('local' => 'id',
                                                          'foreign' => 'ull_flow_doc_id'));

    $this->hasMany('UllFlowMemory as UllFlowMemories', array('local' => 'id',
                                                             'foreign' => 'ull_flow_doc_id'));

    $taggable0 = new Taggable();
    $this->actAs($taggable0);
  }
}