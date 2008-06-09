<?php

/**
 * Base static class for performing query and update operations on the 'ull_flow_field' table.
 *
 * 
 *
 * @package    plugins.ullFlowPlugin.lib.model.om
 */
abstract class BaseUllFlowFieldPeer {

	/** the default database name for this class */
	const DATABASE_NAME = 'propel';

	/** the table name for this class */
	const TABLE_NAME = 'ull_flow_field';

	/** A class that can be returned by this peer. */
	const CLASS_DEFAULT = 'plugins.ullFlowPlugin.lib.model.UllFlowField';

	/** The total number of columns. */
	const NUM_COLUMNS = 17;

	/** The number of lazy-loaded columns. */
	const NUM_LAZY_LOAD_COLUMNS = 0;


	/** the column name for the ID field */
	const ID = 'ull_flow_field.ID';

	/** the column name for the ULL_FLOW_APP_ID field */
	const ULL_FLOW_APP_ID = 'ull_flow_field.ULL_FLOW_APP_ID';

	/** the column name for the ULL_FIELD_ID field */
	const ULL_FIELD_ID = 'ull_flow_field.ULL_FIELD_ID';

	/** the column name for the OPTIONS field */
	const OPTIONS = 'ull_flow_field.OPTIONS';

	/** the column name for the CAPTION_I18N_DEFAULT field */
	const CAPTION_I18N_DEFAULT = 'ull_flow_field.CAPTION_I18N_DEFAULT';

	/** the column name for the SEQUENCE field */
	const SEQUENCE = 'ull_flow_field.SEQUENCE';

	/** the column name for the ENABLED field */
	const ENABLED = 'ull_flow_field.ENABLED';

	/** the column name for the MANDATORY field */
	const MANDATORY = 'ull_flow_field.MANDATORY';

	/** the column name for the IS_TITLE field */
	const IS_TITLE = 'ull_flow_field.IS_TITLE';

	/** the column name for the IS_PRIORITY field */
	const IS_PRIORITY = 'ull_flow_field.IS_PRIORITY';

	/** the column name for the IS_DEADLINE field */
	const IS_DEADLINE = 'ull_flow_field.IS_DEADLINE';

	/** the column name for the IS_CUSTOM_FIELD1 field */
	const IS_CUSTOM_FIELD1 = 'ull_flow_field.IS_CUSTOM_FIELD1';

	/** the column name for the ULL_ACCESS_GROUP_ID field */
	const ULL_ACCESS_GROUP_ID = 'ull_flow_field.ULL_ACCESS_GROUP_ID';

	/** the column name for the CREATOR_USER_ID field */
	const CREATOR_USER_ID = 'ull_flow_field.CREATOR_USER_ID';

	/** the column name for the CREATED_AT field */
	const CREATED_AT = 'ull_flow_field.CREATED_AT';

	/** the column name for the UPDATOR_USER_ID field */
	const UPDATOR_USER_ID = 'ull_flow_field.UPDATOR_USER_ID';

	/** the column name for the UPDATED_AT field */
	const UPDATED_AT = 'ull_flow_field.UPDATED_AT';

	/** The PHP to DB Name Mapping */
	private static $phpNameMap = null;


	/**
	 * holds an array of fieldnames
	 *
	 * first dimension keys are the type constants
	 * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
	 */
	private static $fieldNames = array (
		BasePeer::TYPE_PHPNAME => array ('Id', 'UllFlowAppId', 'UllFieldId', 'Options', 'CaptionI18nDefault', 'Sequence', 'Enabled', 'Mandatory', 'IsTitle', 'IsPriority', 'IsDeadline', 'IsCustomField1', 'UllAccessGroupId', 'CreatorUserId', 'CreatedAt', 'UpdatorUserId', 'UpdatedAt', ),
		BasePeer::TYPE_COLNAME => array (UllFlowFieldPeer::ID, UllFlowFieldPeer::ULL_FLOW_APP_ID, UllFlowFieldPeer::ULL_FIELD_ID, UllFlowFieldPeer::OPTIONS, UllFlowFieldPeer::CAPTION_I18N_DEFAULT, UllFlowFieldPeer::SEQUENCE, UllFlowFieldPeer::ENABLED, UllFlowFieldPeer::MANDATORY, UllFlowFieldPeer::IS_TITLE, UllFlowFieldPeer::IS_PRIORITY, UllFlowFieldPeer::IS_DEADLINE, UllFlowFieldPeer::IS_CUSTOM_FIELD1, UllFlowFieldPeer::ULL_ACCESS_GROUP_ID, UllFlowFieldPeer::CREATOR_USER_ID, UllFlowFieldPeer::CREATED_AT, UllFlowFieldPeer::UPDATOR_USER_ID, UllFlowFieldPeer::UPDATED_AT, ),
		BasePeer::TYPE_FIELDNAME => array ('id', 'ull_flow_app_id', 'ull_field_id', 'options', 'caption_i18n_default', 'sequence', 'enabled', 'mandatory', 'is_title', 'is_priority', 'is_deadline', 'is_custom_field1', 'ull_access_group_id', 'creator_user_id', 'created_at', 'updator_user_id', 'updated_at', ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, )
	);

	/**
	 * holds an array of keys for quick access to the fieldnames array
	 *
	 * first dimension keys are the type constants
	 * e.g. self::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
	 */
	private static $fieldKeys = array (
		BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'UllFlowAppId' => 1, 'UllFieldId' => 2, 'Options' => 3, 'CaptionI18nDefault' => 4, 'Sequence' => 5, 'Enabled' => 6, 'Mandatory' => 7, 'IsTitle' => 8, 'IsPriority' => 9, 'IsDeadline' => 10, 'IsCustomField1' => 11, 'UllAccessGroupId' => 12, 'CreatorUserId' => 13, 'CreatedAt' => 14, 'UpdatorUserId' => 15, 'UpdatedAt' => 16, ),
		BasePeer::TYPE_COLNAME => array (UllFlowFieldPeer::ID => 0, UllFlowFieldPeer::ULL_FLOW_APP_ID => 1, UllFlowFieldPeer::ULL_FIELD_ID => 2, UllFlowFieldPeer::OPTIONS => 3, UllFlowFieldPeer::CAPTION_I18N_DEFAULT => 4, UllFlowFieldPeer::SEQUENCE => 5, UllFlowFieldPeer::ENABLED => 6, UllFlowFieldPeer::MANDATORY => 7, UllFlowFieldPeer::IS_TITLE => 8, UllFlowFieldPeer::IS_PRIORITY => 9, UllFlowFieldPeer::IS_DEADLINE => 10, UllFlowFieldPeer::IS_CUSTOM_FIELD1 => 11, UllFlowFieldPeer::ULL_ACCESS_GROUP_ID => 12, UllFlowFieldPeer::CREATOR_USER_ID => 13, UllFlowFieldPeer::CREATED_AT => 14, UllFlowFieldPeer::UPDATOR_USER_ID => 15, UllFlowFieldPeer::UPDATED_AT => 16, ),
		BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'ull_flow_app_id' => 1, 'ull_field_id' => 2, 'options' => 3, 'caption_i18n_default' => 4, 'sequence' => 5, 'enabled' => 6, 'mandatory' => 7, 'is_title' => 8, 'is_priority' => 9, 'is_deadline' => 10, 'is_custom_field1' => 11, 'ull_access_group_id' => 12, 'creator_user_id' => 13, 'created_at' => 14, 'updator_user_id' => 15, 'updated_at' => 16, ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, )
	);

	/**
	 * @return     MapBuilder the map builder for this peer
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function getMapBuilder()
	{
		include_once 'plugins/ullFlowPlugin/lib/model/map/UllFlowFieldMapBuilder.php';
		return BasePeer::getMapBuilder('plugins.ullFlowPlugin.lib.model.map.UllFlowFieldMapBuilder');
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
			$map = UllFlowFieldPeer::getTableMap();
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
	 * @param      string $column The column name for current table. (i.e. UllFlowFieldPeer::COLUMN_NAME).
	 * @return     string
	 */
	public static function alias($alias, $column)
	{
		return str_replace(UllFlowFieldPeer::TABLE_NAME.'.', $alias.'.', $column);
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

		$criteria->addSelectColumn(UllFlowFieldPeer::ID);

		$criteria->addSelectColumn(UllFlowFieldPeer::ULL_FLOW_APP_ID);

		$criteria->addSelectColumn(UllFlowFieldPeer::ULL_FIELD_ID);

		$criteria->addSelectColumn(UllFlowFieldPeer::OPTIONS);

		$criteria->addSelectColumn(UllFlowFieldPeer::CAPTION_I18N_DEFAULT);

		$criteria->addSelectColumn(UllFlowFieldPeer::SEQUENCE);

		$criteria->addSelectColumn(UllFlowFieldPeer::ENABLED);

		$criteria->addSelectColumn(UllFlowFieldPeer::MANDATORY);

		$criteria->addSelectColumn(UllFlowFieldPeer::IS_TITLE);

		$criteria->addSelectColumn(UllFlowFieldPeer::IS_PRIORITY);

		$criteria->addSelectColumn(UllFlowFieldPeer::IS_DEADLINE);

		$criteria->addSelectColumn(UllFlowFieldPeer::IS_CUSTOM_FIELD1);

		$criteria->addSelectColumn(UllFlowFieldPeer::ULL_ACCESS_GROUP_ID);

		$criteria->addSelectColumn(UllFlowFieldPeer::CREATOR_USER_ID);

		$criteria->addSelectColumn(UllFlowFieldPeer::CREATED_AT);

		$criteria->addSelectColumn(UllFlowFieldPeer::UPDATOR_USER_ID);

		$criteria->addSelectColumn(UllFlowFieldPeer::UPDATED_AT);

	}

	const COUNT = 'COUNT(ull_flow_field.ID)';
	const COUNT_DISTINCT = 'COUNT(DISTINCT ull_flow_field.ID)';

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
			$criteria->addSelectColumn(UllFlowFieldPeer::COUNT_DISTINCT);
		} else {
			$criteria->addSelectColumn(UllFlowFieldPeer::COUNT);
		}

		// just in case we're grouping: add those columns to the select statement
		foreach($criteria->getGroupByColumns() as $column)
		{
			$criteria->addSelectColumn($column);
		}

		$rs = UllFlowFieldPeer::doSelectRS($criteria, $con);
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
	 * @return     UllFlowField
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectOne(Criteria $criteria, $con = null)
	{
		$critcopy = clone $criteria;
		$critcopy->setLimit(1);
		$objects = UllFlowFieldPeer::doSelect($critcopy, $con);
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
		return UllFlowFieldPeer::populateObjects(UllFlowFieldPeer::doSelectRS($criteria, $con));
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

    foreach (sfMixer::getCallables('BaseUllFlowFieldPeer:addDoSelectRS:addDoSelectRS') as $callable)
    {
      call_user_func($callable, 'BaseUllFlowFieldPeer', $criteria, $con);
    }


		if ($con === null) {
			$con = Propel::getConnection(self::DATABASE_NAME);
		}

		if (!$criteria->getSelectColumns()) {
			$criteria = clone $criteria;
			UllFlowFieldPeer::addSelectColumns($criteria);
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
		$cls = UllFlowFieldPeer::getOMClass();
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
			$criteria->addSelectColumn(UllFlowFieldPeer::COUNT_DISTINCT);
		} else {
			$criteria->addSelectColumn(UllFlowFieldPeer::COUNT);
		}

		// just in case we're grouping: add those columns to the select statement
		foreach($criteria->getGroupByColumns() as $column)
		{
			$criteria->addSelectColumn($column);
		}

		$criteria->addJoin(UllFlowFieldPeer::ULL_FLOW_APP_ID, UllFlowAppPeer::ID);

		$rs = UllFlowFieldPeer::doSelectRS($criteria, $con);
		if ($rs->next()) {
			return $rs->getInt(1);
		} else {
			// no rows returned; we infer that means 0 matches.
			return 0;
		}
	}


	/**
	 * Selects a collection of UllFlowField objects pre-filled with their UllFlowApp objects.
	 *
	 * @return     array Array of UllFlowField objects.
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

		UllFlowFieldPeer::addSelectColumns($c);
		$startcol = (UllFlowFieldPeer::NUM_COLUMNS - UllFlowFieldPeer::NUM_LAZY_LOAD_COLUMNS) + 1;
		UllFlowAppPeer::addSelectColumns($c);

		$c->addJoin(UllFlowFieldPeer::ULL_FLOW_APP_ID, UllFlowAppPeer::ID);
		$rs = BasePeer::doSelect($c, $con);
		$results = array();

		while($rs->next()) {

			$omClass = UllFlowFieldPeer::getOMClass();

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
					$temp_obj2->addUllFlowField($obj1); //CHECKME
					break;
				}
			}
			if ($newObject) {
				$obj2->initUllFlowFields();
				$obj2->addUllFlowField($obj1); //CHECKME
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
			$criteria->addSelectColumn(UllFlowFieldPeer::COUNT_DISTINCT);
		} else {
			$criteria->addSelectColumn(UllFlowFieldPeer::COUNT);
		}

		// just in case we're grouping: add those columns to the select statement
		foreach($criteria->getGroupByColumns() as $column)
		{
			$criteria->addSelectColumn($column);
		}

		$criteria->addJoin(UllFlowFieldPeer::ULL_FLOW_APP_ID, UllFlowAppPeer::ID);

		$rs = UllFlowFieldPeer::doSelectRS($criteria, $con);
		if ($rs->next()) {
			return $rs->getInt(1);
		} else {
			// no rows returned; we infer that means 0 matches.
			return 0;
		}
	}


	/**
	 * Selects a collection of UllFlowField objects pre-filled with all related objects.
	 *
	 * @return     array Array of UllFlowField objects.
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

		UllFlowFieldPeer::addSelectColumns($c);
		$startcol2 = (UllFlowFieldPeer::NUM_COLUMNS - UllFlowFieldPeer::NUM_LAZY_LOAD_COLUMNS) + 1;

		UllFlowAppPeer::addSelectColumns($c);
		$startcol3 = $startcol2 + UllFlowAppPeer::NUM_COLUMNS;

		$c->addJoin(UllFlowFieldPeer::ULL_FLOW_APP_ID, UllFlowAppPeer::ID);

		$rs = BasePeer::doSelect($c, $con);
		$results = array();

		while($rs->next()) {

			$omClass = UllFlowFieldPeer::getOMClass();


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
					$temp_obj2->addUllFlowField($obj1); // CHECKME
					break;
				}
			}

			if ($newObject) {
				$obj2->initUllFlowFields();
				$obj2->addUllFlowField($obj1);
			}

			$results[] = $obj1;
		}
		return $results;
	}


  /**
   * Selects a collection of UllFlowField objects pre-filled with their i18n objects.
   *
   * @return array Array of UllFlowField objects.
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

    UllFlowFieldPeer::addSelectColumns($c);
    $startcol = (UllFlowFieldPeer::NUM_COLUMNS - UllFlowFieldPeer::NUM_LAZY_LOAD_COLUMNS) + 1;

    UllFlowFieldI18nPeer::addSelectColumns($c);

    $c->addJoin(UllFlowFieldPeer::ID, UllFlowFieldI18nPeer::ID);
    $c->add(UllFlowFieldI18nPeer::CULTURE, $culture);

    $rs = BasePeer::doSelect($c, $con);
    $results = array();

    while($rs->next()) {

      $omClass = UllFlowFieldPeer::getOMClass();

      $cls = Propel::import($omClass);
      $obj1 = new $cls();
      $obj1->hydrate($rs);
      $obj1->setCulture($culture);

      $omClass = UllFlowFieldI18nPeer::getOMClass($rs, $startcol);

      $cls = Propel::import($omClass);
      $obj2 = new $cls();
      $obj2->hydrate($rs, $startcol);

      $obj1->setUllFlowFieldI18nForCulture($obj2, $culture);
      $obj2->setUllFlowField($obj1);

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
		return UllFlowFieldPeer::CLASS_DEFAULT;
	}

	/**
	 * Method perform an INSERT on the database, given a UllFlowField or Criteria object.
	 *
	 * @param      mixed $values Criteria or UllFlowField object containing data that is used to create the INSERT statement.
	 * @param      Connection $con the connection to use
	 * @return     mixed The new primary key.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doInsert($values, $con = null)
	{

    foreach (sfMixer::getCallables('BaseUllFlowFieldPeer:doInsert:pre') as $callable)
    {
      $ret = call_user_func($callable, 'BaseUllFlowFieldPeer', $values, $con);
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
			$criteria = $values->buildCriteria(); // build Criteria from UllFlowField object
		}

		$criteria->remove(UllFlowFieldPeer::ID); // remove pkey col since this table uses auto-increment


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

		
    foreach (sfMixer::getCallables('BaseUllFlowFieldPeer:doInsert:post') as $callable)
    {
      call_user_func($callable, 'BaseUllFlowFieldPeer', $values, $con, $pk);
    }

    return $pk;
	}

	/**
	 * Method perform an UPDATE on the database, given a UllFlowField or Criteria object.
	 *
	 * @param      mixed $values Criteria or UllFlowField object containing data that is used to create the UPDATE statement.
	 * @param      Connection $con The connection to use (specify Connection object to exert more control over transactions).
	 * @return     int The number of affected rows (if supported by underlying database driver).
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doUpdate($values, $con = null)
	{

    foreach (sfMixer::getCallables('BaseUllFlowFieldPeer:doUpdate:pre') as $callable)
    {
      $ret = call_user_func($callable, 'BaseUllFlowFieldPeer', $values, $con);
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

			$comparison = $criteria->getComparison(UllFlowFieldPeer::ID);
			$selectCriteria->add(UllFlowFieldPeer::ID, $criteria->remove(UllFlowFieldPeer::ID), $comparison);

		} else { // $values is UllFlowField object
			$criteria = $values->buildCriteria(); // gets full criteria
			$selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
		}

		// set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		$ret = BasePeer::doUpdate($selectCriteria, $criteria, $con);
	

    foreach (sfMixer::getCallables('BaseUllFlowFieldPeer:doUpdate:post') as $callable)
    {
      call_user_func($callable, 'BaseUllFlowFieldPeer', $values, $con, $ret);
    }

    return $ret;
  }

	/**
	 * Method to DELETE all rows from the ull_flow_field table.
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
			$affectedRows += UllFlowFieldPeer::doOnDeleteCascade(new Criteria(), $con);
			$affectedRows += BasePeer::doDeleteAll(UllFlowFieldPeer::TABLE_NAME, $con);
			$con->commit();
			return $affectedRows;
		} catch (PropelException $e) {
			$con->rollback();
			throw $e;
		}
	}

	/**
	 * Method perform a DELETE on the database, given a UllFlowField or Criteria object OR a primary key value.
	 *
	 * @param      mixed $values Criteria or UllFlowField object or primary key or array of primary keys
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
			$con = Propel::getConnection(UllFlowFieldPeer::DATABASE_NAME);
		}

		if ($values instanceof Criteria) {
			$criteria = clone $values; // rename for clarity
		} elseif ($values instanceof UllFlowField) {

			$criteria = $values->buildPkeyCriteria();
		} else {
			// it must be the primary key
			$criteria = new Criteria(self::DATABASE_NAME);
			$criteria->add(UllFlowFieldPeer::ID, (array) $values, Criteria::IN);
		}

		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		$affectedRows = 0; // initialize var to track total num of affected rows

		try {
			// use transaction because $criteria could contain info
			// for more than one table or we could emulating ON DELETE CASCADE, etc.
			$con->begin();
			$affectedRows += UllFlowFieldPeer::doOnDeleteCascade($criteria, $con);
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
		$objects = UllFlowFieldPeer::doSelect($criteria, $con);
		foreach($objects as $obj) {


			include_once 'plugins/ullFlowPlugin/lib/model/UllFlowFieldI18n.php';

			// delete related UllFlowFieldI18n objects
			$c = new Criteria();
			
			$c->add(UllFlowFieldI18nPeer::ID, $obj->getId());
			$affectedRows += UllFlowFieldI18nPeer::doDelete($c, $con);
		}
		return $affectedRows;
	}

	/**
	 * Validates all modified columns of given UllFlowField object.
	 * If parameter $columns is either a single column name or an array of column names
	 * than only those columns are validated.
	 *
	 * NOTICE: This does not apply to primary or foreign keys for now.
	 *
	 * @param      UllFlowField $obj The object to validate.
	 * @param      mixed $cols Column name or array of column names.
	 *
	 * @return     mixed TRUE if all columns are valid or the error message of the first invalid column.
	 */
	public static function doValidate(UllFlowField $obj, $cols = null)
	{
		$columns = array();

		if ($cols) {
			$dbMap = Propel::getDatabaseMap(UllFlowFieldPeer::DATABASE_NAME);
			$tableMap = $dbMap->getTable(UllFlowFieldPeer::TABLE_NAME);

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

		$res =  BasePeer::doValidate(UllFlowFieldPeer::DATABASE_NAME, UllFlowFieldPeer::TABLE_NAME, $columns);
    if ($res !== true) {
        $request = sfContext::getInstance()->getRequest();
        foreach ($res as $failed) {
            $col = UllFlowFieldPeer::translateFieldname($failed->getColumn(), BasePeer::TYPE_COLNAME, BasePeer::TYPE_PHPNAME);
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
	 * @return     UllFlowField
	 */
	public static function retrieveByPK($pk, $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(self::DATABASE_NAME);
		}

		$criteria = new Criteria(UllFlowFieldPeer::DATABASE_NAME);

		$criteria->add(UllFlowFieldPeer::ID, $pk);


		$v = UllFlowFieldPeer::doSelect($criteria, $con);

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
			$criteria->add(UllFlowFieldPeer::ID, $pks, Criteria::IN);
			$objs = UllFlowFieldPeer::doSelect($criteria, $con);
		}
		return $objs;
	}

} // BaseUllFlowFieldPeer

// static code to register the map builder for this Peer with the main Propel class
if (Propel::isInit()) {
	// the MapBuilder classes register themselves with Propel during initialization
	// so we need to load them here.
	try {
		BaseUllFlowFieldPeer::getMapBuilder();
	} catch (Exception $e) {
		Propel::log('Could not initialize Peer: ' . $e->getMessage(), Propel::LOG_ERR);
	}
} else {
	// even if Propel is not yet initialized, the map builder class can be registered
	// now and then it will be loaded when Propel initializes.
	require_once 'plugins/ullFlowPlugin/lib/model/map/UllFlowFieldMapBuilder.php';
	Propel::registerMapBuilder('plugins.ullFlowPlugin.lib.model.map.UllFlowFieldMapBuilder');
}
