<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseUllGroup extends UllEntity
{
    public function setUp()
    {
        parent::setUp();
    $this->hasMany('UllUser', array('refClass' => 'UllEntityGroup',
                                        'local' => 'ull_group_id',
                                        'foreign' => 'ull_entity_id'));

        $this->hasMany('UllWikiAccessLevelAccess', array('local' => 'id',
                                                         'foreign' => 'ull_group_id'));

        $this->hasMany('UllEntityGroup', array('local' => 'id',
                                               'foreign' => 'ull_group_id'));

        $this->hasMany('UllPermission as UllPermissions', array('refClass' => 'UllGroupPermission',
                                                                'local' => 'ull_group_id',
                                                                'foreign' => 'ull_permission_id'));

        $this->hasMany('UllGroupPermission', array('local' => 'id',
                                                   'foreign' => 'ull_group_id'));
    }
}