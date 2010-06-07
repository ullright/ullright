<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);

$t = new sfDoctrineTestCase(26, new lime_output_color, $configuration);
$path = sfConfig::get('sf_root_dir') . '/plugins/ullCorePlugin/data/fixtures/';
$t->setFixturesPath($path);


$t->begin('hasGroup()');

  $groupMasterAdminsId = UllGroupTable::findIdByDisplayName('MasterAdmins'); 
  $groupTestId = UllGroupTable::findIdByDisplayName('TestGroup');


  $t->is(
        UllUserTable::hasGroup('MasterAdmins', '1')
      , true
      , 'returns true for a given group and user_id (as string ;-))'
      );      
  $t->is(
        UllUserTable::hasGroup('MasterAdmins', $groupMasterAdminsId)
      , false
      , 'returns false for a given group and user_id'
      );
  $t->is(
        UllUserTable::hasGroup($groupMasterAdminsId, 1)
      , true
      , 'returns true for a given group_id and user_id'
      );
  $t->is(
        UllUserTable::hasGroup(2, $groupMasterAdminsId)
      , false
      , 'returns false for a given group_id and user_id'
      );
  $t->is(
        UllUserTable::hasGroup(array($groupMasterAdminsId, $groupTestId), 1)
      , true
      , 'returns true for a given array of group_ids and user_id'
      );
  $t->is(
        UllUserTable::hasGroup(array('Masteradmins', 'Helpdesk'), 1)
      , true
      , 'returns true for a given array of group names and user_id'
      );
  $t->is(
        UllUserTable::hasGroup(array('Masteradmins', 'FooBarGroup'), 3)
      , false
      , 'returns false for a given array of invalid group names and user_id'
      );                            
  $t->is(
        UllUserTable::hasGroup('Masteradmins')
      , false
      , 'returns false for a given group and using the sessions user_id and not logged in'
      );      
  sfContext::getInstance()->getUser()->setAttribute('user_id', 1);
  $t->is(
        UllUserTable::hasGroup('XYZ')
      , true
      , 'MasterAdmins are members of all groups'
      );
  $t->logout();
      
      
$t->diag('hasPermission()');
  $t->is(
        UllUserTable::hasPermission('invalidPermission')
      , false
      , 'Returns false if not logged in and for an invalid permission'
      );
  $t->is(
        UllUserTable::hasPermission('testPermission', 2)
      , true
      , 'Returns true for a given permission and user_id'
      );
  $t->is(
        UllUserTable::hasPermission('invalidPermission', 2)
      , false
      , 'Returns false for an invalid permission'
      );
  $t->is(
        UllUserTable::hasPermission('invalidPermission', 1)
      , true
      , 'Returns true for any permission for MasterAdmin'
      );

      
  $permission = new UllPermission;
  $permission->slug = 'ull_foo_show';
  $permission->save();
  $groupPermission = new UllGroupPermission;
  $groupPermission->UllPermission = $permission;
  $groupPermission->UllGroup = Doctrine::getTable('UllGroup')->findOneByDisplayName('Everyone');
  $groupPermission->save();
  
  $t->is(
    UllUserTable::hasPermission('ull_foo_show'),
    true,
    'Access allowed. Not logged in, but permission ull_foo_show is accessible by everyone'
  );
  
  $t->loginAs('test_user');
  $t->is(
    UllUserTable::hasPermission('ull_foo_show'),
    true,
    'Access allowed. "Everyone" includes logged in users'
  );  
  $t->logout();
  
  $groupPermission->UllGroup = Doctrine::getTable('UllGroup')->findOneByDisplayName('Logged in users');
  $groupPermission->save();
  
  $t->is(
    UllUserTable::hasPermission('ull_foo_show'),
    false,
    'Access not allowed. Not logged in and permission ull_foo_show is not accessible for everyone'
  );
  
  $t->loginAs('test_user');
  
  $t->is(
    UllUserTable::hasPermission('ull_foo_show'),
    true,
    'Access allowed. Logged in as unprivileged user, and permission ull_foo_show is accessible by logged in users'
  );  
  
  $t->logout();
  

$t->begin('findChoices()');
  $admin = Doctrine::getTable('UllUser')->findOneByUserName('admin');
  $test_user = Doctrine::getTable('UllUser')->findOneByUserName('test_user');
  $t->is(
      UllUserTable::findChoices(),
      array(
        $admin->id => array('name' => 'Admin Master'),
        $test_user->id => array('name' => 'User Test'),
      ),
      'returns the correct choices for UllUser'
  );
  
  
$t->diag('findUsernameById()');
  $userId = Doctrine::getTable('UllUser')->findOneByUserName('test_user')->id;
  $t->is(UllUserTable::findUsernameById(666), false, 'returns false for an invalid id');  
  $t->is(UllUserTable::findUsernameById($userId), 'test_user', 'returns the correct id');
  
$t->diag('findIdByUsername()');
  $t->is(UllUserTable::findIdByUsername('foobar'), false, 'returns false for an invalid username');  
  $t->is(UllUserTable::findIdByUsername('test_user'), 2, 'returns the correct username');

$t->diag('findLoggedInUser()');
  $t->is(UllUserTable::findLoggedInUser(), false, 'Returns false when nobody is logged in');
  $t->loginAs('test_user');
  $t->is(UllUserTable::findLoggedInUser()->username, 'test_user', 'Returns the correct user object when logged in');
  $t->logout();
  
$t->diag('findLoggedInUsername()');
  $t->is(UllUserTable::findLoggedInUsername(), false, 'Returns false when nobody is logged in');
  $t->loginAs('test_user');
  $t->is(UllUserTable::findLoggedInUsername(), 'test_user', 'Returns the correct user object when logged in');
  $t->logout();  