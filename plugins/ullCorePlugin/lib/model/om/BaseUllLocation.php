<?php

/**
 * Base class that represents a row from the 'ull_location' table.
 *
 * 
 *
 * @package    plugins.ullCorePlugin.lib.model.om
 */
abstract class BaseUllLocation extends BaseObject  implements Persistent {


	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        UllLocationPeer
	 */
	protected static $peer;


	/**
	 * The value for the id field.
	 * @var        int
	 */
	protected $id;


	/**
	 * The value for the name field.
	 * @var        string
	 */
	protected $name;


	/**
	 * The value for the short field.
	 * @var        string
	 */
	protected $short;


	/**
	 * The value for the company_id field.
	 * @var        int
	 */
	protected $company_id;


	/**
	 * The value for the street field.
	 * @var        string
	 */
	protected $street;


	/**
	 * The value for the zip field.
	 * @var        string
	 */
	protected $zip;


	/**
	 * The value for the city field.
	 * @var        string
	 */
	protected $city;


	/**
	 * The value for the country_id field.
	 * @var        int
	 */
	protected $country_id;


	/**
	 * The value for the phone_trunk_num field.
	 * @var        string
	 */
	protected $phone_trunk_num;


	/**
	 * The value for the phone_std_ext_num field.
	 * @var        string
	 */
	protected $phone_std_ext_num;


	/**
	 * The value for the fax_trunk_num field.
	 * @var        string
	 */
	protected $fax_trunk_num;


	/**
	 * The value for the fax_std_ext_num field.
	 * @var        string
	 */
	protected $fax_std_ext_num;


	/**
	 * The value for the creator_user_id field.
	 * @var        int
	 */
	protected $creator_user_id;


	/**
	 * The value for the created_at field.
	 * @var        int
	 */
	protected $created_at;


	/**
	 * The value for the updator_user_id field.
	 * @var        int
	 */
	protected $updator_user_id;

	/**
	 * Flag to prevent endless save loop, if this object is referenced
	 * by another object which falls in this transaction.
	 * @var        boolean
	 */
	protected $alreadyInSave = false;

	/**
	 * Flag to prevent endless validation loop, if this object is referenced
	 * by another object which falls in this transaction.
	 * @var        boolean
	 */
	protected $alreadyInValidation = false;

	/**
	 * Get the [id] column value.
	 * 
	 * @return     int
	 */
	public function getId()
	{

		return $this->id;
	}

	/**
	 * Get the [name] column value.
	 * 
	 * @return     string
	 */
	public function getName()
	{

		return $this->name;
	}

	/**
	 * Get the [short] column value.
	 * 
	 * @return     string
	 */
	public function getShort()
	{

		return $this->short;
	}

	/**
	 * Get the [company_id] column value.
	 * 
	 * @return     int
	 */
	public function getCompanyId()
	{

		return $this->company_id;
	}

	/**
	 * Get the [street] column value.
	 * 
	 * @return     string
	 */
	public function getStreet()
	{

		return $this->street;
	}

	/**
	 * Get the [zip] column value.
	 * 
	 * @return     string
	 */
	public function getZip()
	{

		return $this->zip;
	}

	/**
	 * Get the [city] column value.
	 * 
	 * @return     string
	 */
	public function getCity()
	{

		return $this->city;
	}

	/**
	 * Get the [country_id] column value.
	 * 
	 * @return     int
	 */
	public function getCountryId()
	{

		return $this->country_id;
	}

	/**
	 * Get the [phone_trunk_num] column value.
	 * 
	 * @return     string
	 */
	public function getPhoneTrunkNum()
	{

		return $this->phone_trunk_num;
	}

	/**
	 * Get the [phone_std_ext_num] column value.
	 * 
	 * @return     string
	 */
	public function getPhoneStdExtNum()
	{

		return $this->phone_std_ext_num;
	}

	/**
	 * Get the [fax_trunk_num] column value.
	 * 
	 * @return     string
	 */
	public function getFaxTrunkNum()
	{

		return $this->fax_trunk_num;
	}

	/**
	 * Get the [fax_std_ext_num] column value.
	 * 
	 * @return     string
	 */
	public function getFaxStdExtNum()
	{

		return $this->fax_std_ext_num;
	}

	/**
	 * Get the [creator_user_id] column value.
	 * 
	 * @return     int
	 */
	public function getCreatorUserId()
	{

		return $this->creator_user_id;
	}

	/**
	 * Get the [optionally formatted] [created_at] column value.
	 * 
	 * @param      string $format The date/time format string (either date()-style or strftime()-style).
	 *							If format is NULL, then the integer unix timestamp will be returned.
	 * @return     mixed Formatted date/time value as string or integer unix timestamp (if format is NULL).
	 * @throws     PropelException - if unable to convert the date/time to timestamp.
	 */
	public function getCreatedAt($format = 'Y-m-d H:i:s')
	{

		if ($this->created_at === null || $this->created_at === '') {
			return null;
		} elseif (!is_int($this->created_at)) {
			// a non-timestamp value was set externally, so we convert it
			$ts = strtotime($this->created_at);
			if ($ts === -1 || $ts === false) { // in PHP 5.1 return value changes to FALSE
				throw new PropelException("Unable to parse value of [created_at] as date/time value: " . var_export($this->created_at, true));
			}
		} else {
			$ts = $this->created_at;
		}
		if ($format === null) {
			return $ts;
		} elseif (strpos($format, '%') !== false) {
			return strftime($format, $ts);
		} else {
			return date($format, $ts);
		}
	}

	/**
	 * Get the [updator_user_id] column value.
	 * 
	 * @return     int
	 */
	public function getUpdatorUserId()
	{

		return $this->updator_user_id;
	}

	/**
	 * Set the value of [id] column.
	 * 
	 * @param      int $v new value
	 * @return     void
	 */
	public function setId($v)
	{

		// Since the native PHP type for this column is integer,
		// we will cast the input value to an int (if it is not).
		if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->id !== $v) {
			$this->id = $v;
			$this->modifiedColumns[] = UllLocationPeer::ID;
		}

	} // setId()

	/**
	 * Set the value of [name] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setName($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->name !== $v) {
			$this->name = $v;
			$this->modifiedColumns[] = UllLocationPeer::NAME;
		}

	} // setName()

	/**
	 * Set the value of [short] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setShort($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->short !== $v) {
			$this->short = $v;
			$this->modifiedColumns[] = UllLocationPeer::SHORT;
		}

	} // setShort()

	/**
	 * Set the value of [company_id] column.
	 * 
	 * @param      int $v new value
	 * @return     void
	 */
	public function setCompanyId($v)
	{

		// Since the native PHP type for this column is integer,
		// we will cast the input value to an int (if it is not).
		if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->company_id !== $v) {
			$this->company_id = $v;
			$this->modifiedColumns[] = UllLocationPeer::COMPANY_ID;
		}

	} // setCompanyId()

	/**
	 * Set the value of [street] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setStreet($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->street !== $v) {
			$this->street = $v;
			$this->modifiedColumns[] = UllLocationPeer::STREET;
		}

	} // setStreet()

	/**
	 * Set the value of [zip] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setZip($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->zip !== $v) {
			$this->zip = $v;
			$this->modifiedColumns[] = UllLocationPeer::ZIP;
		}

	} // setZip()

	/**
	 * Set the value of [city] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setCity($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->city !== $v) {
			$this->city = $v;
			$this->modifiedColumns[] = UllLocationPeer::CITY;
		}

	} // setCity()

	/**
	 * Set the value of [country_id] column.
	 * 
	 * @param      int $v new value
	 * @return     void
	 */
	public function setCountryId($v)
	{

		// Since the native PHP type for this column is integer,
		// we will cast the input value to an int (if it is not).
		if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->country_id !== $v) {
			$this->country_id = $v;
			$this->modifiedColumns[] = UllLocationPeer::COUNTRY_ID;
		}

	} // setCountryId()

	/**
	 * Set the value of [phone_trunk_num] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setPhoneTrunkNum($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->phone_trunk_num !== $v) {
			$this->phone_trunk_num = $v;
			$this->modifiedColumns[] = UllLocationPeer::PHONE_TRUNK_NUM;
		}

	} // setPhoneTrunkNum()

	/**
	 * Set the value of [phone_std_ext_num] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setPhoneStdExtNum($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->phone_std_ext_num !== $v) {
			$this->phone_std_ext_num = $v;
			$this->modifiedColumns[] = UllLocationPeer::PHONE_STD_EXT_NUM;
		}

	} // setPhoneStdExtNum()

	/**
	 * Set the value of [fax_trunk_num] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setFaxTrunkNum($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->fax_trunk_num !== $v) {
			$this->fax_trunk_num = $v;
			$this->modifiedColumns[] = UllLocationPeer::FAX_TRUNK_NUM;
		}

	} // setFaxTrunkNum()

	/**
	 * Set the value of [fax_std_ext_num] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setFaxStdExtNum($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->fax_std_ext_num !== $v) {
			$this->fax_std_ext_num = $v;
			$this->modifiedColumns[] = UllLocationPeer::FAX_STD_EXT_NUM;
		}

	} // setFaxStdExtNum()

	/**
	 * Set the value of [creator_user_id] column.
	 * 
	 * @param      int $v new value
	 * @return     void
	 */
	public function setCreatorUserId($v)
	{

		// Since the native PHP type for this column is integer,
		// we will cast the input value to an int (if it is not).
		if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->creator_user_id !== $v) {
			$this->creator_user_id = $v;
			$this->modifiedColumns[] = UllLocationPeer::CREATOR_USER_ID;
		}

	} // setCreatorUserId()

	/**
	 * Set the value of [created_at] column.
	 * 
	 * @param      int $v new value
	 * @return     void
	 */
	public function setCreatedAt($v)
	{

		if ($v !== null && !is_int($v)) {
			$ts = strtotime($v);
			if ($ts === -1 || $ts === false) { // in PHP 5.1 return value changes to FALSE
				throw new PropelException("Unable to parse date/time value for [created_at] from input: " . var_export($v, true));
			}
		} else {
			$ts = $v;
		}
		if ($this->created_at !== $ts) {
			$this->created_at = $ts;
			$this->modifiedColumns[] = UllLocationPeer::CREATED_AT;
		}

	} // setCreatedAt()

	/**
	 * Set the value of [updator_user_id] column.
	 * 
	 * @param      int $v new value
	 * @return     void
	 */
	public function setUpdatorUserId($v)
	{

		// Since the native PHP type for this column is integer,
		// we will cast the input value to an int (if it is not).
		if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->updator_user_id !== $v) {
			$this->updator_user_id = $v;
			$this->modifiedColumns[] = UllLocationPeer::UPDATOR_USER_ID;
		}

	} // setUpdatorUserId()

	/**
	 * Hydrates (populates) the object variables with values from the database resultset.
	 *
	 * An offset (1-based "start column") is specified so that objects can be hydrated
	 * with a subset of the columns in the resultset rows.  This is needed, for example,
	 * for results of JOIN queries where the resultset row includes columns from two or
	 * more tables.
	 *
	 * @param      ResultSet $rs The ResultSet class with cursor advanced to desired record pos.
	 * @param      int $startcol 1-based offset column which indicates which restultset column to start with.
	 * @return     int next starting column
	 * @throws     PropelException  - Any caught Exception will be rewrapped as a PropelException.
	 */
	public function hydrate(ResultSet $rs, $startcol = 1)
	{
		try {

			$this->id = $rs->getInt($startcol + 0);

			$this->name = $rs->getString($startcol + 1);

			$this->short = $rs->getString($startcol + 2);

			$this->company_id = $rs->getInt($startcol + 3);

			$this->street = $rs->getString($startcol + 4);

			$this->zip = $rs->getString($startcol + 5);

			$this->city = $rs->getString($startcol + 6);

			$this->country_id = $rs->getInt($startcol + 7);

			$this->phone_trunk_num = $rs->getString($startcol + 8);

			$this->phone_std_ext_num = $rs->getString($startcol + 9);

			$this->fax_trunk_num = $rs->getString($startcol + 10);

			$this->fax_std_ext_num = $rs->getString($startcol + 11);

			$this->creator_user_id = $rs->getInt($startcol + 12);

			$this->created_at = $rs->getTimestamp($startcol + 13, null);

			$this->updator_user_id = $rs->getInt($startcol + 14);

			$this->resetModified();

			$this->setNew(false);

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + 15; // 15 = UllLocationPeer::NUM_COLUMNS - UllLocationPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating UllLocation object", $e);
		}
	}

	/**
	 * Removes this object from datastore and sets delete attribute.
	 *
	 * @param      Connection $con
	 * @return     void
	 * @throws     PropelException
	 * @see        BaseObject::setDeleted()
	 * @see        BaseObject::isDeleted()
	 */
	public function delete($con = null)
	{

    foreach (sfMixer::getCallables('BaseUllLocation:delete:pre') as $callable)
    {
      $ret = call_user_func($callable, $this, $con);
      if ($ret)
      {
        return;
      }
    }


		if ($this->isDeleted()) {
			throw new PropelException("This object has already been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(UllLocationPeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			UllLocationPeer::doDelete($this, $con);
			$this->setDeleted(true);
			$con->commit();
		} catch (PropelException $e) {
			$con->rollback();
			throw $e;
		}
	

    foreach (sfMixer::getCallables('BaseUllLocation:delete:post') as $callable)
    {
      call_user_func($callable, $this, $con);
    }

  }
	/**
	 * Stores the object in the database.  If the object is new,
	 * it inserts it; otherwise an update is performed.  This method
	 * wraps the doSave() worker method in a transaction.
	 *
	 * @param      Connection $con
	 * @return     int The number of rows affected by this insert/update and any referring fk objects' save() operations.
	 * @throws     PropelException
	 * @see        doSave()
	 */
	public function save($con = null)
	{

    foreach (sfMixer::getCallables('BaseUllLocation:save:pre') as $callable)
    {
      $affectedRows = call_user_func($callable, $this, $con);
      if (is_int($affectedRows))
      {
        return $affectedRows;
      }
    }


    if ($this->isNew() && !$this->isColumnModified(UllLocationPeer::CREATED_AT))
    {
      $this->setCreatedAt(time());
    }

		if ($this->isDeleted()) {
			throw new PropelException("You cannot save an object that has been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(UllLocationPeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			$affectedRows = $this->doSave($con);
			$con->commit();
    foreach (sfMixer::getCallables('BaseUllLocation:save:post') as $callable)
    {
      call_user_func($callable, $this, $con, $affectedRows);
    }

			return $affectedRows;
		} catch (PropelException $e) {
			$con->rollback();
			throw $e;
		}
	}

	/**
	 * Stores the object in the database.
	 *
	 * If the object is new, it inserts it; otherwise an update is performed.
	 * All related objects are also updated in this method.
	 *
	 * @param      Connection $con
	 * @return     int The number of rows affected by this insert/update and any referring fk objects' save() operations.
	 * @throws     PropelException
	 * @see        save()
	 */
	protected function doSave($con)
	{
		$affectedRows = 0; // initialize var to track total num of affected rows
		if (!$this->alreadyInSave) {
			$this->alreadyInSave = true;


			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = UllLocationPeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setId($pk);  //[IMV] update autoincrement primary key

					$this->setNew(false);
				} else {
					$affectedRows += UllLocationPeer::doUpdate($this, $con);
				}
				$this->resetModified(); // [HL] After being saved an object is no longer 'modified'
			}

			$this->alreadyInSave = false;
		}
		return $affectedRows;
	} // doSave()

	/**
	 * Array of ValidationFailed objects.
	 * @var        array ValidationFailed[]
	 */
	protected $validationFailures = array();

	/**
	 * Gets any ValidationFailed objects that resulted from last call to validate().
	 *
	 *
	 * @return     array ValidationFailed[]
	 * @see        validate()
	 */
	public function getValidationFailures()
	{
		return $this->validationFailures;
	}

	/**
	 * Validates the objects modified field values and all objects related to this table.
	 *
	 * If $columns is either a column name or an array of column names
	 * only those columns are validated.
	 *
	 * @param      mixed $columns Column name or an array of column names.
	 * @return     boolean Whether all columns pass validation.
	 * @see        doValidate()
	 * @see        getValidationFailures()
	 */
	public function validate($columns = null)
	{
		$res = $this->doValidate($columns);
		if ($res === true) {
			$this->validationFailures = array();
			return true;
		} else {
			$this->validationFailures = $res;
			return false;
		}
	}

	/**
	 * This function performs the validation work for complex object models.
	 *
	 * In addition to checking the current object, all related objects will
	 * also be validated.  If all pass then <code>true</code> is returned; otherwise
	 * an aggreagated array of ValidationFailed objects will be returned.
	 *
	 * @param      array $columns Array of column names to validate.
	 * @return     mixed <code>true</code> if all validations pass; array of <code>ValidationFailed</code> objets otherwise.
	 */
	protected function doValidate($columns = null)
	{
		if (!$this->alreadyInValidation) {
			$this->alreadyInValidation = true;
			$retval = null;

			$failureMap = array();


			if (($retval = UllLocationPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}



			$this->alreadyInValidation = false;
		}

		return (!empty($failureMap) ? $failureMap : true);
	}

	/**
	 * Retrieves a field from the object by name passed in as a string.
	 *
	 * @param      string $name name
	 * @param      string $type The type of fieldname the $name is of:
	 *                     one of the class type constants TYPE_PHPNAME,
	 *                     TYPE_COLNAME, TYPE_FIELDNAME, TYPE_NUM
	 * @return     mixed Value of field.
	 */
	public function getByName($name, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = UllLocationPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		return $this->getByPosition($pos);
	}

	/**
	 * Retrieves a field from the object by Position as specified in the xml schema.
	 * Zero-based.
	 *
	 * @param      int $pos position in xml schema
	 * @return     mixed Value of field at $pos
	 */
	public function getByPosition($pos)
	{
		switch($pos) {
			case 0:
				return $this->getId();
				break;
			case 1:
				return $this->getName();
				break;
			case 2:
				return $this->getShort();
				break;
			case 3:
				return $this->getCompanyId();
				break;
			case 4:
				return $this->getStreet();
				break;
			case 5:
				return $this->getZip();
				break;
			case 6:
				return $this->getCity();
				break;
			case 7:
				return $this->getCountryId();
				break;
			case 8:
				return $this->getPhoneTrunkNum();
				break;
			case 9:
				return $this->getPhoneStdExtNum();
				break;
			case 10:
				return $this->getFaxTrunkNum();
				break;
			case 11:
				return $this->getFaxStdExtNum();
				break;
			case 12:
				return $this->getCreatorUserId();
				break;
			case 13:
				return $this->getCreatedAt();
				break;
			case 14:
				return $this->getUpdatorUserId();
				break;
			default:
				return null;
				break;
		} // switch()
	}

	/**
	 * Exports the object as an array.
	 *
	 * You can specify the key type of the array by passing one of the class
	 * type constants.
	 *
	 * @param      string $keyType One of the class type constants TYPE_PHPNAME,
	 *                        TYPE_COLNAME, TYPE_FIELDNAME, TYPE_NUM
	 * @return     an associative array containing the field names (as keys) and field values
	 */
	public function toArray($keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = UllLocationPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getName(),
			$keys[2] => $this->getShort(),
			$keys[3] => $this->getCompanyId(),
			$keys[4] => $this->getStreet(),
			$keys[5] => $this->getZip(),
			$keys[6] => $this->getCity(),
			$keys[7] => $this->getCountryId(),
			$keys[8] => $this->getPhoneTrunkNum(),
			$keys[9] => $this->getPhoneStdExtNum(),
			$keys[10] => $this->getFaxTrunkNum(),
			$keys[11] => $this->getFaxStdExtNum(),
			$keys[12] => $this->getCreatorUserId(),
			$keys[13] => $this->getCreatedAt(),
			$keys[14] => $this->getUpdatorUserId(),
		);
		return $result;
	}

	/**
	 * Sets a field from the object by name passed in as a string.
	 *
	 * @param      string $name peer name
	 * @param      mixed $value field value
	 * @param      string $type The type of fieldname the $name is of:
	 *                     one of the class type constants TYPE_PHPNAME,
	 *                     TYPE_COLNAME, TYPE_FIELDNAME, TYPE_NUM
	 * @return     void
	 */
	public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = UllLocationPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		return $this->setByPosition($pos, $value);
	}

	/**
	 * Sets a field from the object by Position as specified in the xml schema.
	 * Zero-based.
	 *
	 * @param      int $pos position in xml schema
	 * @param      mixed $value field value
	 * @return     void
	 */
	public function setByPosition($pos, $value)
	{
		switch($pos) {
			case 0:
				$this->setId($value);
				break;
			case 1:
				$this->setName($value);
				break;
			case 2:
				$this->setShort($value);
				break;
			case 3:
				$this->setCompanyId($value);
				break;
			case 4:
				$this->setStreet($value);
				break;
			case 5:
				$this->setZip($value);
				break;
			case 6:
				$this->setCity($value);
				break;
			case 7:
				$this->setCountryId($value);
				break;
			case 8:
				$this->setPhoneTrunkNum($value);
				break;
			case 9:
				$this->setPhoneStdExtNum($value);
				break;
			case 10:
				$this->setFaxTrunkNum($value);
				break;
			case 11:
				$this->setFaxStdExtNum($value);
				break;
			case 12:
				$this->setCreatorUserId($value);
				break;
			case 13:
				$this->setCreatedAt($value);
				break;
			case 14:
				$this->setUpdatorUserId($value);
				break;
		} // switch()
	}

	/**
	 * Populates the object using an array.
	 *
	 * This is particularly useful when populating an object from one of the
	 * request arrays (e.g. $_POST).  This method goes through the column
	 * names, checking to see whether a matching key exists in populated
	 * array. If so the setByName() method is called for that column.
	 *
	 * You can specify the key type of the array by additionally passing one
	 * of the class type constants TYPE_PHPNAME, TYPE_COLNAME, TYPE_FIELDNAME,
	 * TYPE_NUM. The default key type is the column's phpname (e.g. 'authorId')
	 *
	 * @param      array  $arr     An array to populate the object from.
	 * @param      string $keyType The type of keys the array uses.
	 * @return     void
	 */
	public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = UllLocationPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setName($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setShort($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setCompanyId($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setStreet($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setZip($arr[$keys[5]]);
		if (array_key_exists($keys[6], $arr)) $this->setCity($arr[$keys[6]]);
		if (array_key_exists($keys[7], $arr)) $this->setCountryId($arr[$keys[7]]);
		if (array_key_exists($keys[8], $arr)) $this->setPhoneTrunkNum($arr[$keys[8]]);
		if (array_key_exists($keys[9], $arr)) $this->setPhoneStdExtNum($arr[$keys[9]]);
		if (array_key_exists($keys[10], $arr)) $this->setFaxTrunkNum($arr[$keys[10]]);
		if (array_key_exists($keys[11], $arr)) $this->setFaxStdExtNum($arr[$keys[11]]);
		if (array_key_exists($keys[12], $arr)) $this->setCreatorUserId($arr[$keys[12]]);
		if (array_key_exists($keys[13], $arr)) $this->setCreatedAt($arr[$keys[13]]);
		if (array_key_exists($keys[14], $arr)) $this->setUpdatorUserId($arr[$keys[14]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return     Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(UllLocationPeer::DATABASE_NAME);

		if ($this->isColumnModified(UllLocationPeer::ID)) $criteria->add(UllLocationPeer::ID, $this->id);
		if ($this->isColumnModified(UllLocationPeer::NAME)) $criteria->add(UllLocationPeer::NAME, $this->name);
		if ($this->isColumnModified(UllLocationPeer::SHORT)) $criteria->add(UllLocationPeer::SHORT, $this->short);
		if ($this->isColumnModified(UllLocationPeer::COMPANY_ID)) $criteria->add(UllLocationPeer::COMPANY_ID, $this->company_id);
		if ($this->isColumnModified(UllLocationPeer::STREET)) $criteria->add(UllLocationPeer::STREET, $this->street);
		if ($this->isColumnModified(UllLocationPeer::ZIP)) $criteria->add(UllLocationPeer::ZIP, $this->zip);
		if ($this->isColumnModified(UllLocationPeer::CITY)) $criteria->add(UllLocationPeer::CITY, $this->city);
		if ($this->isColumnModified(UllLocationPeer::COUNTRY_ID)) $criteria->add(UllLocationPeer::COUNTRY_ID, $this->country_id);
		if ($this->isColumnModified(UllLocationPeer::PHONE_TRUNK_NUM)) $criteria->add(UllLocationPeer::PHONE_TRUNK_NUM, $this->phone_trunk_num);
		if ($this->isColumnModified(UllLocationPeer::PHONE_STD_EXT_NUM)) $criteria->add(UllLocationPeer::PHONE_STD_EXT_NUM, $this->phone_std_ext_num);
		if ($this->isColumnModified(UllLocationPeer::FAX_TRUNK_NUM)) $criteria->add(UllLocationPeer::FAX_TRUNK_NUM, $this->fax_trunk_num);
		if ($this->isColumnModified(UllLocationPeer::FAX_STD_EXT_NUM)) $criteria->add(UllLocationPeer::FAX_STD_EXT_NUM, $this->fax_std_ext_num);
		if ($this->isColumnModified(UllLocationPeer::CREATOR_USER_ID)) $criteria->add(UllLocationPeer::CREATOR_USER_ID, $this->creator_user_id);
		if ($this->isColumnModified(UllLocationPeer::CREATED_AT)) $criteria->add(UllLocationPeer::CREATED_AT, $this->created_at);
		if ($this->isColumnModified(UllLocationPeer::UPDATOR_USER_ID)) $criteria->add(UllLocationPeer::UPDATOR_USER_ID, $this->updator_user_id);

		return $criteria;
	}

	/**
	 * Builds a Criteria object containing the primary key for this object.
	 *
	 * Unlike buildCriteria() this method includes the primary key values regardless
	 * of whether or not they have been modified.
	 *
	 * @return     Criteria The Criteria object containing value(s) for primary key(s).
	 */
	public function buildPkeyCriteria()
	{
		$criteria = new Criteria(UllLocationPeer::DATABASE_NAME);

		$criteria->add(UllLocationPeer::ID, $this->id);

		return $criteria;
	}

	/**
	 * Returns the primary key for this object (row).
	 * @return     int
	 */
	public function getPrimaryKey()
	{
		return $this->getId();
	}

	/**
	 * Generic method to set the primary key (id column).
	 *
	 * @param      int $key Primary key.
	 * @return     void
	 */
	public function setPrimaryKey($key)
	{
		$this->setId($key);
	}

	/**
	 * Sets contents of passed object to values from current object.
	 *
	 * If desired, this method can also make copies of all associated (fkey referrers)
	 * objects.
	 *
	 * @param      object $copyObj An object of UllLocation (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setName($this->name);

		$copyObj->setShort($this->short);

		$copyObj->setCompanyId($this->company_id);

		$copyObj->setStreet($this->street);

		$copyObj->setZip($this->zip);

		$copyObj->setCity($this->city);

		$copyObj->setCountryId($this->country_id);

		$copyObj->setPhoneTrunkNum($this->phone_trunk_num);

		$copyObj->setPhoneStdExtNum($this->phone_std_ext_num);

		$copyObj->setFaxTrunkNum($this->fax_trunk_num);

		$copyObj->setFaxStdExtNum($this->fax_std_ext_num);

		$copyObj->setCreatorUserId($this->creator_user_id);

		$copyObj->setCreatedAt($this->created_at);

		$copyObj->setUpdatorUserId($this->updator_user_id);


		$copyObj->setNew(true);

		$copyObj->setId(NULL); // this is a pkey column, so set to default value

	}

	/**
	 * Makes a copy of this object that will be inserted as a new row in table when saved.
	 * It creates a new object filling in the simple attributes, but skipping any primary
	 * keys that are defined for the table.
	 *
	 * If desired, this method can also make copies of all associated (fkey referrers)
	 * objects.
	 *
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @return     UllLocation Clone of current object.
	 * @throws     PropelException
	 */
	public function copy($deepCopy = false)
	{
		// we use get_class(), because this might be a subclass
		$clazz = get_class($this);
		$copyObj = new $clazz();
		$this->copyInto($copyObj, $deepCopy);
		return $copyObj;
	}

	/**
	 * Returns a peer instance associated with this om.
	 *
	 * Since Peer classes are not to have any instance attributes, this method returns the
	 * same instance for all member of this class. The method could therefore
	 * be static, but this would prevent one from overriding the behavior.
	 *
	 * @return     UllLocationPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new UllLocationPeer();
		}
		return self::$peer;
	}


  public function __call($method, $arguments)
  {
    if (!$callable = sfMixer::getCallable('BaseUllLocation:'.$method))
    {
      throw new sfException(sprintf('Call to undefined method BaseUllLocation::%s', $method));
    }

    array_unshift($arguments, $this);

    return call_user_func_array($callable, $arguments);
  }


} // BaseUllLocation
