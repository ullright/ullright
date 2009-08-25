<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

$t = new lime_test(6, new lime_output_color(), $configuration);
$instance = sfContext::createInstance($configuration);

$v = new ullValidatorNumberI18n();

$t->ok((string)$v->clean('01234.358') === '1234.358', 'returns correct number - en');
$t->ok((string)$v->clean('-423,342.64') === '-423342.64', 'returns correct number - en');
$t->ok((string)$v->clean('-423,342.') === '-423342', 'returns correct number - en');

$instance->getUser()->setCulture("de");

$t->ok((string)$v->clean('-01234,35') === '-1234.35', 'returns correct number - de');
$t->ok((string)$v->clean('423.342,64') === '423342.64', 'returns correct number - de');
$t->ok((string)$v->clean('423.342.') === '423342', 'returns correct number - de');

