<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Addullcmsitemtranslation extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->createTable('ull_cms_item_translation', array(
             'id' => 
             array(
              'type' => 'integer',
              'length' => 8,
              'primary' => true,
             ),
             'name' => 
             array(
              'type' => 'string',
              'notnull' => true,
              'length' => 128,
             ),
             'full_path' => 
             array(
              'type' => 'string',
              'notnull' => true,
              'length' => 255,
             ),
             'title' => 
             array(
              'type' => 'string',
              'notnull' => true,
              'length' => 255,
             ),
             'body' => 
             array(
              'type' => 'clob',
              'notnull' => true,
              'length' => NULL,
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
    }

    public function down()
    {
        $this->dropTable('ull_cms_item_translation');
    }
}