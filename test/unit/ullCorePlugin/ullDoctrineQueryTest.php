<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

class myTestCase extends lime_test {}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);
sfLoader::loadHelpers('I18N');

$t = new myTestCase(5, new lime_output_color, $configuration);

$t->diag('common query');
  
  $q = new Doctrine_Query();
  $q
    ->from('UllUser x')
    ->select('x.first_name, x.last_name')
  ;

  $q->where('x.first_name = ?', 'master');
  $q->orWhere('x.last_name = ?', 'admin');
  $q->andWhere('x.first_name = ?', 'user');
   
  $t->is($q->getSqlQuery(), 'SELECT u.id AS u__id, u.first_name AS u__first_name, u.last_name AS u__last_name FROM ull_entity u ' .
    'WHERE (u.first_name = ? OR u.last_name = ? AND u.first_name = ? AND (u.type = \'user\'))', 'returns correct SQL');
  
  //we have (x OR y AND z) here
  //what if we want the OR to take precedence?
  //we could wrap this up in one where, e.g.
  //where((x or y) and z)
  //but this is not be possible in complex class
  //hierarchies where different components
  //add different parts to clauses
  
$t->diag('ullDoctrineQuery');
  
  $q = new ullDoctrineQuery();
  $q
    ->from('UllUser x')
    ->select('x.first_name, x.last_name')
  ;

  $q->where('x.first_name = ?', 'master');
  $q->openParenthesisBeforeLastPart();
  $q->orWhere('x.last_name = ?', 'admin');
  $q->closeParenthesis();
  $q->andWhere('x.first_name = ?', 'user');
  
  $t->is($q->getSqlQuery(), 'SELECT u.id AS u__id, u.first_name AS u__first_name, u.last_name AS u__last_name FROM ull_entity u ' .
    'WHERE ( u.first_name = ? OR u.last_name = ? ) AND u.first_name = ? AND (u.type = \'user\')', 'returns correct SQL');
  
  $t->isa_ok($q->execute(), 'Doctrine_Collection', 'Successfully executes the query');

  //or we can use the alternative wrapping method
  
$t->diag('ullDoctrineQuery');
  
  $q = new ullDoctrineQuery();
  $q
    ->from('UllUser x')
    ->select('x.first_name, x.last_name')
  ;

  $q->where('x.first_name = ?', 'master');
  $q->orWhere('x.last_name = ?', 'admin');
  $q->wrapExistingWhereInParantheses();
  $q->andWhere('x.first_name = ?', 'user');
  
  $t->is($q->getSqlQuery(), 'SELECT u.id AS u__id, u.first_name AS u__first_name, u.last_name AS u__last_name FROM ull_entity u ' .
    'WHERE ( u.first_name = ? OR u.last_name = ? ) AND u.first_name = ? AND (u.type = \'user\')', 'returns correct SQL');
  
  $t->isa_ok($q->execute(), 'Doctrine_Collection', 'Successfully executes the query');
  