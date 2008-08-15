<?php


/**
 * This class adds structure of 'ull_access_group_group' table to 'propel' DatabaseMap object.
 *
 *
 *
 * These statically-built map classes are used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    plugins.ullCorePlugin.lib.model.map
 */
class UllAccessGroupGroupMapBuilder {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'plugins.ullCorePlugin.lib.model.map.UllAccessGroupGroupMapBuilder';

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

		$tMap = $this->dbMap->addTable('ull_access_group_group');
		$tMap->setPhpName('UllAccessGroupGroup');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'int', CreoleTypes::INTEGER, true, null);

		$tMap->addForeignKey('ULL_ACCESS_GROUP_ID', 'UllAccessGroupId', 'int', CreoleTypes::INTEGER, 'ull_access_group', 'ID', false, null);

		$tMap->addForeignKey('ULL_GROUP_ID', 'UllGroupId', 'int', CreoleTypes::INTEGER, 'ull_group', 'ID', false, null);

		$tMap->addColumn('READ_FLAG', 'ReadFlag', 'boolean', CreoleTypes::BOOLEAN, false, null);

		$tMap->addColumn('WRITE_FLAG', 'WriteFlag', 'boolean', CreoleTypes::BOOLEAN, false, null);

	} // doBuild()

} // UllAccessGroupGroupMapBuilder
