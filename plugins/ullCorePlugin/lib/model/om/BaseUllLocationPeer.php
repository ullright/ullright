<?php

/**
 * Base static class for performing query and update operations on the 'ull_location' table.
 *
 * 
 *
 * @package    plugins.ullCorePlugin.lib.model.om
 */
abstract class BaseUllLocationPeer {

	/** the default database name for this class */
	const DATABASE_NAME = 'propel';

	/** the table name for this class */
	const TABLE_NAME = 'ull_location';

	/** A class that can be returned by this peer. */
	const CLASS_DEFAULT = 'plugins.ullCorePlugin.lib.model.UllLocation';

	/** The total number of columns. */
	const NUM_COLUMNS = 15;

	/** The number of lazy-loaded columns. */
	const NUM_LAZY_LOAD_COLUMNS = 0;


	/** the column name for the ID field */
	const ID = 'ull_location.ID';

	/** the column name for the NAME field */
	const NAME = 'ull_location.NAME';

	/** the column name for the SHORT field */
	const SHORT = 'ull_location.SHORT';

	/** the column name for the COMPANY_ID field */
	const COMPANY_ID = 'ull_location.COMPANY_ID';

	/** the column name for the STREET field */
	const STREET = 'ull_location.STREET';

	/** the column name for the ZIP field */
	const ZIP = 'ull_location.ZIP';

	/** the column name for the CITY field */
	const CITY = 'ull_location.CITY';

	/** the column name for the COUNTRY_ID field */
	const COUNTRY_ID = 'ull_location.COUNTRY_ID';

	/** the column name for the PHONE_TRUNK_NUM field */
	const PHONE_TRUNK_NUM = 'ull_location.PHONE_TRUNK_NUM';

	/** the column name for the PHONE_STD_EXT_NUM field */
	const PHONE_STD_EXT_NUM = 'ull_location.PHONE_STD_EXT_NUM';

	/** the column name for the FAX_TRUNK_NUM field */
	const FAX_TRUNK_NUM = 'ull_location.FAX_TRUNK_NUM';

	/** the column name for the FAX_STD_EXT_NUM field */
	const FAX_STD_EXT_NUM = 'ull_location.FAX_STD_EXT_NUM';

	/** the column name for the CREATOR_USER_ID field */
	const CREATOR_USER_ID = 'ull_location.CREATOR_USER_ID';

	/** the column name for the CREATED_AT field */
	const CREATED_AT = 'ull_location.CREATED_AT';

	/** the column name for the UPDATOR_USER_ID field */
	const UPDATOR_USER_ID = 'ull_location.UPDATOR_USER_ID';

	/** The PHP to DB Name Mapping */
	private static $phpNameMap = null;


	/**
	 * holds an array of fieldnames
	 *
	 * first dimension keys are the type constants
	 * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
	 */
	private static $fieldNames = array (
		BasePeer::TYPE_PHPNAME => array ('Id', 'Name', 'Short', 'CompanyId', 'Street', 'Zip', 'City', 'CountryId', 'PhoneTrunkNum', 'PhoneStdExtNum', 'FaxTrunkNum', 'FaxStdExtNum', 'CreatorUserId', 'CreatedAt', 'UpdatorUserId', ),
		BasePeer::TYPE_COLNAME => array (UllLocationPeer::ID, UllLocationPeer::NAME, UllLocationPeer::SHORT, UllLocationPeer::COMPANY_ID, UllLocationPeer::STREET, UllLocationPeer::ZIP, UllLocationPeer::CITY, UllLocationPeer::COUNTRY_ID, UllLocationPeer::PHONE_TRUNK_NUM, UllLocationPeer::PHONE_STD_EXT_NUM, UllLocationPeer::FAX_TRUNK_NUM, UllLocationPeer::FAX_STD_EXT_NUM, UllLocationPeer::CREATOR_USER_ID, UllLocationPeer::CREATED_AT, UllLocationPeer::UPDATOR_USER_ID, ),
		BasePeer::TYPE_FIELDNAME => array ('id', 'name', 'short', 'company_id', 'street', 'zip', 'city', 'country_id', 'phone_trunk_num', 'phone_std_ext_num', 'fax_trunk_num', 'fax_std_ext_num', 'creator_user_id', 'created_at', 'updator_user_id', ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, )
	);

	/**
	 * holds an array of keys for quick access to the fieldnames array
	 *
	 * first dimension keys are the type constants
	 * e.g. self::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
	 */
	private static $fieldKeys = array (
		BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'Name' => 1, 'Short' => 2, 'CompanyId' => 3, 'Street' => 4, 'Zip' => 5, 'City' => 6, 'CountryId' => 7, 'PhoneTrunkNum' => 8, 'PhoneStdExtNum' => 9, 'FaxTrunkNum' => 10, 'FaxStdExtNum' => 11, 'CreatorUserId' => 12, 'CreatedAt' => 13, 'UpdatorUserId' => 14, ),
		BasePeer::TYPE_COLNAME => array (UllLocationPeer::ID => 0, UllLocationPeer::NAME => 1, UllLocationPeer::SHORT => 2, UllLocationPeer::COMPANY_ID => 3, UllLocationPeer::STREET => 4, UllLocationPeer::ZIP => 5, UllLocationPeer::CITY => 6, UllLocationPeer::COUNTRY_ID => 7, UllLocationPeer::PHONE_TRUNK_NUM => 8, UllLocationPeer::PHONE_STD_EXT_NUM => 9, UllLocationPeer::FAX_TRUNK_NUM => 10, UllLocationPeer::FAX_STD_EXT_NUM => 11, UllLocationPeer::CREATOR_USER_ID => 12, UllLocationPeer::CREATED_AT => 13, UllLocationPeer::UPDATOR_USER_ID => 14, ),
		BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'name' => 1, 'short' => 2, 'company_id' => 3, 'street' => 4, 'zip' => 5, 'city' => 6, 'country_id' => 7, 'phone_trunk_num' => 8, 'phone_std_ext_num' => 9, 'fax_trunk_num' => 10, 'fax_std_ext_num' => 11, 'creator_user_id' => 12, 'created_at' => 13, 'updator_user_id' => 14, ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, )
	);

	/**
	 * @return     MapBuilder the map builder for this peer
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function getMapBuilder()
	{
		include_once 'plugins/ullCorePlugin/lib/model/map/UllLocationMapBuilder.php';
		return BasePeer::getMapBuilder('plugins.ullCorePlugin.lib.model.map.UllLocationMapBuilder');
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
			$map = UllLocationPeer::getTableMap();
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
	 * @param      string $column The column name for current table. (i.e. UllLocationPeer::COLUMN_NAME).
	 * @return     string
	 */
	public static function alias($alias, $column)
	{
		return str_replace(UllLocationPeer::TABLE_NAME.'.', $alias.'.', $column);
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

		$criteria->addSelectColumn(UllLocationPeer::ID);

		$criteria->addSelectColumn(UllLocationPeer::NAME);

		$criteria->addSelectColumn(UllLocationPeer::SHORT);

		$criteria->addSelectColumn(UllLocationPeer::COMPANY_ID);

		$criteria->addSelectColumn(UllLocationPeer::STREET);

		$criteria->addSelectColumn(UllLocationPeer::ZIP);

		$criteria->addSelectColumn(UllLocationPeer::CITY);

		$criteria->addSelectColumn(UllLocationPeer::COUNTRY_ID);

		$criteria->addSelectColumn(UllLocationPeer::PHONE_TRUNK_NUM);

		$criteria->addSelectColumn(UllLocationPeer::PHONE_STD_EXT_NUM);

		$criteria->addSelectColumn(UllLocationPeer::FAX_TRUNK_NUM);

		$criteria->addSelectColumn(UllLocationPeer::FAX_STD_EXT_NUM);

		$criteria->addSelectColumn(UllLocationPeer::CREATOR_USER_ID);

		$criteria->addSelectColumn(UllLocationPeer::CREATED_AT);

		$criteria->addSelectColumn(UllLocationPeer::UPDATOR_USER_ID);

	}

	const COUNT = 'COUNT(ull_location.ID)';
	const COUNT_DISTINCT = 'COUNT(DISTINCT ull_location.ID)';

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
			$criteria->addSelectColumn(UllLocationPeer::COUNT_DISTINCT);
		} else {
			$criteria->addSelectColumn(UllLocationPeer::COUNT);
		}

		// just in case we're grouping: add those columns to the select statement
		foreach($criteria->getGroupByColumns() as $column)
		{
			$criteria->addSelectColumn($column);
		}

		$rs = UllLocationPeer::doSelectRS($criteria, $con);
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
	 * @return     UllLocation
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectOne(Criteria $criteria, $con = null)
	{
		$critcopy = clone $criteria;
		$critcopy->setLimit(1);
		$objects = UllLocationPeer::doSelect($critcopy, $con);
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
		return UllLocationPeer::populateObjects(UllLocationPeer::doSelectRS($criteria, $con));
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

    foreach (sfMixer::getCallables('BaseUllLocationPeer:addDoSelectRS:addDoSelectRS') as $callable)
    {
      call_user_func($callable, 'BaseUllLocationPeer', $criteria, $con);
    }


		if ($con === null) {
			$con = Propel::getConnection(self::DATABASE_NAME);
		}

		if (!$criteria->getSelectColumns()) {
			$criteria = clone $criteria;
			UllLocationPeer::addSelectColumns($criteria);
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
		$cls = UllLocationPeer::getOMClass();
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
		return UllLocationPeer::CLASS_DEFAULT;
	}

	/**
	 * Method perform an INSERT on the database, given a UllLocation or Criteria object.
	 *
	 * @param      mixed $values Criteria or UllLocation object containing data that is used to create the INSERT statement.
	 * @param      Connection $con the connection to use
	 * @return     mixed The new primary key.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doInsert($values, $con = null)
	{

    foreach (sfMixer::getCallables('BaseUllLocationPeer:doInsert:pre') as $callable)
    {
      $ret = call_user_func($callable, 'BaseUllLocationPeer', $values, $con);
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
			$criteria = $values->buildCriteria(); // build Criteria from UllLocation object
		}

		$criteria->remove(UllLocationPeer::ID); // remove pkey col since this table uses auto-increment


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

		
    foreach (sfMixer::getCallables('BaseUllLocationPeer:doInsert:post') as $callable)
    {
      call_user_func($callable, 'BaseUllLocationPeer', $values, $con, $pk);
    }

    return $pk;
	}

	/**
	 * Method perform an UPDATE on the database, given a UllLocation or Criteria object.
	 *
	 * @param      mixed $values Criteria or UllLocation object containing data that is used to create the UPDATE statement.
	 * @param      Connection $con The connection to use (specify Connection object to exert more control over transactions).
	 * @return     int The number of affected rows (if supported by underlying database driver).
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doUpdate($values, $con = null)
	{

    foreach (sfMixer::getCallables('BaseUllLocationPeer:doUpdate:pre') as $callable)
    {
      $ret = call_user_func($callable, 'BaseUllLocationPeer', $values, $con);
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

			$comparison = $criteria->getComparison(UllLocationPeer::ID);
			$selectCriteria->add(UllLocationPeer::ID, $criteria->remove(UllLocationPeer::ID), $comparison);

		} else { // $values is UllLocation object
			$criteria = $values->buildCriteria(); // gets full criteria
			$selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
		}

		// set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		$ret = BasePeer::doUpdate($selectCriteria, $criteria, $con);
	

    foreach (sfMixer::getCallables('BaseUllLocationPeer:doUpdate:post') as $callable)
    {
      call_user_func($callable, 'BaseUllLocationPeer', $values, $con, $ret);
    }

    return $ret;
  }

	/**
	 * Method to DELETE all rows from the ull_location table.
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
			$affectedRows += BasePeer::doDeleteAll(UllLocationPeer::TABLE_NAME, $con);
			$con->commit();
			return $affectedRows;
		} catch (PropelException $e) {
			$con->rollback();
			throw $e;
		}
	}

	/**
	 * Method perform a DELETE on the database, given a UllLocation or Criteria object OR a primary key value.
	 *
	 * @param      mixed $values Criteria or UllLocation object or primary key or array of primary keys
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
			$con = Propel::getConnection(UllLocationPeer::DATABASE_NAME);
		}

		if ($values instanceof Criteria) {
			$criteria = clone $values; // rename for clarity
		} elseif ($values instanceof UllLocation) {

			$criteria = $values->buildPkeyCriteria();
		} else {
			// it must be the primary key
			$criteria = new Criteria(self::DATABASE_NAME);
			$criteria->add(UllLocationPeer::ID, (array) $values, Criteria::IN);
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
	 * Validates all modified columns of given UllLocation object.
	 * If parameter $columns is either a single column name or an array of column names
	 * than only those columns are validated.
	 *
	 * NOTICE: This does not apply to primary or foreign keys for now.
	 *
	 * @param      UllLocation $obj The object to validate.
	 * @param      mixed $cols Column name or array of column names.
	 *
	 * @return     mixed TRUE if all columns are valid or the error message of the first invalid column.
	 */
	public static function doValidate(UllLocation $obj, $cols = null)
	{
		$columns = array();

		if ($cols) {
			$dbMap = Propel::getDatabaseMap(UllLocationPeer::DATABASE_NAME);
			$tableMap = $dbMap->getTable(UllLocationPeer::TABLE_NAME);

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

		$res =  BasePeer::doValidate(UllLocationPeer::DATABASE_NAME, UllLocationPeer::TABLE_NAME, $columns);
    if ($res !== true) {
        $request = sfContext::getInstance()->getRequest();
        foreach ($res as $failed) {
            $col = UllLocationPeer::translateFieldname($failed->getColumn(), BasePeer::TYPE_COLNAME, BasePeer::TYPE_PHPNAME);
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
	 * @return     UllLocation
	 */
	public static function retrieveByPK($pk, $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(self::DATABASE_NAME);
		}

		$criteria = new Criteria(UllLocationPeer::DATABASE_NAME);

		$criteria->add(UllLocationPeer::ID, $pk);


		$v = UllLocationPeer::doSelect($criteria, $con);

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
			$criteria->add(UllLocationPeer::ID, $pks, Criteria::IN);
			$objs = UllLocationPeer::doSelect($criteria, $con);
		}
		return $objs;
	}

} // BaseUllLocationPeer

// static code to register the map builder for this Peer with the main Propel class
if (Propel::isInit()) {
	// the MapBuilder classes register themselves with Propel during initialization
	// so we need to load them here.
	try {
		BaseUllLocationPeer::getMapBuilder();
	} catch (Exception $e) {
		Propel::log('Could not initialize Peer: ' . $e->getMessage(), Propel::LOG_ERR);
	}
} else {
	// even if Propel is not yet initialized, the map builder class can be registered
	// now and then it will be loaded when Propel initializes.
	require_once 'plugins/ullCorePlugin/lib/model/map/UllLocationMapBuilder.php';
	Propel::registerMapBuilder('plugins.ullCorePlugin.lib.model.map.UllLocationMapBuilder');
}
