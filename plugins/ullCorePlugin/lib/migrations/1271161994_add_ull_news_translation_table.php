<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class AddUllNewsTranslationTable extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->createTable('ull_news_translation', array(
             'id' => 
             array(
              'type' => 'integer',
              'length' => 8,
              'primary' => true,
             ),
             'title' => 
             array(
              'type' => 'string',
              'notnull' => true,
              'length' => 255,
             ),
             'abstract' => 
             array(
              'type' => 'string',
              'notnull' => true,
              'length' => 4000,
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
        $this->dropTable('ull_news_translation');
    }
}