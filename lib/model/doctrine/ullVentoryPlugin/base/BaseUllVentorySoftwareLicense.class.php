<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseUllVentorySoftwareLicense extends UllRecord
{
    public function setTableDefinition()
    {
        parent::setTableDefinition();
        $this->setTableName('ull_ventory_software_license');
        $this->hasColumn('ull_ventory_software_id', 'integer', null, array('type' => 'integer', 'notnull' => true));
        $this->hasColumn('license_key', 'string', 128, array('type' => 'string', 'length' => '128'));
        $this->hasColumn('quantity', 'integer', null, array('type' => 'integer'));
        $this->hasColumn('supplier', 'string', 128, array('type' => 'string', 'length' => '128'));
        $this->hasColumn('delivery_date', 'timestamp', null, array('type' => 'timestamp'));
        $this->hasColumn('comment', 'string', 4000, array('type' => 'string', 'length' => '4000'));
    }

    public function setUp()
    {
        parent::setUp();
    $this->hasOne('UllVentorySoftware', array('local' => 'ull_ventory_software_id',
                                                  'foreign' => 'id'));

        $this->hasMany('UllVentoryItemSoftware', array('local' => 'id',
                                                       'foreign' => 'ull_ventory_software_license_id'));
    }
}