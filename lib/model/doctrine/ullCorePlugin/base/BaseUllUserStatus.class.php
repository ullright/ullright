<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseUllUserStatus extends UllRecord
{
    public function setTableDefinition()
    {
        parent::setTableDefinition();
        $this->setTableName('ull_user_status');
        $this->hasColumn('slug', 'string', 64, array('type' => 'string', 'length' => '64'));
        $this->hasColumn('name', 'string', 50, array('type' => 'string', 'notnull' => true, 'length' => '50'));
        $this->hasColumn('is_active', 'boolean', null, array('type' => 'boolean'));
    }

    public function setUp()
    {
        parent::setUp();
    $this->hasMany('UllParentEntity', array('local' => 'id',
                                                'foreign' => 'ull_user_status_id'));

        $i18n0 = new Doctrine_Template_I18n(array('fields' => array(0 => 'name')));
        $this->actAs($i18n0);
    }
}