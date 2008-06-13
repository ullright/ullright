<?php


/**
 * This class adds structure of 'ull_wiki' table to 'propel' DatabaseMap object.
 *
 *
 *
 * These statically-built map classes are used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    plugins.ullWikiPlugin.lib.model.map
 */
class UllWikiMapBuilder {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'plugins.ullWikiPlugin.lib.model.map.UllWikiMapBuilder';

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

		$tMap = $this->dbMap->addTable('ull_wiki');
		$tMap->setPhpName('UllWiki');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'int', CreoleTypes::INTEGER, true, null);

		$tMap->addColumn('DOCID', 'Docid', 'int', CreoleTypes::INTEGER, true, null);

		$tMap->addColumn('CURRENT', 'Current', 'int', CreoleTypes::INTEGER, false, null);

		$tMap->addColumn('CULTURE', 'Culture', 'string', CreoleTypes::VARCHAR, false, 7);

		$tMap->addColumn('BODY', 'Body', 'string', CreoleTypes::LONGVARCHAR, false, null);

		$tMap->addColumn('SUBJECT', 'Subject', 'string', CreoleTypes::VARCHAR, false, 255);

		$tMap->addColumn('CHANGELOG_COMMENT', 'ChangelogComment', 'string', CreoleTypes::VARCHAR, false, 255);

		$tMap->addColumn('READ_COUNTER', 'ReadCounter', 'int', CreoleTypes::INTEGER, false, null);

		$tMap->addColumn('EDIT_COUNTER', 'EditCounter', 'int', CreoleTypes::INTEGER, false, null);

		$tMap->addColumn('DUPLICATE_TAGS_FOR_PROPEL_SEARCH', 'DuplicateTagsForPropelSearch', 'string', CreoleTypes::LONGVARCHAR, false, null);

		$tMap->addColumn('LOCKED_BY_USER_ID', 'LockedByUserId', 'int', CreoleTypes::INTEGER, false, null);

		$tMap->addColumn('LOCKED_AT', 'LockedAt', 'int', CreoleTypes::TIMESTAMP, false, null);

		$tMap->addColumn('CREATOR_USER_ID', 'CreatorUserId', 'int', CreoleTypes::INTEGER, false, null);

		$tMap->addColumn('CREATED_AT', 'CreatedAt', 'int', CreoleTypes::TIMESTAMP, false, null);

		$tMap->addColumn('UPDATOR_USER_ID', 'UpdatorUserId', 'int', CreoleTypes::INTEGER, false, null);

		$tMap->addColumn('UPDATED_AT', 'UpdatedAt', 'int', CreoleTypes::TIMESTAMP, false, null);

	} // doBuild()

} // UllWikiMapBuilder
