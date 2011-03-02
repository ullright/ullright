<?php

class RenameUllNewsletterEditionColumns extends Doctrine_Migration_Base
{
  public function up()
  {
    //this foreign key name valid everywhere?
    $this->dropForeignKey('ull_newsletter_edition',
    	'ull_newsletter_edition_sent_by_ull_user_id_ull_entity_id');
    
    $this->renameColumn('ull_newsletter_edition', 'sent_at', 'submitted_at');
    $this->renameColumn('ull_newsletter_edition', 'sent_by_ull_user_id', 'submitted_by_ull_user_id');
    $this->renameColumn('ull_newsletter_edition', 'num_sent_emails', 'num_total_recipients');
    $this->addColumn('ull_newsletter_edition', 'num_sent_recipients', 'integer');
  }

  public function down()
  {
    throw new Doctrine_Migration_IrreversibleMigrationException('This migration can not be undone.');
  }
}
