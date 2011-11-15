<?php

class LoadUllMailErrorData extends Doctrine_Migration_Base
{
  public function up()
  {
    echo shell_exec('php symfony doctrine:data-load --append plugins/ullMailPlugin/data/fixtures/ullMailErrorFixtures.yml');
  }

  public function down()
  {
  }
}
