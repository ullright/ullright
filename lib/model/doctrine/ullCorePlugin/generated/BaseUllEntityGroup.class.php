<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseUllEntityGroup extends UllRecord
{
  public function setTableDefinition()
  {
    parent::setTableDefinition();
    $this->setTableName('ull_entity_group');
    $this->hasColumn('ull_entity_id', 'integer', null, array('type' => 'integer', 'notnull' => true));
    $this->hasColumn('ull_group_id', 'integer', null, array('type' => 'integer', 'notnull' => true));
  }

  public function setUp()
  {
    parent::setUp();
    $this->hasOne('UllEntity', array('local' => 'ull_entity_id',
                                     'foreign' => 'id'));

    $this->hasOne('UllGroup', array('local' => 'ull_group_id',
                                    'foreign' => 'id'));

    $this->hasOne('UllEntity as UllEntityAsGroup', array('local' => 'ull_group_id',
                                                         'foreign' => 'id'));

    $this->hasOne('UllUser', array('local' => 'ull_entity_id',
                                   'foreign' => 'id'));
  }
}