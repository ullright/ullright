<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseUllVentoryItemManufacturer extends UllRecord
{
    public function setTableDefinition()
    {
        parent::setTableDefinition();
        $this->setTableName('ull_ventory_item_manufacturer');
        $this->hasColumn('name', 'string', 128, array('type' => 'string', 'notnull' => true, 'length' => '128'));
    }

    public function setUp()
    {
        parent::setUp();
    $this->hasMany('UllVentoryItemModel', array('local' => 'id',
                                                    'foreign' => 'ull_ventory_item_manufacturer_id'));
    }
}