<?php

class ullTableToolHistoryGenerator extends ullTableToolGenerator
{
	protected $columnsBlacklist = array();
	protected $updator;
	protected $updated_at;
	protected $scheduled_update_date;
	protected $id;
	protected $wasScheduledUpdate;
	protected $scheduledUpdater;

	public function buildHistoryForm(Doctrine_Record $curRow, Doctrine_Record $revRow) {
		$changes = array_diff_assoc($curRow->toArray(), $revRow->toArray());

		foreach ($curRow->toArray() as $key => $value)
		{
			if (!array_key_exists($key, $changes))
			$this->columnsBlacklist[] = $key;
		}
		$this->columnsBlacklist[] = 'version';
		$this->columnsBlacklist[] = 'updated_at';
		$this->columnsBlacklist[] = 'updator_user_id';
		$this->columnsBlacklist[] = 'created_at';
		$this->columnsBlacklist[] = 'creator_user_id';
		$this->columnsBlacklist[] = 'scheduled_update_date';

		//->Updator is available in Version as well
		$this->updator = $curRow->Updator;
		$this->id = $curRow->identifier();

		if ($curRow->contains('scheduled_update_date')) {
			$this->scheduled_update_date = $curRow->scheduled_update_date;
		}

		$this->updated_at = $curRow->updated_at;

		if ($curRow->getTable()->hasTemplate('Doctrine_Template_SuperVersionable'))
		{
			$versionRecord = $curRow->getAuditLog()->getVersionRecord($curRow, $curRow->version);
			if ($versionRecord->contains('reference_version') && ($versionRecord->reference_version != NULL))
			{
				//var_dump($versionRecord->reference_version);
				$this->wasScheduledUpdate = true;
				$this->scheduledUpdater = $versionRecord->Updator;
			}
		}
		parent::removeBlacklistColumns();
		parent::buildForm($curRow);
	}

	public function _checkBuildRequirement()
	{
		if (!$this->isBuilt)
		{
			throw new RuntimeException('You have to call buildHistoryForm() first');
		}
	}

	public function getUpdator()
	{
		$this->_checkBuildRequirement();
		return $this->updator;
	}

	public function getUpdatedAt()
	{
		$this->_checkBuildRequirement();
		return $this->updated_at;
	}

	public function getScheduledUpdateDate()
	{
		$this->_checkBuildRequirement();
		return $this->scheduled_update_date;
	}

	public function getIdentifierArray()
	{
		$this->_checkBuildRequirement();
		return $this->id;
	}

	public function wasScheduledUpdate()
	{
		$this->_checkBuildRequirement();
		return $this->wasScheduledUpdate;
	}

	public function getscheduledUpdator()
	{
		$this->_checkBuildRequirement();
		return $this->scheduledUpdater;
	}
}