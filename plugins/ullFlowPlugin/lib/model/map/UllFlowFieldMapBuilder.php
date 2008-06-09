<?php


/**
 * This class adds structure of 'ull_flow_field' table to 'propel' DatabaseMap object.
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
class UllFlowFieldMapBuilder {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'plugins.ullFlowPlugin.lib.model.map.UllFlowFieldMapBuilder';

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

		$tMap = $this->dbMap->addTable('ull_flow_field');
		$tMap->setPhpName('UllFlowField');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'int', CreoleTypes::INTEGER, true, null);

		$tMap->addForeignKey('ULL_FLOW_APP_ID', 'UllFlowAppId', 'int', CreoleTypes::INTEGER, 'ull_flow_app', 'ID', false, null);

		$tMap->addColumn('ULL_FIELD_ID', 'UllFieldId', 'int', CreoleTypes::INTEGER, false, null);

		$tMap->addColumn('OPTIONS', 'Options', 'string', CreoleTypes::LONGVARCHAR, false, null);

		$tMap->addColumn('CAPTION_I18N_DEFAULT', 'CaptionI18nDefault', 'string', CreoleTypes::VARCHAR, false, 32);

		$tMap->addColumn('SEQUENCE', 'Sequence', 'int', CreoleTypes::INTEGER, false, null);

		$tMap->addColumn('ENABLED', 'Enabled', 'boolean', CreoleTypes::BOOLEAN, false, null);

		$tMap->addColumn('MANDATORY', 'Mandatory', 'boolean', CreoleTypes::BOOLEAN, false, null);

		$tMap->addColumn('IS_TITLE', 'IsTitle', 'boolean', CreoleTypes::BOOLEAN, false, null);

		$tMap->addColumn('IS_PRIORITY', 'IsPriority', 'boolean', CreoleTypes::BOOLEAN, false, null);

		$tMap->addColumn('IS_DEADLINE', 'IsDeadline', 'boolean', CreoleTypes::BOOLEAN, false, null);

		$tMap->addColumn('IS_CUSTOM_FIELD1', 'IsCustomField1', 'boolean', CreoleTypes::BOOLEAN, false, null);

		$tMap->addColumn('ULL_ACCESS_GROUP_ID', 'UllAccessGroupId', 'int', CreoleTypes::INTEGER, false, null);

		$tMap->addColumn('CREATOR_USER_ID', 'CreatorUserId', 'int', CreoleTypes::INTEGER, false, null);

		$tMap->addColumn('CREATED_AT', 'CreatedAt', 'int', CreoleTypes::TIMESTAMP, false, null);

		$tMap->addColumn('UPDATOR_USER_ID', 'UpdatorUserId', 'int', CreoleTypes::INTEGER, false, null);

		$tMap->addColumn('UPDATED_AT', 'UpdatedAt', 'int', CreoleTypes::TIMESTAMP, false, null);

	} // doBuild()

} // UllFlowFieldMapBuilder
