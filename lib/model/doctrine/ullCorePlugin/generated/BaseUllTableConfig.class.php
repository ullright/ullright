<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseUllTableConfig extends UllRecord
{
  public function setTableDefinition()
  {
    parent::setTableDefinition();
    $this->setTableName('ull_table_config');
    $this->hasColumn('db_table_name', 'string', 32, array('type' => 'string', 'length' => '32'));
    $this->hasColumn('label', 'string', 64, array('type' => 'string', 'length' => '64'));
    $this->hasColumn('description', 'clob', null, array('type' => 'clob'));
    $this->hasColumn('sort_fields', 'string', 255, array('type' => 'string', 'length' => '255'));
    $this->hasColumn('search_fields', 'string', 255, array('type' => 'string', 'length' => '255'));
  }

}