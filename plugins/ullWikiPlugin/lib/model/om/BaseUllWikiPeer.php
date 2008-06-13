<?php

/**
 * Base static class for performing query and update operations on the 'ull_wiki' table.
 *
 * 
 *
 * @package    plugins.ullWikiPlugin.lib.model.om
 */
abstract class BaseUllWikiPeer {

	/** the default database name for this class */
	const DATABASE_NAME = 'propel';

	/** the table name for this class */
	const TABLE_NAME = 'ull_wiki';

	/** A class that can be returned by this peer. */
	const CLASS_DEFAULT = 'plugins.ullWikiPlugin.lib.model.UllWiki';

	/** The total number of columns. */
	const NUM_COLUMNS = 16;

	/** The number of lazy-loaded columns. */
	const NUM_LAZY_LOAD_COLUMNS = 0;


	/** the column name for the ID field */
	const ID = 'ull_wiki.ID';

	/** the column name for the DOCID field */
	const DOCID = 'ull_wiki.DOCID';

	/** the column name for the CURRENT field */
	const CURRENT = 'ull_wiki.CURRENT';

	/** the column name for the CULTURE field */
	const CULTURE = 'ull_wiki.CULTURE';

	/** the column name for the BODY field */
	const BODY = 'ull_wiki.BODY';

	/** the column name for the SUBJECT field */
	const SUBJECT = 'ull_wiki.SUBJECT';

	/** the column name for the CHANGELOG_COMMENT field */
	const CHANGELOG_COMMENT = 'ull_wiki.CHANGELOG_COMMENT';

	/** the column name for the READ_COUNTER field */
	const READ_COUNTER = 'ull_wiki.READ_COUNTER';

	/** the column name for the EDIT_COUNTER field */
	const EDIT_COUNTER = 'ull_wiki.EDIT_COUNTER';

	/** the column name for the DUPLICATE_TAGS_FOR_PROPEL_SEARCH field */
	const DUPLICATE_TAGS_FOR_PROPEL_SEARCH = 'ull_wiki.DUPLICATE_TAGS_FOR_PROPEL_SEARCH';

	/** the column name for the LOCKED_BY_USER_ID field */
	const LOCKED_BY_USER_ID = 'ull_wiki.LOCKED_BY_USER_ID';

	/** the column name for the LOCKED_AT field */
	const LOCKED_AT = 'ull_wiki.LOCKED_AT';

	/** the column name for the CREATOR_USER_ID field */
	const CREATOR_USER_ID = 'ull_wiki.CREATOR_USER_ID';

	/** the column name for the CREATED_AT field */
	const CREATED_AT = 'ull_wiki.CREATED_AT';

	/** the column name for the UPDATOR_USER_ID field */
	const UPDATOR_USER_ID = 'ull_wiki.UPDATOR_USER_ID';

	/** the column name for the UPDATED_AT field */
	const UPDATED_AT = 'ull_wiki.UPDATED_AT';

	/** The PHP to DB Name Mapping */
	private static $phpNameMap = null;


	/**
	 * holds an array of fieldnames
	 *
	 * first dimension keys are the type constants
	 * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
	 */
	private static $fieldNames = array (
		BasePeer::TYPE_PHPNAME => array ('Id', 'Docid', 'Current', 'Culture', 'Body', 'Subject', 'ChangelogComment', 'ReadCounter', 'EditCounter', 'DuplicateTagsForPropelSearch', 'LockedByUserId', 'LockedAt', 'CreatorUserId', 'CreatedAt', 'UpdatorUserId', 'UpdatedAt', ),
		BasePeer::TYPE_COLNAME => array (UllWikiPeer::ID, UllWikiPeer::DOCID, UllWikiPeer::CURRENT, UllWikiPeer::CULTURE, UllWikiPeer::BODY, UllWikiPeer::SUBJECT, UllWikiPeer::CHANGELOG_COMMENT, UllWikiPeer::READ_COUNTER, UllWikiPeer::EDIT_COUNTER, UllWikiPeer::DUPLICATE_TAGS_FOR_PROPEL_SEARCH, UllWikiPeer::LOCKED_BY_USER_ID, UllWikiPeer::LOCKED_AT, UllWikiPeer::CREATOR_USER_ID, UllWikiPeer::CREATED_AT, UllWikiPeer::UPDATOR_USER_ID, UllWikiPeer::UPDATED_AT, ),
		BasePeer::TYPE_FIELDNAME => array ('id', 'docid', 'current', 'culture', 'body', 'subject', 'changelog_comment', 'read_counter', 'edit_counter', 'duplicate_tags_for_propel_search', 'locked_by_user_id', 'locked_at', 'creator_user_id', 'created_at', 'updator_user_id', 'updated_at', ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, )
	);

	/**
	 * holds an array of keys for quick access to the fieldnames array
	 *
	 * first dimension keys are the type constants
	 * e.g. self::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
	 */
	private static $fieldKeys = array (
		BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'Docid' => 1, 'Current' => 2, 'Culture' => 3, 'Body' => 4, 'Subject' => 5, 'ChangelogComment' => 6, 'ReadCounter' => 7, 'EditCounter' => 8, 'DuplicateTagsForPropelSearch' => 9, 'LockedByUserId' => 10, 'LockedAt' => 11, 'CreatorUserId' => 12, 'CreatedAt' => 13, 'UpdatorUserId' => 14, 'UpdatedAt' => 15, ),
		BasePeer::TYPE_COLNAME => array (UllWikiPeer::ID => 0, UllWikiPeer::DOCID => 1, UllWikiPeer::CURRENT => 2, UllWikiPeer::CULTURE => 3, UllWikiPeer::BODY => 4, UllWikiPeer::SUBJECT => 5, UllWikiPeer::CHANGELOG_COMMENT => 6, UllWikiPeer::READ_COUNTER => 7, UllWikiPeer::EDIT_COUNTER => 8, UllWikiPeer::DUPLICATE_TAGS_FOR_PROPEL_SEARCH => 9, UllWikiPeer::LOCKED_BY_USER_ID => 10, UllWikiPeer::LOCKED_AT => 11, UllWikiPeer::CREATOR_USER_ID => 12, UllWikiPeer::CREATED_AT => 13, UllWikiPeer::UPDATOR_USER_ID => 14, UllWikiPeer::UPDATED_AT => 15, ),
		BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'docid' => 1, 'current' => 2, 'culture' => 3, 'body' => 4, 'subject' => 5, 'changelog_comment' => 6, 'read_counter' => 7, 'edit_counter' => 8, 'duplicate_tags_for_propel_search' => 9, 'locked_by_user_id' => 10, 'locked_at' => 11, 'creator_user_id' => 12, 'created_at' => 13, 'updator_user_id' => 14, 'updated_at' => 15, ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, )
	);

	/**
	 * @return     MapBuilder the map builder for this peer
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function getMapBuilder()
	{
		include_once 'plugins/ullWikiPlugin/lib/model/map/UllWikiMapBuilder.php';
		return BasePeer::getMapBuilder('plugins.ullWikiPlugin.lib.model.map.UllWikiMapBuilder');
	}
	/**
	 * Gets a map (hash) of PHP names to DB column names.
	 *
	 * @return     array The PHP to DB name map for this peer
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 * @deprecated Use the getFieldNames() and translateFieldName() methods instead of this.
	 */
	public static function getPhpNameMap()
	{
		if (self::$phpNameMap === null) {
			$map = UllWikiPeer::getTableMap();
			$columns = $map->getColumns();
			$nameMap = array();
			foreach ($columns as $column) {
				$nameMap[$column->getPhpName()] = $column->getColumnName();
			}
			self::$phpNameMap = $nameMap;
		}
		return self::$phpNameMap;
	}
	/**
	 * Translates a fieldname to another type
	 *
	 * @param      string $name field name
	 * @param      string $fromType One of the class type constants TYPE_PHPNAME,
	 *                         TYPE_COLNAME, TYPE_FIELDNAME, TYPE_NUM
	 * @param      string $toType   One of the class type constants
	 * @return     string translated name of the field.
	 */
	static public function translateFieldName($name, $fromType, $toType)
	{
		$toNames = self::getFieldNames($toType);
		$key = isset(self::$fieldKeys[$fromType][$name]) ? self::$fieldKeys[$fromType][$name] : null;
		if ($key === null) {
			throw new PropelException("'$name' could not be found in the field names of type '$fromType'. These are: " . print_r(self::$fieldKeys[$fromType], true));
		}
		return $toNames[$key];
	}

	/**
	 * Returns an array of of field names.
	 *
	 * @param      string $type The type of fieldnames to return:
	 *                      One of the class type constants TYPE_PHPNAME,
	 *                      TYPE_COLNAME, TYPE_FIELDNAME, TYPE_NUM
	 * @return     array A list of field names
	 */

	static public function getFieldNames($type = BasePeer::TYPE_PHPNAME)
	{
		if (!array_key_exists($type, self::$fieldNames)) {
			throw new PropelException('Method getFieldNames() expects the parameter $type to be one of the class constants TYPE_PHPNAME, TYPE_COLNAME, TYPE_FIELDNAME, TYPE_NUM. ' . $type . ' was given.');
		}
		return self::$fieldNames[$type];
	}

	/**
	 * Convenience method which changes table.column to alias.column.
	 *
	 * Using this method you can maintain SQL abstraction while using column aliases.
	 * <code>
	 *		$c->addAlias("alias1", TablePeer::TABLE_NAME);
	 *		$c->addJoin(TablePeer::alias("alias1", TablePeer::PRIMARY_KEY_COLUMN), TablePeer::PRIMARY_KEY_COLUMN);
	 * </code>
	 * @param      string $alias The alias for the current table.
	 * @param      string $column The column name for current table. (i.e. UllWikiPeer::COLUMN_NAME).
	 * @return     string
	 */
	public static function alias($alias, $column)
	{
		return str_replace(UllWikiPeer::TABLE_NAME.'.', $alias.'.', $column);
	}

	/**
	 * Add all the columns needed to create a new object.
	 *
	 * Note: any columns that were marked with lazyLoad="true" in the
	 * XML schema will not be added to the select list and only loaded
	 * on demand.
	 *
	 * @param      criteria object containing the columns to add.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function addSelectColumns(Criteria $criteria)
	{

		$criteria->addSelectColumn(UllWikiPeer::ID);

		$criteria->addSelectColumn(UllWikiPeer::DOCID);

		$criteria->addSelectColumn(UllWikiPeer::CURRENT);

		$criteria->addSelectColumn(UllWikiPeer::CULTURE);

		$criteria->addSelectColumn(UllWikiPeer::BODY);

		$criteria->addSelectColumn(UllWikiPeer::SUBJECT);

		$criteria->addSelectColumn(UllWikiPeer::CHANGELOG_COMMENT);

		$criteria->addSelectColumn(UllWikiPeer::READ_COUNTER);

		$criteria->addSelectColumn(UllWikiPeer::EDIT_COUNTER);

		$criteria->addSelectColumn(UllWikiPeer::DUPLICATE_TAGS_FOR_PROPEL_SEARCH);

		$criteria->addSelectColumn(UllWikiPeer::LOCKED_BY_USER_ID);

		$criteria->addSelectColumn(UllWikiPeer::LOCKED_AT);

		$criteria->addSelectColumn(UllWikiPeer::CREATOR_USER_ID);

		$criteria->addSelectColumn(UllWikiPeer::CREATED_AT);

		$criteria->addSelectColumn(UllWikiPeer::UPDATOR_USER_ID);

		$criteria->addSelectColumn(UllWikiPeer::UPDATED_AT);

	}

	const COUNT = 'COUNT(ull_wiki.ID)';
	const COUNT_DISTINCT = 'COUNT(DISTINCT ull_wiki.ID)';

	/**
	 * Returns the number of rows matching criteria.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct Whether to select only distinct columns (You can also set DISTINCT modifier in Criteria).
	 * @param      Connection $con
	 * @return     int Number of matching rows.
	 */
	public static function doCount(Criteria $criteria, $distinct = false, $con = null)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// clear out anything that might confuse the ORDER BY clause
		$criteria->clearSelectColumns()->clearOrderByColumns();
		if ($distinct || in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->addSelectColumn(UllWikiPeer::COUNT_DISTINCT);
		} else {
			$criteria->addSelectColumn(UllWikiPeer::COUNT);
		}

		// just in case we're grouping: add those columns to the select statement
		foreach($criteria->getGroupByColumns() as $column)
		{
			$criteria->addSelectColumn($column);
		}

		$rs = UllWikiPeer::doSelectRS($criteria, $con);
		if ($rs->next()) {
			return $rs->getInt(1);
		} else {
			// no rows returned; we infer that means 0 matches.
			return 0;
		}
	}
	/**
	 * Method to select one object from the DB.
	 *
	 * @param      Criteria $criteria object used to create the SELECT statement.
	 * @param      Connection $con
	 * @return     UllWiki
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectOne(Criteria $criteria, $con = null)
	{
		$critcopy = clone $criteria;
		$critcopy->setLimit(1);
		$objects = UllWikiPeer::doSelect($critcopy, $con);
		if ($objects) {
			return $objects[0];
		}
		return null;
	}
	/**
	 * Method to do selects.
	 *
	 * @param      Criteria $criteria The Criteria object used to build the SELECT statement.
	 * @param      Connection $con
	 * @return     array Array of selected Objects
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelect(Criteria $criteria, $con = null)
	{
		return UllWikiPeer::populateObjects(UllWikiPeer::doSelectRS($criteria, $con));
	}
	/**
	 * Prepares the Criteria object and uses the parent doSelect()
	 * method to get a ResultSet.
	 *
	 * Use this method directly if you want to just get the resultset
	 * (instead of an array of objects).
	 *
	 * @param      Criteria $criteria The Criteria object used to build the SELECT statement.
	 * @param      Connection $con the connection to use
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 * @return     ResultSet The resultset object with numerically-indexed fields.
	 * @see        BasePeer::doSelect()
	 */
	public static function doSelectRS(Criteria $criteria, $con = null)
	{

    foreach (sfMixer::getCallables('BaseUllWikiPeer:addDoSelectRS:addDoSelectRS') as $callable)
    {
      call_user_func($callable, 'BaseUllWikiPeer', $criteria, $con);
    }


		if ($con === null) {
			$con = Propel::getConnection(self::DATABASE_NAME);
		}

		if (!$criteria->getSelectColumns()) {
			$criteria = clone $criteria;
			UllWikiPeer::addSelectColumns($criteria);
		}

		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		// BasePeer returns a Creole ResultSet, set to return
		// rows indexed numerically.
		return BasePeer::doSelect($criteria, $con);
	}
	/**
	 * The returned array will contain objects of the default type or
	 * objects that inherit from the default.
	 *
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function populateObjects(ResultSet $rs)
	{
		$results = array();
	
		// set the class once to avoid overhead in the loop
		$cls = UllWikiPeer::getOMClass();
		$cls = Propel::import($cls);
		// populate the object(s)
		while($rs->next()) {
		
			$obj = new $cls();
			$obj->hydrate($rs);
			$results[] = $obj;
			
		}
		return $results;
	}
	/**
	 * Returns the TableMap related to this peer.
	 * This method is not needed for general use but a specific application could have a need.
	 * @return     TableMap
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function getTableMap()
	{
		return Propel::getDatabaseMap(self::DATABASE_NAME)->getTable(self::TABLE_NAME);
	}

	/**
	 * The class that the Peer will make instances of.
	 *
	 * This uses a dot-path notation which is tranalted into a path
	 * relative to a location on the PHP include_path.
	 * (e.g. path.to.MyClass -> 'path/to/MyClass.php')
	 *
	 * @return     string path.to.ClassName
	 */
	public static function getOMClass()
	{
		return UllWikiPeer::CLASS_DEFAULT;
	}

	/**
	 * Method perform an INSERT on the database, given a UllWiki or Criteria object.
	 *
	 * @param      mixed $values Criteria or UllWiki object containing data that is used to create the INSERT statement.
	 * @param      Connection $con the connection to use
	 * @return     mixed The new primary key.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doInsert($values, $con = null)
	{

    foreach (sfMixer::getCallables('BaseUllWikiPeer:doInsert:pre') as $callable)
    {
      $ret = call_user_func($callable, 'BaseUllWikiPeer', $values, $con);
      if (false !== $ret)
      {
        return $ret;
      }
    }


		if ($con === null) {
			$con = Propel::getConnection(self::DATABASE_NAME);
		}

		if ($values instanceof Criteria) {
			$criteria = clone $values; // rename for clarity
		} else {
			$criteria = $values->buildCriteria(); // build Criteria from UllWiki object
		}

		$criteria->remove(UllWikiPeer::ID); // remove pkey col since this table uses auto-increment


		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		try {
			// use transaction because $criteria could contain info
			// for more than one table (I guess, conceivably)
			$con->begin();
			$pk = BasePeer::doInsert($criteria, $con);
			$con->commit();
		} catch(PropelException $e) {
			$con->rollback();
			throw $e;
		}

		
    foreach (sfMixer::getCallables('BaseUllWikiPeer:doInsert:post') as $callable)
    {
      call_user_func($callable, 'BaseUllWikiPeer', $values, $con, $pk);
    }

    return $pk;
	}

	/**
	 * Method perform an UPDATE on the database, given a UllWiki or Criteria object.
	 *
	 * @param      mixed $values Criteria or UllWiki object containing data that is used to create the UPDATE statement.
	 * @param      Connection $con The connection to use (specify Connection object to exert more control over transactions).
	 * @return     int The number of affected rows (if supported by underlying database driver).
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doUpdate($values, $con = null)
	{

    foreach (sfMixer::getCallables('BaseUllWikiPeer:doUpdate:pre') as $callable)
    {
      $ret = call_user_func($callable, 'BaseUllWikiPeer', $values, $con);
      if (false !== $ret)
      {
        return $ret;
      }
    }


		if ($con === null) {
			$con = Propel::getConnection(self::DATABASE_NAME);
		}

		$selectCriteria = new Criteria(self::DATABASE_NAME);

		if ($values instanceof Criteria) {
			$criteria = clone $values; // rename for clarity

			$comparison = $criteria->getComparison(UllWikiPeer::ID);
			$selectCriteria->add(UllWikiPeer::ID, $criteria->remove(UllWikiPeer::ID), $comparison);

		} else { // $values is UllWiki object
			$criteria = $values->buildCriteria(); // gets full criteria
			$selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
		}

		// set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		$ret = BasePeer::doUpdate($selectCriteria, $criteria, $con);
	

    foreach (sfMixer::getCallables('BaseUllWikiPeer:doUpdate:post') as $callable)
    {
      call_user_func($callable, 'BaseUllWikiPeer', $values, $con, $ret);
    }

    return $ret;
  }

	/**
	 * Method to DELETE all rows from the ull_wiki table.
	 *
	 * @return     int The number of affected rows (if supported by underlying database driver).
	 */
	public static function doDeleteAll($con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(self::DATABASE_NAME);
		}
		$affectedRows = 0; // initialize var to track total num of affected rows
		try {
			// use transaction because $criteria could contain info
			// for more than one table or we could emulating ON DELETE CASCADE, etc.
			$con->begin();
			$affectedRows += BasePeer::doDeleteAll(UllWikiPeer::TABLE_NAME, $con);
			$con->commit();
			return $affectedRows;
		} catch (PropelException $e) {
			$con->rollback();
			throw $e;
		}
	}

	/**
	 * Method perform a DELETE on the database, given a UllWiki or Criteria object OR a primary key value.
	 *
	 * @param      mixed $values Criteria or UllWiki object or primary key or array of primary keys
	 *              which is used to create the DELETE statement
	 * @param      Connection $con the connection to use
	 * @return     int 	The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
	 *				if supported by native driver or if emulated using Propel.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	 public static function doDelete($values, $con = null)
	 {
		if ($con === null) {
			$con = Propel::getConnection(UllWikiPeer::DATABASE_NAME);
		}

		if ($values instanceof Criteria) {
			$criteria = clone $values; // rename for clarity
		} elseif ($values instanceof UllWiki) {

			$criteria = $values->buildPkeyCriteria();
		} else {
			// it must be the primary key
			$criteria = new Criteria(self::DATABASE_NAME);
			$criteria->add(UllWikiPeer::ID, (array) $values, Criteria::IN);
		}

		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		$affectedRows = 0; // initialize var to track total num of affected rows

		try {
			// use transaction because $criteria could contain info
			// for more than one table or we could emulating ON DELETE CASCADE, etc.
			$con->begin();
			
			$affectedRows += BasePeer::doDelete($criteria, $con);
			$con->commit();
			return $affectedRows;
		} catch (PropelException $e) {
			$con->rollback();
			throw $e;
		}
	}

	/**
	 * Validates all modified columns of given UllWiki object.
	 * If parameter $columns is either a single column name or an array of column names
	 * than only those columns are validated.
	 *
	 * NOTICE: This does not apply to primary or foreign keys for now.
	 *
	 * @param      UllWiki $obj The object to validate.
	 * @param      mixed $cols Column name or array of column names.
	 *
	 * @return     mixed TRUE if all columns are valid or the error message of the first invalid column.
	 */
	public static function doValidate(UllWiki $obj, $cols = null)
	{
		$columns = array();

		if ($cols) {
			$dbMap = Propel::getDatabaseMap(UllWikiPeer::DATABASE_NAME);
			$tableMap = $dbMap->getTable(UllWikiPeer::TABLE_NAME);

			if (! is_array($cols)) {
				$cols = array($cols);
			}

			foreach($cols as $colName) {
				if ($tableMap->containsColumn($colName)) {
					$get = 'get' . $tableMap->getColumn($colName)->getPhpName();
					$columns[$colName] = $obj->$get();
				}
			}
		} else {

		}

		$res =  BasePeer::doValidate(UllWikiPeer::DATABASE_NAME, UllWikiPeer::TABLE_NAME, $columns);
    if ($res !== true) {
        $request = sfContext::getInstance()->getRequest();
        foreach ($res as $failed) {
            $col = UllWikiPeer::translateFieldname($failed->getColumn(), BasePeer::TYPE_COLNAME, BasePeer::TYPE_PHPNAME);
            $request->setError($col, $failed->getMessage());
        }
    }

    return $res;
	}

	/**
	 * Retrieve a single object by pkey.
	 *
	 * @param      mixed $pk the primary key.
	 * @param      Connection $con the connection to use
	 * @return     UllWiki
	 */
	public static function retrieveByPK($pk, $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(self::DATABASE_NAME);
		}

		$criteria = new Criteria(UllWikiPeer::DATABASE_NAME);

		$criteria->add(UllWikiPeer::ID, $pk);


		$v = UllWikiPeer::doSelect($criteria, $con);

		return !empty($v) > 0 ? $v[0] : null;
	}

	/**
	 * Retrieve multiple objects by pkey.
	 *
	 * @param      array $pks List of primary keys
	 * @param      Connection $con the connection to use
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function retrieveByPKs($pks, $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(self::DATABASE_NAME);
		}

		$objs = null;
		if (empty($pks)) {
			$objs = array();
		} else {
			$criteria = new Criteria();
			$criteria->add(UllWikiPeer::ID, $pks, Criteria::IN);
			$objs = UllWikiPeer::doSelect($criteria, $con);
		}
		return $objs;
	}

} // BaseUllWikiPeer

// static code to register the map builder for this Peer with the main Propel class
if (Propel::isInit()) {
	// the MapBuilder classes register themselves with Propel during initialization
	// so we need to load them here.
	try {
		BaseUllWikiPeer::getMapBuilder();
	} catch (Exception $e) {
		Propel::log('Could not initialize Peer: ' . $e->getMessage(), Propel::LOG_ERR);
	}
} else {
	// even if Propel is not yet initialized, the map builder class can be registered
	// now and then it will be loaded when Propel initializes.
	require_once 'plugins/ullWikiPlugin/lib/model/map/UllWikiMapBuilder.php';
	Propel::registerMapBuilder('plugins.ullWikiPlugin.lib.model.map.UllWikiMapBuilder');
}
