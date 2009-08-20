<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseUllVentoryItemTaking extends UllRecord
{
    public function setTableDefinition()
    {
        parent::setTableDefinition();
        $this->setTableName('ull_ventory_item_taking');
        $this->hasColumn('ull_ventory_item_id', 'integer', null, array('type' => 'integer', 'notnull' => true));
        $this->hasColumn('ull_ventory_taking_id', 'integer', null, array('type' => 'integer', 'notnull' => true));
    }

    public function setUp()
    {
        parent::setUp();
    $this->hasOne('UllVentoryItem', array('local' => 'ull_ventory_item_id',
                                              'foreign' => 'id',
                                              'onDelete' => 'CASCADE'));

        $this->hasOne('UllVentoryTaking', array('local' => 'ull_ventory_taking_id',
                                                'foreign' => 'id'));
    }
}