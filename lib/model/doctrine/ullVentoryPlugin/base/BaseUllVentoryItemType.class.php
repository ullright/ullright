<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseUllVentoryItemType extends UllRecord
{
    public function setTableDefinition()
    {
        parent::setTableDefinition();
        $this->setTableName('ull_ventory_item_type');
        $this->hasColumn('slug', 'string', 128, array('type' => 'string', 'length' => '128'));
        $this->hasColumn('name', 'string', 128, array('type' => 'string', 'notnull' => true, 'length' => '128'));
        $this->hasColumn('has_software', 'boolean', null, array('type' => 'boolean'));
    }

    public function setUp()
    {
        parent::setUp();
    $this->hasMany('UllVentoryItemModel', array('local' => 'id',
                                                    'foreign' => 'ull_ventory_item_type_id'));

        $this->hasMany('UllVentoryItemTypeAttribute', array('local' => 'id',
                                                            'foreign' => 'ull_ventory_item_type_id'));

        $i18n0 = new Doctrine_Template_I18n(array('fields' => array(0 => 'name')));
        $this->actAs($i18n0);
    }
}