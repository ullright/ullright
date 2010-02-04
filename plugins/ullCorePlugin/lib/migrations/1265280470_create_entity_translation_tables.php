<?php

class CreateEntityTranslationTables extends Doctrine_Migration_Base
{
  public function up()
  {
    $this->createTable('ull_entity_translation', array(
             'id' => 
             array(
              'type' => 'integer',
              'length' => 8,
              'primary' => true,
             ),
             'display_name' => 
             array(
              'type' => 'string',
              'length' => 64,
             ),
             'lang' => 
             array(
              'fixed' => true,
              'primary' => true,
              'type' => 'string',
              'length' => 2,
             ),
             ), array(
             'indexes' => 
             array(
             ),
             'primary' => 
             array(
              0 => 'id',
              1 => 'lang',
             ),
             ));
             
    $this->createForeignKey('ull_entity_translation', 'ull_entity_translation_id_ull_entity_id', array(
             'name' => 'ull_entity_translation_id_ull_entity_id',
             'local' => 'id',
             'foreign' => 'id',
             'foreignTable' => 'ull_entity',
             'onUpdate' => 'CASCADE',
             'onDelete' => 'CASCADE',
             ));
  }
  
  public function postUp()
  {
    //transfer data from old translation tables to the new one
    $dbh = Doctrine_Manager::getInstance()->getCurrentConnection()->getDbh();
    $dbh->exec("INSERT INTO ull_entity_translation SELECT * FROM ull_ventory_status_dummy_user_translation");
    $dbh->exec("INSERT INTO ull_entity_translation SELECT * FROM ull_ventory_origin_dummy_user_translation");
  }

  public function down()
  {
    throw new Doctrine_Migration_IrreversibleMigrationException('This migration can not be undone.');
  }
}
