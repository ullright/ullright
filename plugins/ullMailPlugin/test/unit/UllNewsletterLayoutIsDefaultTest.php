<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/unit.php';

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);

$t = new sfDoctrineTestCase(4, new lime_output_color, $configuration);
$path = dirname(__FILE__);
$t->setFixturesPath($path);


$t->begin('create two ullNewsletterLayout objects, check default Layout');
  $layoutOne = new UllNewsletterLayout();
  $layoutOne->name = 'testLayoutOne';
  $layoutOne->html_body = '<html><body>test one</body></html>';
  $layoutOne->save();
  
  $layoutTwo = new UllNewsletterLayout();
  $layoutTwo->name = 'testLayoutTwo';
  $layoutTwo->html_body = '<html><body>test two</body></html>';
  $layoutTwo->save();
  
  $t->is(PluginUllNewsletterLayoutTable::getDefaultId(), false, 'There isn\'t a default layout');
  
$t->diag('set layouts as default');
  $layoutOne->is_default = true;
  $layoutOne->save();
  
  $t->is(PluginUllNewsletterLayoutTable::getDefaultId(), $layoutOne->id, 'Layout one is default');
  
  
  $layoutTwo->is_default = true;
  $layoutTwo->save();
  
  $t->is(PluginUllNewsletterLayoutTable::getDefaultId(), $layoutTwo->id, 'Layout two is default');
  
  $layoutOne->refresh();
  $t->is($layoutOne->is_default, false, 'is_default flag of layout one is "false"');