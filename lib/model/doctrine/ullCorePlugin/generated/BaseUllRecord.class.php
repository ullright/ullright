<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseUllRecord extends sfDoctrineRecord
{
  public function setTableDefinition()
  {
    $this->setTableName('ull_record');
    $this->hasColumn('namespace', 'string', 32, array('type' => 'string', 'length' => '32'));
    $this->hasColumn('creator_user_id', 'integer', null, array('type' => 'integer', 'default' => '1'));
    $this->hasColumn('updator_user_id', 'integer', null, array('type' => 'integer', 'default' => '1'));
  }

  public function setUp()
  {
    $this->hasOne('UllUser as Creator', array('local' => 'creator_user_id',
                                              'foreign' => 'id'));

    $this->hasOne('UllUser as Updator', array('local' => 'updator_user_id',
                                              'foreign' => 'id'));

    $timestampable0 = new Doctrine_Template_Timestampable(array('created' => array('name' => 'created_at', 'type' => 'timestamp'), 'updated' => array('name' => 'updated_at', 'type' => 'timestamp')));
    $this->actAs($timestampable0);
  }
}