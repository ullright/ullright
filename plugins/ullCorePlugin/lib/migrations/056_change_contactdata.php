<?php

class ChangeContactData extends Doctrine_Migration
{
  protected $tableNames = array(
    'ull_entity',
    'ull_user_version',
    'ull_group_version',
    'ull_ventory_origin_dummy_user_version',
    'ull_ventory_status_dummy_user_version',
    'ull_clone_user_version'
    );

  public function up()
  {
    foreach ($this->tableNames as $tableName)
    {
      $this->addColumn($tableName, 'is_show_in_phonebook', 'boolean', array('default' => true));
      $this->addColumn($tableName, 'is_photo_public', 'boolean', array('default' => true)); 
      $this->addColumn($tableName, 'alternative_phone_extension', 'integer', array());
    }
  }

  public function down()
  {
    foreach ($this->tableNames as $tableName)
    {
      $this->removeColumn($tableName, 'is_show_in_phonebook');
      $this->removeColumn($tableName, 'is_photo_public');
      $this->removeColumn($tableName, 'alternative_phone_extension');
    }
  }
}