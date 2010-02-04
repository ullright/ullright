<?php

class FixUllFlowDocActionTask extends sfBaseTask
{

  protected function configure()
  {
    $this->namespace        = 'ullright';
    $this->name             = 'fix-ullflowdoc-action';
    $this->briefDescription = 'Fixes the doc\'s action to the latest non-status-only action';
    $this->detailedDescription = <<<EOF
    The [{$this->name} task|INFO] fixes the doc\'s action to the latest non-status-only action
    The only intended usage is the one-time conversion from revisions prior to 1029!

    Call it with:

    [php symfony {$this->namespace}:{$this->name}|INFO]
EOF;

    $this->addArgument('application', sfCommandArgument::OPTIONAL,
      'The application name', 'frontend');
    $this->addArgument('env', sfCommandArgument::OPTIONAL,
      'The environment', 'cli');
  }


  protected function execute($arguments = array(), $options = array())
  {
    $this->logSection($this->name, 'Fix actions...');

    $configuration = ProjectConfiguration::getApplicationConfiguration(
    $arguments['application'], $arguments['env'], true);

    $databaseManager = new sfDatabaseManager($configuration);

    $docs = Doctrine::getTable('UllFlowDoc')->findAll();
    foreach ($docs as $doc)
    {
      $actionId = $doc->findLatestNonStatusOnlyMemory()->ull_flow_action_id;
      
      if ($actionId != $doc->ull_flow_action_id && in_array($doc->ull_flow_action_id, array(2,3)))
      {
        $this->log('Fixing for id ' . $doc->id . ': from ' . $doc->ull_flow_action_id . ' to '. $actionId);
        // do a update query do avoid triggering any hooks and behaviours
        $q = new Doctrine_Query;
        $q
          ->update('UllFlowDoc d')
          ->set('d.ull_flow_action_id', $actionId)
          ->where('d.id = ?', $doc->id)
          ->execute()
        ;
      }
 
      
    }
  }
}