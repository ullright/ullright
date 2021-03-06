<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class AddUllNewsTable extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->createTable('ull_news', array(
             'id' => 
             array(
              'type' => 'integer',
              'length' => 8,
              'autoincrement' => true,
              'primary' => true,
             ),
             'namespace' => 
             array(
              'type' => 'string',
              'length' => 32,
             ),
             'link_name' => 
             array(
              'type' => 'string',
              'length' => 255,
             ),
             'link_url' => 
             array(
              'type' => 'string',
              'length' => 255,
             ),
             'image_upload' => 
             array(
              'type' => 'string',
              'notnull' => true,
              'length' => 255,
             ),
             'activation_date' => 
             array(
              'type' => 'date',
              'notnull' => true,
              'length' => 25,
             ),
             'deactivation_date' => 
             array(
              'type' => 'date',
              'length' => 25,
             ),
             'created_at' => 
             array(
              'notnull' => true,
              'type' => 'timestamp',
              'length' => 25,
             ),
             'updated_at' => 
             array(
              'notnull' => true,
              'type' => 'timestamp',
              'length' => 25,
             ),
             'creator_user_id' => 
             array(
              'type' => 'integer',
              'length' => 8,
             ),
             'updator_user_id' => 
             array(
              'type' => 'integer',
              'length' => 8,
             ),
             'slug' => 
             array(
              'type' => 'string',
              'length' => 255,
             ),
             ), array(
             'indexes' => 
             array(
              'sluggable' => 
              array(
              'fields' => 
              array(
               0 => 'slug',
              ),
              'type' => 'unique',
              ),
             ),
             'primary' => 
             array(
              0 => 'id',
             ),
             ));
    }

    public function down()
    {
        $this->dropTable('ull_news');
    }
}