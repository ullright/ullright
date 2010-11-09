<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/unit.php';

$t = new lime_test(12, new lime_output_color);

$t->diag('ullSearchFormEntry - construct from string');

$sfe = new ullSearchFormEntry('isVirtual.my_subject');
$t->is($sfe->isVirtual, true, 'virtual parsing ok');
$t->is($sfe->columnName, 'my_subject', 'column name parsing ok');
$t->is($sfe->multipleCount, 1, 'multiple count parsing ok');
$t->is_deeply($sfe->relations, array(), 'relation parsing ok');

$sfe = new ullSearchFormEntry('Creator.UllLocation.country');
$t->is($sfe->isVirtual, false, 'virtual parsing ok');
$t->is($sfe->columnName, 'country', 'column name parsing ok');
$t->is_deeply($sfe->relations, array('Creator', 'UllLocation'), 'relation parsing ok');

$t->diag('ullSearchFormEntry - toString');

$sfe = new ullSearchFormEntry();
$sfe->isVirtual = true;
$sfe->columnName = 'column';
$sfe->relations[] = 'relationOne';
$sfe->relations[] = 'relationTwo';
$t->is($sfe->__toString(), 'isVirtual.relationOne.relationTwo.column', 'toString ok');


$t->diag('ullSearchFormEntry - equals');

$sfeCompare = new ullSearchFormEntry();
$sfeCompare->isVirtual = true;
$sfeCompare->columnName = 'column';
$sfeCompare->relations[] = 'relationOne';
$sfeCompare->relations[] = 'relationTwo';

$t->is($sfe->equals($sfeCompare), true, 'equals ok');
$sfeCompare->isVirtual = false;
$t->is($sfe->equals($sfeCompare), false, 'equals ok');
$sfeCompare->isVirtual = true;
$sfeCompare->columnName = 'Column';
$t->is($sfe->equals($sfeCompare), false, 'equals ok');
$sfeCompare->columnName = 'column';
$sfeCompare->relations = array();
$t->is($sfe->equals($sfeCompare), false, 'equals ok');
