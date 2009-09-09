<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseUllSelectChild extends UllRecord
{
    public function setTableDefinition()
    {
        parent::setTableDefinition();
        $this->setTableName('ull_select_child');
        $this->hasColumn('ull_select_id', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('label', 'string', 64, array(
             'type' => 'string',
             'length' => '64',
             ));
        $this->hasColumn('sequence', 'integer', null, array(
             'type' => 'integer',
             ));
    }

    public function setUp()
    {
        parent::setUp();
    $this->hasOne('UllSelect', array(
             'local' => 'ull_select_id',
             'foreign' => 'id'));

        $i18n0 = new Doctrine_Template_I18n(array(
             'fields' => 
             array(
              0 => 'label',
             ),
             ));
        $sluggable0 = new Doctrine_Template_Sluggable(array(
             'unique' => true,
             'fields' => 
             array(
              0 => 'label',
             ),
             'canUpdate' => true,
             ));
        $this->actAs($i18n0);
        $this->actAs($sluggable0);
    }
}