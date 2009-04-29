<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseUllEntity extends UllParentEntity
{
    public function setTableDefinition()
    {
        parent::setTableDefinition();
        $this->setTableName('ull_entity');
        $this->hasColumn('type', 'string', 255, array('type' => 'string', 'length' => 255));

        $this->setSubClasses(array('UllUser' => array('type' => 'user'), 'UllGroup' => array('type' => 'group')));
    }

    public function setUp()
    {
        parent::setUp();
    $this->hasMany('UllEntityGroup', array('local' => 'id',
                                               'foreign' => 'ull_entity_id'));

        $this->hasMany('UllEntityGroup as UllEntityGroupsAsGroup', array('local' => 'id',
                                                                         'foreign' => 'ull_group_id'));

        $this->hasMany('UllFlowDoc as UllFlowDocs', array('local' => 'id',
                                                          'foreign' => 'assigned_to_ull_entity_id'));

        $this->hasMany('UllFlowMemory as UllFlowMemories', array('local' => 'id',
                                                                 'foreign' => 'assigned_to_ull_entity_id'));
    }
}