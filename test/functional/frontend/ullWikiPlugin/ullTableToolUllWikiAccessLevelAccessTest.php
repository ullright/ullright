<?php

include dirname(__FILE__) . '/../../../bootstrap/functional.php';

$selector = new ullDomGridSelector('table.list_table > tbody', 'tr', 'td', array(),
      array(
        'edit_delete',      
        'ull_group_id',
        'ull_privilege_id',
        'model_id'
      )
    );

$b = new ullTableToolTestBrowser(
	'UllWikiAccessLevelAccess', 
	'Access rights', 
	'Manage Access rights', 
  6, 
  $selector, 
  $configuration,
  array('order' => 'created_at', 'desc' => true)
);
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();

$createValues = array(
	'ull_group_id' => array(Doctrine::getTable('UllGroup')->findByDisplayName('Everyone')->id, 'Everyone'),
  'ull_privilege_id' => array(Doctrine::getTable('UllPrivilege')->findOneBySlug('read')->id, 'read'),
  'model_id' => array(Doctrine::getTable('UllWikiAccessLevel')->findOneBySlug('wiki_admins')->id, 'For wiki-admins')
);

$editValues = array(
  'ull_group_id' => array(Doctrine::getTable('UllGroup')->findByDisplayName('Logged in users')->id, 'Logged in users'),
  'ull_privilege_id' => array(Doctrine::getTable('UllPrivilege')->findOneBySlug('write')->id, 'write'),
  'model_id' => array(Doctrine::getTable('UllWikiAccessLevel')->findOneBySlug('public_readable')->id, 'Public readable')
);

$b->setCreateValues($createValues);
$b->setEditValues($editValues);
$b->executeTest();


