<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseUllWikiAccessLevel extends UllRecord
{
    public function setTableDefinition()
    {
        parent::setTableDefinition();
        $this->setTableName('ull_wiki_access_level');
        $this->hasColumn('slug', 'string', 64, array('type' => 'string', 'length' => '64'));
        $this->hasColumn('name', 'string', 128, array('type' => 'string', 'length' => '128'));
    }

    public function setUp()
    {
        parent::setUp();
    $this->hasMany('UllWiki', array('local' => 'id',
                                        'foreign' => 'ull_wiki_access_level_id'));

        $this->hasMany('UllWikiAccessLevelAccess', array('local' => 'id',
                                                         'foreign' => 'model_id'));

        $i18n0 = new Doctrine_Template_I18n(array('fields' => array(0 => 'name')));
        $this->actAs($i18n0);
    }
}