<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseUllVentorySoftware extends UllRecord
{
    public function setTableDefinition()
    {
        parent::setTableDefinition();
        $this->setTableName('ull_ventory_software');
        $this->hasColumn('name', 'string', 128, array('type' => 'string', 'length' => '128'));
    }

    public function setUp()
    {
        parent::setUp();
    $this->hasMany('UllVentoryItemSoftware', array('local' => 'id',
                                                       'foreign' => 'ull_ventory_software_id'));

        $this->hasMany('UllVentorySoftwareLicense', array('local' => 'id',
                                                          'foreign' => 'ull_ventory_software_id'));
    }
}