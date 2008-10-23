<?php


/**
 * This class adds structure of 'ull_flow_doc' table to 'propel' DatabaseMap object.
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
class UllFlowDocMapBuilder {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'plugins.ullFlowPlugin.lib.model.map.UllFlowDocMapBuilder';

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

		$tMap = $this->dbMap->addTable('ull_flow_doc');
		$tMap->setPhpName('UllFlowDoc');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'int', CreoleTypes::INTEGER, true, null);

		$tMap->addForeignKey('ULL_FLOW_APP_ID', 'UllFlowAppId', 'int', CreoleTypes::INTEGER, 'ull_flow_app', 'ID', false, null);

		$tMap->addColumn('TITLE', 'Title', 'string', CreoleTypes::VARCHAR, false, 256);

		$tMap->addForeignKey('ULL_FLOW_ACTION_ID', 'UllFlowActionId', 'int', CreoleTypes::INTEGER, 'ull_flow_action', 'ID', false, null);

		$tMap->addColumn('ASSIGNED_TO_ULL_USER_ID', 'AssignedToUllUserId', 'int', CreoleTypes::INTEGER, false, null);

		$tMap->addColumn('ASSIGNED_TO_ULL_GROUP_ID', 'AssignedToUllGroupId', 'int', CreoleTypes::INTEGER, false, null);

		$tMap->addColumn('ASSIGNED_TO_ULL_FLOW_STEP_ID', 'AssignedToUllFlowStepId', 'int', CreoleTypes::INTEGER, false, null);

		$tMap->addColumn('PRIORITY', 'Priority', 'int', CreoleTypes::INTEGER, false, null);

		$tMap->addColumn('DEADLINE', 'Deadline', 'int', CreoleTypes::TIMESTAMP, false, null);

		$tMap->addColumn('CUSTOM_FIELD1', 'CustomField1', 'string', CreoleTypes::VARCHAR, false, 256);

		$tMap->addColumn('READ_ULL_GROUP_ID', 'ReadUllGroupId', 'int', CreoleTypes::INTEGER, false, null);

		$tMap->addColumn('DUPLICATE_TAGS_FOR_PROPEL_SEARCH', 'DuplicateTagsForPropelSearch', 'string', CreoleTypes::LONGVARCHAR, false, null);

		$tMap->addColumn('WRITE_ULL_GROUP_ID', 'WriteUllGroupId', 'int', CreoleTypes::INTEGER, false, null);

		$tMap->addColumn('CREATOR_USER_ID', 'CreatorUserId', 'int', CreoleTypes::INTEGER, false, null);

		$tMap->addColumn('CREATED_AT', 'CreatedAt', 'int', CreoleTypes::TIMESTAMP, false, null);

		$tMap->addColumn('UPDATOR_USER_ID', 'UpdatorUserId', 'int', CreoleTypes::INTEGER, false, null);

		$tMap->addColumn('UPDATED_AT', 'UpdatedAt', 'int', CreoleTypes::TIMESTAMP, false, null);

	} // doBuild()

} // UllFlowDocMapBuilder
