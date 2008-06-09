<?php


/**
 * This class adds structure of 'ull_location' table to 'propel' DatabaseMap object.
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
class UllLocationMapBuilder {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'plugins.ullCorePlugin.lib.model.map.UllLocationMapBuilder';

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

		$tMap = $this->dbMap->addTable('ull_location');
		$tMap->setPhpName('UllLocation');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'int', CreoleTypes::INTEGER, true, null);

		$tMap->addColumn('NAME', 'Name', 'string', CreoleTypes::VARCHAR, false, 255);

		$tMap->addColumn('SHORT', 'Short', 'string', CreoleTypes::VARCHAR, false, 8);

		$tMap->addColumn('COMPANY_ID', 'CompanyId', 'int', CreoleTypes::INTEGER, false, null);

		$tMap->addColumn('STREET', 'Street', 'string', CreoleTypes::VARCHAR, false, 255);

		$tMap->addColumn('ZIP', 'Zip', 'string', CreoleTypes::VARCHAR, false, 8);

		$tMap->addColumn('CITY', 'City', 'string', CreoleTypes::VARCHAR, false, 64);

		$tMap->addColumn('COUNTRY_ID', 'CountryId', 'int', CreoleTypes::INTEGER, false, null);

		$tMap->addColumn('PHONE_TRUNK_NUM', 'PhoneTrunkNum', 'string', CreoleTypes::VARCHAR, false, 32);

		$tMap->addColumn('PHONE_STD_EXT_NUM', 'PhoneStdExtNum', 'string', CreoleTypes::VARCHAR, false, 8);

		$tMap->addColumn('FAX_TRUNK_NUM', 'FaxTrunkNum', 'string', CreoleTypes::VARCHAR, false, 32);

		$tMap->addColumn('FAX_STD_EXT_NUM', 'FaxStdExtNum', 'string', CreoleTypes::VARCHAR, false, 8);

		$tMap->addColumn('CREATOR_USER_ID', 'CreatorUserId', 'int', CreoleTypes::INTEGER, false, null);

		$tMap->addColumn('CREATED_AT', 'CreatedAt', 'int', CreoleTypes::TIMESTAMP, false, null);

		$tMap->addColumn('UPDATOR_USER_ID', 'UpdatorUserId', 'int', CreoleTypes::INTEGER, false, null);

	} // doBuild()

} // UllLocationMapBuilder
