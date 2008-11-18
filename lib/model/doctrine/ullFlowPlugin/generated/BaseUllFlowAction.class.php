<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseUllFlowAction extends UllRecord
{
  public function setTableDefinition()
  {
    parent::setTableDefinition();
    $this->setTableName('ull_flow_action');
    $this->hasColumn('slug', 'varchar', 32, array('type' => 'varchar', 'notnull' => true, 'length' => '32'));
    $this->hasColumn('label', 'varchar', 32, array('type' => 'varchar', 'notnull' => true, 'length' => '32'));
    $this->hasColumn('status_only', 'boolean', null, array('type' => 'boolean'));
    $this->hasColumn('disable_validation', 'boolean', null, array('type' => 'boolean'));
    $this->hasColumn('notify_creator', 'boolean', null, array('type' => 'boolean'));
    $this->hasColumn('notify_next', 'boolean', null, array('type' => 'boolean'));
    $this->hasColumn('in_resultlist_by_default', 'boolean', null, array('type' => 'boolean'));
    $this->hasColumn('show_assigned_to', 'boolean', null, array('type' => 'boolean'));
    $this->hasColumn('comment_is_mandatory', 'boolean', null, array('type' => 'boolean'));
  }

  public function setUp()
  {
    parent::setUp();
    $this->hasMany('UllFlowDoc as UllFlowDocs', array('local' => 'id',
                                                      'foreign' => 'ull_flow_action_id'));

    $this->hasMany('UllFlowMemory as UllFlowMemories', array('local' => 'id',
                                                             'foreign' => 'ull_flow_action_id'));

    $this->hasMany('UllFlowStep', array('refClass' => 'UllFlowStepAction',
                                        'local' => 'ull_flow_action_id',
                                        'foreign' => 'ull_flow_step_id'));

    $i18n0 = new Doctrine_Template_I18n(array('fields' => array(0 => 'label')));
    $this->actAs($i18n0);
  }
}