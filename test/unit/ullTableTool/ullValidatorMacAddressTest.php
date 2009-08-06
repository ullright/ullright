<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

$t = new lime_test_simple(3, new lime_output_color);

$v = new ullValidatorMacAddress();

//@Test MAC address validation
$t->is($v->clean('00-1A-2b-3C-4d-5E'), '00:1a:2b:3c:4d:5e', 'returns correct mac address');
$t->is($v->clean('45:9F:00:A2:e9:3A'), '45:9f:00:a2:e9:3a', 'returns correct mac address');

//@Test Invalid MAC address error
$t->expect('sfValidatorError');
$v->clean('45:9F:0:A2:e9:3');
