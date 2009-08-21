<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseUllVentoryItemModel extends UllRecord
{
    public function setTableDefinition()
    {
        parent::setTableDefinition();
        $this->setTableName('ull_ventory_item_model');
        $this->hasColumn('name', 'string', 128, array('type' => 'string', 'notnull' => true, 'length' => '128'));
        $this->hasColumn('ull_ventory_item_manufacturer_id', 'integer', null, array('type' => 'integer', 'notnull' => true));
        $this->hasColumn('ull_ventory_item_type_id', 'integer', null, array('type' => 'integer', 'notnull' => true));
    }

    public function setUp()
    {
        parent::setUp();
    $this->hasOne('UllVentoryItemManufacturer', array('local' => 'ull_ventory_item_manufacturer_id',
                                                          'foreign' => 'id',
                                                          'onDelete' => 'CASCADE'));

        $this->hasOne('UllVentoryItemType', array('local' => 'ull_ventory_item_type_id',
                                                  'foreign' => 'id',
                                                  'onDelete' => 'CASCADE'));

        $this->hasMany('UllVentoryItem', array('local' => 'id',
                                               'foreign' => 'ull_ventory_item_model_id'));

        $this->hasMany('UllVentoryItemAttributePreset', array('local' => 'id',
                                                              'foreign' => 'ull_ventory_item_model_id'));
    }
}