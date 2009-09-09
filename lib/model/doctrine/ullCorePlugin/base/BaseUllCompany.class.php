<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseUllCompany extends UllRecord
{
    public function setTableDefinition()
    {
        parent::setTableDefinition();
        $this->setTableName('ull_company');
        $this->hasColumn('name', 'string', 100, array('type' => 'string', 'notnull' => true, 'length' => '100'));
        $this->hasColumn('short_name', 'string', 15, array('type' => 'string', 'length' => '15'));
    }

    public function setUp()
    {
        parent::setUp();
    $this->hasMany('UllParentEntity', array('local' => 'id',
                                                'foreign' => 'ull_company_id'));
    }
}