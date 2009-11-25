<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);

$t = new sfDoctrineTestCase(7, new lime_output_color, $configuration);
$path = sfConfig::get('sf_root_dir') . '/plugins/ullCorePlugin/data/fixtures/';
$t->setFixturesPath($path);


$t->begin('has()');

  $admin = Doctrine::getTable('UllUser')->findOneByUsername('admin');
  $testUser = Doctrine::getTable('UllUser')->findOneByUsername('test_user');
  $group = Doctrine::getTable('UllGroup')->findOneByDisplayName('TestGroup');

  // "login as test_user"
  sfContext::getInstance()->getUser()->setAttribute('user_id', $testUser->id);
  
  try
  {
    UllEntityTable::has($testUser, 'foobar');
    $t->fail('doesn\'t throw an exception for an invalid UllUser');
  }
  catch (Exception $e)
  {
    $t->pass('throws an exception for an invalid UllUser');
  }
  
  $t->ok(UllEntityTable::has($testUser), 'returns true for a given UllUser when logged in as this user');
  $t->ok(UllEntityTable::has($group), 'returns true for a given UllGroup when the logged in user is member of this group');
  
  $t->ok(UllEntityTable::has($group, $testUser), 'returns true for a given UllGroup and a given UllUser who is member of this group');
  
  
$t->diag('findByDisplayName()');

  $t->is(UllEntityTable::findByDisplayName('Master Admin')->id, 1, 'returns the correct ID');  
  
  
$t->diag('findIdByDisplayName()');

  $t->is(UllEntityTable::findIdByDisplayName('Master Admin'), 1, 'returns the correct ID');
  

$t->diag('getSubordinateTree()');
  $testUser2 = new UllUser;
  $testUser2->first_name = 'Timmy';
  $testUser2->last_name = 'Secondtest';
  $testUser2->username = 'test_user2';
  $testUser2->Superior = $admin;
  $testUser2->save();
  
  $testUser3 = new UllUser;
  $testUser3->first_name = 'Toby';
  $testUser3->last_name = 'Thirdtest';
  $testUser3->username = 'test_user3';
  $testUser3->Superior = $admin;
  $testUser3->save();  
  
  $poorGuy = new UllUser;
  $poorGuy->first_name = 'Poor';
  $poorGuy->last_name = 'Guy';
  $poorGuy->username = 'poor_guy';
  $poorGuy->Superior = $testUser;
  $poorGuy->save();


  $t->is(
    UllEntityTable::getSubordinateTree($admin, false),
    array(
      $admin->id => array(
        'data'      => $admin->id,
        'meta'      => array(),
        'children'  => array(
         $testUser2->id => array(
            'data'      => $testUser2->id,
            'meta'      => array(
              'leftmost' => true,
            ),         
            'children'  => null,
          ),      
         $testUser3->id => array(
            'data'      => $testUser3->id,
            'meta'      => array(),         
            'children'  => null,
          ),
          $testUser->id => array(
            'data'      => $testUser->id,
            'meta'      => array(
              'rightmost' => true,
            ), 
            'children'  => array(
              $poorGuy->id => array(
                'data'      => $poorGuy->id,
                'meta'      => array(),
                'children'  => null,
              ),
            ),
          ),                        
        ),
      ),
    ),
    'Returns the correct tree'
  );  