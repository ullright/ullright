<?php

class ApplyScheduledUpdatesTask extends sfBaseTask
{
	protected $blacklist =
	array(
      'updated_at',
      'version',
      'reference_version',
      'scheduled_update_date',
      'done_at');

	protected function configure()
	{
		$this->namespace        = 'ullright';
		$this->name             = 'apply-scheduled-updates';
		$this->briefDescription = 'Applies scheduled updates for every SuperVersionable model';
		$this->detailedDescription = <<<EOF
    The [{$this->name} task|INFO] applies due scheduled updates (aka future versions)
    for every model in the application which uses the SuperVersionable template.

    Call it with:

    [php symfony {$this->namespace}:{$this->name}|INFO]
    
    This task usually is invoked by a (daily) cronjob.
    The 'now' argument is intended for debugging purposes only.
EOF;
          ////we don't want the old version, we want the current one :)
		$this->addArgument('now', sfCommandArgument::OPTIONAL,
      'Set to \'now\' to execute all scheduled updates NOW', 'not_now');
		$this->addArgument('application', sfCommandArgument::OPTIONAL,
      'The application name', 'frontend');
		$this->addArgument('env', sfCommandArgument::OPTIONAL,
      'The environment', 'cli');
	}


	protected function execute($arguments = array(), $options = array())
	{
		if ($arguments['now'] == 'now') {
			$answer = $this->ask('Are you sure you wish to apply all scheduled updates ' .
            'regardless of their due date? (y/n)');

			if ($answer != 'y')
			{
				$this->log('Successfully cancelled');
				return;
			}
		}

		$configuration = ProjectConfiguration::getApplicationConfiguration(
		$arguments['application'], $arguments['env'], true);

		$databaseManager = new sfDatabaseManager($configuration);
		$conn = Doctrine_Manager::connection();
		//$dateOperator = ($arguments['now'] == 'now') ? '<' : '>';

		Doctrine::loadModels(sfConfig::get('sf_root_dir') . '/lib/model/doctrine');
		$modelNames = Doctrine::getLoadedModels();

		$conn->beginTransaction();
		
		foreach ($modelNames as $modelName) {
			
			$table = Doctrine::getTable($modelName);
			if ($table->hasTemplate('Doctrine_Template_SuperVersionable'))
			{
//				if ($modelName == 'UllParentEntity' || $modelName == 'UllEntity')
//				{
//					continue; //TODO: we need a better way for this
//				}

				$this->logSection('Now looking at model: ' . $modelName);

				$template = $table->getTemplate('Doctrine_Template_SuperVersionable');
				$className = $template->getClassName();

				$q = Doctrine_Query::create()->
				  from($className)->
				  where('version < 0');
				if ($arguments['now'] != 'now')
				{
					$q = $q->andWhere('CURRENT_DATE() > scheduled_update_date');
				}
				$q = $q->andWhere('done_at IS NULL')->
				          orderBy('scheduled_update_date ASC, version DESC'); //oldest on top

				$futureVersions = $q->execute();
				$this->log('Found ' . count($futureVersions) . ' scheduled updates to apply');

				if (count($futureVersions) == 0) {
					continue;
				}

				$this->log("Now retrieving scheduled updates");         ////we don't want the old version, we want the current one :)
				foreach ($futureVersions as $futureVersion)
				{
					//var_dump($futureVersion->toArray());
					$q = Doctrine_Query::create()->
					 from($modelName)->
					 where('id = ?', $futureVersion->id);
					$row = $q->fetchOne();
					$tempRow = clone $row;
					$row->revert($futureVersion->reference_version);
					//var_dump($row->toArray());

					$changes = array_diff_assoc($futureVersion->toArray(), $row->toArray());

//this is never executed because changes will always include blacklisted columns...
//					if (count($changes) == 0)
//					{
//					 $this->log("Scheduled update contains no changes, skipping");
//					 continue;
//					}
						
					$this->log("Changes to apply for " . $modelName . ' with id: ' . $row->id);
					//var_dump($futureVersion->toArray());
					foreach ($futureVersion->toArray() as $key => $value)
					{
						if (array_key_exists($key, $changes) && !in_array($key, $this->blacklist)) {
							$this->log($key . ' to: ' . $futureVersion->$key);
							$tempRow->$key = $futureVersion->$key;
						}
					}
						
					$this->log('Now saving ' . $modelName . ' with id: ' . $row->id);
					//$tempRow->reference_version = ;
					$tempRow->save();
          $newFutureVersion = $tempRow->getAuditLog()->getVersionRecord($tempRow);
          $newFutureVersion->reference_version = $futureVersion->version;
          $newFutureVersion->save();
					
          $futureVersion->done_at = date('Y-m-d H:i:s');
				  $futureVersion->save();
				}
			}
		}
    //$conn->rollback();
    $conn->commit();	 
	}
}