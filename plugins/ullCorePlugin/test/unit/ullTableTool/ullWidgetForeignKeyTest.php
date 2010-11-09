<?php

include dirname(__FILE__) . '/../../../../../test/bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase {}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);
sfContext::getInstance()->getConfiguration()->loadHelpers(array('Url', 'Tag', 'Escaping', 'I18N', 'ull'));

$t = new myTestCase(2, new lime_output_color, $configuration);
$path = sfConfig::get('sf_root_dir') . '/plugins/ullCorePlugin/data/fixtures/';
$t->setFixturesPath($path);

$t->begin('init test');

  $testUser = Doctrine::getTable('UllUser')->findOneByUserName('test_user');
  $testUser['last_name'] = '<big>User</big>';
  $testUser->save();
  
  $options = array('model' => 'UllUser');
  $widget = new ullWidgetForeignKey($options);

$t->diag('->render()');
  
  $t->is($widget->render('foo', $testUser['id']), 'Test &lt;big&gt;User&lt;/big&gt;');

$t->diag('show entity popup');
 
  $options = array('model' => 'UllUser', 'show_ull_entity_popup' => true);
  $widget = new ullWidgetForeignKey($options);
  
   $t->is($widget->render('foo', $testUser['id']),
    '<a title="Show business card" onclick="this.href=&quot;#&quot;;popup(
            &quot;/ullUser/show/' . $testUser['id'] . '&quot;,
            &quot;Popup' . $testUser['id'] . '&quot;,
            &quot;width=720,height=720,scrollbars=yes,resizable=yes&quot;
          );" href="/ullUser/show/' . $testUser['id'] . '">Test &lt;big&gt;User&lt;/big&gt;</a>');
