<?php

class UllWikiDeletedAtIi extends Doctrine_Migration_Base
{
  public function up()
  {
    //TODO: Does this work? What happens to data/non-timestamps?
    $this->renameColumn('ull_wiki_version', 'deleted', 'deleted_at');
    $this->changeColumn('ull_wiki_version', 'deleted_at', 'timestamp');
  }
  
  public function postUp()
  {
    $dbh = Doctrine_Manager::getInstance()->getCurrentConnection()->getDbh();
    $dbh->exec("UPDATE ull_wiki_version SET deleted_at=NULL where deleted_at='0000-00-00 00:00:00'");
  }

  public function down()
  {
    //TODO: Does this work? What happens to data/non-booleans?
    //Throw Exception if we can't make it work
    $this->changeColumn('ull_wiki_version', 'deleted_at', 'boolean');
    $this->renameColumn('ull_wiki_version', 'deleted_at', 'deleted');
  }
}
