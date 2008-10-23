<?php

/**
 * Base static class for performing query and update operations on the 'ull_flow_doc' table.
 *
 * 
 *
 * @package    plugins.ullFlowPlugin.lib.model.om
 */
abstract class BaseUllFlowDocPeer {

	/** the default database name for this class */
	const DATABASE_NAME = 'propel';

	/** the table name for this class */
	const TABLE_NAME = 'ull_flow_doc';

	/** A class that can be returned by this peer. */
	const CLASS_DEFAULT = 'plugins.ullFlowPlugin.lib.model.UllFlowDoc';

	/** The total number of columns. */
	const NUM_COLUMNS = 17;

	/** The number of lazy-loaded columns. */
	const NUM_LAZY_LOAD_COLUMNS = 0;


	/** the column name for the ID field */
	const ID = 'ull_flow_doc.ID';

	/** the column name for the ULL_FLOW_APP_ID field */
	const ULL_FLOW_APP_ID = 'ull_flow_doc.ULL_FLOW_APP_ID';

	/** the column name for the TITLE field */
	const TITLE = 'ull_flow_doc.TITLE';

	/** the column name for the ULL_FLOW_ACTION_ID field */
	const ULL_FLOW_ACTION_ID = 'ull_flow_doc.ULL_FLOW_ACTION_ID';

	/** the column name for the ASSIGNED_TO_ULL_USER_ID field */
	const ASSIGNED_TO_ULL_USER_ID = 'ull_flow_doc.ASSIGNED_TO_ULL_USER_ID';

	/** the column name for the ASSIGNED_TO_ULL_GROUP_ID field */
	const ASSIGNED_TO_ULL_GROUP_ID = 'ull_flow_doc.ASSIGNED_TO_ULL_GROUP_ID';

	/** the column name for the ASSIGNED_TO_ULL_FLOW_STEP_ID field */
	const ASSIGNED_TO_ULL_FLOW_STEP_ID = 'ull_flow_doc.ASSIGNED_TO_ULL_FLOW_STEP_ID';

	/** the column name for the PRIORITY field */
	const PRIORITY = 'ull_flow_doc.PRIORITY';

	/** the column name for the DEADLINE field */
	const DEADLINE = 'ull_flow_doc.DEADLINE';

	/** the column name for the CUSTOM_FIELD1 field */
	const CUSTOM_FIELD1 = 'ull_flow_doc.CUSTOM_FIELD1';

	/** the column name for the READ_ULL_GROUP_ID field */
	const READ_ULL_GROUP_ID = 'ull_flow_doc.READ_ULL_GROUP_ID';

	/** the column name for the DUPLICATE_TAGS_FOR_PROPEL_SEARCH field */
	const DUPLICATE_TAGS_FOR_PROPEL_SEARCH = 'ull_flow_doc.DUPLICATE_TAGS_FOR_PROPEL_SEARCH';

	/** the column name for the WRITE_ULL_GROUP_ID field */
	const WRITE_ULL_GROUP_ID = 'ull_flow_doc.WRITE_ULL_GROUP_ID';

	/** the column name for the CREATOR_USER_ID field */
	const CREATOR_USER_ID = 'ull_flow_doc.CREATOR_USER_ID';

	/** the column name for the CREATED_AT field */
	const CREATED_AT = 'ull_flow_doc.CREATED_AT';

	/** the column name for the UPDATOR_USER_ID field */
	const UPDATOR_USER_ID = 'ull_flow_doc.UPDATOR_USER_ID';

	/** the column name for the UPDATED_AT field */
	const UPDATED_AT = 'ull_flow_doc.UPDATED_AT';

	/** The PHP to DB Name Mapping */
	private static $phpNameMap = null;


	/**
	 * holds an array of fieldnames
	 *
	 * first dimension keys are the type constants
	 * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
	 */
	private static $fieldNames = array (
		BasePeer::TYPE_PHPNAME => array ('Id', 'UllFlowAppId', 'Title', 'UllFlowActionId', 'AssignedToUllUserId', 'AssignedToUllGroupId', 'AssignedToUllFlowStepId', 'Priority', 'Deadline', 'CustomField1', 'ReadUllGroupId', 'DuplicateTagsForPropelSearch', 'WriteUllGroupId', 'CreatorUserId', 'CreatedAt', 'UpdatorUserId', 'UpdatedAt', ),
		BasePeer::TYPE_COLNAME => array (UllFlowDocPeer::ID, UllFlowDocPeer::ULL_FLOW_APP_ID, UllFlowDocPeer::TITLE, UllFlowDocPeer::ULL_FLOW_ACTION_ID, UllFlowDocPeer::ASSIGNED_TO_ULL_USER_ID, UllFlowDocPeer::ASSIGNED_TO_ULL_GROUP_ID, UllFlowDocPeer::ASSIGNED_TO_ULL_FLOW_STEP_ID, UllFlowDocPeer::PRIORITY, UllFlowDocPeer::DEADLINE, UllFlowDocPeer::CUSTOM_FIELD1, UllFlowDocPeer::READ_ULL_GROUP_ID, UllFlowDocPeer::DUPLICATE_TAGS_FOR_PROPEL_SEARCH, UllFlowDocPeer::WRITE_ULL_GROUP_ID, UllFlowDocPeer::CREATOR_USER_ID, UllFlowDocPeer::CREATED_AT, UllFlowDocPeer::UPDATOR_USER_ID, UllFlowDocPeer::UPDATED_AT, ),
		BasePeer::TYPE_FIELDNAME => array ('id', 'ull_flow_app_id', 'title', 'ull_flow_action_id', 'assigned_to_ull_user_id', 'assigned_to_ull_group_id', 'assigned_to_ull_flow_step_id', 'priority', 'deadline', 'custom_field1', 'read_ull_group_id', 'duplicate_tags_for_propel_search', 'write_ull_group_id', 'creator_user_id', 'created_at', 'updator_user_id', 'updated_at', ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, )
	);

	/**
	 * holds an array of keys for quick access to the fieldnames array
	 *
	 * first dimension keys are the type constants
	 * e.g. self::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
	 */
	private static $fieldKeys = array (
		BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'UllFlowAppId' => 1, 'Title' => 2, 'UllFlowActionId' => 3, 'AssignedToUllUserId' => 4, 'AssignedToUllGroupId' => 5, 'AssignedToUllFlowStepId' => 6, 'Priority' => 7, 'Deadline' => 8, 'CustomField1' => 9, 'ReadUllGroupId' => 10, 'DuplicateTagsForPropelSearch' => 11, 'WriteUllGroupId' => 12, 'CreatorUserId' => 13, 'CreatedAt' => 14, 'UpdatorUserId' => 15, 'UpdatedAt' => 16, ),
		BasePeer::TYPE_COLNAME => array (UllFlowDocPeer::ID => 0, UllFlowDocPeer::ULL_FLOW_APP_ID => 1, UllFlowDocPeer::TITLE => 2, UllFlowDocPeer::ULL_FLOW_ACTION_ID => 3, UllFlowDocPeer::ASSIGNED_TO_ULL_USER_ID => 4, UllFlowDocPeer::ASSIGNED_TO_ULL_GROUP_ID => 5, UllFlowDocPeer::ASSIGNED_TO_ULL_FLOW_STEP_ID => 6, UllFlowDocPeer::PRIORITY => 7, UllFlowDocPeer::DEADLINE => 8, UllFlowDocPeer::CUSTOM_FIELD1 => 9, UllFlowDocPeer::READ_ULL_GROUP_ID => 10, UllFlowDocPeer::DUPLICATE_TAGS_FOR_PROPEL_SEARCH => 11, UllFlowDocPeer::WRITE_ULL_GROUP_ID => 12, UllFlowDocPeer::CREATOR_USER_ID => 13, UllFlowDocPeer::CREATED_AT => 14, UllFlowDocPeer::UPDATOR_USER_ID => 15, UllFlowDocPeer::UPDATED_AT => 16, ),
		BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'ull_flow_app_id' => 1, 'title' => 2, 'ull_flow_action_id' => 3, 'assigned_to_ull_user_id' => 4, 'assigned_to_ull_group_id' => 5, 'assigned_to_ull_flow_step_id' => 6, 'priority' => 7, 'deadline' => 8, 'custom_field1' => 9, 'read_ull_group_id' => 10, 'duplicate_tags_for_propel_search' => 11, 'write_ull_group_id' => 12, 'creator_user_id' => 13, 'created_at' => 14, 'updator_user_id' => 15, 'updated_at' => 16, ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, )
	);

	/**
	 * @return     MapBuilder the map builder for this peer
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function getMapBuilder()
	{
		include_once 'plugins/ullFlowPlugin/lib/model/map/UllFlowDocMapBuilder.php';
		return BasePeer::getMapBuilder('plugins.ullFlowPlugin.lib.model.map.UllFlowDocMapBuilder');
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
			$map = UllFlowDocPeer::getTableMap();
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
	 * @param      string $column The column name for current table. (i.e. UllFlowDocPeer::COLUMN_NAME).
	 * @return     string
	 */
	public static function alias($alias, $column)
	{
		return str_replace(UllFlowDocPeer::TABLE_NAME.'.', $alias.'.', $column);
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

		$criteria->addSelectColumn(UllFlowDocPeer::ID);

		$criteria->addSelectColumn(UllFlowDocPeer::ULL_FLOW_APP_ID);

		$criteria->addSelectColumn(UllFlowDocPeer::TITLE);

		$criteria->addSelectColumn(UllFlowDocPeer::ULL_FLOW_ACTION_ID);

		$criteria->addSelectColumn(UllFlowDocPeer::ASSIGNED_TO_ULL_USER_ID);

		$criteria->addSelectColumn(UllFlowDocPeer::ASSIGNED_TO_ULL_GROUP_ID);

		$criteria->addSelectColumn(UllFlowDocPeer::ASSIGNED_TO_ULL_FLOW_STEP_ID);

		$criteria->addSelectColumn(UllFlowDocPeer::PRIORITY);

		$criteria->addSelectColumn(UllFlowDocPeer::DEADLINE);

		$criteria->addSelectColumn(UllFlowDocPeer::CUSTOM_FIELD1);

		$criteria->addSelectColumn(UllFlowDocPeer::READ_ULL_GROUP_ID);

		$criteria->addSelectColumn(UllFlowDocPeer::DUPLICATE_TAGS_FOR_PROPEL_SEARCH);

		$criteria->addSelectColumn(UllFlowDocPeer::WRITE_ULL_GROUP_ID);

		$criteria->addSelectColumn(UllFlowDocPeer::CREATOR_USER_ID);

		$criteria->addSelectColumn(UllFlowDocPeer::CREATED_AT);

		$criteria->addSelectColumn(UllFlowDocPeer::UPDATOR_USER_ID);

		$criteria->addSelectColumn(UllFlowDocPeer::UPDATED_AT);

	}

	const COUNT = 'COUNT(ull_flow_doc.ID)';
	const COUNT_DISTINCT = 'COUNT(DISTINCT ull_flow_doc.ID)';

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
			$criteria->addSelectColumn(UllFlowDocPeer::COUNT_DISTINCT);
		} else {
			$criteria->addSelectColumn(UllFlowDocPeer::COUNT);
		}

		// just in case we're grouping: add those columns to the select statement
		foreach($criteria->getGroupByColumns() as $column)
		{
			$criteria->addSelectColumn($column);
		}

		$rs = UllFlowDocPeer::doSelectRS($criteria, $con);
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
	 * @return     UllFlowDoc
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectOne(Criteria $criteria, $con = null)
	{
		$critcopy = clone $criteria;
		$critcopy->setLimit(1);
		$objects = UllFlowDocPeer::doSelect($critcopy, $con);
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
		return UllFlowDocPeer::populateObjects(UllFlowDocPeer::doSelectRS($criteria, $con));
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

    foreach (sfMixer::getCallables('BaseUllFlowDocPeer:addDoSelectRS:addDoSelectRS') as $callable)
    {
      call_user_func($callable, 'BaseUllFlowDocPeer', $criteria, $con);
    }


		if ($con === null) {
			$con = Propel::getConnection(self::DATABASE_NAME);
		}

		if (!$criteria->getSelectColumns()) {
			$criteria = clone $criteria;
			UllFlowDocPeer::addSelectColumns($criteria);
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
		$cls = UllFlowDocPeer::getOMClass();
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
	 * Returns the number of rows matching criteria, joining the related UllFlowApp table
	 *
	 * @param      Criteria $c
	 * @param      boolean $distinct Whether to select only distinct columns (You can also set DISTINCT modifier in Criteria).
	 * @param      Connection $con
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinUllFlowApp(Criteria $criteria, $distinct = false, $con = null)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// clear out anything that might confuse the ORDER BY clause
		$criteria->clearSelectColumns()->clearOrderByColumns();
		if ($distinct || in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->addSelectColumn(UllFlowDocPeer::COUNT_DISTINCT);
		} else {
			$criteria->addSelectColumn(UllFlowDocPeer::COUNT);
		}

		// just in case we're grouping: add those columns to the select statement
		foreach($criteria->getGroupByColumns() as $column)
		{
			$criteria->addSelectColumn($column);
		}

		$criteria->addJoin(UllFlowDocPeer::ULL_FLOW_APP_ID, UllFlowAppPeer::ID);

		$rs = UllFlowDocPeer::doSelectRS($criteria, $con);
		if ($rs->next()) {
			return $rs->getInt(1);
		} else {
			// no rows returned; we infer that means 0 matches.
			return 0;
		}
	}


	/**
	 * Returns the number of rows matching criteria, joining the related UllFlowAction table
	 *
	 * @param      Criteria $c
	 * @param      boolean $distinct Whether to select only distinct columns (You can also set DISTINCT modifier in Criteria).
	 * @param      Connection $con
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinUllFlowAction(Criteria $criteria, $distinct = false, $con = null)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// clear out anything that might confuse the ORDER BY clause
		$criteria->clearSelectColumns()->clearOrderByColumns();
		if ($distinct || in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->addSelectColumn(UllFlowDocPeer::COUNT_DISTINCT);
		} else {
			$criteria->addSelectColumn(UllFlowDocPeer::COUNT);
		}

		// just in case we're grouping: add those columns to the select statement
		foreach($criteria->getGroupByColumns() as $column)
		{
			$criteria->addSelectColumn($column);
		}

		$criteria->addJoin(UllFlowDocPeer::ULL_FLOW_ACTION_ID, UllFlowActionPeer::ID);

		$rs = UllFlowDocPeer::doSelectRS($criteria, $con);
		if ($rs->next()) {
			return $rs->getInt(1);
		} else {
			// no rows returned; we infer that means 0 matches.
			return 0;
		}
	}


	/**
	 * Selects a collection of UllFlowDoc objects pre-filled with their UllFlowApp objects.
	 *
	 * @return     array Array of UllFlowDoc objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinUllFlowApp(Criteria $c, $con = null)
	{
		$c = clone $c;

		// Set the correct dbName if it has not been overridden
		if ($c->getDbName() == Propel::getDefaultDB()) {
			$c->setDbName(self::DATABASE_NAME);
		}

		UllFlowDocPeer::addSelectColumns($c);
		$startcol = (UllFlowDocPeer::NUM_COLUMNS - UllFlowDocPeer::NUM_LAZY_LOAD_COLUMNS) + 1;
		UllFlowAppPeer::addSelectColumns($c);

		$c->addJoin(UllFlowDocPeer::ULL_FLOW_APP_ID, UllFlowAppPeer::ID);
		$rs = BasePeer::doSelect($c, $con);
		$results = array();

		while($rs->next()) {

			$omClass = UllFlowDocPeer::getOMClass();

			$cls = Propel::import($omClass);
			$obj1 = new $cls();
			$obj1->hydrate($rs);

			$omClass = UllFlowAppPeer::getOMClass();

			$cls = Propel::import($omClass);
			$obj2 = new $cls();
			$obj2->hydrate($rs, $startcol);

			$newObject = true;
			foreach($results as $temp_obj1) {
				$temp_obj2 = $temp_obj1->getUllFlowApp(); //CHECKME
				if ($temp_obj2->getPrimaryKey() === $obj2->getPrimaryKey()) {
					$newObject = false;
					// e.g. $author->addBookRelatedByBookId()
					$temp_obj2->addUllFlowDoc($obj1); //CHECKME
					break;
				}
			}
			if ($newObject) {
				$obj2->initUllFlowDocs();
				$obj2->addUllFlowDoc($obj1); //CHECKME
			}
			$results[] = $obj1;
		}
		return $results;
	}


	/**
	 * Selects a collection of UllFlowDoc objects pre-filled with their UllFlowAction objects.
	 *
	 * @return     array Array of UllFlowDoc objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinUllFlowAction(Criteria $c, $con = null)
	{
		$c = clone $c;

		// Set the correct dbName if it has not been overridden
		if ($c->getDbName() == Propel::getDefaultDB()) {
			$c->setDbName(self::DATABASE_NAME);
		}

		UllFlowDocPeer::addSelectColumns($c);
		$startcol = (UllFlowDocPeer::NUM_COLUMNS - UllFlowDocPeer::NUM_LAZY_LOAD_COLUMNS) + 1;
		UllFlowActionPeer::addSelectColumns($c);

		$c->addJoin(UllFlowDocPeer::ULL_FLOW_ACTION_ID, UllFlowActionPeer::ID);
		$rs = BasePeer::doSelect($c, $con);
		$results = array();

		while($rs->next()) {

			$omClass = UllFlowDocPeer::getOMClass();

			$cls = Propel::import($omClass);
			$obj1 = new $cls();
			$obj1->hydrate($rs);

			$omClass = UllFlowActionPeer::getOMClass();

			$cls = Propel::import($omClass);
			$obj2 = new $cls();
			$obj2->hydrate($rs, $startcol);

			$newObject = true;
			foreach($results as $temp_obj1) {
				$temp_obj2 = $temp_obj1->getUllFlowAction(); //CHECKME
				if ($temp_obj2->getPrimaryKey() === $obj2->getPrimaryKey()) {
					$newObject = false;
					// e.g. $author->addBookRelatedByBookId()
					$temp_obj2->addUllFlowDoc($obj1); //CHECKME
					break;
				}
			}
			if ($newObject) {
				$obj2->initUllFlowDocs();
				$obj2->addUllFlowDoc($obj1); //CHECKME
			}
			$results[] = $obj1;
		}
		return $results;
	}


	/**
	 * Returns the number of rows matching criteria, joining all related tables
	 *
	 * @param      Criteria $c
	 * @param      boolean $distinct Whether to select only distinct columns (You can also set DISTINCT modifier in Criteria).
	 * @param      Connection $con
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinAll(Criteria $criteria, $distinct = false, $con = null)
	{
		$criteria = clone $criteria;

		// clear out anything that might confuse the ORDER BY clause
		$criteria->clearSelectColumns()->clearOrderByColumns();
		if ($distinct || in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->addSelectColumn(UllFlowDocPeer::COUNT_DISTINCT);
		} else {
			$criteria->addSelectColumn(UllFlowDocPeer::COUNT);
		}

		// just in case we're grouping: add those columns to the select statement
		foreach($criteria->getGroupByColumns() as $column)
		{
			$criteria->addSelectColumn($column);
		}

		$criteria->addJoin(UllFlowDocPeer::ULL_FLOW_APP_ID, UllFlowAppPeer::ID);

		$criteria->addJoin(UllFlowDocPeer::ULL_FLOW_ACTION_ID, UllFlowActionPeer::ID);

		$rs = UllFlowDocPeer::doSelectRS($criteria, $con);
		if ($rs->next()) {
			return $rs->getInt(1);
		} else {
			// no rows returned; we infer that means 0 matches.
			return 0;
		}
	}


	/**
	 * Selects a collection of UllFlowDoc objects pre-filled with all related objects.
	 *
	 * @return     array Array of UllFlowDoc objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinAll(Criteria $c, $con = null)
	{
		$c = clone $c;

		// Set the correct dbName if it has not been overridden
		if ($c->getDbName() == Propel::getDefaultDB()) {
			$c->setDbName(self::DATABASE_NAME);
		}

		UllFlowDocPeer::addSelectColumns($c);
		$startcol2 = (UllFlowDocPeer::NUM_COLUMNS - UllFlowDocPeer::NUM_LAZY_LOAD_COLUMNS) + 1;

		UllFlowAppPeer::addSelectColumns($c);
		$startcol3 = $startcol2 + UllFlowAppPeer::NUM_COLUMNS;

		UllFlowActionPeer::addSelectColumns($c);
		$startcol4 = $startcol3 + UllFlowActionPeer::NUM_COLUMNS;

		$c->addJoin(UllFlowDocPeer::ULL_FLOW_APP_ID, UllFlowAppPeer::ID);

		$c->addJoin(UllFlowDocPeer::ULL_FLOW_ACTION_ID, UllFlowActionPeer::ID);

		$rs = BasePeer::doSelect($c, $con);
		$results = array();

		while($rs->next()) {

			$omClass = UllFlowDocPeer::getOMClass();


			$cls = Propel::import($omClass);
			$obj1 = new $cls();
			$obj1->hydrate($rs);


				// Add objects for joined UllFlowApp rows
	
			$omClass = UllFlowAppPeer::getOMClass();


			$cls = Propel::import($omClass);
			$obj2 = new $cls();
			$obj2->hydrate($rs, $startcol2);

			$newObject = true;
			for ($j=0, $resCount=count($results); $j < $resCount; $j++) {
				$temp_obj1 = $results[$j];
				$temp_obj2 = $temp_obj1->getUllFlowApp(); // CHECKME
				if ($temp_obj2->getPrimaryKey() === $obj2->getPrimaryKey()) {
					$newObject = false;
					$temp_obj2->addUllFlowDoc($obj1); // CHECKME
					break;
				}
			}

			if ($newObject) {
				$obj2->initUllFlowDocs();
				$obj2->addUllFlowDoc($obj1);
			}


				// Add objects for joined UllFlowAction rows
	
			$omClass = UllFlowActionPeer::getOMClass();


			$cls = Propel::import($omClass);
			$obj3 = new $cls();
			$obj3->hydrate($rs, $startcol3);

			$newObject = true;
			for ($j=0, $resCount=count($results); $j < $resCount; $j++) {
				$temp_obj1 = $results[$j];
				$temp_obj3 = $temp_obj1->getUllFlowAction(); // CHECKME
				if ($temp_obj3->getPrimaryKey() === $obj3->getPrimaryKey()) {
					$newObject = false;
					$temp_obj3->addUllFlowDoc($obj1); // CHECKME
					break;
				}
			}

			if ($newObject) {
				$obj3->initUllFlowDocs();
				$obj3->addUllFlowDoc($obj1);
			}

			$results[] = $obj1;
		}
		return $results;
	}


	/**
	 * Returns the number of rows matching criteria, joining the related UllFlowApp table
	 *
	 * @param      Criteria $c
	 * @param      boolean $distinct Whether to select only distinct columns (You can also set DISTINCT modifier in Criteria).
	 * @param      Connection $con
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinAllExceptUllFlowApp(Criteria $criteria, $distinct = false, $con = null)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// clear out anything that might confuse the ORDER BY clause
		$criteria->clearSelectColumns()->clearOrderByColumns();
		if ($distinct || in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->addSelectColumn(UllFlowDocPeer::COUNT_DISTINCT);
		} else {
			$criteria->addSelectColumn(UllFlowDocPeer::COUNT);
		}

		// just in case we're grouping: add those columns to the select statement
		foreach($criteria->getGroupByColumns() as $column)
		{
			$criteria->addSelectColumn($column);
		}

		$criteria->addJoin(UllFlowDocPeer::ULL_FLOW_ACTION_ID, UllFlowActionPeer::ID);

		$rs = UllFlowDocPeer::doSelectRS($criteria, $con);
		if ($rs->next()) {
			return $rs->getInt(1);
		} else {
			// no rows returned; we infer that means 0 matches.
			return 0;
		}
	}


	/**
	 * Returns the number of rows matching criteria, joining the related UllFlowAction table
	 *
	 * @param      Criteria $c
	 * @param      boolean $distinct Whether to select only distinct columns (You can also set DISTINCT modifier in Criteria).
	 * @param      Connection $con
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinAllExceptUllFlowAction(Criteria $criteria, $distinct = false, $con = null)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// clear out anything that might confuse the ORDER BY clause
		$criteria->clearSelectColumns()->clearOrderByColumns();
		if ($distinct || in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->addSelectColumn(UllFlowDocPeer::COUNT_DISTINCT);
		} else {
			$criteria->addSelectColumn(UllFlowDocPeer::COUNT);
		}

		// just in case we're grouping: add those columns to the select statement
		foreach($criteria->getGroupByColumns() as $column)
		{
			$criteria->addSelectColumn($column);
		}

		$criteria->addJoin(UllFlowDocPeer::ULL_FLOW_APP_ID, UllFlowAppPeer::ID);

		$rs = UllFlowDocPeer::doSelectRS($criteria, $con);
		if ($rs->next()) {
			return $rs->getInt(1);
		} else {
			// no rows returned; we infer that means 0 matches.
			return 0;
		}
	}


	/**
	 * Selects a collection of UllFlowDoc objects pre-filled with all related objects except UllFlowApp.
	 *
	 * @return     array Array of UllFlowDoc objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinAllExceptUllFlowApp(Criteria $c, $con = null)
	{
		$c = clone $c;

		// Set the correct dbName if it has not been overridden
		// $c->getDbName() will return the same object if not set to another value
		// so == check is okay and faster
		if ($c->getDbName() == Propel::getDefaultDB()) {
			$c->setDbName(self::DATABASE_NAME);
		}

		UllFlowDocPeer::addSelectColumns($c);
		$startcol2 = (UllFlowDocPeer::NUM_COLUMNS - UllFlowDocPeer::NUM_LAZY_LOAD_COLUMNS) + 1;

		UllFlowActionPeer::addSelectColumns($c);
		$startcol3 = $startcol2 + UllFlowActionPeer::NUM_COLUMNS;

		$c->addJoin(UllFlowDocPeer::ULL_FLOW_ACTION_ID, UllFlowActionPeer::ID);


		$rs = BasePeer::doSelect($c, $con);
		$results = array();

		while($rs->next()) {

			$omClass = UllFlowDocPeer::getOMClass();

			$cls = Propel::import($omClass);
			$obj1 = new $cls();
			$obj1->hydrate($rs);

			$omClass = UllFlowActionPeer::getOMClass();


			$cls = Propel::import($omClass);
			$obj2  = new $cls();
			$obj2->hydrate($rs, $startcol2);

			$newObject = true;
			for ($j=0, $resCount=count($results); $j < $resCount; $j++) {
				$temp_obj1 = $results[$j];
				$temp_obj2 = $temp_obj1->getUllFlowAction(); //CHECKME
				if ($temp_obj2->getPrimaryKey() === $obj2->getPrimaryKey()) {
					$newObject = false;
					$temp_obj2->addUllFlowDoc($obj1);
					break;
				}
			}

			if ($newObject) {
				$obj2->initUllFlowDocs();
				$obj2->addUllFlowDoc($obj1);
			}

			$results[] = $obj1;
		}
		return $results;
	}


	/**
	 * Selects a collection of UllFlowDoc objects pre-filled with all related objects except UllFlowAction.
	 *
	 * @return     array Array of UllFlowDoc objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinAllExceptUllFlowAction(Criteria $c, $con = null)
	{
		$c = clone $c;

		// Set the correct dbName if it has not been overridden
		// $c->getDbName() will return the same object if not set to another value
		// so == check is okay and faster
		if ($c->getDbName() == Propel::getDefaultDB()) {
			$c->setDbName(self::DATABASE_NAME);
		}

		UllFlowDocPeer::addSelectColumns($c);
		$startcol2 = (UllFlowDocPeer::NUM_COLUMNS - UllFlowDocPeer::NUM_LAZY_LOAD_COLUMNS) + 1;

		UllFlowAppPeer::addSelectColumns($c);
		$startcol3 = $startcol2 + UllFlowAppPeer::NUM_COLUMNS;

		$c->addJoin(UllFlowDocPeer::ULL_FLOW_APP_ID, UllFlowAppPeer::ID);


		$rs = BasePeer::doSelect($c, $con);
		$results = array();

		while($rs->next()) {

			$omClass = UllFlowDocPeer::getOMClass();

			$cls = Propel::import($omClass);
			$obj1 = new $cls();
			$obj1->hydrate($rs);

			$omClass = UllFlowAppPeer::getOMClass();


			$cls = Propel::import($omClass);
			$obj2  = new $cls();
			$obj2->hydrate($rs, $startcol2);

			$newObject = true;
			for ($j=0, $resCount=count($results); $j < $resCount; $j++) {
				$temp_obj1 = $results[$j];
				$temp_obj2 = $temp_obj1->getUllFlowApp(); //CHECKME
				if ($temp_obj2->getPrimaryKey() === $obj2->getPrimaryKey()) {
					$newObject = false;
					$temp_obj2->addUllFlowDoc($obj1);
					break;
				}
			}

			if ($newObject) {
				$obj2->initUllFlowDocs();
				$obj2->addUllFlowDoc($obj1);
			}

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
		return UllFlowDocPeer::CLASS_DEFAULT;
	}

	/**
	 * Method perform an INSERT on the database, given a UllFlowDoc or Criteria object.
	 *
	 * @param      mixed $values Criteria or UllFlowDoc object containing data that is used to create the INSERT statement.
	 * @param      Connection $con the connection to use
	 * @return     mixed The new primary key.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doInsert($values, $con = null)
	{

    foreach (sfMixer::getCallables('BaseUllFlowDocPeer:doInsert:pre') as $callable)
    {
      $ret = call_user_func($callable, 'BaseUllFlowDocPeer', $values, $con);
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
			$criteria = $values->buildCriteria(); // build Criteria from UllFlowDoc object
		}

		$criteria->remove(UllFlowDocPeer::ID); // remove pkey col since this table uses auto-increment


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

		
    foreach (sfMixer::getCallables('BaseUllFlowDocPeer:doInsert:post') as $callable)
    {
      call_user_func($callable, 'BaseUllFlowDocPeer', $values, $con, $pk);
    }

    return $pk;
	}

	/**
	 * Method perform an UPDATE on the database, given a UllFlowDoc or Criteria object.
	 *
	 * @param      mixed $values Criteria or UllFlowDoc object containing data that is used to create the UPDATE statement.
	 * @param      Connection $con The connection to use (specify Connection object to exert more control over transactions).
	 * @return     int The number of affected rows (if supported by underlying database driver).
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doUpdate($values, $con = null)
	{

    foreach (sfMixer::getCallables('BaseUllFlowDocPeer:doUpdate:pre') as $callable)
    {
      $ret = call_user_func($callable, 'BaseUllFlowDocPeer', $values, $con);
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

			$comparison = $criteria->getComparison(UllFlowDocPeer::ID);
			$selectCriteria->add(UllFlowDocPeer::ID, $criteria->remove(UllFlowDocPeer::ID), $comparison);

		} else { // $values is UllFlowDoc object
			$criteria = $values->buildCriteria(); // gets full criteria
			$selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
		}

		// set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		$ret = BasePeer::doUpdate($selectCriteria, $criteria, $con);
	

    foreach (sfMixer::getCallables('BaseUllFlowDocPeer:doUpdate:post') as $callable)
    {
      call_user_func($callable, 'BaseUllFlowDocPeer', $values, $con, $ret);
    }

    return $ret;
  }

	/**
	 * Method to DELETE all rows from the ull_flow_doc table.
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
			$affectedRows += BasePeer::doDeleteAll(UllFlowDocPeer::TABLE_NAME, $con);
			$con->commit();
			return $affectedRows;
		} catch (PropelException $e) {
			$con->rollback();
			throw $e;
		}
	}

	/**
	 * Method perform a DELETE on the database, given a UllFlowDoc or Criteria object OR a primary key value.
	 *
	 * @param      mixed $values Criteria or UllFlowDoc object or primary key or array of primary keys
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
			$con = Propel::getConnection(UllFlowDocPeer::DATABASE_NAME);
		}

		if ($values instanceof Criteria) {
			$criteria = clone $values; // rename for clarity
		} elseif ($values instanceof UllFlowDoc) {

			$criteria = $values->buildPkeyCriteria();
		} else {
			// it must be the primary key
			$criteria = new Criteria(self::DATABASE_NAME);
			$criteria->add(UllFlowDocPeer::ID, (array) $values, Criteria::IN);
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
	 * Validates all modified columns of given UllFlowDoc object.
	 * If parameter $columns is either a single column name or an array of column names
	 * than only those columns are validated.
	 *
	 * NOTICE: This does not apply to primary or foreign keys for now.
	 *
	 * @param      UllFlowDoc $obj The object to validate.
	 * @param      mixed $cols Column name or array of column names.
	 *
	 * @return     mixed TRUE if all columns are valid or the error message of the first invalid column.
	 */
	public static function doValidate(UllFlowDoc $obj, $cols = null)
	{
		$columns = array();

		if ($cols) {
			$dbMap = Propel::getDatabaseMap(UllFlowDocPeer::DATABASE_NAME);
			$tableMap = $dbMap->getTable(UllFlowDocPeer::TABLE_NAME);

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

		$res =  BasePeer::doValidate(UllFlowDocPeer::DATABASE_NAME, UllFlowDocPeer::TABLE_NAME, $columns);
    if ($res !== true) {
        $request = sfContext::getInstance()->getRequest();
        foreach ($res as $failed) {
            $col = UllFlowDocPeer::translateFieldname($failed->getColumn(), BasePeer::TYPE_COLNAME, BasePeer::TYPE_PHPNAME);
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
	 * @return     UllFlowDoc
	 */
	public static function retrieveByPK($pk, $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(self::DATABASE_NAME);
		}

		$criteria = new Criteria(UllFlowDocPeer::DATABASE_NAME);

		$criteria->add(UllFlowDocPeer::ID, $pk);


		$v = UllFlowDocPeer::doSelect($criteria, $con);

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
			$criteria->add(UllFlowDocPeer::ID, $pks, Criteria::IN);
			$objs = UllFlowDocPeer::doSelect($criteria, $con);
		}
		return $objs;
	}

} // BaseUllFlowDocPeer

// static code to register the map builder for this Peer with the main Propel class
if (Propel::isInit()) {
	// the MapBuilder classes register themselves with Propel during initialization
	// so we need to load them here.
	try {
		BaseUllFlowDocPeer::getMapBuilder();
	} catch (Exception $e) {
		Propel::log('Could not initialize Peer: ' . $e->getMessage(), Propel::LOG_ERR);
	}
} else {
	// even if Propel is not yet initialized, the map builder class can be registered
	// now and then it will be loaded when Propel initializes.
	require_once 'plugins/ullFlowPlugin/lib/model/map/UllFlowDocMapBuilder.php';
	Propel::registerMapBuilder('plugins.ullFlowPlugin.lib.model.map.UllFlowDocMapBuilder');
}
