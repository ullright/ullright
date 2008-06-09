<?php

/**
 * Base static class for performing query and update operations on the 'ull_flow_value' table.
 *
 * 
 *
 * @package    plugins.ullFlowPlugin.lib.model.om
 */
abstract class BaseUllFlowValuePeer {

	/** the default database name for this class */
	const DATABASE_NAME = 'propel';

	/** the table name for this class */
	const TABLE_NAME = 'ull_flow_value';

	/** A class that can be returned by this peer. */
	const CLASS_DEFAULT = 'plugins.ullFlowPlugin.lib.model.UllFlowValue';

	/** The total number of columns. */
	const NUM_COLUMNS = 10;

	/** The number of lazy-loaded columns. */
	const NUM_LAZY_LOAD_COLUMNS = 0;


	/** the column name for the ID field */
	const ID = 'ull_flow_value.ID';

	/** the column name for the ULL_FLOW_DOC_ID field */
	const ULL_FLOW_DOC_ID = 'ull_flow_value.ULL_FLOW_DOC_ID';

	/** the column name for the ULL_FLOW_FIELD_ID field */
	const ULL_FLOW_FIELD_ID = 'ull_flow_value.ULL_FLOW_FIELD_ID';

	/** the column name for the ULL_FLOW_MEMORY_ID field */
	const ULL_FLOW_MEMORY_ID = 'ull_flow_value.ULL_FLOW_MEMORY_ID';

	/** the column name for the CURRENT field */
	const CURRENT = 'ull_flow_value.CURRENT';

	/** the column name for the VALUE field */
	const VALUE = 'ull_flow_value.VALUE';

	/** the column name for the CREATOR_USER_ID field */
	const CREATOR_USER_ID = 'ull_flow_value.CREATOR_USER_ID';

	/** the column name for the CREATED_AT field */
	const CREATED_AT = 'ull_flow_value.CREATED_AT';

	/** the column name for the UPDATOR_USER_ID field */
	const UPDATOR_USER_ID = 'ull_flow_value.UPDATOR_USER_ID';

	/** the column name for the UPDATED_AT field */
	const UPDATED_AT = 'ull_flow_value.UPDATED_AT';

	/** The PHP to DB Name Mapping */
	private static $phpNameMap = null;


	/**
	 * holds an array of fieldnames
	 *
	 * first dimension keys are the type constants
	 * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
	 */
	private static $fieldNames = array (
		BasePeer::TYPE_PHPNAME => array ('Id', 'UllFlowDocId', 'UllFlowFieldId', 'UllFlowMemoryId', 'Current', 'Value', 'CreatorUserId', 'CreatedAt', 'UpdatorUserId', 'UpdatedAt', ),
		BasePeer::TYPE_COLNAME => array (UllFlowValuePeer::ID, UllFlowValuePeer::ULL_FLOW_DOC_ID, UllFlowValuePeer::ULL_FLOW_FIELD_ID, UllFlowValuePeer::ULL_FLOW_MEMORY_ID, UllFlowValuePeer::CURRENT, UllFlowValuePeer::VALUE, UllFlowValuePeer::CREATOR_USER_ID, UllFlowValuePeer::CREATED_AT, UllFlowValuePeer::UPDATOR_USER_ID, UllFlowValuePeer::UPDATED_AT, ),
		BasePeer::TYPE_FIELDNAME => array ('id', 'ull_flow_doc_id', 'ull_flow_field_id', 'ull_flow_memory_id', 'current', 'value', 'creator_user_id', 'created_at', 'updator_user_id', 'updated_at', ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, )
	);

	/**
	 * holds an array of keys for quick access to the fieldnames array
	 *
	 * first dimension keys are the type constants
	 * e.g. self::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
	 */
	private static $fieldKeys = array (
		BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'UllFlowDocId' => 1, 'UllFlowFieldId' => 2, 'UllFlowMemoryId' => 3, 'Current' => 4, 'Value' => 5, 'CreatorUserId' => 6, 'CreatedAt' => 7, 'UpdatorUserId' => 8, 'UpdatedAt' => 9, ),
		BasePeer::TYPE_COLNAME => array (UllFlowValuePeer::ID => 0, UllFlowValuePeer::ULL_FLOW_DOC_ID => 1, UllFlowValuePeer::ULL_FLOW_FIELD_ID => 2, UllFlowValuePeer::ULL_FLOW_MEMORY_ID => 3, UllFlowValuePeer::CURRENT => 4, UllFlowValuePeer::VALUE => 5, UllFlowValuePeer::CREATOR_USER_ID => 6, UllFlowValuePeer::CREATED_AT => 7, UllFlowValuePeer::UPDATOR_USER_ID => 8, UllFlowValuePeer::UPDATED_AT => 9, ),
		BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'ull_flow_doc_id' => 1, 'ull_flow_field_id' => 2, 'ull_flow_memory_id' => 3, 'current' => 4, 'value' => 5, 'creator_user_id' => 6, 'created_at' => 7, 'updator_user_id' => 8, 'updated_at' => 9, ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, )
	);

	/**
	 * @return     MapBuilder the map builder for this peer
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function getMapBuilder()
	{
		include_once 'plugins/ullFlowPlugin/lib/model/map/UllFlowValueMapBuilder.php';
		return BasePeer::getMapBuilder('plugins.ullFlowPlugin.lib.model.map.UllFlowValueMapBuilder');
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
			$map = UllFlowValuePeer::getTableMap();
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
	 * @param      string $column The column name for current table. (i.e. UllFlowValuePeer::COLUMN_NAME).
	 * @return     string
	 */
	public static function alias($alias, $column)
	{
		return str_replace(UllFlowValuePeer::TABLE_NAME.'.', $alias.'.', $column);
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

		$criteria->addSelectColumn(UllFlowValuePeer::ID);

		$criteria->addSelectColumn(UllFlowValuePeer::ULL_FLOW_DOC_ID);

		$criteria->addSelectColumn(UllFlowValuePeer::ULL_FLOW_FIELD_ID);

		$criteria->addSelectColumn(UllFlowValuePeer::ULL_FLOW_MEMORY_ID);

		$criteria->addSelectColumn(UllFlowValuePeer::CURRENT);

		$criteria->addSelectColumn(UllFlowValuePeer::VALUE);

		$criteria->addSelectColumn(UllFlowValuePeer::CREATOR_USER_ID);

		$criteria->addSelectColumn(UllFlowValuePeer::CREATED_AT);

		$criteria->addSelectColumn(UllFlowValuePeer::UPDATOR_USER_ID);

		$criteria->addSelectColumn(UllFlowValuePeer::UPDATED_AT);

	}

	const COUNT = 'COUNT(ull_flow_value.ID)';
	const COUNT_DISTINCT = 'COUNT(DISTINCT ull_flow_value.ID)';

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
			$criteria->addSelectColumn(UllFlowValuePeer::COUNT_DISTINCT);
		} else {
			$criteria->addSelectColumn(UllFlowValuePeer::COUNT);
		}

		// just in case we're grouping: add those columns to the select statement
		foreach($criteria->getGroupByColumns() as $column)
		{
			$criteria->addSelectColumn($column);
		}

		$rs = UllFlowValuePeer::doSelectRS($criteria, $con);
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
	 * @return     UllFlowValue
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectOne(Criteria $criteria, $con = null)
	{
		$critcopy = clone $criteria;
		$critcopy->setLimit(1);
		$objects = UllFlowValuePeer::doSelect($critcopy, $con);
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
		return UllFlowValuePeer::populateObjects(UllFlowValuePeer::doSelectRS($criteria, $con));
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

    foreach (sfMixer::getCallables('BaseUllFlowValuePeer:addDoSelectRS:addDoSelectRS') as $callable)
    {
      call_user_func($callable, 'BaseUllFlowValuePeer', $criteria, $con);
    }


		if ($con === null) {
			$con = Propel::getConnection(self::DATABASE_NAME);
		}

		if (!$criteria->getSelectColumns()) {
			$criteria = clone $criteria;
			UllFlowValuePeer::addSelectColumns($criteria);
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
		$cls = UllFlowValuePeer::getOMClass();
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
	 * Returns the number of rows matching criteria, joining the related UllFlowDoc table
	 *
	 * @param      Criteria $c
	 * @param      boolean $distinct Whether to select only distinct columns (You can also set DISTINCT modifier in Criteria).
	 * @param      Connection $con
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinUllFlowDoc(Criteria $criteria, $distinct = false, $con = null)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// clear out anything that might confuse the ORDER BY clause
		$criteria->clearSelectColumns()->clearOrderByColumns();
		if ($distinct || in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->addSelectColumn(UllFlowValuePeer::COUNT_DISTINCT);
		} else {
			$criteria->addSelectColumn(UllFlowValuePeer::COUNT);
		}

		// just in case we're grouping: add those columns to the select statement
		foreach($criteria->getGroupByColumns() as $column)
		{
			$criteria->addSelectColumn($column);
		}

		$criteria->addJoin(UllFlowValuePeer::ULL_FLOW_DOC_ID, UllFlowDocPeer::ID);

		$rs = UllFlowValuePeer::doSelectRS($criteria, $con);
		if ($rs->next()) {
			return $rs->getInt(1);
		} else {
			// no rows returned; we infer that means 0 matches.
			return 0;
		}
	}


	/**
	 * Returns the number of rows matching criteria, joining the related UllFlowField table
	 *
	 * @param      Criteria $c
	 * @param      boolean $distinct Whether to select only distinct columns (You can also set DISTINCT modifier in Criteria).
	 * @param      Connection $con
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinUllFlowField(Criteria $criteria, $distinct = false, $con = null)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// clear out anything that might confuse the ORDER BY clause
		$criteria->clearSelectColumns()->clearOrderByColumns();
		if ($distinct || in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->addSelectColumn(UllFlowValuePeer::COUNT_DISTINCT);
		} else {
			$criteria->addSelectColumn(UllFlowValuePeer::COUNT);
		}

		// just in case we're grouping: add those columns to the select statement
		foreach($criteria->getGroupByColumns() as $column)
		{
			$criteria->addSelectColumn($column);
		}

		$criteria->addJoin(UllFlowValuePeer::ULL_FLOW_FIELD_ID, UllFlowFieldPeer::ID);

		$rs = UllFlowValuePeer::doSelectRS($criteria, $con);
		if ($rs->next()) {
			return $rs->getInt(1);
		} else {
			// no rows returned; we infer that means 0 matches.
			return 0;
		}
	}


	/**
	 * Returns the number of rows matching criteria, joining the related UllFlowMemory table
	 *
	 * @param      Criteria $c
	 * @param      boolean $distinct Whether to select only distinct columns (You can also set DISTINCT modifier in Criteria).
	 * @param      Connection $con
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinUllFlowMemory(Criteria $criteria, $distinct = false, $con = null)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// clear out anything that might confuse the ORDER BY clause
		$criteria->clearSelectColumns()->clearOrderByColumns();
		if ($distinct || in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->addSelectColumn(UllFlowValuePeer::COUNT_DISTINCT);
		} else {
			$criteria->addSelectColumn(UllFlowValuePeer::COUNT);
		}

		// just in case we're grouping: add those columns to the select statement
		foreach($criteria->getGroupByColumns() as $column)
		{
			$criteria->addSelectColumn($column);
		}

		$criteria->addJoin(UllFlowValuePeer::ULL_FLOW_MEMORY_ID, UllFlowMemoryPeer::ID);

		$rs = UllFlowValuePeer::doSelectRS($criteria, $con);
		if ($rs->next()) {
			return $rs->getInt(1);
		} else {
			// no rows returned; we infer that means 0 matches.
			return 0;
		}
	}


	/**
	 * Selects a collection of UllFlowValue objects pre-filled with their UllFlowDoc objects.
	 *
	 * @return     array Array of UllFlowValue objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinUllFlowDoc(Criteria $c, $con = null)
	{
		$c = clone $c;

		// Set the correct dbName if it has not been overridden
		if ($c->getDbName() == Propel::getDefaultDB()) {
			$c->setDbName(self::DATABASE_NAME);
		}

		UllFlowValuePeer::addSelectColumns($c);
		$startcol = (UllFlowValuePeer::NUM_COLUMNS - UllFlowValuePeer::NUM_LAZY_LOAD_COLUMNS) + 1;
		UllFlowDocPeer::addSelectColumns($c);

		$c->addJoin(UllFlowValuePeer::ULL_FLOW_DOC_ID, UllFlowDocPeer::ID);
		$rs = BasePeer::doSelect($c, $con);
		$results = array();

		while($rs->next()) {

			$omClass = UllFlowValuePeer::getOMClass();

			$cls = Propel::import($omClass);
			$obj1 = new $cls();
			$obj1->hydrate($rs);

			$omClass = UllFlowDocPeer::getOMClass();

			$cls = Propel::import($omClass);
			$obj2 = new $cls();
			$obj2->hydrate($rs, $startcol);

			$newObject = true;
			foreach($results as $temp_obj1) {
				$temp_obj2 = $temp_obj1->getUllFlowDoc(); //CHECKME
				if ($temp_obj2->getPrimaryKey() === $obj2->getPrimaryKey()) {
					$newObject = false;
					// e.g. $author->addBookRelatedByBookId()
					$temp_obj2->addUllFlowValue($obj1); //CHECKME
					break;
				}
			}
			if ($newObject) {
				$obj2->initUllFlowValues();
				$obj2->addUllFlowValue($obj1); //CHECKME
			}
			$results[] = $obj1;
		}
		return $results;
	}


	/**
	 * Selects a collection of UllFlowValue objects pre-filled with their UllFlowField objects.
	 *
	 * @return     array Array of UllFlowValue objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinUllFlowField(Criteria $c, $con = null)
	{
		$c = clone $c;

		// Set the correct dbName if it has not been overridden
		if ($c->getDbName() == Propel::getDefaultDB()) {
			$c->setDbName(self::DATABASE_NAME);
		}

		UllFlowValuePeer::addSelectColumns($c);
		$startcol = (UllFlowValuePeer::NUM_COLUMNS - UllFlowValuePeer::NUM_LAZY_LOAD_COLUMNS) + 1;
		UllFlowFieldPeer::addSelectColumns($c);

		$c->addJoin(UllFlowValuePeer::ULL_FLOW_FIELD_ID, UllFlowFieldPeer::ID);
		$rs = BasePeer::doSelect($c, $con);
		$results = array();

		while($rs->next()) {

			$omClass = UllFlowValuePeer::getOMClass();

			$cls = Propel::import($omClass);
			$obj1 = new $cls();
			$obj1->hydrate($rs);

			$omClass = UllFlowFieldPeer::getOMClass();

			$cls = Propel::import($omClass);
			$obj2 = new $cls();
			$obj2->hydrate($rs, $startcol);

			$newObject = true;
			foreach($results as $temp_obj1) {
				$temp_obj2 = $temp_obj1->getUllFlowField(); //CHECKME
				if ($temp_obj2->getPrimaryKey() === $obj2->getPrimaryKey()) {
					$newObject = false;
					// e.g. $author->addBookRelatedByBookId()
					$temp_obj2->addUllFlowValue($obj1); //CHECKME
					break;
				}
			}
			if ($newObject) {
				$obj2->initUllFlowValues();
				$obj2->addUllFlowValue($obj1); //CHECKME
			}
			$results[] = $obj1;
		}
		return $results;
	}


	/**
	 * Selects a collection of UllFlowValue objects pre-filled with their UllFlowMemory objects.
	 *
	 * @return     array Array of UllFlowValue objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinUllFlowMemory(Criteria $c, $con = null)
	{
		$c = clone $c;

		// Set the correct dbName if it has not been overridden
		if ($c->getDbName() == Propel::getDefaultDB()) {
			$c->setDbName(self::DATABASE_NAME);
		}

		UllFlowValuePeer::addSelectColumns($c);
		$startcol = (UllFlowValuePeer::NUM_COLUMNS - UllFlowValuePeer::NUM_LAZY_LOAD_COLUMNS) + 1;
		UllFlowMemoryPeer::addSelectColumns($c);

		$c->addJoin(UllFlowValuePeer::ULL_FLOW_MEMORY_ID, UllFlowMemoryPeer::ID);
		$rs = BasePeer::doSelect($c, $con);
		$results = array();

		while($rs->next()) {

			$omClass = UllFlowValuePeer::getOMClass();

			$cls = Propel::import($omClass);
			$obj1 = new $cls();
			$obj1->hydrate($rs);

			$omClass = UllFlowMemoryPeer::getOMClass();

			$cls = Propel::import($omClass);
			$obj2 = new $cls();
			$obj2->hydrate($rs, $startcol);

			$newObject = true;
			foreach($results as $temp_obj1) {
				$temp_obj2 = $temp_obj1->getUllFlowMemory(); //CHECKME
				if ($temp_obj2->getPrimaryKey() === $obj2->getPrimaryKey()) {
					$newObject = false;
					// e.g. $author->addBookRelatedByBookId()
					$temp_obj2->addUllFlowValue($obj1); //CHECKME
					break;
				}
			}
			if ($newObject) {
				$obj2->initUllFlowValues();
				$obj2->addUllFlowValue($obj1); //CHECKME
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
			$criteria->addSelectColumn(UllFlowValuePeer::COUNT_DISTINCT);
		} else {
			$criteria->addSelectColumn(UllFlowValuePeer::COUNT);
		}

		// just in case we're grouping: add those columns to the select statement
		foreach($criteria->getGroupByColumns() as $column)
		{
			$criteria->addSelectColumn($column);
		}

		$criteria->addJoin(UllFlowValuePeer::ULL_FLOW_DOC_ID, UllFlowDocPeer::ID);

		$criteria->addJoin(UllFlowValuePeer::ULL_FLOW_FIELD_ID, UllFlowFieldPeer::ID);

		$criteria->addJoin(UllFlowValuePeer::ULL_FLOW_MEMORY_ID, UllFlowMemoryPeer::ID);

		$rs = UllFlowValuePeer::doSelectRS($criteria, $con);
		if ($rs->next()) {
			return $rs->getInt(1);
		} else {
			// no rows returned; we infer that means 0 matches.
			return 0;
		}
	}


	/**
	 * Selects a collection of UllFlowValue objects pre-filled with all related objects.
	 *
	 * @return     array Array of UllFlowValue objects.
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

		UllFlowValuePeer::addSelectColumns($c);
		$startcol2 = (UllFlowValuePeer::NUM_COLUMNS - UllFlowValuePeer::NUM_LAZY_LOAD_COLUMNS) + 1;

		UllFlowDocPeer::addSelectColumns($c);
		$startcol3 = $startcol2 + UllFlowDocPeer::NUM_COLUMNS;

		UllFlowFieldPeer::addSelectColumns($c);
		$startcol4 = $startcol3 + UllFlowFieldPeer::NUM_COLUMNS;

		UllFlowMemoryPeer::addSelectColumns($c);
		$startcol5 = $startcol4 + UllFlowMemoryPeer::NUM_COLUMNS;

		$c->addJoin(UllFlowValuePeer::ULL_FLOW_DOC_ID, UllFlowDocPeer::ID);

		$c->addJoin(UllFlowValuePeer::ULL_FLOW_FIELD_ID, UllFlowFieldPeer::ID);

		$c->addJoin(UllFlowValuePeer::ULL_FLOW_MEMORY_ID, UllFlowMemoryPeer::ID);

		$rs = BasePeer::doSelect($c, $con);
		$results = array();

		while($rs->next()) {

			$omClass = UllFlowValuePeer::getOMClass();


			$cls = Propel::import($omClass);
			$obj1 = new $cls();
			$obj1->hydrate($rs);


				// Add objects for joined UllFlowDoc rows
	
			$omClass = UllFlowDocPeer::getOMClass();


			$cls = Propel::import($omClass);
			$obj2 = new $cls();
			$obj2->hydrate($rs, $startcol2);

			$newObject = true;
			for ($j=0, $resCount=count($results); $j < $resCount; $j++) {
				$temp_obj1 = $results[$j];
				$temp_obj2 = $temp_obj1->getUllFlowDoc(); // CHECKME
				if ($temp_obj2->getPrimaryKey() === $obj2->getPrimaryKey()) {
					$newObject = false;
					$temp_obj2->addUllFlowValue($obj1); // CHECKME
					break;
				}
			}

			if ($newObject) {
				$obj2->initUllFlowValues();
				$obj2->addUllFlowValue($obj1);
			}


				// Add objects for joined UllFlowField rows
	
			$omClass = UllFlowFieldPeer::getOMClass();


			$cls = Propel::import($omClass);
			$obj3 = new $cls();
			$obj3->hydrate($rs, $startcol3);

			$newObject = true;
			for ($j=0, $resCount=count($results); $j < $resCount; $j++) {
				$temp_obj1 = $results[$j];
				$temp_obj3 = $temp_obj1->getUllFlowField(); // CHECKME
				if ($temp_obj3->getPrimaryKey() === $obj3->getPrimaryKey()) {
					$newObject = false;
					$temp_obj3->addUllFlowValue($obj1); // CHECKME
					break;
				}
			}

			if ($newObject) {
				$obj3->initUllFlowValues();
				$obj3->addUllFlowValue($obj1);
			}


				// Add objects for joined UllFlowMemory rows
	
			$omClass = UllFlowMemoryPeer::getOMClass();


			$cls = Propel::import($omClass);
			$obj4 = new $cls();
			$obj4->hydrate($rs, $startcol4);

			$newObject = true;
			for ($j=0, $resCount=count($results); $j < $resCount; $j++) {
				$temp_obj1 = $results[$j];
				$temp_obj4 = $temp_obj1->getUllFlowMemory(); // CHECKME
				if ($temp_obj4->getPrimaryKey() === $obj4->getPrimaryKey()) {
					$newObject = false;
					$temp_obj4->addUllFlowValue($obj1); // CHECKME
					break;
				}
			}

			if ($newObject) {
				$obj4->initUllFlowValues();
				$obj4->addUllFlowValue($obj1);
			}

			$results[] = $obj1;
		}
		return $results;
	}


	/**
	 * Returns the number of rows matching criteria, joining the related UllFlowDoc table
	 *
	 * @param      Criteria $c
	 * @param      boolean $distinct Whether to select only distinct columns (You can also set DISTINCT modifier in Criteria).
	 * @param      Connection $con
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinAllExceptUllFlowDoc(Criteria $criteria, $distinct = false, $con = null)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// clear out anything that might confuse the ORDER BY clause
		$criteria->clearSelectColumns()->clearOrderByColumns();
		if ($distinct || in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->addSelectColumn(UllFlowValuePeer::COUNT_DISTINCT);
		} else {
			$criteria->addSelectColumn(UllFlowValuePeer::COUNT);
		}

		// just in case we're grouping: add those columns to the select statement
		foreach($criteria->getGroupByColumns() as $column)
		{
			$criteria->addSelectColumn($column);
		}

		$criteria->addJoin(UllFlowValuePeer::ULL_FLOW_FIELD_ID, UllFlowFieldPeer::ID);

		$criteria->addJoin(UllFlowValuePeer::ULL_FLOW_MEMORY_ID, UllFlowMemoryPeer::ID);

		$rs = UllFlowValuePeer::doSelectRS($criteria, $con);
		if ($rs->next()) {
			return $rs->getInt(1);
		} else {
			// no rows returned; we infer that means 0 matches.
			return 0;
		}
	}


	/**
	 * Returns the number of rows matching criteria, joining the related UllFlowField table
	 *
	 * @param      Criteria $c
	 * @param      boolean $distinct Whether to select only distinct columns (You can also set DISTINCT modifier in Criteria).
	 * @param      Connection $con
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinAllExceptUllFlowField(Criteria $criteria, $distinct = false, $con = null)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// clear out anything that might confuse the ORDER BY clause
		$criteria->clearSelectColumns()->clearOrderByColumns();
		if ($distinct || in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->addSelectColumn(UllFlowValuePeer::COUNT_DISTINCT);
		} else {
			$criteria->addSelectColumn(UllFlowValuePeer::COUNT);
		}

		// just in case we're grouping: add those columns to the select statement
		foreach($criteria->getGroupByColumns() as $column)
		{
			$criteria->addSelectColumn($column);
		}

		$criteria->addJoin(UllFlowValuePeer::ULL_FLOW_DOC_ID, UllFlowDocPeer::ID);

		$criteria->addJoin(UllFlowValuePeer::ULL_FLOW_MEMORY_ID, UllFlowMemoryPeer::ID);

		$rs = UllFlowValuePeer::doSelectRS($criteria, $con);
		if ($rs->next()) {
			return $rs->getInt(1);
		} else {
			// no rows returned; we infer that means 0 matches.
			return 0;
		}
	}


	/**
	 * Returns the number of rows matching criteria, joining the related UllFlowMemory table
	 *
	 * @param      Criteria $c
	 * @param      boolean $distinct Whether to select only distinct columns (You can also set DISTINCT modifier in Criteria).
	 * @param      Connection $con
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinAllExceptUllFlowMemory(Criteria $criteria, $distinct = false, $con = null)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// clear out anything that might confuse the ORDER BY clause
		$criteria->clearSelectColumns()->clearOrderByColumns();
		if ($distinct || in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->addSelectColumn(UllFlowValuePeer::COUNT_DISTINCT);
		} else {
			$criteria->addSelectColumn(UllFlowValuePeer::COUNT);
		}

		// just in case we're grouping: add those columns to the select statement
		foreach($criteria->getGroupByColumns() as $column)
		{
			$criteria->addSelectColumn($column);
		}

		$criteria->addJoin(UllFlowValuePeer::ULL_FLOW_DOC_ID, UllFlowDocPeer::ID);

		$criteria->addJoin(UllFlowValuePeer::ULL_FLOW_FIELD_ID, UllFlowFieldPeer::ID);

		$rs = UllFlowValuePeer::doSelectRS($criteria, $con);
		if ($rs->next()) {
			return $rs->getInt(1);
		} else {
			// no rows returned; we infer that means 0 matches.
			return 0;
		}
	}


	/**
	 * Selects a collection of UllFlowValue objects pre-filled with all related objects except UllFlowDoc.
	 *
	 * @return     array Array of UllFlowValue objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinAllExceptUllFlowDoc(Criteria $c, $con = null)
	{
		$c = clone $c;

		// Set the correct dbName if it has not been overridden
		// $c->getDbName() will return the same object if not set to another value
		// so == check is okay and faster
		if ($c->getDbName() == Propel::getDefaultDB()) {
			$c->setDbName(self::DATABASE_NAME);
		}

		UllFlowValuePeer::addSelectColumns($c);
		$startcol2 = (UllFlowValuePeer::NUM_COLUMNS - UllFlowValuePeer::NUM_LAZY_LOAD_COLUMNS) + 1;

		UllFlowFieldPeer::addSelectColumns($c);
		$startcol3 = $startcol2 + UllFlowFieldPeer::NUM_COLUMNS;

		UllFlowMemoryPeer::addSelectColumns($c);
		$startcol4 = $startcol3 + UllFlowMemoryPeer::NUM_COLUMNS;

		$c->addJoin(UllFlowValuePeer::ULL_FLOW_FIELD_ID, UllFlowFieldPeer::ID);

		$c->addJoin(UllFlowValuePeer::ULL_FLOW_MEMORY_ID, UllFlowMemoryPeer::ID);


		$rs = BasePeer::doSelect($c, $con);
		$results = array();

		while($rs->next()) {

			$omClass = UllFlowValuePeer::getOMClass();

			$cls = Propel::import($omClass);
			$obj1 = new $cls();
			$obj1->hydrate($rs);

			$omClass = UllFlowFieldPeer::getOMClass();


			$cls = Propel::import($omClass);
			$obj2  = new $cls();
			$obj2->hydrate($rs, $startcol2);

			$newObject = true;
			for ($j=0, $resCount=count($results); $j < $resCount; $j++) {
				$temp_obj1 = $results[$j];
				$temp_obj2 = $temp_obj1->getUllFlowField(); //CHECKME
				if ($temp_obj2->getPrimaryKey() === $obj2->getPrimaryKey()) {
					$newObject = false;
					$temp_obj2->addUllFlowValue($obj1);
					break;
				}
			}

			if ($newObject) {
				$obj2->initUllFlowValues();
				$obj2->addUllFlowValue($obj1);
			}

			$omClass = UllFlowMemoryPeer::getOMClass();


			$cls = Propel::import($omClass);
			$obj3  = new $cls();
			$obj3->hydrate($rs, $startcol3);

			$newObject = true;
			for ($j=0, $resCount=count($results); $j < $resCount; $j++) {
				$temp_obj1 = $results[$j];
				$temp_obj3 = $temp_obj1->getUllFlowMemory(); //CHECKME
				if ($temp_obj3->getPrimaryKey() === $obj3->getPrimaryKey()) {
					$newObject = false;
					$temp_obj3->addUllFlowValue($obj1);
					break;
				}
			}

			if ($newObject) {
				$obj3->initUllFlowValues();
				$obj3->addUllFlowValue($obj1);
			}

			$results[] = $obj1;
		}
		return $results;
	}


	/**
	 * Selects a collection of UllFlowValue objects pre-filled with all related objects except UllFlowField.
	 *
	 * @return     array Array of UllFlowValue objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinAllExceptUllFlowField(Criteria $c, $con = null)
	{
		$c = clone $c;

		// Set the correct dbName if it has not been overridden
		// $c->getDbName() will return the same object if not set to another value
		// so == check is okay and faster
		if ($c->getDbName() == Propel::getDefaultDB()) {
			$c->setDbName(self::DATABASE_NAME);
		}

		UllFlowValuePeer::addSelectColumns($c);
		$startcol2 = (UllFlowValuePeer::NUM_COLUMNS - UllFlowValuePeer::NUM_LAZY_LOAD_COLUMNS) + 1;

		UllFlowDocPeer::addSelectColumns($c);
		$startcol3 = $startcol2 + UllFlowDocPeer::NUM_COLUMNS;

		UllFlowMemoryPeer::addSelectColumns($c);
		$startcol4 = $startcol3 + UllFlowMemoryPeer::NUM_COLUMNS;

		$c->addJoin(UllFlowValuePeer::ULL_FLOW_DOC_ID, UllFlowDocPeer::ID);

		$c->addJoin(UllFlowValuePeer::ULL_FLOW_MEMORY_ID, UllFlowMemoryPeer::ID);


		$rs = BasePeer::doSelect($c, $con);
		$results = array();

		while($rs->next()) {

			$omClass = UllFlowValuePeer::getOMClass();

			$cls = Propel::import($omClass);
			$obj1 = new $cls();
			$obj1->hydrate($rs);

			$omClass = UllFlowDocPeer::getOMClass();


			$cls = Propel::import($omClass);
			$obj2  = new $cls();
			$obj2->hydrate($rs, $startcol2);

			$newObject = true;
			for ($j=0, $resCount=count($results); $j < $resCount; $j++) {
				$temp_obj1 = $results[$j];
				$temp_obj2 = $temp_obj1->getUllFlowDoc(); //CHECKME
				if ($temp_obj2->getPrimaryKey() === $obj2->getPrimaryKey()) {
					$newObject = false;
					$temp_obj2->addUllFlowValue($obj1);
					break;
				}
			}

			if ($newObject) {
				$obj2->initUllFlowValues();
				$obj2->addUllFlowValue($obj1);
			}

			$omClass = UllFlowMemoryPeer::getOMClass();


			$cls = Propel::import($omClass);
			$obj3  = new $cls();
			$obj3->hydrate($rs, $startcol3);

			$newObject = true;
			for ($j=0, $resCount=count($results); $j < $resCount; $j++) {
				$temp_obj1 = $results[$j];
				$temp_obj3 = $temp_obj1->getUllFlowMemory(); //CHECKME
				if ($temp_obj3->getPrimaryKey() === $obj3->getPrimaryKey()) {
					$newObject = false;
					$temp_obj3->addUllFlowValue($obj1);
					break;
				}
			}

			if ($newObject) {
				$obj3->initUllFlowValues();
				$obj3->addUllFlowValue($obj1);
			}

			$results[] = $obj1;
		}
		return $results;
	}


	/**
	 * Selects a collection of UllFlowValue objects pre-filled with all related objects except UllFlowMemory.
	 *
	 * @return     array Array of UllFlowValue objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinAllExceptUllFlowMemory(Criteria $c, $con = null)
	{
		$c = clone $c;

		// Set the correct dbName if it has not been overridden
		// $c->getDbName() will return the same object if not set to another value
		// so == check is okay and faster
		if ($c->getDbName() == Propel::getDefaultDB()) {
			$c->setDbName(self::DATABASE_NAME);
		}

		UllFlowValuePeer::addSelectColumns($c);
		$startcol2 = (UllFlowValuePeer::NUM_COLUMNS - UllFlowValuePeer::NUM_LAZY_LOAD_COLUMNS) + 1;

		UllFlowDocPeer::addSelectColumns($c);
		$startcol3 = $startcol2 + UllFlowDocPeer::NUM_COLUMNS;

		UllFlowFieldPeer::addSelectColumns($c);
		$startcol4 = $startcol3 + UllFlowFieldPeer::NUM_COLUMNS;

		$c->addJoin(UllFlowValuePeer::ULL_FLOW_DOC_ID, UllFlowDocPeer::ID);

		$c->addJoin(UllFlowValuePeer::ULL_FLOW_FIELD_ID, UllFlowFieldPeer::ID);


		$rs = BasePeer::doSelect($c, $con);
		$results = array();

		while($rs->next()) {

			$omClass = UllFlowValuePeer::getOMClass();

			$cls = Propel::import($omClass);
			$obj1 = new $cls();
			$obj1->hydrate($rs);

			$omClass = UllFlowDocPeer::getOMClass();


			$cls = Propel::import($omClass);
			$obj2  = new $cls();
			$obj2->hydrate($rs, $startcol2);

			$newObject = true;
			for ($j=0, $resCount=count($results); $j < $resCount; $j++) {
				$temp_obj1 = $results[$j];
				$temp_obj2 = $temp_obj1->getUllFlowDoc(); //CHECKME
				if ($temp_obj2->getPrimaryKey() === $obj2->getPrimaryKey()) {
					$newObject = false;
					$temp_obj2->addUllFlowValue($obj1);
					break;
				}
			}

			if ($newObject) {
				$obj2->initUllFlowValues();
				$obj2->addUllFlowValue($obj1);
			}

			$omClass = UllFlowFieldPeer::getOMClass();


			$cls = Propel::import($omClass);
			$obj3  = new $cls();
			$obj3->hydrate($rs, $startcol3);

			$newObject = true;
			for ($j=0, $resCount=count($results); $j < $resCount; $j++) {
				$temp_obj1 = $results[$j];
				$temp_obj3 = $temp_obj1->getUllFlowField(); //CHECKME
				if ($temp_obj3->getPrimaryKey() === $obj3->getPrimaryKey()) {
					$newObject = false;
					$temp_obj3->addUllFlowValue($obj1);
					break;
				}
			}

			if ($newObject) {
				$obj3->initUllFlowValues();
				$obj3->addUllFlowValue($obj1);
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
		return UllFlowValuePeer::CLASS_DEFAULT;
	}

	/**
	 * Method perform an INSERT on the database, given a UllFlowValue or Criteria object.
	 *
	 * @param      mixed $values Criteria or UllFlowValue object containing data that is used to create the INSERT statement.
	 * @param      Connection $con the connection to use
	 * @return     mixed The new primary key.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doInsert($values, $con = null)
	{

    foreach (sfMixer::getCallables('BaseUllFlowValuePeer:doInsert:pre') as $callable)
    {
      $ret = call_user_func($callable, 'BaseUllFlowValuePeer', $values, $con);
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
			$criteria = $values->buildCriteria(); // build Criteria from UllFlowValue object
		}

		$criteria->remove(UllFlowValuePeer::ID); // remove pkey col since this table uses auto-increment


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

		
    foreach (sfMixer::getCallables('BaseUllFlowValuePeer:doInsert:post') as $callable)
    {
      call_user_func($callable, 'BaseUllFlowValuePeer', $values, $con, $pk);
    }

    return $pk;
	}

	/**
	 * Method perform an UPDATE on the database, given a UllFlowValue or Criteria object.
	 *
	 * @param      mixed $values Criteria or UllFlowValue object containing data that is used to create the UPDATE statement.
	 * @param      Connection $con The connection to use (specify Connection object to exert more control over transactions).
	 * @return     int The number of affected rows (if supported by underlying database driver).
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doUpdate($values, $con = null)
	{

    foreach (sfMixer::getCallables('BaseUllFlowValuePeer:doUpdate:pre') as $callable)
    {
      $ret = call_user_func($callable, 'BaseUllFlowValuePeer', $values, $con);
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

			$comparison = $criteria->getComparison(UllFlowValuePeer::ID);
			$selectCriteria->add(UllFlowValuePeer::ID, $criteria->remove(UllFlowValuePeer::ID), $comparison);

		} else { // $values is UllFlowValue object
			$criteria = $values->buildCriteria(); // gets full criteria
			$selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
		}

		// set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		$ret = BasePeer::doUpdate($selectCriteria, $criteria, $con);
	

    foreach (sfMixer::getCallables('BaseUllFlowValuePeer:doUpdate:post') as $callable)
    {
      call_user_func($callable, 'BaseUllFlowValuePeer', $values, $con, $ret);
    }

    return $ret;
  }

	/**
	 * Method to DELETE all rows from the ull_flow_value table.
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
			$affectedRows += BasePeer::doDeleteAll(UllFlowValuePeer::TABLE_NAME, $con);
			$con->commit();
			return $affectedRows;
		} catch (PropelException $e) {
			$con->rollback();
			throw $e;
		}
	}

	/**
	 * Method perform a DELETE on the database, given a UllFlowValue or Criteria object OR a primary key value.
	 *
	 * @param      mixed $values Criteria or UllFlowValue object or primary key or array of primary keys
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
			$con = Propel::getConnection(UllFlowValuePeer::DATABASE_NAME);
		}

		if ($values instanceof Criteria) {
			$criteria = clone $values; // rename for clarity
		} elseif ($values instanceof UllFlowValue) {

			$criteria = $values->buildPkeyCriteria();
		} else {
			// it must be the primary key
			$criteria = new Criteria(self::DATABASE_NAME);
			$criteria->add(UllFlowValuePeer::ID, (array) $values, Criteria::IN);
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
	 * Validates all modified columns of given UllFlowValue object.
	 * If parameter $columns is either a single column name or an array of column names
	 * than only those columns are validated.
	 *
	 * NOTICE: This does not apply to primary or foreign keys for now.
	 *
	 * @param      UllFlowValue $obj The object to validate.
	 * @param      mixed $cols Column name or array of column names.
	 *
	 * @return     mixed TRUE if all columns are valid or the error message of the first invalid column.
	 */
	public static function doValidate(UllFlowValue $obj, $cols = null)
	{
		$columns = array();

		if ($cols) {
			$dbMap = Propel::getDatabaseMap(UllFlowValuePeer::DATABASE_NAME);
			$tableMap = $dbMap->getTable(UllFlowValuePeer::TABLE_NAME);

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

		$res =  BasePeer::doValidate(UllFlowValuePeer::DATABASE_NAME, UllFlowValuePeer::TABLE_NAME, $columns);
    if ($res !== true) {
        $request = sfContext::getInstance()->getRequest();
        foreach ($res as $failed) {
            $col = UllFlowValuePeer::translateFieldname($failed->getColumn(), BasePeer::TYPE_COLNAME, BasePeer::TYPE_PHPNAME);
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
	 * @return     UllFlowValue
	 */
	public static function retrieveByPK($pk, $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(self::DATABASE_NAME);
		}

		$criteria = new Criteria(UllFlowValuePeer::DATABASE_NAME);

		$criteria->add(UllFlowValuePeer::ID, $pk);


		$v = UllFlowValuePeer::doSelect($criteria, $con);

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
			$criteria->add(UllFlowValuePeer::ID, $pks, Criteria::IN);
			$objs = UllFlowValuePeer::doSelect($criteria, $con);
		}
		return $objs;
	}

} // BaseUllFlowValuePeer

// static code to register the map builder for this Peer with the main Propel class
if (Propel::isInit()) {
	// the MapBuilder classes register themselves with Propel during initialization
	// so we need to load them here.
	try {
		BaseUllFlowValuePeer::getMapBuilder();
	} catch (Exception $e) {
		Propel::log('Could not initialize Peer: ' . $e->getMessage(), Propel::LOG_ERR);
	}
} else {
	// even if Propel is not yet initialized, the map builder class can be registered
	// now and then it will be loaded when Propel initializes.
	require_once 'plugins/ullFlowPlugin/lib/model/map/UllFlowValueMapBuilder.php';
	Propel::registerMapBuilder('plugins.ullFlowPlugin.lib.model.map.UllFlowValueMapBuilder');
}
