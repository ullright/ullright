<?php

class AddUllVentoryTables extends Doctrine_Migration
{
  public function up()
  {
    $this->createTable('ull_ventory_item_memory', array('id' => array('type' => 'integer', 'length' => 20, 'autoincrement' => true, 'primary' => true), 'namespace' => array('type' => 'string', 'length' => 32), 'ull_ventory_item_id' => array('type' => 'integer', 'notnull' => true, 'length' => 2147483647), 'transfer_at' => array('type' => 'timestamp', 'notnull' => true, 'length' => 25), 'source_ull_entity_id' => array('type' => 'integer', 'notnull' => true, 'length' => 2147483647), 'target_ull_entity_id' => array('type' => 'integer', 'notnull' => true, 'length' => 2147483647), 'comment' => array('type' => 'string', 'length' => 4000), 'created_at' => array('type' => 'timestamp', 'length' => 25), 'updated_at' => array('type' => 'timestamp', 'length' => 25), 'creator_user_id' => array('type' => 'integer', 'length' => 2147483647), 'updator_user_id' => array('type' => 'integer', 'length' => 2147483647)), array('indexes' => array(), 'primary' => array(0 => 'id')));
    $this->createTable('ull_ventory_item', array('id' => array('type' => 'integer', 'length' => 20, 'autoincrement' => true, 'primary' => true), 'namespace' => array('type' => 'string', 'length' => 32), 'inventory_number' => array('type' => 'string', 'notnull' => true, 'unique' => true, 'length' => 128), 'serial_number' => array('type' => 'string', 'length' => 128), 'comment' => array('type' => 'string', 'length' => 4000), 'ull_ventory_item_model_id' => array('type' => 'integer', 'notnull' => true, 'length' => 2147483647), 'ull_entity_id' => array('type' => 'integer', 'notnull' => true, 'length' => 2147483647), 'created_at' => array('type' => 'timestamp', 'length' => 25), 'updated_at' => array('type' => 'timestamp', 'length' => 25), 'creator_user_id' => array('type' => 'integer', 'length' => 2147483647), 'updator_user_id' => array('type' => 'integer', 'length' => 2147483647)), array('indexes' => array(), 'primary' => array(0 => 'id')));
    $this->createTable('ull_ventory_item_model', array('id' => array('type' => 'integer', 'length' => 20, 'autoincrement' => true, 'primary' => true), 'namespace' => array('type' => 'string', 'length' => 32), 'name' => array('type' => 'string', 'notnull' => true, 'length' => 128), 'ull_ventory_item_manufacturer_id' => array('type' => 'integer', 'notnull' => true, 'length' => 2147483647), 'ull_ventory_item_type_id' => array('type' => 'integer', 'notnull' => true, 'length' => 2147483647), 'created_at' => array('type' => 'timestamp', 'length' => 25), 'updated_at' => array('type' => 'timestamp', 'length' => 25), 'creator_user_id' => array('type' => 'integer', 'length' => 2147483647), 'updator_user_id' => array('type' => 'integer', 'length' => 2147483647)), array('indexes' => array(), 'primary' => array(0 => 'id')));
    $this->createTable('ull_ventory_item_type_attribute', array('id' => array('type' => 'integer', 'length' => 20, 'autoincrement' => true, 'primary' => true), 'namespace' => array('type' => 'string', 'length' => 32), 'ull_ventory_item_type_id' => array('type' => 'integer', 'notnull' => true, 'length' => 2147483647), 'ull_ventory_item_attribute_id' => array('type' => 'integer', 'notnull' => true, 'length' => 2147483647), 'is_mandatory' => array('type' => 'boolean', 'length' => 25), 'is_presetable' => array('type' => 'boolean', 'default' => 1, 'length' => 25), 'created_at' => array('type' => 'timestamp', 'length' => 25), 'updated_at' => array('type' => 'timestamp', 'length' => 25), 'creator_user_id' => array('type' => 'integer', 'length' => 2147483647), 'updator_user_id' => array('type' => 'integer', 'length' => 2147483647)), array('indexes' => array(), 'primary' => array(0 => 'id')));
    $this->createTable('ull_ventory_item_attribute', array('id' => array('type' => 'integer', 'length' => 20, 'autoincrement' => true, 'primary' => true), 'namespace' => array('type' => 'string', 'length' => 32), 'slug' => array('type' => 'string', 'unique' => true, 'length' => 128), 'ull_column_type_id' => array('type' => 'integer', 'length' => 2147483647), 'created_at' => array('type' => 'timestamp', 'length' => 25), 'updated_at' => array('type' => 'timestamp', 'length' => 25), 'creator_user_id' => array('type' => 'integer', 'length' => 2147483647), 'updator_user_id' => array('type' => 'integer', 'length' => 2147483647)), array('indexes' => array(), 'primary' => array(0 => 'id')));
    $this->createTable('ull_ventory_item_attribute_value', array('id' => array('type' => 'integer', 'length' => 20, 'autoincrement' => true, 'primary' => true), 'namespace' => array('type' => 'string', 'length' => 32), 'ull_ventory_item_id' => array('type' => 'integer', 'notnull' => true, 'length' => 2147483647), 'ull_ventory_item_type_attribute_id' => array('type' => 'integer', 'notnull' => true, 'length' => 2147483647), 'value' => array('type' => 'string', 'length' => 4000), 'comment' => array('type' => 'string', 'length' => 4000), 'created_at' => array('type' => 'timestamp', 'length' => 25), 'updated_at' => array('type' => 'timestamp', 'length' => 25), 'creator_user_id' => array('type' => 'integer', 'length' => 2147483647), 'updator_user_id' => array('type' => 'integer', 'length' => 2147483647)), array('indexes' => array(), 'primary' => array(0 => 'id')));
    $this->createTable('ull_ventory_taking', array('id' => array('type' => 'integer', 'length' => 20, 'autoincrement' => true, 'primary' => true), 'namespace' => array('type' => 'string', 'length' => 32), 'name' => array('type' => 'string', 'length' => 128), 'created_at' => array('type' => 'timestamp', 'length' => 25), 'updated_at' => array('type' => 'timestamp', 'length' => 25), 'creator_user_id' => array('type' => 'integer', 'length' => 2147483647), 'updator_user_id' => array('type' => 'integer', 'length' => 2147483647)), array('indexes' => array(), 'primary' => array(0 => 'id')));
    $this->createTable('ull_ventory_item_type', array('id' => array('type' => 'integer', 'length' => 20, 'autoincrement' => true, 'primary' => true), 'namespace' => array('type' => 'string', 'length' => 32), 'slug' => array('type' => 'string', 'length' => 128), 'has_software' => array('type' => 'boolean', 'length' => 25), 'created_at' => array('type' => 'timestamp', 'length' => 25), 'updated_at' => array('type' => 'timestamp', 'length' => 25), 'creator_user_id' => array('type' => 'integer', 'length' => 2147483647), 'updator_user_id' => array('type' => 'integer', 'length' => 2147483647)), array('indexes' => array(), 'primary' => array(0 => 'id')));
    $this->createTable('ull_ventory_item_type_translation', array('id' => array('type' => 'integer', 'length' => 20, 'primary' => true), 'name' => array('type' => 'string', 'notnull' => true, 'length' => 128), 'lang' => array('fixed' => true, 'primary' => true, 'type' => 'string', 'length' => 2)), array('indexes' => array(), 'primary' => array(0 => 'id', 1 => 'lang')));
    $this->createTable('ull_ventory_item_taking', array('id' => array('type' => 'integer', 'length' => 20, 'autoincrement' => true, 'primary' => true), 'namespace' => array('type' => 'string', 'length' => 32), 'ull_ventory_item_id' => array('type' => 'integer', 'notnull' => true, 'length' => 2147483647), 'ull_ventory_taking_id' => array('type' => 'integer', 'notnull' => true, 'length' => 2147483647), 'created_at' => array('type' => 'timestamp', 'length' => 25), 'updated_at' => array('type' => 'timestamp', 'length' => 25), 'creator_user_id' => array('type' => 'integer', 'length' => 2147483647), 'updator_user_id' => array('type' => 'integer', 'length' => 2147483647)), array('indexes' => array(), 'primary' => array(0 => 'id')));
    $this->createTable('ull_ventory_item_attribute_preset', array('id' => array('type' => 'integer', 'length' => 20, 'autoincrement' => true, 'primary' => true), 'namespace' => array('type' => 'string', 'length' => 32), 'ull_ventory_item_model_id' => array('type' => 'integer', 'notnull' => true, 'length' => 2147483647), 'ull_ventory_item_type_attribute_id' => array('type' => 'integer', 'notnull' => true, 'length' => 2147483647), 'value' => array('type' => 'string', 'length' => 4000), 'created_at' => array('type' => 'timestamp', 'length' => 25), 'updated_at' => array('type' => 'timestamp', 'length' => 25), 'creator_user_id' => array('type' => 'integer', 'length' => 2147483647), 'updator_user_id' => array('type' => 'integer', 'length' => 2147483647)), array('indexes' => array(), 'primary' => array(0 => 'id')));
    $this->createTable('ull_ventory_item_manufacturer', array('id' => array('type' => 'integer', 'length' => 20, 'autoincrement' => true, 'primary' => true), 'namespace' => array('type' => 'string', 'length' => 32), 'name' => array('type' => 'string', 'notnull' => true, 'length' => 128), 'created_at' => array('type' => 'timestamp', 'length' => 25), 'updated_at' => array('type' => 'timestamp', 'length' => 25), 'creator_user_id' => array('type' => 'integer', 'length' => 2147483647), 'updator_user_id' => array('type' => 'integer', 'length' => 2147483647)), array('indexes' => array(), 'primary' => array(0 => 'id')));
    $this->createTable('ull_ventory_item_attribute_translation', array('id' => array('type' => 'integer', 'length' => 20, 'primary' => true), 'name' => array('type' => 'string', 'notnull' => true, 'length' => 128), 'help' => array('type' => 'string', 'length' => 4000), 'lang' => array('fixed' => true, 'primary' => true, 'type' => 'string', 'length' => 2)), array('indexes' => array(), 'primary' => array(0 => 'id', 1 => 'lang')));
    $this->createTable('ull_ventory_status_dummy_user_version', array('id' => array('type' => 'integer', 'length' => 20, 'primary' => true), 'namespace' => array('type' => 'string', 'length' => 32), 'first_name' => array('type' => 'string', 'length' => 64), 'last_name' => array('type' => 'string', 'length' => 64), 'display_name' => array('type' => 'string', 'length' => 64), 'username' => array('type' => 'string', 'length' => 64), 'email' => array('type' => 'string', 'length' => 64), 'password' => array('type' => 'string', 'length' => 40), 'sex' => array('type' => 'enum', 'values' =>  array( 0 => NULL,  1 => 'm',  2 => 'f', ), 'length' => NULL), 'entry_date' => array('type' => 'date', 'length' => 25), 'deactivation_date' => array('type' => 'date', 'length' => 25), 'separation_date' => array('type' => 'date', 'length' => 25), 'ull_employment_type_id' => array('type' => 'integer', 'length' => 2147483647), 'ull_job_title_id' => array('type' => 'integer', 'length' => 2147483647), 'ull_company_id' => array('type' => 'integer', 'length' => 2147483647), 'ull_department_id' => array('type' => 'integer', 'length' => 2147483647), 'ull_location_id' => array('type' => 'integer', 'length' => 2147483647), 'superior_ull_user_id' => array('type' => 'integer', 'length' => 2147483647), 'phone_extension' => array('type' => 'integer', 'length' => 20), 'is_show_extension_in_phonebook' => array('type' => 'boolean', 'length' => 25), 'fax_extension' => array('type' => 'integer', 'length' => 20), 'is_show_fax_extension_in_phonebook' => array('type' => 'boolean', 'length' => 25), 'comment' => array('type' => 'string', 'length' => 4000), 'ull_user_status_id' => array('type' => 'integer', 'notnull' => true, 'default' => '1', 'length' => 2147483647), 'is_virtual_group' => array('type' => 'boolean', 'default' => 0, 'length' => 25), 'type' => array('type' => 'string', 'length' => 255), 'created_at' => array('type' => 'timestamp', 'length' => 25), 'updated_at' => array('type' => 'timestamp', 'length' => 25), 'creator_user_id' => array('type' => 'integer', 'length' => 2147483647), 'updator_user_id' => array('type' => 'integer', 'length' => 2147483647), 'version' => array('primary' => true, 'type' => 'integer', 'length' => 8), 'reference_version' => array('type' => 'integer', 'length' => 8), 'scheduled_update_date' => array('type' => 'date', 'length' => 2147483647), 'done_at' => array('type' => 'timestamp', 'length' => 2147483647)), array('indexes' => array(), 'primary' => array(0 => 'id', 1 => 'version')));
    $this->createTable('ull_ventory_status_dummy_user_translation', array('id' => array('type' => 'integer', 'length' => 20, 'primary' => true), 'display_name' => array('type' => 'string', 'length' => 64), 'lang' => array('fixed' => true, 'primary' => true, 'type' => 'string', 'length' => 2)), array('indexes' => array(), 'primary' => array(0 => 'id', 1 => 'lang')));
    $this->createTable('ull_ventory_origin_dummy_user_version', array('id' => array('type' => 'integer', 'length' => 20, 'primary' => true), 'namespace' => array('type' => 'string', 'length' => 32), 'first_name' => array('type' => 'string', 'length' => 64), 'last_name' => array('type' => 'string', 'length' => 64), 'display_name' => array('type' => 'string', 'length' => 64), 'username' => array('type' => 'string', 'length' => 64), 'email' => array('type' => 'string', 'length' => 64), 'password' => array('type' => 'string', 'length' => 40), 'sex' => array('type' => 'enum', 'values' =>  array( 0 => NULL,  1 => 'm',  2 => 'f', ), 'length' => NULL), 'entry_date' => array('type' => 'date', 'length' => 25), 'deactivation_date' => array('type' => 'date', 'length' => 25), 'separation_date' => array('type' => 'date', 'length' => 25), 'ull_employment_type_id' => array('type' => 'integer', 'length' => 2147483647), 'ull_job_title_id' => array('type' => 'integer', 'length' => 2147483647), 'ull_company_id' => array('type' => 'integer', 'length' => 2147483647), 'ull_department_id' => array('type' => 'integer', 'length' => 2147483647), 'ull_location_id' => array('type' => 'integer', 'length' => 2147483647), 'superior_ull_user_id' => array('type' => 'integer', 'length' => 2147483647), 'phone_extension' => array('type' => 'integer', 'length' => 20), 'is_show_extension_in_phonebook' => array('type' => 'boolean', 'length' => 25), 'fax_extension' => array('type' => 'integer', 'length' => 20), 'is_show_fax_extension_in_phonebook' => array('type' => 'boolean', 'length' => 25), 'comment' => array('type' => 'string', 'length' => 4000), 'ull_user_status_id' => array('type' => 'integer', 'notnull' => true, 'default' => '1', 'length' => 2147483647), 'is_virtual_group' => array('type' => 'boolean', 'default' => 0, 'length' => 25), 'type' => array('type' => 'string', 'length' => 255), 'created_at' => array('type' => 'timestamp', 'length' => 25), 'updated_at' => array('type' => 'timestamp', 'length' => 25), 'creator_user_id' => array('type' => 'integer', 'length' => 2147483647), 'updator_user_id' => array('type' => 'integer', 'length' => 2147483647), 'version' => array('primary' => true, 'type' => 'integer', 'length' => 8), 'reference_version' => array('type' => 'integer', 'length' => 8), 'scheduled_update_date' => array('type' => 'date', 'length' => 2147483647), 'done_at' => array('type' => 'timestamp', 'length' => 2147483647)), array('indexes' => array(), 'primary' => array(0 => 'id', 1 => 'version')));
    $this->createTable('ull_ventory_origin_dummy_user_translation', array('id' => array('type' => 'integer', 'length' => 20, 'primary' => true), 'display_name' => array('type' => 'string', 'length' => 64), 'lang' => array('fixed' => true, 'primary' => true, 'type' => 'string', 'length' => 2)), array('indexes' => array(), 'primary' => array(0 => 'id', 1 => 'lang')));
    $this->createTable('ull_ventory_software', array('id' => array('type' => 'integer', 'length' => 20, 'autoincrement' => true, 'primary' => true), 'namespace' => array('type' => 'string', 'length' => 32), 'name' => array('type' => 'string', 'length' => 128), 'created_at' => array('type' => 'timestamp', 'length' => 25), 'updated_at' => array('type' => 'timestamp', 'length' => 25), 'creator_user_id' => array('type' => 'integer', 'length' => 2147483647), 'updator_user_id' => array('type' => 'integer', 'length' => 2147483647)), array('indexes' => array(), 'primary' => array(0 => 'id')));
    $this->createTable('ull_ventory_item_software', array('id' => array('type' => 'integer', 'length' => 20, 'autoincrement' => true, 'primary' => true), 'namespace' => array('type' => 'string', 'length' => 32), 'ull_ventory_item_id' => array('type' => 'integer', 'notnull' => true, 'length' => 2147483647), 'ull_ventory_software_id' => array('type' => 'integer', 'notnull' => true, 'length' => 2147483647), 'ull_ventory_software_license_id' => array('type' => 'integer', 'length' => 2147483647), 'comment' => array('type' => 'string', 'length' => 4000), 'created_at' => array('type' => 'timestamp', 'length' => 25), 'updated_at' => array('type' => 'timestamp', 'length' => 25), 'creator_user_id' => array('type' => 'integer', 'length' => 2147483647), 'updator_user_id' => array('type' => 'integer', 'length' => 2147483647)), array('indexes' => array(), 'primary' => array(0 => 'id')));
    $this->createTable('ull_ventory_software_license', array('id' => array('type' => 'integer', 'length' => 20, 'autoincrement' => true, 'primary' => true), 'namespace' => array('type' => 'string', 'length' => 32), 'ull_ventory_software_id' => array('type' => 'integer', 'notnull' => true, 'length' => 2147483647), 'license_key' => array('type' => 'string', 'length' => 128), 'quantity' => array('type' => 'integer', 'length' => 2147483647), 'created_at' => array('type' => 'timestamp', 'length' => 25), 'updated_at' => array('type' => 'timestamp', 'length' => 25), 'creator_user_id' => array('type' => 'integer', 'length' => 2147483647), 'updator_user_id' => array('type' => 'integer', 'length' => 2147483647)), array('indexes' => array(), 'primary' => array(0 => 'id')));

    $this->createForeignKey('ull_ventory_item_memory', array('local' => 'creator_user_id', 'foreign' => 'id', 'foreignTable' => 'ull_entity', 'onUpdate' => NULL, 'onDelete' => NULL, 'name' => 'ull_ventory_item_memory_creator_user_id'));
    $this->createForeignKey('ull_ventory_item_memory', array('local' => 'updator_user_id', 'foreign' => 'id', 'foreignTable' => 'ull_entity', 'onUpdate' => NULL, 'onDelete' => NULL, 'name' => 'ull_ventory_item_memory_updator_user_id'));
    $this->createForeignKey('ull_ventory_item_memory', array('local' => 'ull_ventory_item_id', 'foreign' => 'id', 'foreignTable' => 'ull_ventory_item', 'onUpdate' => NULL, 'onDelete' => NULL, 'name' => 'ull_ventory_item_memory_ull_ventory_item_id'));
    $this->createForeignKey('ull_ventory_item_memory', array('local' => 'source_ull_entity_id', 'foreign' => 'id', 'foreignTable' => 'ull_entity', 'onUpdate' => NULL, 'onDelete' => NULL, 'name' => 'ull_ventory_item_memory_source_ull_entity_id'));
    $this->createForeignKey('ull_ventory_item_memory', array('local' => 'target_ull_entity_id', 'foreign' => 'id', 'foreignTable' => 'ull_entity', 'onUpdate' => NULL, 'onDelete' => NULL, 'name' => 'ull_ventory_item_memory_target_ull_entity_id'));
    $this->createForeignKey('ull_ventory_item', array('local' => 'creator_user_id', 'foreign' => 'id', 'foreignTable' => 'ull_entity', 'onUpdate' => NULL, 'onDelete' => NULL, 'name' => 'ull_ventory_item_creator_user_id'));
    $this->createForeignKey('ull_ventory_item', array('local' => 'updator_user_id', 'foreign' => 'id', 'foreignTable' => 'ull_entity', 'onUpdate' => NULL, 'onDelete' => NULL, 'name' => 'ull_ventory_item_updator_user_id'));
    $this->createForeignKey('ull_ventory_item', array('local' => 'ull_ventory_item_model_id', 'foreign' => 'id', 'foreignTable' => 'ull_ventory_item_model', 'onUpdate' => NULL, 'onDelete' => NULL, 'name' => 'ull_ventory_item_ull_ventory_item_model_id'));
    $this->createForeignKey('ull_ventory_item', array('local' => 'ull_entity_id', 'foreign' => 'id', 'foreignTable' => 'ull_entity', 'onUpdate' => NULL, 'onDelete' => NULL, 'name' => 'ull_ventory_item_ull_entity_id'));
    $this->createForeignKey('ull_ventory_item_model', array('local' => 'creator_user_id', 'foreign' => 'id', 'foreignTable' => 'ull_entity', 'onUpdate' => NULL, 'onDelete' => NULL, 'name' => 'ull_ventory_item_model_creator_user_id'));
    $this->createForeignKey('ull_ventory_item_model', array('local' => 'updator_user_id', 'foreign' => 'id', 'foreignTable' => 'ull_entity', 'onUpdate' => NULL, 'onDelete' => NULL, 'name' => 'ull_ventory_item_model_updator_user_id'));
    $this->createForeignKey('ull_ventory_item_model', array('local' => 'ull_ventory_item_manufacturer_id', 'foreign' => 'id', 'foreignTable' => 'ull_ventory_item_manufacturer', 'onUpdate' => NULL, 'onDelete' => NULL, 'name' => 'ull_ventory_item_model_ull_ventory_item_manufacturer_id'));
    $this->createForeignKey('ull_ventory_item_model', array('local' => 'ull_ventory_item_type_id', 'foreign' => 'id', 'foreignTable' => 'ull_ventory_item_type', 'onUpdate' => NULL, 'onDelete' => NULL, 'name' => 'ull_ventory_item_model_ull_ventory_item_type_id'));
    $this->createForeignKey('ull_ventory_item_type_attribute', array('local' => 'creator_user_id', 'foreign' => 'id', 'foreignTable' => 'ull_entity', 'onUpdate' => NULL, 'onDelete' => NULL, 'name' => 'ull_ventory_item_type_attribute_creator_user_id'));
    $this->createForeignKey('ull_ventory_item_type_attribute', array('local' => 'updator_user_id', 'foreign' => 'id', 'foreignTable' => 'ull_entity', 'onUpdate' => NULL, 'onDelete' => NULL, 'name' => 'ull_ventory_item_type_attribute_updator_user_id'));
    $this->createForeignKey('ull_ventory_item_type_attribute', array('local' => 'ull_ventory_item_type_id', 'foreign' => 'id', 'foreignTable' => 'ull_ventory_item_type', 'onUpdate' => NULL, 'onDelete' => NULL, 'name' => 'ull_ventory_item_type_attribute_ull_ventory_item_type_id'));
    $this->createForeignKey('ull_ventory_item_type_attribute', array('local' => 'ull_ventory_item_attribute_id', 'foreign' => 'id', 'foreignTable' => 'ull_ventory_item_attribute', 'onUpdate' => NULL, 'onDelete' => NULL, 'name' => 'ull_ventory_item_type_attribute_ull_ventory_item_attribute_id'));
    $this->createForeignKey('ull_ventory_item_attribute', array('local' => 'creator_user_id', 'foreign' => 'id', 'foreignTable' => 'ull_entity', 'onUpdate' => NULL, 'onDelete' => NULL, 'name' => 'ull_ventory_item_attribute_creator_user_id'));
    $this->createForeignKey('ull_ventory_item_attribute', array('local' => 'updator_user_id', 'foreign' => 'id', 'foreignTable' => 'ull_entity', 'onUpdate' => NULL, 'onDelete' => NULL, 'name' => 'ull_ventory_item_attribute_updator_user_id'));
    $this->createForeignKey('ull_ventory_item_attribute', array('local' => 'ull_column_type_id', 'foreign' => 'id', 'foreignTable' => 'ull_column_type', 'onUpdate' => NULL, 'onDelete' => NULL, 'name' => 'ull_ventory_item_attribute_ull_column_type_id'));
    $this->createForeignKey('ull_ventory_item_attribute_value', array('local' => 'creator_user_id', 'foreign' => 'id', 'foreignTable' => 'ull_entity', 'onUpdate' => NULL, 'onDelete' => NULL, 'name' => 'ull_ventory_item_attribute_value_creator_user_id'));
    $this->createForeignKey('ull_ventory_item_attribute_value', array('local' => 'updator_user_id', 'foreign' => 'id', 'foreignTable' => 'ull_entity', 'onUpdate' => NULL, 'onDelete' => NULL, 'name' => 'ull_ventory_item_attribute_value_updator_user_id'));
    $this->createForeignKey('ull_ventory_item_attribute_value', array('local' => 'ull_ventory_item_id', 'foreign' => 'id', 'foreignTable' => 'ull_ventory_item', 'onUpdate' => NULL, 'onDelete' => NULL, 'name' => 'ull_ventory_item_attribute_value_ull_ventory_item_id'));
    $this->createForeignKey('ull_ventory_item_attribute_value', array('local' => 'ull_ventory_item_type_attribute_id', 'foreign' => 'id', 'foreignTable' => 'ull_ventory_item_type_attribute', 'onUpdate' => NULL, 'onDelete' => NULL, 'name' => 'ull_ventory_item_attribute_value_item_type_attribute_id'));
    $this->createForeignKey('ull_ventory_taking', array('local' => 'creator_user_id', 'foreign' => 'id', 'foreignTable' => 'ull_entity', 'onUpdate' => NULL, 'onDelete' => NULL, 'name' => 'ull_ventory_taking_creator_user_id'));
    $this->createForeignKey('ull_ventory_taking', array('local' => 'updator_user_id', 'foreign' => 'id', 'foreignTable' => 'ull_entity', 'onUpdate' => NULL, 'onDelete' => NULL, 'name' => 'ull_ventory_taking_updator_user_id'));
    $this->createForeignKey('ull_ventory_item_type', array('local' => 'creator_user_id', 'foreign' => 'id', 'foreignTable' => 'ull_entity', 'onUpdate' => NULL, 'onDelete' => NULL, 'name' => 'ull_ventory_item_type_creator_user_id'));
    $this->createForeignKey('ull_ventory_item_type', array('local' => 'updator_user_id', 'foreign' => 'id', 'foreignTable' => 'ull_entity', 'onUpdate' => NULL, 'onDelete' => NULL, 'name' => 'ull_ventory_item_type_updator_user_id'));
    $this->createForeignKey('ull_ventory_item_taking', array('local' => 'creator_user_id', 'foreign' => 'id', 'foreignTable' => 'ull_entity', 'onUpdate' => NULL, 'onDelete' => NULL, 'name' => 'ull_ventory_item_taking_creator_user_id'));
    $this->createForeignKey('ull_ventory_item_taking', array('local' => 'updator_user_id', 'foreign' => 'id', 'foreignTable' => 'ull_entity', 'onUpdate' => NULL, 'onDelete' => NULL, 'name' => 'ull_ventory_item_taking_updator_user_id'));
    $this->createForeignKey('ull_ventory_item_taking', array('local' => 'ull_ventory_item_id', 'foreign' => 'id', 'foreignTable' => 'ull_ventory_item', 'onUpdate' => NULL, 'onDelete' => NULL, 'name' => 'ull_ventory_item_taking_ull_ventory_item_id'));
    $this->createForeignKey('ull_ventory_item_taking', array('local' => 'ull_ventory_taking_id', 'foreign' => 'id', 'foreignTable' => 'ull_ventory_taking', 'onUpdate' => NULL, 'onDelete' => NULL, 'name' => 'ull_ventory_item_taking_ull_ventory_taking_id'));
    $this->createForeignKey('ull_ventory_item_attribute_preset', array('local' => 'creator_user_id', 'foreign' => 'id', 'foreignTable' => 'ull_entity', 'onUpdate' => NULL, 'onDelete' => NULL, 'name' => 'ull_ventory_item_attribute_preset_creator_user_id'));
    $this->createForeignKey('ull_ventory_item_attribute_preset', array('local' => 'updator_user_id', 'foreign' => 'id', 'foreignTable' => 'ull_entity', 'onUpdate' => NULL, 'onDelete' => NULL, 'name' => 'ull_ventory_item_attribute_preset_updator_user_id'));
    $this->createForeignKey('ull_ventory_item_attribute_preset', array('local' => 'ull_ventory_item_model_id', 'foreign' => 'id', 'foreignTable' => 'ull_ventory_item_model', 'onUpdate' => NULL, 'onDelete' => NULL, 'name' => 'ull_ventory_item_attribute_preset_ull_ventory_item_model_id'));
    $this->createForeignKey('ull_ventory_item_attribute_preset', array('local' => 'ull_ventory_item_type_attribute_id', 'foreign' => 'id', 'foreignTable' => 'ull_ventory_item_type_attribute', 'onUpdate' => NULL, 'onDelete' => NULL, 'name' => 'ull_ventory_item_attribute_preset_item_type_attribute_id'));
    $this->createForeignKey('ull_ventory_item_manufacturer', array('local' => 'creator_user_id', 'foreign' => 'id', 'foreignTable' => 'ull_entity', 'onUpdate' => NULL, 'onDelete' => NULL, 'name' => 'ull_ventory_item_manufacturer_creator_user_id'));
    $this->createForeignKey('ull_ventory_item_manufacturer', array('local' => 'updator_user_id', 'foreign' => 'id', 'foreignTable' => 'ull_entity', 'onUpdate' => NULL, 'onDelete' => NULL, 'name' => 'ull_ventory_item_manufacturer_updator_user_id'));
    $this->createForeignKey('ull_ventory_item_attribute_translation', array('local' => 'id', 'foreign' => 'id', 'foreignTable' => 'ull_ventory_item_attribute', 'onUpdate' => 'CASCADE', 'onDelete' => 'CASCADE', 'name' => 'ull_ventory_item_attribute_translation_id'));
    $this->createForeignKey('ull_ventory_status_dummy_user_version', array('local' => 'id', 'foreign' => 'id', 'foreignTable' => 'ull_entity', 'onUpdate' => 'CASCADE', 'onDelete' => 'CASCADE', 'name' => 'ull_ventory_status_dummy_user_version_id'));
    $this->createForeignKey('ull_ventory_status_dummy_user_version', array('local' => 'updator_user_id', 'foreign' => 'id', 'foreignTable' => 'ull_entity', 'onUpdate' => NULL, 'onDelete' => NULL, 'name' => 'ull_ventory_status_dummy_user_version_updator_user_id'));
    $this->createForeignKey('ull_ventory_status_dummy_user_translation', array('local' => 'id', 'foreign' => 'id', 'foreignTable' => 'ull_entity', 'onUpdate' => 'CASCADE', 'onDelete' => 'CASCADE', 'name' => 'ull_ventory_status_dummy_user_translation_id'));
    $this->createForeignKey('ull_ventory_origin_dummy_user_version', array('local' => 'id', 'foreign' => 'id', 'foreignTable' => 'ull_entity', 'onUpdate' => 'CASCADE', 'onDelete' => 'CASCADE', 'name' => 'ull_ventory_origin_dummy_user_version_id'));
    $this->createForeignKey('ull_ventory_origin_dummy_user_version', array('local' => 'updator_user_id', 'foreign' => 'id', 'foreignTable' => 'ull_entity', 'onUpdate' => NULL, 'onDelete' => NULL, 'name' => 'ull_ventory_origin_dummy_user_version_updator_user_id'));
    $this->createForeignKey('ull_ventory_origin_dummy_user_translation', array('local' => 'id', 'foreign' => 'id', 'foreignTable' => 'ull_entity', 'onUpdate' => 'CASCADE', 'onDelete' => 'CASCADE', 'name' => 'ull_ventory_origin_dummy_user_translation_id'));
    $this->createForeignKey('ull_ventory_item_type_translation', array('local' => 'id', 'foreign' => 'id', 'foreignTable' => 'ull_ventory_item_type', 'onUpdate' => 'CASCADE', 'onDelete' => 'CASCADE', 'name' => 'ull_ventory_item_type_translation_id'));
  }

  public function down()
  {
    $this->dropTable('ull_ventory_item_software');
    $this->dropTable('ull_ventory_software_license');
    $this->dropTable('ull_ventory_software');
    $this->dropTable('ull_ventory_item_type_translation');
    $this->dropTable('ull_ventory_item_attribute_translation');
    $this->dropTable('ull_ventory_item_attribute_value');
    $this->dropTable('ull_ventory_item_taking');
    $this->dropTable('ull_ventory_taking');
    $this->dropTable('ull_ventory_item_memory');
    $this->dropTable('ull_ventory_item_attribute_preset');
    $this->dropTable('ull_ventory_item_type_attribute');
    $this->dropTable('ull_ventory_item_attribute');
    $this->dropTable('ull_ventory_item');
    $this->dropTable('ull_ventory_item_model');
    $this->dropTable('ull_ventory_item_type');
    $this->dropTable('ull_ventory_item_manufacturer');
    $this->dropTable('ull_ventory_status_dummy_user_version');
    $this->dropTable('ull_ventory_status_dummy_user_translation');
    $this->dropTable('ull_ventory_origin_dummy_user_version');
    $this->dropTable('ull_ventory_origin_dummy_user_translation');
  }
}