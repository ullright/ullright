<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class AdjustColumnConfigDbColumnNameLength extends Doctrine_Migration
{
  public function up()
  {
    //make db_column_name wider because of 'is_show_fax_extension_in_phonebook'
    $this->changeColumn('ull_column_config', 'db_column_name', 'string', array('length' => 64));
  }

  public function down()
  {
    $this->changeColumn('ull_column_config', 'db_column_name', 'string', array('length' => 32));
  }
}