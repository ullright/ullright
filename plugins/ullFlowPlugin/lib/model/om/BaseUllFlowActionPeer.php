<?php

/**
 * Base static class for performing query and update operations on the 'ull_flow_action' table.
 *
 * 
 *
 * @package    plugins.ullFlowPlugin.lib.model.om
 */
abstract class BaseUllFlowActionPeer {

	/** the default database name for this class */
	const DATABASE_NAME = 'propel';

	/** the table name for this class */
	const TABLE_NAME = 'ull_flow_action';

	/** A class that can be returned by this peer. */
	const CLASS_DEFAULT = 'plugins.ullFlowPlugin.lib.model.UllFlowAction';

	/** The total number of columns. */
	const NUM_COLUMNS = 14;

	/** The number of lazy-loaded columns. */
	const NUM_LAZY_LOAD_COLUMNS = 0;


	/** the column name for the ID field */
	const ID = 'ull_flow_action.ID';

	/** the column name for the SLUG field */
	const SLUG = 'ull_flow_action.SLUG';

	/** the column name for the CAPTION_I18N_DEFAULT field */
	const CAPTION_I18N_DEFAULT = 'ull_flow_action.CAPTION_I18N_DEFAULT';

	/** the column name for the STATUS_ONLY field */
	const STATUS_ONLY = 'ull_flow_action.STATUS_ONLY';

	/** the column name for the DISABLE_VALIDATION field */
	const DISABLE_VALIDATION = 'ull_flow_action.DISABLE_VALIDATION';

	/** the column name for the NOTIFY_CREATOR field */
	const NOTIFY_CREATOR = 'ull_flow_action.NOTIFY_CREATOR';

	/** the column name for the NOTIFY_NEXT field */
	const NOTIFY_NEXT = 'ull_flow_action.NOTIFY_NEXT';

	/** the column name for the IN_RESULTLIST_BY_DEFAULT field */
	const IN_RESULTLIST_BY_DEFAULT = 'ull_flow_action.IN_RESULTLIST_BY_DEFAULT';

	/** the column name for the SHOW_ASSIGNED_TO field */
	const SHOW_ASSIGNED_TO = 'ull_flow_action.SHOW_ASSIGNED_TO';

	/** the column name for the COMMENT_IS_MANDATORY field */
	const COMMENT_IS_MANDATORY = 'ull_flow_action.COMMENT_IS_MANDATORY';

	/** the column name for the CREATOR_USER_ID field */
	const CREATOR_USER_ID = 'ull_flow_action.CREATOR_USER_ID';

	/** the column name for the CREATED_AT field */
	const CREATED_AT = 'ull_flow_action.CREATED_AT';

	/** the column name for the UPDATOR_USER_ID field */
	const UPDATOR_USER_ID = 'ull_flow_action.UPDATOR_USER_ID';

	/** the column name for the UPDATED_AT field */
	const UPDATED_AT = 'ull_flow_action.UPDATED_AT';

	/** The PHP to DB Name Mapping */
	private static $phpNameMap = null;


	/**
	 * holds an array of fieldnames
	 *
	 * first dimension keys are the type constants
	 * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
	 */
	private static $fieldNames = array (
		BasePeer::TYPE_PHPNAME => array ('Id', 'Slug', 'CaptionI18nDefault', 'StatusOnly', 'DisableValidation', 'NotifyCreator', 'NotifyNext', 'InResultlistByDefault', 'ShowAssignedTo', 'CommentIsMandatory', 'CreatorUserId', 'CreatedAt', 'UpdatorUserId', 'UpdatedAt', ),
		BasePeer::TYPE_COLNAME => array (UllFlowActionPeer::ID, UllFlowActionPeer::SLUG, UllFlowActionPeer::CAPTION_I18N_DEFAULT, UllFlowActionPeer::STATUS_ONLY, UllFlowActionPeer::DISABLE_VALIDATION, UllFlowActionPeer::NOTIFY_CREATOR, UllFlowActionPeer::NOTIFY_NEXT, UllFlowActionPeer::IN_RESULTLIST_BY_DEFAULT, UllFlowActionPeer::SHOW_ASSIGNED_TO, UllFlowActionPeer::COMMENT_IS_MANDATORY, UllFlowActionPeer::CREATOR_USER_ID, UllFlowActionPeer::CREATED_AT, UllFlowActionPeer::UPDATOR_USER_ID, UllFlowActionPeer::UPDATED_AT, ),
		BasePeer::TYPE_FIELDNAME => array ('id', 'slug', 'caption_i18n_default', 'status_only', 'disable_validation', 'notify_creator', 'notify_next', 'in_resultlist_by_default', 'show_assigned_to', 'comment_is_mandatory', 'creator_user_id', 'created_at', 'updator_user_id', 'updated_at', ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, )
	);

	/**
	 * holds an array of keys for quick access to the fieldnames array
	 *
	 * first dimension keys are the type constants
	 * e.g. self::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
	 */
	private static $fieldKeys = array (
		BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'Slug' => 1, 'CaptionI18nDefault' => 2, 'StatusOnly' => 3, 'DisableValidation' => 4, 'NotifyCreator' => 5, 'NotifyNext' => 6, 'InResultlistByDefault' => 7, 'ShowAssignedTo' => 8, 'CommentIsMandatory' => 9, 'CreatorUserId' => 10, 'CreatedAt' => 11, 'UpdatorUserId' => 12, 'UpdatedAt' => 13, ),
		BasePeer::TYPE_COLNAME => array (UllFlowActionPeer::ID => 0, UllFlowActionPeer::SLUG => 1, UllFlowActionPeer::CAPTION_I18N_DEFAULT => 2, UllFlowActionPeer::STATUS_ONLY => 3, UllFlowActionPeer::DISABLE_VALIDATION => 4, UllFlowActionPeer::NOTIFY_CREATOR => 5, UllFlowActionPeer::NOTIFY_NEXT => 6, UllFlowActionPeer::IN_RESULTLIST_BY_DEFAULT => 7, UllFlowActionPeer::SHOW_ASSIGNED_TO => 8, UllFlowActionPeer::COMMENT_IS_MANDATORY => 9, UllFlowActionPeer::CREATOR_USER_ID => 10, UllFlowActionPeer::CREATED_AT => 11, UllFlowActionPeer::UPDATOR_USER_ID => 12, UllFlowActionPeer::UPDATED_AT => 13, ),
		BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'slug' => 1, 'caption_i18n_default' => 2, 'status_only' => 3, 'disable_validation' => 4, 'notify_creator' => 5, 'notify_next' => 6, 'in_resultlist_by_default' => 7, 'show_assigned_to' => 8, 'comment_is_mandatory' => 9, 'creator_user_id' => 10, 'created_at' => 11, 'updator_user_id' => 12, 'updated_at' => 13, ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, )
	);

	/**
	 * @return     MapBuilder the map builder for this peer
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function getMapBuilder()
	{
		include_once 'plugins/ullFlowPlugin/lib/model/map/UllFlowActionMapBuilder.php';
		return BasePeer::getMapBuilder('plugins.ullFlowPlugin.lib.model.map.UllFlowActionMapBuilder');
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
			$map = UllFlowActionPeer::getTableMap();
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
	 * @param      string $column The column name for current table. (i.e. UllFlowActionPeer::COLUMN_NAME).
	 * @return     string
	 */
	public static function alias($alias, $column)
	{
		return str_replace(UllFlowActionPeer::TABLE_NAME.'.', $alias.'.', $column);
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

		$criteria->addSelectColumn(UllFlowActionPeer::ID);

		$criteria->addSelectColumn(UllFlowActionPeer::SLUG);

		$criteria->addSelectColumn(UllFlowActionPeer::CAPTION_I18N_DEFAULT);

		$criteria->addSelectColumn(UllFlowActionPeer::STATUS_ONLY);

		$criteria->addSelectColumn(UllFlowActionPeer::DISABLE_VALIDATION);

		$criteria->addSelectColumn(UllFlowActionPeer::NOTIFY_CREATOR);

		$criteria->addSelectColumn(UllFlowActionPeer::NOTIFY_NEXT);

		$criteria->addSelectColumn(UllFlowActionPeer::IN_RESULTLIST_BY_DEFAULT);

		$criteria->addSelectColumn(UllFlowActionPeer::SHOW_ASSIGNED_TO);

		$criteria->addSelectColumn(UllFlowActionPeer::COMMENT_IS_MANDATORY);

		$criteria->addSelectColumn(UllFlowActionPeer::CREATOR_USER_ID);

		$criteria->addSelectColumn(UllFlowActionPeer::CREATED_AT);

		$criteria->addSelectColumn(UllFlowActionPeer::UPDATOR_USER_ID);

		$criteria->addSelectColumn(UllFlowActionPeer::UPDATED_AT);

	}

	const COUNT = 'COUNT(ull_flow_action.ID)';
	const COUNT_DISTINCT = 'COUNT(DISTINCT ull_flow_action.ID)';

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
			$criteria->addSelectColumn(UllFlowActionPeer::COUNT_DISTINCT);
		} else {
			$criteria->addSelectColumn(UllFlowActionPeer::COUNT);
		}

		// just in case we're grouping: add those columns to the select statement
		foreach($criteria->getGroupByColumns() as $column)
		{
			$criteria->addSelectColumn($column);
		}

		$rs = UllFlowActionPeer::doSelectRS($criteria, $con);
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
	 * @return     UllFlowAction
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectOne(Criteria $criteria, $con = null)
	{
		$critcopy = clone $criteria;
		$critcopy->setLimit(1);
		$objects = UllFlowActionPeer::doSelect($critcopy, $con);
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
		return UllFlowActionPeer::populateObjects(UllFlowActionPeer::doSelectRS($criteria, $con));
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

    foreach (sfMixer::getCallables('BaseUllFlowActionPeer:addDoSelectRS:addDoSelectRS') as $callable)
    {
      call_user_func($callable, 'BaseUllFlowActionPeer', $criteria, $con);
    }


		if ($con === null) {
			$con = Propel::getConnection(self::DATABASE_NAME);
		}

		if (!$criteria->getSelectColumns()) {
			$criteria = clone $criteria;
			UllFlowActionPeer::addSelectColumns($criteria);
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
		$cls = UllFlowActionPeer::getOMClass();
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
   * Selects a collection of UllFlowAction objects pre-filled with their i18n objects.
   *
   * @return array Array of UllFlowAction objects.
   * @throws PropelException Any exceptions caught during processing will be
   *     rethrown wrapped into a PropelException.
   */
  public static function doSelectWithI18n(Criteria $c, $culture = null, $con = null)
  {
    if ($culture === null)
    {
      $culture = sfContext::getInstance()->getUser()->getCulture();
    }

    // Set the correct dbName if it has not been overridden
    if ($c->getDbName() == Propel::getDefaultDB())
    {
      $c->setDbName(self::DATABASE_NAME);
    }

    UllFlowActionPeer::addSelectColumns($c);
    $startcol = (UllFlowActionPeer::NUM_COLUMNS - UllFlowActionPeer::NUM_LAZY_LOAD_COLUMNS) + 1;

    UllFlowActionI18nPeer::addSelectColumns($c);

    $c->addJoin(UllFlowActionPeer::ID, UllFlowActionI18nPeer::ID);
    $c->add(UllFlowActionI18nPeer::CULTURE, $culture);

    $rs = BasePeer::doSelect($c, $con);
    $results = array();

    while($rs->next()) {

      $omClass = UllFlowActionPeer::getOMClass();

      $cls = Propel::import($omClass);
      $obj1 = new $cls();
      $obj1->hydrate($rs);
      $obj1->setCulture($culture);

      $omClass = UllFlowActionI18nPeer::getOMClass($rs, $startcol);

      $cls = Propel::import($omClass);
      $obj2 = new $cls();
      $obj2->hydrate($rs, $startcol);

      $obj1->setUllFlowActionI18nForCulture($obj2, $culture);
      $obj2->setUllFlowAction($obj1);

      $results[] = $obj1;
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
		return UllFlowActionPeer::CLASS_DEFAULT;
	}

	/**
	 * Method perform an INSERT on the database, given a UllFlowAction or Criteria object.
	 *
	 * @param      mixed $values Criteria or UllFlowAction object containing data that is used to create the INSERT statement.
	 * @param      Connection $con the connection to use
	 * @return     mixed The new primary key.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doInsert($values, $con = null)
	{

    foreach (sfMixer::getCallables('BaseUllFlowActionPeer:doInsert:pre') as $callable)
    {
      $ret = call_user_func($callable, 'BaseUllFlowActionPeer', $values, $con);
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
			$criteria = $values->buildCriteria(); // build Criteria from UllFlowAction object
		}

		$criteria->remove(UllFlowActionPeer::ID); // remove pkey col since this table uses auto-increment


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

		
    foreach (sfMixer::getCallables('BaseUllFlowActionPeer:doInsert:post') as $callable)
    {
      call_user_func($callable, 'BaseUllFlowActionPeer', $values, $con, $pk);
    }

    return $pk;
	}

	/**
	 * Method perform an UPDATE on the database, given a UllFlowAction or Criteria object.
	 *
	 * @param      mixed $values Criteria or UllFlowAction object containing data that is used to create the UPDATE statement.
	 * @param      Connection $con The connection to use (specify Connection object to exert more control over transactions).
	 * @return     int The number of affected rows (if supported by underlying database driver).
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doUpdate($values, $con = null)
	{

    foreach (sfMixer::getCallables('BaseUllFlowActionPeer:doUpdate:pre') as $callable)
    {
      $ret = call_user_func($callable, 'BaseUllFlowActionPeer', $values, $con);
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

			$comparison = $criteria->getComparison(UllFlowActionPeer::ID);
			$selectCriteria->add(UllFlowActionPeer::ID, $criteria->remove(UllFlowActionPeer::ID), $comparison);

		} else { // $values is UllFlowAction object
			$criteria = $values->buildCriteria(); // gets full criteria
			$selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
		}

		// set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		$ret = BasePeer::doUpdate($selectCriteria, $criteria, $con);
	

    foreach (sfMixer::getCallables('BaseUllFlowActionPeer:doUpdate:post') as $callable)
    {
      call_user_func($callable, 'BaseUllFlowActionPeer', $values, $con, $ret);
    }

    return $ret;
  }

	/**
	 * Method to DELETE all rows from the ull_flow_action table.
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
			$affectedRows += UllFlowActionPeer::doOnDeleteCascade(new Criteria(), $con);
			$affectedRows += BasePeer::doDeleteAll(UllFlowActionPeer::TABLE_NAME, $con);
			$con->commit();
			return $affectedRows;
		} catch (PropelException $e) {
			$con->rollback();
			throw $e;
		}
	}

	/**
	 * Method perform a DELETE on the database, given a UllFlowAction or Criteria object OR a primary key value.
	 *
	 * @param      mixed $values Criteria or UllFlowAction object or primary key or array of primary keys
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
			$con = Propel::getConnection(UllFlowActionPeer::DATABASE_NAME);
		}

		if ($values instanceof Criteria) {
			$criteria = clone $values; // rename for clarity
		} elseif ($values instanceof UllFlowAction) {

			$criteria = $values->buildPkeyCriteria();
		} else {
			// it must be the primary key
			$criteria = new Criteria(self::DATABASE_NAME);
			$criteria->add(UllFlowActionPeer::ID, (array) $values, Criteria::IN);
		}

		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		$affectedRows = 0; // initialize var to track total num of affected rows

		try {
			// use transaction because $criteria could contain info
			// for more than one table or we could emulating ON DELETE CASCADE, etc.
			$con->begin();
			$affectedRows += UllFlowActionPeer::doOnDeleteCascade($criteria, $con);
			$affectedRows += BasePeer::doDelete($criteria, $con);
			$con->commit();
			return $affectedRows;
		} catch (PropelException $e) {
			$con->rollback();
			throw $e;
		}
	}

	/**
	 * This is a method for emulating ON DELETE CASCADE for DBs that don't support this
	 * feature (like MySQL or SQLite).
	 *
	 * This method is not very speedy because it must perform a query first to get
	 * the implicated records and then perform the deletes by calling those Peer classes.
	 *
	 * This method should be used within a transaction if possible.
	 *
	 * @param      Criteria $criteria
	 * @param      Connection $con
	 * @return     int The number of affected rows (if supported by underlying database driver).
	 */
	protected static function doOnDeleteCascade(Criteria $criteria, Connection $con)
	{
		// initialize var to track total num of affected rows
		$affectedRows = 0;

		// first find the objects that are implicated by the $criteria
		$objects = UllFlowActionPeer::doSelect($criteria, $con);
		foreach($objects as $obj) {


			include_once 'plugins/ullFlowPlugin/lib/model/UllFlowActionI18n.php';

			// delete related UllFlowActionI18n objects
			$c = new Criteria();
			
			$c->add(UllFlowActionI18nPeer::ID, $obj->getId());
			$affectedRows += UllFlowActionI18nPeer::doDelete($c, $con);
		}
		return $affectedRows;
	}

	/**
	 * Validates all modified columns of given UllFlowAction object.
	 * If parameter $columns is either a single column name or an array of column names
	 * than only those columns are validated.
	 *
	 * NOTICE: This does not apply to primary or foreign keys for now.
	 *
	 * @param      UllFlowAction $obj The object to validate.
	 * @param      mixed $cols Column name or array of column names.
	 *
	 * @return     mixed TRUE if all columns are valid or the error message of the first invalid column.
	 */
	public static function doValidate(UllFlowAction $obj, $cols = null)
	{
		$columns = array();

		if ($cols) {
			$dbMap = Propel::getDatabaseMap(UllFlowActionPeer::DATABASE_NAME);
			$tableMap = $dbMap->getTable(UllFlowActionPeer::TABLE_NAME);

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

		$res =  BasePeer::doValidate(UllFlowActionPeer::DATABASE_NAME, UllFlowActionPeer::TABLE_NAME, $columns);
    if ($res !== true) {
        $request = sfContext::getInstance()->getRequest();
        foreach ($res as $failed) {
            $col = UllFlowActionPeer::translateFieldname($failed->getColumn(), BasePeer::TYPE_COLNAME, BasePeer::TYPE_PHPNAME);
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
	 * @return     UllFlowAction
	 */
	public static function retrieveByPK($pk, $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(self::DATABASE_NAME);
		}

		$criteria = new Criteria(UllFlowActionPeer::DATABASE_NAME);

		$criteria->add(UllFlowActionPeer::ID, $pk);


		$v = UllFlowActionPeer::doSelect($criteria, $con);

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
			$criteria->add(UllFlowActionPeer::ID, $pks, Criteria::IN);
			$objs = UllFlowActionPeer::doSelect($criteria, $con);
		}
		return $objs;
	}

} // BaseUllFlowActionPeer

// static code to register the map builder for this Peer with the main Propel class
if (Propel::isInit()) {
	// the MapBuilder classes register themselves with Propel during initialization
	// so we need to load them here.
	try {
		BaseUllFlowActionPeer::getMapBuilder();
	} catch (Exception $e) {
		Propel::log('Could not initialize Peer: ' . $e->getMessage(), Propel::LOG_ERR);
	}
} else {
	// even if Propel is not yet initialized, the map builder class can be registered
	// now and then it will be loaded when Propel initializes.
	require_once 'plugins/ullFlowPlugin/lib/model/map/UllFlowActionMapBuilder.php';
	Propel::registerMapBuilder('plugins.ullFlowPlugin.lib.model.map.UllFlowActionMapBuilder');
}
