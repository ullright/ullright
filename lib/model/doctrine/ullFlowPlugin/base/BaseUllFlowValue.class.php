<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseUllFlowValue extends UllRecord
{
    public function setTableDefinition()
    {
        parent::setTableDefinition();
        $this->setTableName('ull_flow_value');
        $this->hasColumn('ull_flow_doc_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('ull_flow_column_config_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('ull_flow_memory_id', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('value', 'string', 65536, array(
             'type' => 'string',
             'length' => '65536',
             ));
    }

    public function setUp()
    {
        parent::setUp();
    $this->hasOne('UllFlowDoc', array(
             'local' => 'ull_flow_doc_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));

        $this->hasOne('UllFlowColumnConfig', array(
             'local' => 'ull_flow_column_config_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));
    }
}