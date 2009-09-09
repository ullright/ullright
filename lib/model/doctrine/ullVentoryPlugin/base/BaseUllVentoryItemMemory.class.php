<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseUllVentoryItemMemory extends UllRecord
{
    public function setTableDefinition()
    {
        parent::setTableDefinition();
        $this->setTableName('ull_ventory_item_memory');
        $this->hasColumn('ull_ventory_item_id', 'integer', null, array('type' => 'integer', 'notnull' => true));
        $this->hasColumn('transfer_at', 'timestamp', null, array('type' => 'timestamp', 'notnull' => true));
        $this->hasColumn('source_ull_entity_id', 'integer', null, array('type' => 'integer', 'notnull' => true));
        $this->hasColumn('target_ull_entity_id', 'integer', null, array('type' => 'integer', 'notnull' => true));
        $this->hasColumn('comment', 'string', 4000, array('type' => 'string', 'length' => '4000'));
    }

    public function setUp()
    {
        parent::setUp();
    $this->hasOne('UllVentoryItem', array('local' => 'ull_ventory_item_id',
                                              'foreign' => 'id',
                                              'onDelete' => 'CASCADE'));

        $this->hasOne('UllEntity as SourceUllEntity', array('local' => 'source_ull_entity_id',
                                                            'foreign' => 'id'));

        $this->hasOne('UllEntity as TargetUllEntity', array('local' => 'target_ull_entity_id',
                                                            'foreign' => 'id'));
    }
}