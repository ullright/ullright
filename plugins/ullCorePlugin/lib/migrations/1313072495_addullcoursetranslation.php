<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Addullcoursetranslation extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->createTable('ull_course_translation', array(
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
              'length' => 255,
             ),
             'description' => 
             array(
              'type' => 'string',
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
        $this->dropTable('ull_course_translation');
    }
}