<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
}

$t = new myTestCase(1, new lime_output_color, $configuration);
sfContext::createInstance($configuration);
$path = sfConfig::get('sf_root_dir') . '/plugins/ullCorePlugin/data/fixtures/';
$t->setFixturesPath($path);

$w = new ullWidgetTaggable;

// ->render()
$t->begin('->render()');

  $t->ok(strstr($w->render('foo', 'tag1, tag2'), '<input type="text" name="foo" value="tag1, tag2" id="foo" />'), 'renders the correct input type=test tag');
  