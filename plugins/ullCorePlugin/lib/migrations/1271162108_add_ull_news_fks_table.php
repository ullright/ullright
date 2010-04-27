<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class AddUllNewsFksTable extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->createForeignKey('ull_news', 'ull_news_creator_user_id_ull_entity_id', array(
             'name' => 'ull_news_creator_user_id_ull_entity_id',
             'local' => 'creator_user_id',
             'foreign' => 'id',
             'foreignTable' => 'ull_entity',
             ));
        $this->createForeignKey('ull_news', 'ull_news_updator_user_id_ull_entity_id', array(
             'name' => 'ull_news_updator_user_id_ull_entity_id',
             'local' => 'updator_user_id',
             'foreign' => 'id',
             'foreignTable' => 'ull_entity',
             ));
        $this->createForeignKey('ull_news_translation', 'ull_news_translation_id_ull_news_id', array(
             'name' => 'ull_news_translation_id_ull_news_id',
             'local' => 'id',
             'foreign' => 'id',
             'foreignTable' => 'ull_news',
             'onUpdate' => 'CASCADE',
             'onDelete' => 'CASCADE',
             ));
    }

    public function down()
    {
        $this->dropForeignKey('ull_news', 'ull_news_creator_user_id_ull_entity_id');
        $this->dropForeignKey('ull_news', 'ull_news_updator_user_id_ull_entity_id');
        $this->dropForeignKey('ull_news_translation', 'ull_news_translation_id_ull_news_id');
    }
}