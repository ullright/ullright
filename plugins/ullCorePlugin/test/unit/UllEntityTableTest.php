<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/unit.php';

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);

$t = new sfDoctrineTestCase(10, new lime_output_color, $configuration);
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
    UllEntityTable::getSubordinateTree($admin, 9999, false)->toArray(),
    array(
      'data'      => $admin->id,
      'meta'      => array(
        'level'         => 1,
        'subordinates'  => array(
          array(
            'data'      => $testUser2->id,
            'meta'      => array(
              'level'    => 2,
              'is_first' => true,
            ),         
            'subnodes'  => array(),
          ),      
          array(
            'data'      => $testUser3->id,
            'meta'      => array(
              'level'    => 2,
              'is_last' => true,
            ),
            'subnodes'  => array(),
          ),    
        ),
      ),
      'subnodes'  => array(
        array(
          'data'      => $testUser->id,
          'meta'      => array(
            'level'         => 2,
            'subordinates'  => array(
              array(
                'data'      => $poorGuy->id,
                'meta'      => array(
                  'level'     => 2,
                  'is_first'  => true, 
                  'is_last'   => true,
                ),
                'subnodes'  => array(),
              ),   
            ),
          'is_first'      => true,
          'is_last'       => true,                    
          ), 
          'subnodes'  => array(
          ),
        ),                        
      ),
    ),
    'Returns the correct tree'
  );  
  $t->is(
    UllEntityTable::getSubordinateTree($admin, 2, false)->toArray(),
    array(
      'data'      => $admin->id,
      'meta'      => array(
        'level'       => 1,
        'subordinates' => array(
          array(
            'data'      => $testUser2->id,
            'meta'      => array(
              'level'     => 2,
              'is_first'  => true,
            ),         
            'subnodes'  => array(),
          ),      
          array(
            'data'      => $testUser3->id,
            'meta'      => array(
              'level'     => 2,
              'is_last'   => true,
            ),
            'subnodes'  => array(),
          ),    
        ),
      ),
      'subnodes'  => array(
        array(
          'data'      => $testUser->id,
          'meta'      => array(
            'level'         => 2,
            'is_first'      => true,
            'is_last'       => true,
          ), 
          'subnodes'  => array(),
        ),                        
      ),
    ),
    'Returns the correct tree for depth = 2'
  );   
  $t->is(
    UllEntityTable::getSubordinateTree($admin,1, false)->toArray(),
    array(
      'data'      => $admin->id,
      'meta'      => array(
        'level'     => 1,
      ),
      'subnodes'  => array(),
    ),
    'Returns the correct tree for depth = 1'
  );   
  
  
  $poorGuy->is_show_in_orgchart = false;
  $poorGuy->save();
  $t->is(
    UllEntityTable::getSubordinateTree($testUser, 9999, false)->toArray(),
    array(
      'data'      => $testUser->id,
      'meta'      => array(
        'level'     => 1,
      ),
      'subnodes'  => array(),
    ),
    'Returns the correct tree for test_user with a subordinate which should not be shown in the orgchart'
  );   
