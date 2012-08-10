<?php

class AddUllCmsDefaultPageContentType extends Doctrine_Migration_Base
{
  public function up()
  {
    $x = new UllCmsContentType;
    $x->Translation['en']->name = 'Default';
    $x->Translation['de']->name = 'Standard';
    $x->type = 'page';
    $x->save(); 
  }

  public function down()
  {
  }
}
