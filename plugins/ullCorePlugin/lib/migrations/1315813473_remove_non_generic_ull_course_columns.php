<?php

class RemoveNonGenericUllCourseColumns extends Doctrine_Migration_Base
{
  public function up()
  {
    $this->removeColumn('ull_course', 'is_equipment_included');
    $this->removeColumn('ull_course', 'is_admission_included');
  }

  public function down()
  {
    throw new Doctrine_Migration_IrreversibleMigrationException('This migration can not be undone.');
  }
}
