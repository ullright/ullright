<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class AddUllCmsFksTable extends Doctrine_Migration_Base
{
    public function up()
    {
      
      // Deactivated by KU 2010-07-02 because we now have the ullright:recreate-foreign-keys task
//        $this->createForeignKey('test_table', 'test_table_creator_user_id_ull_entity_id', array(
//             'name' => 'test_table_creator_user_id_ull_entity_id',
//             'local' => 'creator_user_id',
//             'foreign' => 'id',
//             'foreignTable' => 'ull_entity',
//             ));
//        $this->createForeignKey('test_table', 'test_table_updator_user_id_ull_entity_id', array(
//             'name' => 'test_table_updator_user_id_ull_entity_id',
//             'local' => 'updator_user_id',
//             'foreign' => 'id',
//             'foreignTable' => 'ull_entity',
//             ));
//        $this->createForeignKey('test_table', 'test_table_ull_user_id_ull_entity_id', array(
//             'name' => 'test_table_ull_user_id_ull_entity_id',
//             'local' => 'ull_user_id',
//             'foreign' => 'id',
//             'foreignTable' => 'ull_entity',
//             ));
//        $this->createForeignKey('ull_cms_item_translation', 'ull_cms_item_translation_id_ull_cms_item_id', array(
//             'name' => 'ull_cms_item_translation_id_ull_cms_item_id',
//             'local' => 'id',
//             'foreign' => 'id',
//             'foreignTable' => 'ull_cms_item',
//             'onUpdate' => 'CASCADE',
//             'onDelete' => 'CASCADE',
//             ));
    }

    public function down()
    {
//        $this->dropForeignKey('ull_cms_item', 'ull_cms_item_creator_user_id_ull_entity_id');
//        $this->dropForeignKey('ull_cms_item', 'ull_cms_item_updator_user_id_ull_entity_id');
//        $this->dropForeignKey('ull_cms_item', 'ull_cms_item_parent_ull_cms_item_id_ull_cms_item_id');
//        $this->dropForeignKey('ull_cms_item_translation', 'ull_cms_item_translation_id_ull_cms_item_id');
    }
}