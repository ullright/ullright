<?php

/**
 * Tests for patched symfony stuff
 */

include dirname(__FILE__) . '/../../../../test/bootstrap/unit.php';

$instance = sfContext::createInstance($configuration);
$instance->getConfiguration()->loadHelpers(array('Text', 'Tag'));

$t = new lime_test(1, new lime_output_color);

$t->diag('auto_link_text()');

  $t->is(
    auto_link_text('<a href="mailto:me@example.com">bar</a> you@example.com'),
    '<a href="mailto:me@example.com">bar</a> <a href="mailto:you@example.com">you@example.com</a>',
    'Leaves already linked email addresses unchanged'
  );
