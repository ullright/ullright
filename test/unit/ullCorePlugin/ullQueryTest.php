<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase {}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);
sfLoader::loadHelpers('I18N');

$t = new myTestCase(24, new lime_output_color, $configuration);
$path = sfConfig::get('sf_root_dir') . '/plugins/ullCorePlugin/data/fixtures/';
$t->setFixturesPath($path);


$t->begin('__construct()');
  $q = new ullQuery('TestTable');

  
$t->begin('__construct() with INDEXBY | Also tests limit()');
  $q = new ullQuery('UllUserStatus', 'slug');
  $q->addSelect('is_absent');
  $q->limit(1);
  $t->is(
    $q->getSqlQuery(),
    "SELECT u.id AS u__id, u.slug AS u__slug, u.is_absent AS u__is_absent FROM ull_user_status u LIMIT 1",
    'Created the correct SQL query'
  );
  $t->is(
    $q->execute(array(), DOCTRINE::HYDRATE_ARRAY), 
    array('active' => array('id' => 1, 'slug' => 'active', 'is_absent' => false)), 
    'Returns an indexed by array'
  );
  
  
$t->diag('getBaseModel()');
  $t->is($q->getBaseModel(), 'TestTable', 'Returns the correct base model');


$t->diag('getDoctrineQuery()');
  $t->isa_ok($q->getDoctrineQuery(), 'ullDoctrineQuery', 'Returns a valid ullDoctrineQuery object');   
  $t->is($q->getDoctrineQuery()->getDqlPart('from'), array(0 => 'TestTable x'), 'Constructor sets the correct basemodel in the query');
  
  
$t->diag('relationStringToAlias()');
  $t->is(
    $q->relationStringToAlias('UllUser->UllUserStatus->Translation'),
    'x_ulluser_ulluserstatus_translation',
    'Returns the correct string'
  );
  $t->is(
    $q->relationStringToAlias('UllUser->UllUserStatus', false),
    'ulluser_ulluserstatus',
    'Returns the correct string with option prependBaseAlias = false'
  );    
  $t->is(
    $q->relationStringToAlias(''),
    'x',
    'Returns the correct string'
  );  

  
$t->diag('nestPlainArray()');
  $t->is(
    $q->nestPlainArray(array('UllUser', 'UllUserStatus', 'Translation')),
    array('UllUser' => array('UllUserStatus' => array('Translation' => array()))),
    'Creates the correct nested array'
  );

  
$t->diag('addRelations / getRelations()');
  $q->addRelations(array('UllUser', 'UllUserStatus', 'Translation'));
  $t->is(
    $q->getRelations(),
    array('UllUser' => array('UllUserStatus' => array('Translation' => array()))),
    'Returns the correct relations'
  );
  $q->addRelations(array('Creator'));
  $q->addRelations(array());
  $q->addRelations(array('UllUser', 'UllJobTitle'));
  $t->is(
    $q->getRelations(),
    array(
      'UllUser' => 
        array(
          'UllUserStatus' => 
            array(
              'Translation' => array()
            ),
          'UllJobTitle' => array()
        ),
      'Creator' => array()
    ),
    'Returns the correct relations'
  );  

  
$t->diag('addRelationsToQuery()');
  $q->addRelationsToQuery();
  $t->is(
    $q->getDoctrineQuery()->getDqlPart('from'), 
    array(
      0 => 'TestTable x',
      1 => 'x.UllUser x_ulluser',
      2 => 'x_ulluser.UllUserStatus x_ulluser_ulluserstatus',
      3 => 'x_ulluser_ulluserstatus.Translation x_ulluser_ulluserstatus_translation',
      4 => 'x_ulluser.UllJobTitle x_ulluser_ulljobtitle',
      5 => 'x.Creator x_creator',
    ),
    'Creates the correct from Parts');

    
$t->diag('addSelect()');    
  $q = new ullQuery('TestTable');
  $q->addSelect(array(
      'my_email',
      'my_string',
      'UllUser->username',
      'UllUser->UllEmploymentType->name',
      'Creator->username',
      'CONCAT(\'Write an email to \', my_email) as non_native_sql_column',
  ));
  $t->is(
    $q->getSqlQuery(),
    "SELECT t.id AS t__id, t.my_email AS t__my_email, t2.id AS t2__id, t2.lang AS t2__lang, t2.my_string AS t2__my_string, u.id AS u__id, u.username AS u__username, u.type AS u__type, u2.id AS u2__id, u3.id AS u3__id, u3.lang AS u3__lang, u3.name AS u3__name, u4.id AS u4__id, u4.username AS u4__username, u4.type AS u4__type, CONCAT('Write an email to ', t.my_email) AS t__0 FROM test_table t LEFT JOIN test_table_translation t2 ON t.id = t2.id LEFT JOIN ull_entity u ON t.ull_user_id = u.id AND u.type = 'user' LEFT JOIN ull_employment_type u2 ON u.ull_employment_type_id = u2.id LEFT JOIN ull_employment_type_translation u3 ON u2.id = u3.id LEFT JOIN ull_entity u4 ON t.creator_user_id = u4.id AND u4.type = 'user' WHERE (t2.lang = ? AND u3.lang = ?)",
    'Returns the correct query'
  );
  
  
$t->diag('addOrderBy()');
  $q->addOrderBy('my_string, UllUser->UllEmploymentType->name desc, UllUser->UllLocation->name');
  $t->is(
    $q->getSqlQuery(),
    "SELECT t.id AS t__id, t.my_email AS t__my_email, t2.id AS t2__id, t2.lang AS t2__lang, t2.my_string AS t2__my_string, u.id AS u__id, u.username AS u__username, u.type AS u__type, u2.id AS u2__id, u3.id AS u3__id, u3.lang AS u3__lang, u3.name AS u3__name, u4.id AS u4__id, u4.username AS u4__username, u4.type AS u4__type, CONCAT('Write an email to ', t.my_email) AS t__0 FROM test_table t LEFT JOIN test_table_translation t2 ON t.id = t2.id LEFT JOIN ull_entity u ON t.ull_user_id = u.id AND u.type = 'user' LEFT JOIN ull_employment_type u2 ON u.ull_employment_type_id = u2.id LEFT JOIN ull_employment_type_translation u3 ON u2.id = u3.id LEFT JOIN ull_entity u4 ON t.creator_user_id = u4.id AND u4.type = 'user' LEFT JOIN ull_location u5 ON u.ull_location_id = u5.id WHERE (t2.lang = ? AND u3.lang = ? AND t2.lang = ? AND u3.lang = ?) ORDER BY t2.my_string asc, u3.name desc, u5.name asc",
    'Returns the correct query'
  );

  
$t->diag('addOrderByPrefix()');
  $q->addOrderByPrefix('my_email, UllUser->UllLocation->short_name');
  $t->is(
    $q->getSqlQuery(),
    "SELECT t.id AS t__id, t.my_email AS t__my_email, t2.id AS t2__id, t2.lang AS t2__lang, t2.my_string AS t2__my_string, u.id AS u__id, u.username AS u__username, u.type AS u__type, u2.id AS u2__id, u3.id AS u3__id, u3.lang AS u3__lang, u3.name AS u3__name, u4.id AS u4__id, u4.username AS u4__username, u4.type AS u4__type, CONCAT('Write an email to ', t.my_email) AS t__0 FROM test_table t LEFT JOIN test_table_translation t2 ON t.id = t2.id LEFT JOIN ull_entity u ON t.ull_user_id = u.id AND u.type = 'user' LEFT JOIN ull_employment_type u2 ON u.ull_employment_type_id = u2.id LEFT JOIN ull_employment_type_translation u3 ON u2.id = u3.id LEFT JOIN ull_entity u4 ON t.creator_user_id = u4.id AND u4.type = 'user' LEFT JOIN ull_location u5 ON u.ull_location_id = u5.id WHERE (t2.lang = ? AND u3.lang = ? AND t2.lang = ? AND u3.lang = ?) ORDER BY t.my_email asc, u5.short_name asc, t2.my_string asc, u3.name desc, u5.name asc",
    'Returns the correct query'
  );

  
$t->diag('addWhere()');
  $q->addWhere('my_email = ?', 'foobar@example.com');
  $q->addWhere('UllUser->UllEmploymentType->name = ?', 'CEO');
  $t->is(
    $q->getSqlQuery(),
    "SELECT t.id AS t__id, t.my_email AS t__my_email, t2.id AS t2__id, t2.lang AS t2__lang, t2.my_string AS t2__my_string, u.id AS u__id, u.username AS u__username, u.type AS u__type, u2.id AS u2__id, u3.id AS u3__id, u3.lang AS u3__lang, u3.name AS u3__name, u4.id AS u4__id, u4.username AS u4__username, u4.type AS u4__type, CONCAT('Write an email to ', t.my_email) AS t__0 FROM test_table t LEFT JOIN test_table_translation t2 ON t.id = t2.id LEFT JOIN ull_entity u ON t.ull_user_id = u.id AND u.type = 'user' LEFT JOIN ull_employment_type u2 ON u.ull_employment_type_id = u2.id LEFT JOIN ull_employment_type_translation u3 ON u2.id = u3.id LEFT JOIN ull_entity u4 ON t.creator_user_id = u4.id AND u4.type = 'user' LEFT JOIN ull_location u5 ON u.ull_location_id = u5.id WHERE (t2.lang = ? AND u3.lang = ? AND t2.lang = ? AND u3.lang = ? AND t.my_email = ? AND u3.lang = ? AND u3.name = ?) ORDER BY t.my_email asc, u5.short_name asc, t2.my_string asc, u3.name desc, u5.name asc",
    'Returns the correct query'
  );
  
$t->diag('orWhere()');
  $q->orWhere('my_email = ?', 'barfoo@mpleexa.moc');
  $q->orWhere('UllUser->UllEmploymentType->name = ?', 'OEC');
  $t->is(
    $q->getSqlQuery(),
    "SELECT t.id AS t__id, t.my_email AS t__my_email, t2.id AS t2__id, t2.lang AS t2__lang, t2.my_string AS t2__my_string, u.id AS u__id, u.username AS u__username, u.type AS u__type, u2.id AS u2__id, u3.id AS u3__id, u3.lang AS u3__lang, u3.name AS u3__name, u4.id AS u4__id, u4.username AS u4__username, u4.type AS u4__type, CONCAT('Write an email to ', t.my_email) AS t__0 FROM test_table t LEFT JOIN test_table_translation t2 ON t.id = t2.id LEFT JOIN ull_entity u ON t.ull_user_id = u.id AND u.type = 'user' LEFT JOIN ull_employment_type u2 ON u.ull_employment_type_id = u2.id LEFT JOIN ull_employment_type_translation u3 ON u2.id = u3.id LEFT JOIN ull_entity u4 ON t.creator_user_id = u4.id AND u4.type = 'user' LEFT JOIN ull_location u5 ON u.ull_location_id = u5.id WHERE (t2.lang = ? AND u3.lang = ? AND t2.lang = ? AND u3.lang = ? AND t.my_email = ? AND u3.lang = ? AND u3.name = ? OR t.my_email = ? AND u3.lang = ? OR u3.name = ?) ORDER BY t.my_email asc, u5.short_name asc, t2.my_string asc, u3.name desc, u5.name asc",
    'Returns the correct query'
  );


  $t->isa_ok($q->execute(), 'Doctrine_Collection', 'Successfully executes the query');
  $t->ok(is_array($q->execute(null, Doctrine::HYDRATE_ARRAY)), 'Successfully executes the query in array hydration mode');

$t->diag('addSearch()');
  $q = new ullQuery('TestTable');
  $q->addSearch('foobar', array('my_email', 'my_string', 'UllUser->username', 'UllUser->UllEmploymentType->name'));
  
  $t->is(
    $q->getSqlQuery(),
    "SELECT t.id AS t__id, u.id AS u__id, u.type AS u__type, u2.id AS u2__id FROM test_table t LEFT JOIN test_table_translation t2 ON t.id = t2.id LEFT JOIN ull_entity u ON t.ull_user_id = u.id AND u.type = 'user' LEFT JOIN ull_employment_type u2 ON u.ull_employment_type_id = u2.id LEFT JOIN ull_employment_type_translation u3 ON u2.id = u3.id WHERE (t2.lang = ? AND u3.lang = ? AND (t.my_email LIKE ? OR t2.my_string LIKE ? OR u.username LIKE ? OR u3.name LIKE ?))",
    'Returns the correct query'
  );
  
  
  $t->isa_ok($q->execute(), 'Doctrine_Collection', 'Successfully executes the query');
  $t->ok(is_array($q->execute(null, Doctrine::HYDRATE_ARRAY)), 'Successfully executes the query in array hydration mode');
  
//var_dump($q->getSqlQuery());
//var_dump($q->getDoctrineQuery()->getParams());
//var_dump($q->execute(null, Doctrine::HYDRATE_ARRAY));
//var_dump($q->execute()->toArray(false));

$t->diag('count()');
  $q = new ullQuery('TestTable');
  $t->is($q->count(), 2, 'Returns the correct number of results');

  
$t->diag('addGroupBy()');
  $q = new ullQuery('UllUserStatus');
  $q->addSelect('COUNT(*)');
  $q->addGroupBy('is_absent');
  
  $t->is(
    $q->getSqlQuery(),
    "SELECT COUNT(*) AS u__0 FROM ull_user_status u GROUP BY u.is_absent",
    'Returns the correct query'
  );
  

$t->diag('Doctrine 1.0 bug resolved with UllUser and SELECT on >= 2 relations');  
  
  // Threw an exception 'Doctrine_Record_UnknownPropertyException' with message 'Unknown record property / related component "ull_department_id" on "UllUser"' in /var/www/ullright/plugins/ullCorePlugin/lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/lib/vendor/doctrine/Doctrine/Record/Filter/Standard.php:44
  $q = new Doctrine_Query;
  $q
    ->select('u.last_name, c.*, d.*')
    ->from('UllUser u, u.UllCompany c, u.UllDepartment d')
  ;
  
  $q->execute();
  





        