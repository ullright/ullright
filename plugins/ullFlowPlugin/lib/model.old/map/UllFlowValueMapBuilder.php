<?php


/**
 * This class adds structure of 'ull_flow_value' table to 'propel' DatabaseMap object.
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
class UllFlowValueMapBuilder {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'plugins.ullFlowPlugin.lib.model.map.UllFlowValueMapBuilder';

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

		$tMap = $this->dbMap->addTable('ull_flow_value');
		$tMap->setPhpName('UllFlowValue');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'int', CreoleTypes::INTEGER, true, null);

		$tMap->addForeignKey('ULL_FLOW_DOC_ID', 'UllFlowDocId', 'int', CreoleTypes::INTEGER, 'ull_flow_doc', 'ID', false, null);

		$tMap->addForeignKey('ULL_FLOW_FIELD_ID', 'UllFlowFieldId', 'int', CreoleTypes::INTEGER, 'ull_flow_field', 'ID', false, null);

		$tMap->addForeignKey('ULL_FLOW_MEMORY_ID', 'UllFlowMemoryId', 'int', CreoleTypes::INTEGER, 'ull_flow_memory', 'ID', false, null);

		$tMap->addColumn('CURRENT', 'Current', 'boolean', CreoleTypes::BOOLEAN, false, null);

		$tMap->addColumn('VALUE', 'Value', 'string', CreoleTypes::LONGVARCHAR, false, null);

		$tMap->addColumn('CREATOR_USER_ID', 'CreatorUserId', 'int', CreoleTypes::INTEGER, false, null);

		$tMap->addColumn('CREATED_AT', 'CreatedAt', 'int', CreoleTypes::TIMESTAMP, false, null);

		$tMap->addColumn('UPDATOR_USER_ID', 'UpdatorUserId', 'int', CreoleTypes::INTEGER, false, null);

		$tMap->addColumn('UPDATED_AT', 'UpdatedAt', 'int', CreoleTypes::TIMESTAMP, false, null);

	} // doBuild()

} // UllFlowValueMapBuilder
