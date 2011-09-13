<?php

class AddUllCourseIsBookable extends Doctrine_Migration_Base
{
  public function up()
  {
    $this->addColumn('ull_course', 'is_bookable', 'boolean');
  }

  public function down()
  {
    throw new Doctrine_Migration_IrreversibleMigrationException('This migration can not be undone.');
  }
}
