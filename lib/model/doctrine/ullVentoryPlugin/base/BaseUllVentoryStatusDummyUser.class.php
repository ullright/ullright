<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseUllVentoryStatusDummyUser extends UllEntity
{
    public function setUp()
    {
        parent::setUp();
    $i18n0 = new Doctrine_Template_I18n(array(
             'fields' => 
             array(
              0 => 'display_name',
             ),
             ));
        $this->actAs($i18n0);
    }
}