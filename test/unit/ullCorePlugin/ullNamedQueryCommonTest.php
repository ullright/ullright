<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

sfContext::createInstance($configuration);
sfContext::getInstance()->getConfiguration()->loadHelpers('I18N');

$t = new lime_test(5, new lime_output_color);

class myUllNamedQueryCommon extends ullNamedQueryCommon
{
  public function configure()
  {
    $this->baseUri = 'myModule/list';
    $this->i18nCatalogue = 'myModuleMessages';
  }
}

$t->diag('__construct()');
  $namedQuery = new myUllNamedQueryCommon;
  $t->isa_ok($namedQuery, 'myUllNamedQueryCommon', 'returns the correct object');

  
$t->diag('get/setBaseUri()');  
  $t->is($namedQuery->getBaseUri(), 'myModule/list', 'returns the correct base uri');
  $namedQuery->setBaseUri('ullUser/list');
  $t->is($namedQuery->getBaseUri(), 'ullUser/list', 'returns the correct base uri');

  
$t->diag('get/setI18nCatalogue()');  
  $t->is($namedQuery->getI18nCatalogue(), 'myModuleMessages', 'returns the correct catalogue');
  $namedQuery->setI18nCatalogue('ullUserMessages');
  $t->is($namedQuery->getI18nCatalogue(), 'ullUserMessages', 'returns the correct catalogue');  