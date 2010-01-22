<?php

class UllWikiDeletedAt extends Doctrine_Migration_Base
{
  public function up()
  {
    //TODO: Does this work? What happens to data/non-timestamps?
    $this->renameColumn('ull_wiki', 'deleted', 'deleted_at');
    $this->changeColumn('ull_wiki', 'deleted_at', 'timestamp');
  }

  public function down()
  {
    //TODO: Does this work? What happens to data/non-booleans?
    //Throw Exception if we can't make it work
    $this->changeColumn('ull_wiki', 'deleted_at', 'boolean');
    $this->renameColumn('ull_wiki', 'deleted_at', 'deleted');
  }
}
