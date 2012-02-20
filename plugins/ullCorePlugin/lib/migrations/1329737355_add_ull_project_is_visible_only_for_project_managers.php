<?php

class AddUllProjectIsVisibleOnlyForProjectManagers extends Doctrine_Migration_Base
{
  public function up()
  {
    $this->addColumn('ull_project', 'is_visible_only_for_project_managers', 'boolean');
  }

  public function down()
  {
    $this->removeColumn('ull_project', 'is_visible_only_for_project_managers');
  }
}
