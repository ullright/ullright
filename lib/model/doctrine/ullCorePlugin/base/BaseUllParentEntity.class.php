<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseUllParentEntity extends UllRecord
{
    public function setTableDefinition()
    {
        parent::setTableDefinition();
        $this->setTableName('ull_parent_entity');
        $this->hasColumn('first_name', 'string', 64, array('type' => 'string', 'length' => '64'));
        $this->hasColumn('last_name', 'string', 64, array('type' => 'string', 'length' => '64'));
        $this->hasColumn('display_name', 'string', 64, array('type' => 'string', 'length' => '64'));
        $this->hasColumn('username', 'string', 64, array('type' => 'string', 'unique' => true, 'length' => '64'));
        $this->hasColumn('email', 'string', 64, array('type' => 'string', 'length' => '64'));
        $this->hasColumn('password', 'string', 40, array('type' => 'string', 'length' => '40'));
        $this->hasColumn('sex', 'enum', null, array('type' => 'enum', 'values' => array(0 => NULL, 1 => 'm', 2 => 'f')));
        $this->hasColumn('entry_date', 'date', null, array('type' => 'date'));
        $this->hasColumn('deactivation_date', 'date', null, array('type' => 'date'));
        $this->hasColumn('separation_date', 'date', null, array('type' => 'date'));
        $this->hasColumn('ull_employment_type_id', 'integer', null, array('type' => 'integer'));
        $this->hasColumn('ull_job_title_id', 'integer', null, array('type' => 'integer'));
        $this->hasColumn('ull_company_id', 'integer', null, array('type' => 'integer'));
        $this->hasColumn('ull_department_id', 'integer', null, array('type' => 'integer'));
        $this->hasColumn('ull_location_id', 'integer', null, array('type' => 'integer'));
        $this->hasColumn('superior_ull_user_id', 'integer', null, array('type' => 'integer'));
        $this->hasColumn('phone_extension', 'integer', 20, array('type' => 'integer', 'length' => '20'));
        $this->hasColumn('is_show_extension_in_phonebook', 'boolean', null, array('type' => 'boolean'));
        $this->hasColumn('fax_extension', 'integer', 20, array('type' => 'integer', 'length' => '20'));
        $this->hasColumn('is_show_fax_extension_in_phonebook', 'boolean', null, array('type' => 'boolean'));
        $this->hasColumn('comment', 'string', 4000, array('type' => 'string', 'length' => '4000'));
        $this->hasColumn('ull_user_status_id', 'integer', null, array('type' => 'integer', 'notnull' => true, 'default' => '1'));
        $this->hasColumn('is_virtual_group', 'boolean', null, array('type' => 'boolean', 'default' => false));
        $this->hasColumn('photo', 'string', 255, array('type' => 'string', 'length' => '255'));


        $this->setAttribute(Doctrine::ATTR_EXPORT, Doctrine::EXPORT_ALL ^ Doctrine::EXPORT_CONSTRAINTS);
    }

    public function setUp()
    {
        parent::setUp();
    $this->hasOne('UllUser as Superior', array('local' => 'superior_ull_user_id',
                                                   'foreign' => 'id'));

        $this->hasOne('UllJobTitle', array('local' => 'ull_job_title_id',
                                           'foreign' => 'id'));

        $this->hasOne('UllCompany', array('local' => 'ull_company_id',
                                          'foreign' => 'id'));

        $this->hasOne('UllEmploymentType', array('local' => 'ull_employment_type_id',
                                                 'foreign' => 'id'));

        $this->hasOne('UllDepartment', array('local' => 'ull_department_id',
                                             'foreign' => 'id'));

        $this->hasOne('UllLocation', array('local' => 'ull_location_id',
                                           'foreign' => 'id'));

        $this->hasOne('UllUserStatus', array('local' => 'ull_user_status_id',
                                             'foreign' => 'id'));

        $superversionable0 = new Doctrine_Template_SuperVersionable(array('versionColumn' => 'version', 'className' => '%CLASS%Version', 'enableFutureVersions' => true));
        $this->actAs($superversionable0);
    }
}