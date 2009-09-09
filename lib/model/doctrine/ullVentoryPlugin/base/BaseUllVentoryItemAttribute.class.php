<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseUllVentoryItemAttribute extends UllRecord
{
    public function setTableDefinition()
    {
        parent::setTableDefinition();
        $this->setTableName('ull_ventory_item_attribute');
        $this->hasColumn('name', 'string', 128, array(
             'type' => 'string',
             'notnull' => true,
             'length' => '128',
             ));
        $this->hasColumn('help', 'string', 4000, array(
             'type' => 'string',
             'length' => '4000',
             ));
        $this->hasColumn('ull_column_type_id', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('options', 'string', 4000, array(
             'type' => 'string',
             'length' => '4000',
             ));
    }

    public function setUp()
    {
        parent::setUp();
    $this->hasOne('UllColumnType', array(
             'local' => 'ull_column_type_id',
             'foreign' => 'id'));

        $this->hasMany('UllVentoryItemTypeAttribute', array(
             'local' => 'id',
             'foreign' => 'ull_ventory_item_attribute_id'));

        $i18n0 = new Doctrine_Template_I18n(array(
             'fields' => 
             array(
              0 => 'name',
              1 => 'help',
             ),
             ));
        $sluggable0 = new Doctrine_Template_Sluggable(array(
             'unique' => true,
             'fields' => 
             array(
              0 => 'name',
             ),
             'canUpdate' => true,
             ));
        $this->actAs($i18n0);
        $this->actAs($sluggable0);
    }
}