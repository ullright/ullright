<?php

class AddUllUserPersonalContactColumns extends Doctrine_Migration_Base
{
  public function up()
  {
    $this->addColumn('ull_entity', 'title', 'string', 100);
    $this->addColumn('ull_entity', 'birth_date', 'date');
    $this->addColumn('ull_entity', 'street', 'string', 200);
    $this->addColumn('ull_entity', 'post_code', 'string', 10);
    $this->addColumn('ull_entity', 'city', 'string', 100);
    $this->addColumn('ull_entity', 'country', 'string', 10);
    $this->addColumn('ull_entity', 'phone_number', 'string', 20);
    $this->addColumn('ull_entity', 'fax_number', 'string', 20);
    $this->addColumn('ull_entity', 'website', 'string', 255);
  }

  public function down()
  {
    throw new Doctrine_Migration_IrreversibleMigrationException('This migration can not be undone.');
  }
}
