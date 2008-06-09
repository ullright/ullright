<?php


/**
 * This class adds structure of 'ull_flow_memory' table to 'propel' DatabaseMap object.
 *
 *
 *
 * These statically-built map classes are used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    plugins.ullFlowPlugin.lib.model.map
 */
class UllFlowMemoryMapBuilder {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'plugins.ullFlowPlugin.lib.model.map.UllFlowMemoryMapBuilder';

	/**
	 * The database map.
	 */
	private $dbMap;

	/**
	 * Tells us if this DatabaseMapBuilder is built so that we
	 * don't have to re-build it every time.
	 *
	 * @return     boolean true if this DatabaseMapBuilder is built, false otherwise.
	 */
	public function isBuilt()
	{
		return ($this->dbMap !== null);
	}

	/**
	 * Gets the databasemap this map builder built.
	 *
	 * @return     the databasemap
	 */
	public function getDatabaseMap()
	{
		return $this->dbMap;
	}

	/**
	 * The doBuild() method builds the DatabaseMap
	 *
	 * @return     void
	 * @throws     PropelException
	 */
	public function doBuild()
	{
		$this->dbMap = Propel::getDatabaseMap('propel');

		$tMap = $this->dbMap->addTable('ull_flow_memory');
		$tMap->setPhpName('UllFlowMemory');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'int', CreoleTypes::INTEGER, true, null);

		$tMap->addForeignKey('ULL_FLOW_DOC_ID', 'UllFlowDocId', 'int', CreoleTypes::INTEGER, 'ull_flow_doc', 'ID', false, null);

		$tMap->addForeignKey('ULL_FLOW_STEP_ID', 'UllFlowStepId', 'int', CreoleTypes::INTEGER, 'ull_flow_step', 'ID', false, null);

		$tMap->addForeignKey('ULL_FLOW_ACTION_ID', 'UllFlowActionId', 'int', CreoleTypes::INTEGER, 'ull_flow_action', 'ID', false, null);

		$tMap->addColumn('ASSIGNED_TO_ULL_USER_ID', 'AssignedToUllUserId', 'int', CreoleTypes::INTEGER, false, null);

		$tMap->addColumn('ASSIGNED_TO_ULL_GROUP_ID', 'AssignedToUllGroupId', 'int', CreoleTypes::INTEGER, false, null);

		$tMap->addColumn('COMMENT', 'Comment', 'string', CreoleTypes::VARCHAR, false, 255);

		$tMap->addColumn('CREATOR_GROUP_ID', 'CreatorGroupId', 'int', CreoleTypes::INTEGER, false, null);

		$tMap->addColumn('CREATOR_USER_ID', 'CreatorUserId', 'int', CreoleTypes::INTEGER, false, null);

		$tMap->addColumn('CREATED_AT', 'CreatedAt', 'int', CreoleTypes::TIMESTAMP, false, null);

	} // doBuild()

} // UllFlowMemoryMapBuilder
