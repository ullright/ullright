<?php


/**
 * This class adds structure of 'ull_column_info' table to 'propel' DatabaseMap object.
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
class UllColumnInfoMapBuilder {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'plugins.ullCorePlugin.lib.model.map.UllColumnInfoMapBuilder';

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

		$tMap = $this->dbMap->addTable('ull_column_info');
		$tMap->setPhpName('UllColumnInfo');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'int', CreoleTypes::INTEGER, true, null);

		$tMap->addColumn('DB_TABLE_NAME', 'DbTableName', 'string', CreoleTypes::VARCHAR, false, 32);

		$tMap->addColumn('DB_COLUMN_NAME', 'DbColumnName', 'string', CreoleTypes::VARCHAR, false, 32);

		$tMap->addForeignKey('ULL_FIELD_ID', 'UllFieldId', 'int', CreoleTypes::INTEGER, 'ull_field', 'ID', false, null);

		$tMap->addColumn('OPTIONS', 'Options', 'string', CreoleTypes::LONGVARCHAR, false, null);

		$tMap->addColumn('ENABLED', 'Enabled', 'boolean', CreoleTypes::BOOLEAN, false, null);

		$tMap->addColumn('SHOW_IN_LIST', 'ShowInList', 'boolean', CreoleTypes::BOOLEAN, false, null);

		$tMap->addColumn('MANDATORY', 'Mandatory', 'boolean', CreoleTypes::BOOLEAN, false, null);

		$tMap->addColumn('CAPTION_I18N_DEFAULT', 'CaptionI18nDefault', 'string', CreoleTypes::VARCHAR, false, 64);

		$tMap->addColumn('DESCRIPTION_I18N_DEFAULT', 'DescriptionI18nDefault', 'string', CreoleTypes::LONGVARCHAR, false, null);

	} // doBuild()

} // UllColumnInfoMapBuilder
