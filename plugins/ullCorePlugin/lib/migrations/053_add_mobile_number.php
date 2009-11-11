<?php

class AddMobileNumber extends Doctrine_Migration
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
      $this->addColumn($tableName, 'mobile_number', 'string', array('length' => 20));
      
      //we are not using renameColumn here because it would
      //transfer the fax to mobile flags (which doesn't make sense)
      //and not all dbs support it
      $this->removeColumn($tableName, 'is_show_fax_extension_in_phonebook');
      $this->addColumn($tableName, 'is_show_mobile_number_in_phonebook', 'boolean', array());
    }
  }

  public function down()
  {
    foreach ($this->tableNames as $tableName)
    {
      $this->removeColumn($tableName, 'mobile_number');
      $this->removeColumn($tableName, 'is_show_mobile_number_in_phonebook');
      $this->addColumn($tableName, 'is_show_fax_extension_in_phonebook', 'boolean', array());
    }
  }
}