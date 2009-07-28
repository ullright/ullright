<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseUllVentoryItemTypeAttribute extends UllRecord
{
    public function setTableDefinition()
    {
        parent::setTableDefinition();
        $this->setTableName('ull_ventory_item_type_attribute');
        $this->hasColumn('ull_ventory_item_type_id', 'integer', null, array('type' => 'integer', 'notnull' => true));
        $this->hasColumn('ull_ventory_item_attribute_id', 'integer', null, array('type' => 'integer', 'notnull' => true));
        $this->hasColumn('is_mandatory', 'boolean', null, array('type' => 'boolean'));
        $this->hasColumn('is_presetable', 'boolean', null, array('type' => 'boolean', 'default' => true));
    }

    public function setUp()
    {
        parent::setUp();
    $this->hasOne('UllVentoryItemType', array('local' => 'ull_ventory_item_type_id',
                                                  'foreign' => 'id'));

        $this->hasOne('UllVentoryItemAttribute', array('local' => 'ull_ventory_item_attribute_id',
                                                       'foreign' => 'id'));

        $this->hasMany('UllVentoryItemAttributeValue', array('local' => 'id',
                                                             'foreign' => 'ull_ventory_item_type_attribute_id'));

        $this->hasMany('UllVentoryItemAttributePreset', array('local' => 'id',
                                                              'foreign' => 'ull_ventory_item_type_attribute_id'));
    }
}